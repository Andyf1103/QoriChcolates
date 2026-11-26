@extends('admin.layout')
@section('title', 'Nueva categoría')
@section('heading', 'Nueva categoría')
@section('content')
<main class="mx-auto max-w-xl p-4 sm:p-6" x-data="{ name: '{{ old('nombre') }}', slug: '{{ old('slug') }}', slugify(value) { return value.toString().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '').toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') } }">
    <h1 class="text-2xl font-bold">Nueva categoría</h1>
    <form method="POST" action="{{ route('admin.categorias.store') }}" class="mt-6 space-y-4 rounded-lg border bg-white p-6">
        @csrf
        <label class="block">Nombre<input name="nombre" x-model="name" @input="if (!slug) slug = slugify(name)" value="{{ old('nombre') }}" required aria-describedby="nombre-help" class="mt-1 w-full rounded border px-3 py-2"><span id="nombre-help" class="text-xs text-gray-500">Se mostrará en el catálogo.</span>@error('nombre')<span class="block text-sm text-red-700">{{ $message }}</span>@enderror</label>
        <label class="block">Slug<input name="slug" x-model="slug" @input="slug = slugify(slug)" value="{{ old('slug') }}" required placeholder="barras-chocolate" class="mt-1 w-full rounded border px-3 py-2"><span class="text-xs text-gray-500">Vista previa: <span x-text="slug || 'tu-slug'"></span></span>@error('slug')<span class="block text-sm text-red-700">{{ $message }}</span>@enderror</label>
        <div class="flex gap-3"><button type="submit" class="rounded bg-amber-800 px-4 py-2 text-white">Guardar</button><a href="{{ route('admin.categorias.index') }}">Cancelar</a></div>
    </form>
</main>
@endsection
