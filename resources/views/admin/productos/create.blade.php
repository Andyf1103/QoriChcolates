@extends('admin.layout')
@section('title', 'Nuevo producto')
@section('heading', 'Nuevo producto')
@section('content')
<main class="mx-auto max-w-3xl p-4 sm:p-6">
    <div class="mb-4 flex items-center justify-between gap-3">
        <a href="{{ route('admin.productos.index') }}" class="text-sm text-amber-800 hover:underline">← Volver a productos</a>
        <button form="producto-form" id="guardar-producto" type="submit" class="admin-primary-action" style="border: 0; cursor: pointer;">Guardar producto</button>
    </div>
    <form id="producto-form" method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data" class="mt-4 grid gap-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 md:grid-cols-2 dark:border-gray-800 dark:bg-gray-950">
        @csrf
        <div class="md:col-span-2"><label for="nombre" class="font-medium">Nombre <span class="text-red-700">*</span></label><input id="nombre" name="nombre" value="{{ old('nombre') }}" required aria-describedby="nombre-help" class="mt-1 w-full rounded-lg border-gray-300 px-3 py-3 focus:border-amber-700 focus:ring-amber-700"><p id="nombre-help" class="mt-1 text-xs text-gray-500">Usa un nombre claro para el catálogo.</p>@error('nombre')<p class="text-sm text-red-700">{{ $message }}</p>@enderror</div>
        <div><label for="categoria_id" class="font-medium">Categoría <span class="text-red-700">*</span></label><select id="categoria_id" name="categoria_id" required class="mt-1 w-full rounded-lg border-gray-300 px-3 py-3 focus:border-amber-700 focus:ring-amber-700"><option value="">Seleccione...</option>@foreach($categorias as $categoria)<option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>{{ $categoria->nombre }}</option>@endforeach</select>@error('categoria_id')<p class="text-sm text-red-700">{{ $message }}</p>@enderror</div>
        <div><label for="precio" class="font-medium">Precio (Bs) <span class="text-red-700">*</span></label><input id="precio" type="number" step="0.01" min="0.01" name="precio" value="{{ old('precio') }}" required class="mt-1 w-full rounded-lg border-gray-300 px-3 py-3 focus:border-amber-700 focus:ring-amber-700">@error('precio')<p class="text-sm text-red-700">{{ $message }}</p>@enderror</div>
        <div><label for="stock" class="font-medium">Stock inicial <span class="text-red-700">*</span></label><input id="stock" type="number" min="0" name="stock" value="{{ old('stock', 0) }}" required class="mt-1 w-full rounded-lg border-gray-300 px-3 py-3 focus:border-amber-700 focus:ring-amber-700">@error('stock')<p class="text-sm text-red-700">{{ $message }}</p>@enderror</div>
        <div class="md:col-span-2">
            <label for="imagen" class="font-medium">Imagen del producto</label>
            <input id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1 w-full rounded-lg border-gray-300 px-3 py-3 focus:border-amber-700 focus:ring-amber-700">
            <p class="mt-1 text-xs text-gray-500">Opcional. Selecciona una imagen JPEG, PNG o WEBP de hasta 2 MB.</p>
            @error('imagen')<p class="text-sm text-red-700">{{ $message }}</p>@enderror
        </div>
    </form>
</main>
@endsection
