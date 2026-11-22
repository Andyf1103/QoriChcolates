<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovimientoStock;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Support\RecordsAdminAudit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VentaController extends Controller
{
    use RecordsAdminAudit;

    public function pos(Request $request): View
    {
        $productos = Producto::query()
            ->with('categoriaRelacion')
            ->where('activo', true)
            ->where('stock', '>', 0)
            ->when($request->filled('buscar'), function (Builder $query) use ($request) {
                $buscar = $request->string('buscar')->toString();
                $query->where('nombre', 'like', '%'.$buscar.'%');
            })
            ->orderBy('nombre')
            ->get();

        $productosJson = $productos->map(fn (Producto $producto) => [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'precio' => (float) $producto->precio,
            'stock' => $producto->stock,
        ])->values()->all();

        return view('admin.ventas.pos', compact('productos', 'productosJson'));
    }

    public function index(Request $request): View
    {
        $ventas = Venta::query()
            ->with('usuario')
            ->when($request->filled('numero'), fn (Builder $q) => $q->where('numero', 'like', '%'.$request->string('numero').'%'))
            ->when($request->filled('metodo'), fn (Builder $q) => $q->where('metodo_pago', $request->string('metodo')))
            ->when($request->filled('estado'), fn (Builder $q) => $q->where('estado', $request->string('estado')))
            ->when($request->filled('desde'), fn (Builder $q) => $q->whereDate('created_at', '>=', $request->date('desde')))
            ->when($request->filled('hasta'), fn (Builder $q) => $q->whereDate('created_at', '<=', $request->date('hasta')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $activeQuery = Venta::query()->where('estado', Venta::ESTADO_COMPLETADA);
        $desde = $request->filled('desde') ? Carbon::parse($request->string('desde'))->startOfDay() : null;
        $hasta = $request->filled('hasta') ? Carbon::parse($request->string('hasta'))->endOfDay() : null;
        $filtered = (clone $activeQuery)
            ->when($desde, fn (Builder $q) => $q->where('created_at', '>=', $desde))
            ->when($hasta, fn (Builder $q) => $q->where('created_at', '<=', $hasta));

        $totalesPorMetodo = (clone $filtered)
            ->selectRaw('metodo_pago, COUNT(*) as ventas, SUM(total) as total')
            ->groupBy('metodo_pago')
            ->get();

        $masVendidos = VentaDetalle::query()
            ->join('ventas', 'ventas.id', '=', 'venta_detalles.venta_id')
            ->where('ventas.estado', Venta::ESTADO_COMPLETADA)
            ->when($desde, fn (Builder $q) => $q->where('ventas.created_at', '>=', $desde))
            ->when($hasta, fn (Builder $q) => $q->where('ventas.created_at', '<=', $hasta))
            ->selectRaw('venta_detalles.producto_id, venta_detalles.producto_nombre, SUM(venta_detalles.cantidad) as cantidad, SUM(venta_detalles.subtotal) as total')
            ->groupBy('venta_detalles.producto_id', 'venta_detalles.producto_nombre')
            ->orderByDesc('cantidad')
            ->limit(10)
            ->get();

        $resumen = [
            'cantidad' => (clone $filtered)->count(),
            'total' => (clone $filtered)->sum('total'),
        ];

        return view('admin.ventas.index', compact('ventas', 'totalesPorMetodo', 'masVendidos', 'resumen'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_nombre' => ['nullable', 'string', 'max:150'],
            'cliente_ci' => ['nullable', 'string', 'max:50'],
            'metodo_pago' => ['required', Rule::in([Venta::PAGO_EFECTIVO, Venta::PAGO_QR])],
            'monto_recibido' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.id' => ['required', 'integer', 'distinct'],
            'items.*.cantidad' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        try {
            $venta = DB::transaction(function () use ($data) {
                $ids = collect($data['items'])->pluck('id')->unique()->values();
                $productos = Producto::query()
                    ->where('activo', true)
                    ->whereIn('id', $ids)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $quantities = collect($data['items'])->groupBy('id')->map(fn ($rows) => $rows->sum('cantidad'));
                $totalCents = 0;
                $rows = [];

                foreach ($quantities as $id => $cantidad) {
                    $producto = $productos->get((int) $id);
                    if (! $producto) {
                        throw ValidationException::withMessages(['items' => 'Hay productos inactivos o eliminados en el carrito.']);
                    }
                    if ($producto->stock < $cantidad) {
                        throw ValidationException::withMessages(['items' => 'Stock insuficiente para '.$producto->nombre.'.']);
                    }

                    $priceCents = (int) round(((float) $producto->precio) * 100);
                    $subtotalCents = $priceCents * (int) $cantidad;
                    $totalCents += $subtotalCents;
                    $rows[] = [
                        'producto' => $producto,
                        'cantidad' => (int) $cantidad,
                        'precio_cents' => $priceCents,
                        'subtotal_cents' => $subtotalCents,
                    ];
                }

                $receivedCents = (int) round(((float) $data['monto_recibido']) * 100);
                if ($data['metodo_pago'] === Venta::PAGO_QR && $receivedCents !== $totalCents) {
                    throw ValidationException::withMessages(['monto_recibido' => 'El pago QR debe cubrir exactamente el total.']);
                }
                if ($data['metodo_pago'] === Venta::PAGO_EFECTIVO && $receivedCents < $totalCents) {
                    throw ValidationException::withMessages(['monto_recibido' => 'El efectivo recibido es insuficiente.']);
                }

                $changeCents = $data['metodo_pago'] === Venta::PAGO_EFECTIVO
                    ? $receivedCents - $totalCents
                    : 0;

                $dayCount = Venta::whereDate('created_at', now()->toDateString())->count() + 1;
                $numero = 'V-'.now()->format('Ymd').'-'.str_pad((string) $dayCount, 5, '0', STR_PAD_LEFT);

                $venta = Venta::create([
                    'numero' => $numero,
                    'user_id' => auth()->id(),
                    'cliente_nombre' => ($data['cliente_nombre'] ?? null) ?: null,
                    'cliente_ci' => ($data['cliente_ci'] ?? null) ?: null,
                    'subtotal' => number_format($totalCents / 100, 2, '.', ''),
                    'total' => number_format($totalCents / 100, 2, '.', ''),
                    'monto_recibido' => number_format($receivedCents / 100, 2, '.', ''),
                    'cambio' => number_format($changeCents / 100, 2, '.', ''),
                    'metodo_pago' => $data['metodo_pago'],
                    'estado' => Venta::ESTADO_COMPLETADA,
                ]);

                foreach ($rows as $row) {
                    $producto = $row['producto'];
                    VentaDetalle::create([
                        'venta_id' => $venta->id,
                        'producto_id' => $producto->id,
                        'producto_nombre' => $producto->nombre,
                        'precio_unitario' => number_format($row['precio_cents'] / 100, 2, '.', ''),
                        'cantidad' => $row['cantidad'],
                        'subtotal' => number_format($row['subtotal_cents'] / 100, 2, '.', ''),
                    ]);

                    $stockAnterior = $producto->stock;
                    $stockNuevo = $stockAnterior - $row['cantidad'];
                    $producto->update(['stock' => $stockNuevo]);
                    MovimientoStock::create([
                        'producto_id' => $producto->id,
                        'user_id' => auth()->id(),
                        'tipo' => 'salida',
                        'cantidad' => $row['cantidad'],
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockNuevo,
                        'motivo' => 'Venta '.$venta->numero,
                    ]);
                }

                $this->recordAudit('sale_created', $venta, null, $venta->load('detalles')->toArray());

                return $venta;
            });
        } catch (ValidationException $exception) {
            throw $exception;
        }

        return redirect()->route('admin.ventas.pos')
            ->with('success', 'Venta registrada correctamente.');
    }

    public function show(Venta $venta): View
    {
        return view('admin.ventas.show', [
            'venta' => $venta->load(['detalles.producto', 'usuario']),
        ]);
    }

    public function comprobante(Venta $venta): View
    {
        return view('admin.ventas.comprobante', [
            'venta' => $venta->load(['detalles', 'usuario']),
        ]);
    }

    public function anular(Request $request, Venta $venta)
    {
        $data = $request->validate([
            'motivo' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        if ($venta->estado !== Venta::ESTADO_COMPLETADA) {
            return back()->with('error', 'La venta ya se encuentra anulada.');
        }

        DB::transaction(function () use ($data, $venta) {
            $locked = Venta::query()->lockForUpdate()->findOrFail($venta->id);
            if ($locked->estado !== Venta::ESTADO_COMPLETADA) {
                throw ValidationException::withMessages(['motivo' => 'La venta ya fue anulada.']);
            }

            $before = $locked->fresh()->toArray();
            foreach ($locked->detalles()->get() as $detalle) {
                $producto = Producto::query()->withTrashed()->lockForUpdate()->find($detalle->producto_id);
                if (! $producto) {
                    continue;
                }

                $stockAnterior = $producto->stock;
                $stockNuevo = $stockAnterior + $detalle->cantidad;
                $producto->update(['stock' => $stockNuevo]);
                MovimientoStock::create([
                    'producto_id' => $producto->id,
                    'user_id' => auth()->id(),
                    'tipo' => 'entrada',
                    'cantidad' => $detalle->cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $stockNuevo,
                    'motivo' => 'Anulación '.$locked->numero.': '.$data['motivo'],
                ]);
            }

            $locked->update([
                'estado' => Venta::ESTADO_ANULADA,
                'motivo_anulacion' => $data['motivo'],
                'anulada_at' => now(),
            ]);
            $this->recordAudit('sale_annulled', $locked, $before, $locked->fresh()->toArray());
        });

        return back()->with('success', 'Venta anulada y stock revertido correctamente.');
    }
}
