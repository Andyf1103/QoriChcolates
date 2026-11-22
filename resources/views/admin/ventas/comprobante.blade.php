<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Comprobante {{ $venta->numero }}</title><style>body{font-family:Arial,sans-serif;color:#111;margin:24px}table{width:100%;border-collapse:collapse}th,td{border-bottom:1px solid #ddd;padding:8px;text-align:left}td:last-child,th:last-child{text-align:right}.totales{margin-left:auto;width:280px}.totales div{display:flex;justify-content:flex-end}.totales span{width:140px}.totales strong{width:140px;text-align:right}@media print{button{display:none}}@media(max-width:600px){body{margin:10px}table{font-size:12px}}</style></head>
<body>
<button type="button" onclick="window.print()">Imprimir</button>
<h1>Qori Chocolates</h1><p>Comprobante de venta: <strong>{{ $venta->numero }}</strong></p><p>Fecha: {{ $venta->created_at->format('d/m/Y H:i') }} | Estado: {{ ucfirst($venta->estado) }}</p>
<p>Cliente: {{ $venta->cliente_nombre ?: 'Consumidor final' }}<br>CI/NIT: {{ $venta->cliente_ci ?: '-' }}<br>Cajero: {{ $venta->usuario->name ?? '-' }}</p>
<table><thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>@foreach($venta->detalles as $detalle)<tr><td>{{ $detalle->producto_nombre }}</td><td>{{ $detalle->cantidad }}</td><td>Bs {{ number_format((float)$detalle->precio_unitario,2,'.',',') }}</td><td>Bs {{ number_format((float)$detalle->subtotal,2,'.',',') }}</td></tr>@endforeach</tbody></table>
<div class="totales"><div><span>Subtotal</span><strong>Bs {{ number_format((float)$venta->subtotal,2,'.',',') }}</strong></div><div><span>Total</span><strong>Bs {{ number_format((float)$venta->total,2,'.',',') }}</strong></div><div><span>Método</span><strong>{{ ucfirst($venta->metodo_pago) }}</strong></div><div><span>Pago recibido</span><strong>Bs {{ number_format((float)$venta->monto_recibido,2,'.',',') }}</strong></div><div><span>Cambio</span><strong>Bs {{ number_format((float)$venta->cambio,2,'.',',') }}</strong></div></div>
@if($venta->estado === 'anulada')<p><strong>Venta anulada:</strong> {{ $venta->motivo_anulacion }}</p>@endif
</body></html>
