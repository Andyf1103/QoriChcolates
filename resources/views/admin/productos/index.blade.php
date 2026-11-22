@extends('admin.layout')
@section('title', 'Productos - Qori Chocolates')
@section('heading', 'Productos')
@section('content')
<div class="mx-auto max-w-7xl space-y-5 p-4 sm:p-6">
    <div class="admin-page-header"><div><h2 class="text-2xl font-bold">Productos</h2><p class="text-sm text-gray-500">{{ $productos->total() }} resultados</p></div><a href="{{ route('admin.productos.create') }}" class="admin-primary-action">+ Crear nuevo producto</a></div>
    <form method="GET" class="grid gap-3 rounded-xl border border-gray-200 bg-white p-4 sm:grid-cols-2 lg:grid-cols-7 dark:border-gray-800 dark:bg-gray-950">
        <label class="sr-only" for="buscar">Buscar</label><input id="buscar" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre" class="rounded-lg border-gray-300 px-3 py-2 focus:border-amber-700 focus:ring-amber-700">
        <select name="categoria" aria-label="Categoría" class="rounded-lg border-gray-300 px-3 py-2 focus:border-amber-700 focus:ring-amber-700"><option value="">Todas las categorías</option>@foreach($categorias as $categoria)<option value="{{ $categoria->id }}" @selected(request('categoria') == $categoria->id)>{{ $categoria->nombre }}</option>@endforeach</select>
        <select name="stock" aria-label="Stock" class="rounded-lg border-gray-300 px-3 py-2 focus:border-amber-700 focus:ring-amber-700"><option value="">Cualquier stock</option><option value="bajo" @selected(request('stock') === 'bajo')>Stock bajo</option><option value="agotado" @selected(request('stock') === 'agotado')>Agotados</option></select>
        <select name="orden" aria-label="Ordenar" class="rounded-lg border-gray-300 px-3 py-2"><option value="nombre" @selected(request('orden', 'nombre') === 'nombre')>Nombre</option><option value="precio" @selected(request('orden') === 'precio')>Precio</option><option value="stock" @selected(request('orden') === 'stock')>Stock</option></select>
        <select name="direccion" aria-label="Dirección" class="rounded-lg border-gray-300 px-3 py-2"><option value="asc" @selected(request('direccion', 'asc') === 'asc')>Ascendente</option><option value="desc" @selected(request('direccion') === 'desc')>Descendente</option></select>
        <div class="flex gap-2"><button class="flex-1 rounded-lg bg-gray-800 px-3 py-2 font-semibold text-white hover:bg-gray-900">Filtrar</button><a href="{{ route('admin.productos.index') }}" class="rounded-lg border px-3 py-2 text-center hover:bg-gray-50">Limpiar</a></div>
    </form>
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">
        <table class="w-full min-w-[850px] text-left text-sm"><thead><tr class="border-b text-xs uppercase text-gray-500 dark:border-gray-800"><th class="px-5 py-3">Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th class="px-5">Acciones</th></tr></thead><tbody>
        @forelse($productos as $producto)
            <tr class="border-b last:border-0 dark:border-gray-800"><td class="flex items-center gap-3 px-5 py-3 font-medium"><img src="{{ $producto->imagen_principal ? asset('storage/'.$producto->imagen_principal) : asset('images/logoqori.png') }}" loading="lazy" class="h-12 w-12 rounded-lg object-cover" alt="{{ $producto->nombre }}"><span>{{ $producto->nombre }}</span></td><td>{{ $producto->categoria_nombre }}</td><td>Bs {{ number_format((float)$producto->precio, 2) }}</td><td><span class="rounded-full px-2 py-1 font-semibold {{ $producto->stock === 0 ? 'bg-red-100 text-red-800' : ($producto->stock <= 5 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800') }}">{{ $producto->stock === 0 ? 'Agotado' : $producto->stock }}</span></td><td class="px-5 py-3"><div class="flex flex-wrap gap-3">
                @if(request('papelera'))
                    <form method="POST" action="{{ route('admin.productos.restore', $producto->id) }}" @submit.prevent="requestConfirm($el, '¿Restaurar este producto?')">@csrf @method('PATCH')<button type="submit" class="font-semibold text-green-700 hover:underline">Restaurar</button></form>
                @else
                    <a href="{{ route('admin.productos.edit', $producto) }}" class="text-amber-800 hover:underline">Editar</a>
                    <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}" @submit.prevent="requestConfirm($el, '¿Enviar este producto a la papelera?')">@csrf @method('DELETE')<button type="submit" class="text-red-700 hover:underline">Eliminar</button></form>
                @endif
            </div></td></tr>
        @empty <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500">No encontramos productos con estos filtros. <a class="text-amber-800 hover:underline" href="{{ route('admin.productos.create') }}">Crear producto</a></td></tr>@endforelse
        </tbody></table>
    </div>
    <div class="flex flex-wrap items-center justify-between gap-3"><a href="{{ route('admin.productos.index', request('papelera') ? [] : ['papelera' => 1]) }}" class="text-sm text-amber-800 hover:underline">{{ request('papelera') ? '← Ver productos actuales' : 'Ver papelera' }}</a>{{ $productos->links() }}</div>
</div>
@endsection
