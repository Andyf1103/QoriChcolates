@extends('admin.layout')
@section('title', 'Dashboard - Qori Chocolates')
@section('heading', 'Dashboard')
@section('content')
<div class="mx-auto max-w-7xl space-y-6 p-4 sm:p-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div><p class="text-sm text-gray-500">Resumen de tu tienda</p><h2 class="text-2xl font-bold text-gray-900 dark:text-white">Buenos días, {{ auth()->user()->name }}</h2></div>
        <div class="admin-page-actions"><a href="{{ route('admin.productos.create') }}" class="admin-primary-action">+ Crear producto</a><a href="{{ route('admin.ventas.pos') }}" class="admin-secondary-action">Nueva venta</a></div>
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach([
            ['Productos totales', $productosTotales, route('admin.productos.index'), 'bg-green-50 text-green-800'],
            ['Categorías totales', $categoriasTotales, route('admin.categorias.index'), 'bg-amber-50 text-amber-800'],
            ['Stock bajo', $stockBajo, route('admin.productos.index', ['stock_bajo'=>1]), 'bg-orange-50 text-orange-800'],
            ['Agotados', $agotados, route('admin.productos.index', ['agotados'=>1]), 'bg-red-50 text-red-800'],
            ['Valor inventario', 'Bs '.number_format((float)$valorInventario, 2), route('admin.productos.index'), 'bg-blue-50 text-blue-800'],
        ] as [$label, $value, $url, $color])
            <a href="{{ $url }}" class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-amber-700 dark:border-gray-800 dark:bg-gray-950">
                <div class="flex items-start justify-between gap-3"><span class="inline-flex rounded-lg px-2 py-1 text-xs font-semibold {{ $color }}">{{ $label }}</span><span class="text-gray-400 transition group-hover:text-amber-700" aria-hidden="true">→</span></div><p class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
            </a>
        @endforeach
    </div>
    <section class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">
        <div class="flex flex-col gap-2 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800"><div><h3 class="font-semibold text-gray-900 dark:text-white">Alertas de inventario</h3><p class="text-xs text-gray-500">Productos con 5 unidades o menos</p></div><a href="{{ route('admin.productos.index', ['stock_bajo'=>1]) }}" class="text-sm font-semibold text-amber-800 hover:underline">Ver todos →</a></div>
        <div class="overflow-x-auto"><table class="w-full min-w-[600px] text-left text-sm"><thead><tr class="border-b text-xs uppercase text-gray-500 dark:border-gray-800"><th class="px-5 py-3">Producto</th><th class="py-3">Categoría</th><th class="py-3">Stock</th><th class="py-3">Estado</th><th class="py-3 pr-5 text-right">Acción</th></tr></thead><tbody>
        @forelse($productosStockBajo as $producto)
            <tr class="border-b last:border-0 dark:border-gray-800"><td class="px-5 py-3 font-medium">{{ $producto->nombre }}</td><td>{{ $producto->categoria_nombre }}</td><td><span class="rounded-full px-2 py-1 font-semibold {{ $producto->stock === 0 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">{{ $producto->stock }}</span></td><td>{{ $producto->stock === 0 ? 'Agotado' : 'Stock bajo' }}</td><td class="pr-5 text-right"><a href="{{ route('admin.productos.edit', $producto) }}" class="font-semibold text-amber-800 hover:underline">Editar</a></td></tr>
        @empty <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500">No hay productos con stock bajo.</td></tr>@endforelse
        </tbody></table></div>
    </section>
</div>
@endsection
