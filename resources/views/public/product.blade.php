@extends('public.layout')

@section('title', $producto->nombre . ' - Qori Chocolates')
@section('meta_description', 'Consulta disponibilidad y precio de ' . $producto->nombre . ' en el catálogo de Qori Chocolates.')

@php
    $imagenesPorId = [
        1 => 'amargo1', 2 => 'leche2', 3 => 'almendras3', 4 => 'manjar4',
        5 => 'menta5', 6 => 'nuez6', 7 => 'clasica7', 8 => 'premium8',
        9 => 'sucre9', 10 => 'corazon10', 11 => 'mensaje11', 12 => 'surtidas12',
    ];
    $imagenProducto = $producto->imagen_principal ? 'storage/' . $producto->imagen_principal : null;
    $nombreBase = $imagenesPorId[(int) $producto->id] ?? null;
    if (! $imagenProducto && $nombreBase) {
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
            $rutaImagen = 'tailadmin/assets/images/product/' . $nombreBase . '.' . $extension;
            if (is_file(public_path($rutaImagen))) {
                $imagenProducto = $rutaImagen;
                break;
            }
        }
    }
@endphp

@section('content')
    <nav class="catalog-breadcrumb" aria-label="Ruta de navegación">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-3 sm:px-6 lg:px-8">
            <ol>
                <li><a href="{{ route('public.home') }}">Inicio</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('public.catalog') }}">Catálogo</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page">{{ $producto->nombre }}</li>
            </ol>
            <a class="breadcrumb-back" href="{{ route('public.catalog') }}">Volver al catálogo</a>
        </div>
    </nav>
    <section class="bg-white py-16 sm:py-24">
        <div class="mx-auto grid max-w-6xl gap-10 px-5 sm:px-6 md:grid-cols-2 lg:px-8">
            <div class="catalog-card-image">
                @if ($imagenProducto)
                    <img src="{{ asset($imagenProducto) }}" alt="{{ $producto->nombre }}" width="800" height="640">
                @else
                    <span>Imagen no disponible</span>
                @endif
            </div>
            <div class="flex flex-col justify-center">
                <p class="catalog-card-category">{{ $producto->categoria_nombre }}</p>
                <h1 class="mt-3 text-4xl font-bold text-amber-950">{{ $producto->nombre }}</h1>
                <p class="mt-6 text-3xl font-bold text-amber-900">Bs {{ number_format((float) $producto->precio, 2) }}</p>
                <p class="mt-4 text-stone-600">
                    @if ($producto->stock > 0)
                        {{ $producto->stock }} unidades disponibles.
                    @else
                        Producto agotado por el momento.
                    @endif
                </p>
                @if ($producto->stock > 0)
                    <button type="button" class="catalog-add button button-primary mt-8 w-fit"
                        data-product-id="{{ $producto->id }}"
                        data-product-name="{{ $producto->nombre }}"
                        data-product-price="{{ (float) $producto->precio }}"
                        data-product-stock="{{ $producto->stock }}">
                        Agregar al carrito
                    </button>
                    <a
                        href="https://wa.me/59169682107?text={{ urlencode('Hola, quiero consultar por: ' . $producto->nombre . ' (Bs ' . number_format((float) $producto->precio, 2) . ').') }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-3 inline-flex min-h-12 w-fit items-center justify-center rounded-full border border-green-700 px-6 py-3 text-sm font-bold text-green-800 transition hover:bg-green-50"
                    >
                        Consultar por WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection
