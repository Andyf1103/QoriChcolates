<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReporteVentaController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->validateFilters($request);
        $data = $this->buildReport($request, $filters);

        return view('admin.reportes.ventas.index', $data);
    }

    public function pdf(Request $request)
    {
        $filters = $this->validateFilters($request);
        $data = $this->buildReport($request, $filters);

        $pdf = Pdf::loadView('admin.reportes.ventas.pdf', $data)
            ->setPaper('a4', 'portrait');

        $filename = 'reporte-ventas';
        if ($filters['desde'] ?? null) {
            $filename .= '-desde-'.$filters['desde'];
        }
        if ($filters['hasta'] ?? null) {
            $filename .= '-hasta-'.$filters['hasta'];
        }
        $filename .= '.pdf';

        return $pdf->download($filename);
    }

    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'numero' => ['nullable', 'string', 'max:30'],
            'metodo' => ['nullable', Rule::in([Venta::PAGO_EFECTIVO, Venta::PAGO_QR])],
            'estado' => ['nullable', Rule::in([Venta::ESTADO_COMPLETADA, Venta::ESTADO_ANULADA])],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);
    }

    private function buildReport(Request $request, array $filters): array
    {
        $ventas = Venta::query()
            ->with('usuario')
            ->when($filters['numero'] ?? null, fn (Builder $query, $numero) =>
                $query->where('numero', 'like', '%'.$numero.'%'))
            ->when($filters['metodo'] ?? null, fn (Builder $query, $metodo) =>
                $query->where('metodo_pago', $metodo))
            ->when($filters['estado'] ?? null, fn (Builder $query, $estado) =>
                $query->where('estado', $estado))
            ->when($filters['desde'] ?? null, fn (Builder $query, $desde) =>
                $query->whereDate('created_at', '>=', $desde))
            ->when($filters['hasta'] ?? null, fn (Builder $query, $hasta) =>
                $query->whereDate('created_at', '<=', $hasta))
            ->latest()
            ->get();

        $completadas = $ventas->where('estado', Venta::ESTADO_COMPLETADA);
        $totalesPorMetodo = $completadas
            ->groupBy('metodo_pago')
            ->map(fn ($items, $metodo) => [
                'metodo' => $metodo,
                'ventas' => $items->count(),
                'total' => $items->sum('total'),
            ])
            ->values();

        return [
            'ventas' => $ventas,
            'totalesPorMetodo' => $totalesPorMetodo,
            'resumen' => [
                'cantidad' => $completadas->count(),
                'total' => $completadas->sum('total'),
                'anuladas' => $ventas->where('estado', Venta::ESTADO_ANULADA)->count(),
            ],
            'filters' => $filters,
            'generatedAt' => now(),
        ];
    }
}
