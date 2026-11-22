@extends('admin.layout')
@section('title', 'Categorías - Qori Chocolates')
@section('heading', 'Categorías')
@section('content')
<div class="mx-auto max-w-7xl space-y-5 p-4 sm:p-6">
    <div class="admin-page-header"><div><h2 class="text-2xl font-bold">Categorías</h2><p class="text-sm text-gray-500">{{ $categorias->total() }} categorías</p></div><a href="{{ route('admin.categorias.create') }}" class="admin-primary-action">+ Crear nueva categoría</a></div>
    <form method="GET" class="grid gap-3 rounded-xl border border-gray-200 bg-white p-4 sm:grid-cols-4 dark:border-gray-800 dark:bg-gray-950">
        <label class="sr-only" for="buscar-categoria">Buscar por nombre</label>
        <input id="buscar-categoria" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre" class="rounded-lg border-gray-300 px-3 py-2">
        <button class="rounded-lg bg-gray-800 px-4 py-2 font-semibold text-white">Filtrar</button>
        <a href="{{ route('admin.categorias.index') }}" class="rounded-lg border px-4 py-2 text-center">Limpiar</a>
    </form>
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950"><table class="w-full min-w-[700px] text-left text-sm"><thead><tr class="border-b text-xs uppercase text-gray-500 dark:border-gray-800"><th class="px-5 py-3">Nombre</th><th>Slug</th><th>Productos</th><th class="px-5">Acciones</th></tr></thead><tbody>
    @forelse($categorias as $categoria)<tr class="border-b last:border-0 dark:border-gray-800"><td class="px-5 py-4 font-medium">{{ $categoria->nombre }}</td><td class="text-gray-500">{{ $categoria->slug }}</td><td>{{ $categoria->productos_count }}</td><td class="px-5 py-3"><div class="flex flex-wrap gap-3">
        @if(request('papelera'))
            <form method="POST" action="{{ route('admin.categorias.restore', $categoria->id) }}" @submit.prevent="requestConfirm($el, '¿Restaurar esta categoría?')">@csrf @method('PATCH')<button type="submit" class="font-semibold text-green-700 hover:underline">Restaurar</button></form>
        @else
            <a href="{{ route('admin.categorias.edit', $categoria) }}" class="text-amber-800 hover:underline">Editar</a>
            <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}" @submit.prevent="requestConfirm($el, '¿Enviar esta categoría a la papelera?')">@csrf @method('DELETE')<button type="submit" class="text-red-700 hover:underline">Eliminar</button></form>
        @endif
    </div></td></tr>@empty<tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">Aún no hay categorías.</td></tr>@endforelse
    </tbody></table></div>
    <div class="flex flex-wrap items-center justify-between gap-3"><a href="{{ route('admin.categorias.index', request('papelera') ? [] : ['papelera' => 1]) }}" class="text-sm text-amber-800 hover:underline">{{ request('papelera') ? '← Ver categorías activas' : 'Ver papelera' }}</a>{{ $categorias->links() }}</div>
</div>
@endsection
