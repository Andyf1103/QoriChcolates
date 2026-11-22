@extends('admin.layout')
@section('title', 'Historial de ventas - Qori Chocolates')
@section('heading', 'Ventas')
@section('content')
<div class="mx-auto max-w-7xl space-y-6 p-4 sm:p-6">
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div><p class="text-sm text-gray-500">Historial y cierre por período</p><h2 class="text-2xl font-bold">Ventas</h2></div>
        <a href="{{ route('admin.ventas.pos') }}" class="admin-primary-action">Abrir POS</a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border bg-white p-5 dark:border-gray-800 dark:bg-gray-950"><p class="text-sm text-gray-500">Ventas activas</p><p class="mt-2 text-3xl font-bold">{{ $resumen['cantidad'] }}</p></div>
        <div class="rounded-xl border bg-white p-5 dark:border-gray-800 dark:bg-gray-950"><p class="text-sm text-gray-500">Total vendido</p><p class="mt-2 text-3xl font-bold">Bs {{ number_format((float)$resumen['total'], 2, '.', ',') }}</p></div>
        @foreach($totalesPorMetodo as $total)
            <div class="rounded-xl border bg-white p-5 dark:border-gray-800 dark:bg-gray-950"><p class="text-sm text-gray-500">{{ ucfirst($total->metodo_pago) }}</p><p class="mt-2 text-2xl font-bold">Bs {{ number_format((float)$total->total, 2, '.', ',') }}</p><p class="text-xs text-gray-500">{{ $total->ventas }} ventas</p></div>
        @endforeach
    </div>

    <form method="GET" class="grid gap-3 rounded-xl border bg-white p-4 sm:grid-cols-5 dark:border-gray-800 dark:bg-gray-950">
        <input name="numero" value="{{ request('numero') }}" placeholder="Número" class="rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
        <select name="metodo" class="rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-900"><option value="">Método</option><option value="efectivo" @selected(request('metodo')==='efectivo')>Efectivo</option><option value="qr" @selected(request('metodo')==='qr')>QR</option></select>
        <select name="estado" class="rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-900"><option value="">Estado</option><option value="completada" @selected(request('estado')==='completada')>Completada</option><option value="anulada" @selected(request('estado')==='anulada')>Anulada</option></select>
        <input type="date" name="desde" value="{{ request('desde') }}" class="rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
        <input type="date" name="hasta" value="{{ request('hasta') }}" class="rounded-lg border px-3 py-2 dark:border-gray-700 dark:bg-gray-900">
        <button class="admin-secondary-action justify-center sm:col-span-5">Filtrar</button>
    </form>

    <div class="grid gap-5 lg:grid-cols-[1fr_320px]">
        <div class="overflow-x-auto rounded-xl border bg-white dark:border-gray-800 dark:bg-gray-950">
            <table class="w-full min-w-[760px] text-left text-sm"><thead><tr class="border-b text-xs uppercase text-gray-500 dark:border-gray-800"><th class="px-5 py-3">Número</th><th>Fecha</th><th>Cliente</th><th>Método</th><th>Total</th><th>Estado</th><th class="px-5 py-3 text-right">Acción</th></tr></thead><tbody>
            @forelse($ventas as $venta)
                <tr class="border-b last:border-0 dark:border-gray-800"><td class="px-5 py-3 font-semibold">{{ $venta->numero }}</td><td>{{ $venta->created_at->format('d/m/Y H:i') }}</td><td>{{ $venta->cliente_nombre ?: 'Consumidor final' }}</td><td>{{ ucfirst($venta->metodo_pago) }}</td><td>Bs {{ number_format((float)$venta->total, 2, '.', ',') }}</td><td><span class="rounded-full px-2 py-1 text-xs {{ $venta->estado === 'completada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($venta->estado) }}</span></td><td class="px-5 py-3 text-right"><a href="{{ route('admin.ventas.show', $venta) }}" class="font-semibold text-amber-800">Ver</a></td></tr>
            @empty <tr><td colspan="7" class="py-10 text-center text-gray-500">No hay ventas registradas.</td></tr>@endforelse
            </tbody></table>
        </div>
        <aside class="h-fit rounded-xl border bg-white p-5 dark:border-gray-800 dark:bg-gray-950"><h3 class="font-bold">Más vendidos</h3><ol class="mt-3 space-y-3">@forelse($masVendidos as $item)<li class="flex justify-between gap-3 border-b pb-2 dark:border-gray-800"><span>{{ $item->producto_nombre }}</span><strong>{{ $item->cantidad }} ud.</strong></li>@empty<li class="text-sm text-gray-500">Sin datos.</li>@endforelse</ol></aside>
    </div>
    <div>{{ $ventas->links() }}</div>
</div>
@endsection
