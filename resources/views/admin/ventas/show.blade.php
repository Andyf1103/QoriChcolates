@extends('admin.layout')
@section('title', 'Detalle de venta - Qori Chocolates')
@section('heading', 'Detalle de venta')
@section('content')
<div class="mx-auto max-w-4xl space-y-5 p-4 sm:p-6">
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center"><div><p class="text-sm text-gray-500">Venta {{ $venta->numero }}</p><h2 class="text-2xl font-bold">{{ ucfirst($venta->estado) }}</h2></div><div class="flex gap-2"><a href="{{ route('admin.ventas.comprobante', $venta) }}" target="_blank" class="admin-secondary-action">Comprobante</a><a href="{{ route('admin.ventas.index') }}" class="admin-secondary-action">Volver</a></div></div>
    <section class="grid gap-4 rounded-xl border bg-white p-5 sm:grid-cols-2 dark:border-gray-800 dark:bg-gray-950">
        <div><p class="text-xs text-gray-500">Cliente</p><p class="font-semibold">{{ $venta->cliente_nombre ?: 'Consumidor final' }}</p></div>
        <div><p class="text-xs text-gray-500">CI/NIT</p><p class="font-semibold">{{ $venta->cliente_ci ?: '-' }}</p></div>
        <div><p class="text-xs text-gray-500">Cajero</p><p class="font-semibold">{{ $venta->usuario->name ?? '-' }}</p></div>
        <div><p class="text-xs text-gray-500">Fecha</p><p class="font-semibold">{{ $venta->created_at->format('d/m/Y H:i') }}</p></div>
        @if($venta->estado === 'anulada')<div class="sm:col-span-2 rounded-lg bg-red-50 p-3 text-red-800"><strong>Motivo de anulación:</strong> {{ $venta->motivo_anulacion }}</div>@endif
    </section>
    <div class="overflow-x-auto rounded-xl border bg-white dark:border-gray-800 dark:bg-gray-950"><table class="w-full text-left text-sm"><thead><tr class="border-b dark:border-gray-800"><th class="px-5 py-3">Producto</th><th>Precio</th><th>Cantidad</th><th class="px-5 py-3 text-right">Subtotal</th></tr></thead><tbody>@foreach($venta->detalles as $detalle)<tr class="border-b last:border-0 dark:border-gray-800"><td class="px-5 py-3">{{ $detalle->producto_nombre }}</td><td>Bs {{ number_format((float)$detalle->precio_unitario, 2, '.', ',') }}</td><td>{{ $detalle->cantidad }}</td><td class="px-5 py-3 text-right">Bs {{ number_format((float)$detalle->subtotal, 2, '.', ',') }}</td></tr>@endforeach</tbody></table></div>
    <section class="rounded-xl border bg-white p-5 dark:border-gray-800 dark:bg-gray-950"><div class="ml-auto max-w-xs space-y-2"><div class="flex justify-between"><span>Subtotal</span><strong>Bs {{ number_format((float)$venta->subtotal, 2, '.', ',') }}</strong></div><div class="flex justify-between text-lg"><span>Total</span><strong>Bs {{ number_format((float)$venta->total, 2, '.', ',') }}</strong></div><div class="flex justify-between"><span>{{ ucfirst($venta->metodo_pago) }} recibido</span><span>Bs {{ number_format((float)$venta->monto_recibido, 2, '.', ',') }}</span></div><div class="flex justify-between"><span>Cambio</span><span>Bs {{ number_format((float)$venta->cambio, 2, '.', ',') }}</span></div></section>
    @if($venta->estado === 'completada')
        <form method="POST" action="{{ route('admin.ventas.anular', $venta) }}" class="rounded-xl border border-red-200 bg-white p-5 dark:border-red-900 dark:bg-gray-950">@csrf @method('PATCH')<label for="motivo" class="block font-semibold text-red-800">Motivo de anulación</label><textarea id="motivo" name="motivo" required minlength="3" maxlength="255" class="mt-2 w-full rounded-lg border border-red-300 px-3 py-2 dark:border-red-900 dark:bg-gray-900"></textarea><button type="submit" class="mt-3 rounded-lg bg-red-700 px-4 py-2 font-semibold text-white">Anular venta y revertir stock</button></form>
    @endif
</div>
@endsection
