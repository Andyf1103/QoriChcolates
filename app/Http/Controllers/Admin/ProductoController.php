<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductoRequest;
use App\Models\Categoria;
use App\Models\MovimientoStock;
use App\Models\Producto;
use App\Support\RecordsAdminAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductoController extends Controller
{
    use RecordsAdminAudit;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = in_array($request->input('orden'), ['nombre', 'precio', 'stock'], true)
            ? $request->input('orden')
            : 'nombre';
        $direccion = $request->input('direccion') === 'desc' ? 'desc' : 'asc';

        $productos = Producto::query()
            ->with('categoriaRelacion')
            ->when($request->boolean('papelera'), fn ($q) => $q->onlyTrashed())
            ->when(! $request->boolean('papelera'), fn ($q) => $q->withoutTrashed())
            ->when($request->filled('buscar'), fn ($q) => $q->where('nombre', 'like', '%'.$request->string('buscar').'%'))
            ->when($request->filled('categoria'), fn ($q) => $q->where('categoria_id', $request->integer('categoria')))
            ->when($request->boolean('stock_bajo') || $request->input('stock') === 'bajo', fn ($q) => $q->where('stock', '>', 0)->where('stock', '<=', 5))
            ->when($request->boolean('agotados') || $request->input('stock') === 'agotado', fn ($q) => $q->where('stock', 0))
            ->orderBy($sort, $direccion)
            ->paginate(15)
            ->withQueryString();

        return view('admin.productos.index', [
            'productos' => $productos,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.productos.create', [
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductoRequest $request)
    {
        $data = $request->validated();
        $imagen = $data['imagen'] ?? null;
        unset($data['imagen']);
        $storedImagePath = null;

        try {
            $producto = DB::transaction(function () use ($data, $imagen, &$storedImagePath) {
                if ($imagen) {
                    $storedImagePath = $imagen->store('productos', 'public');
                    if ($storedImagePath) {
                        $data['imagen_principal'] = $storedImagePath;
                    }
                }

                $producto = Producto::create($data);
                if ($producto->stock > 0) {
                    MovimientoStock::create([
                        'producto_id' => $producto->id,
                        'user_id' => auth()->id(),
                        'tipo' => 'entrada',
                        'cantidad' => $producto->stock,
                        'stock_anterior' => 0,
                        'stock_nuevo' => $producto->stock,
                        'motivo' => 'Stock inicial',
                    ]);
                }

                return $producto;
            });
        } catch (Throwable $exception) {
            if ($storedImagePath) {
                Storage::disk('public')->delete($storedImagePath);
            }
            throw $exception;
        }
        $this->recordAudit('created', $producto, null, $producto->only(['nombre', 'precio', 'stock', 'categoria_id']));

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return redirect()->route('admin.productos.edit', $producto);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', [
            'producto' => $producto,
            'categorias' => Categoria::orderBy('nombre')->get(),
            'movimientos' => $producto->movimientosStock()->with('user')->latest()->paginate(15)->withQueryString(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductoRequest $request, Producto $producto)
    {
        $data = $request->validated();
        unset($data['stock'], $data['imagen'], $data['eliminar_imagen']);
        $before = $producto->fresh()->toArray();

        try {
            DB::transaction(function () use ($producto, $data) {
                $producto->update($data);
            });
            $this->recordAudit('updated', $producto, $before, $producto->fresh()->toArray());
        } catch (Throwable $exception) {
            throw $exception;
        }

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $before = $producto->toArray();
        $producto->delete();
        $this->recordAudit('deleted', $producto, $before, null);

        return redirect()->route('admin.productos.index')->with('success', 'Producto enviado a la papelera. Puedes restaurarlo.');
    }

    public function restore(int $producto)
    {
        $deleted = Producto::withTrashed()->findOrFail($producto);
        $deleted->restore();
        $this->recordAudit('restored', $deleted, null, $deleted->toArray());

        return back()->with('success', 'Producto restaurado correctamente.');
    }

    public function updateStock(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
            'motivo' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $changed = false;
        DB::transaction(function () use ($validated, $producto, &$changed) {
            $locked = Producto::query()->lockForUpdate()->findOrFail($producto->id);
            $before = $locked->toArray();
            $oldStock = $locked->stock;
            $newStock = $validated['stock'];
            if ($oldStock !== $newStock) {
                $locked->update(['stock' => $newStock]);
                MovimientoStock::create([
                    'producto_id' => $locked->id,
                    'user_id' => auth()->id(),
                    'tipo' => $newStock > $oldStock ? 'entrada' : 'salida',
                    'cantidad' => abs($newStock - $oldStock),
                    'stock_anterior' => $oldStock,
                    'stock_nuevo' => $newStock,
                    'motivo' => $validated['motivo'] ?? null,
                ]);
                $this->recordAudit('stock_changed', $locked, $before, $locked->fresh()->toArray());
            }
        });

        return back()->with('success', 'Stock actualizado correctamente.');
    }
}
