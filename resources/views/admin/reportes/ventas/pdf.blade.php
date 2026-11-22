<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de ventas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        h1 { margin: 0 0 4px; color: #78350f; }
        .meta { color: #6b7280; margin-bottom: 18px; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .summary td { width: 25%; border: 1px solid #d1d5db; padding: 8px; vertical-align: top; }
        .label { color: #6b7280; font-size: 9px; text-transform: uppercase; }
        .value { display: block; margin-top: 4px; font-size: 16px; font-weight: bold; }
        table.list { width: 100%; border-collapse: collapse; }
        table.list th { background: #fef3c7; border: 1px solid #d1d5db; padding: 7px 5px; text-align: left; }
        table.list td { border: 1px solid #d1d5db; padding: 6px 5px; }
        .right { text-align: right; }
        .empty { padding: 20px; text-align: center; color: #6b7280; }
    </style>
</head>
<body>
    <h1>Reporte de ventas</h1>
    <div class="meta">
        Qori Chocolates · Generado el {{ $generatedAt->format('d/m/Y H:i') }}
        @if(($filters['desde'] ?? null) || ($filters['hasta'] ?? null))
            · Período: {{ $filters['desde'] ?? 'inicio' }} a {{ $filters['hasta'] ?? 'actualidad' }}
        @endif
    </div>

    <table class="summary">
        <tr>
            <td><span class="label">Ventas completadas</span><span class="value">{{ $resumen['cantidad'] }}</span></td>
            <td><span class="label">Total vendido</span><span class="value">Bs {{ number_format((float) $resumen['total'], 2, '.', ',') }}</span></td>
            <td><span class="label">Ventas anuladas</span><span class="value">{{ $resumen['anuladas'] }}</span></td>
            <td>
                @foreach($totalesPorMetodo as $total)
                    <span class="label">{{ ucfirst($total['metodo']) }}</span>
                    <span style="display:block;font-weight:bold;">Bs {{ number_format((float) $total['total'], 2, '.', ',') }} ({{ $total['ventas'] }})</span>
                @endforeach
            </td>
        </tr>
    </table>

    <table class="list">
        <thead>
            <tr>
                <th>Número</th><th>Fecha</th><th>Cliente</th><th>Método</th><th class="right">Total</th><th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventas as $venta)
                <tr>
                    <td>{{ $venta->numero }}</td>
                    <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $venta->cliente_nombre ?: 'Consumidor final' }}</td>
                    <td>{{ ucfirst($venta->metodo_pago) }}</td>
                    <td class="right">Bs {{ number_format((float) $venta->total, 2, '.', ',') }}</td>
                    <td>{{ ucfirst($venta->estado) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">No hay ventas que coincidan con los filtros.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
