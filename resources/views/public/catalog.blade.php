@extends('public.layout')

@section('title', 'Catálogo - Qori Chocolates')
@section('meta_description', 'Explora la selección de chocolates artesanales, tabletas, bombones y presentaciones especiales de Qori.')

@section('content')
    <nav class="catalog-breadcrumb" aria-label="Ruta de navegación">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-3 sm:px-6 lg:px-8">
            <ol>
                <li><a href="{{ route('public.home') }}">Inicio</a></li>
                <li aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                </li>
                <li aria-current="page">Catálogo</li>
            </ol>
            <a class="breadcrumb-back" href="{{ route('public.home') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                Volver al inicio
            </a>
        </div>
    </nav>

    <section class="catalog-hero">
        <div class="mx-auto grid max-w-7xl items-end gap-8 px-5 py-14 sm:px-6 sm:py-16 lg:grid-cols-[1fr_auto] lg:px-8">
            <div>
                <p class="catalog-kicker">Selección artesanal de Sucre</p>
                <h1>Chocolate para regalar,<br><em>compartir y disfrutar</em></h1>
            </div>
            <p class="catalog-intro">Barras, bombones, cajas y souvenirs elaborados con atención al detalle y disponibles según stock.</p>
        </div>
    </section>

    <section
        x-data="{ category: 'todos', search: '' }"
        class="catalog-section"
    >
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="catalog-toolbar">
                <div class="catalog-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg>
                    <label for="catalog-search" class="sr-only">Buscar producto</label>
                    <input id="catalog-search" type="search" x-model.debounce.150ms="search" placeholder="Buscar en el catálogo">
                </div>
                <p class="catalog-count"><strong>{{ $productos->count() }}</strong> productos</p>
            </div>

            <div class="catalog-controls" role="group" aria-label="Filtrar catálogo">
                <button
                    type="button"
                    @click="category = 'todos'"
                    :class="category === 'todos' ? 'is-active' : ''"
                    :aria-pressed="(category === 'todos').toString()"
                >Todos</button>
                @foreach ($categorias as $categoria)
                    <button
                        type="button"
                        @click="category = '{{ $categoria->id }}'"
                        :class="category === '{{ $categoria->id }}' ? 'is-active' : ''"
                        :aria-pressed="(category === '{{ $categoria->id }}').toString()"
                    >{{ $categoria->nombre }}</button>
                @endforeach
            </div>

            <p class="sr-only" aria-live="polite" x-text="`${[...$el.parentElement.querySelectorAll('.catalog-card')].filter(card => card.style.display !== 'none').length} productos mostrados`"></p>
            <div class="catalog-grid">
                @forelse ($productos as $producto)
                    @php
                        $imagenesPorId = [
                            1 => 'amargo1',
                            2 => 'leche2',
                            3 => 'almendras3',
                            4 => 'manjar4',
                            5 => 'menta5',
                            6 => 'nuez6',
                            7 => 'clasica7',
                            8 => 'premium8',
                            9 => 'sucre9',
                            10 => 'corazon10',
                            11 => 'mensaje11',
                            12 => 'surtidas12',
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
                    <article
                        x-show="(category === 'todos' || category === '{{ $producto->categoria_id }}') && '{{ strtolower($producto->nombre) }}'.includes(search.toLowerCase())"
                        x-transition
                        class="catalog-card"
                    >
                        @if ($imagenProducto)
                            <div class="catalog-card-image">
                                <img src="{{ asset($imagenProducto) }}" alt="{{ $producto->nombre }}" loading="lazy" width="600" height="480">
                            </div>
                        @else
                            <div class="catalog-card-image catalog-card-placeholder">
                                <span>Imagen no disponible</span>
                            </div>
                        @endif
                        <div class="catalog-card-body">
                            <p class="catalog-card-category">{{ $producto->categoria_nombre }}</p>
                            <h2><a href="{{ route('public.product', $producto) }}">{{ $producto->nombre }}</a></h2>
                            <div class="catalog-card-footer">
                                <div>
                                    <span class="catalog-card-label">Precio</span>
                                    <p class="catalog-card-price">Bs {{ number_format((float) $producto->precio, 2) }}</p>
                                </div>
                                @if ($producto->stock > 0)
                                    <button
                                        type="button"
                                        class="catalog-add"
                                        data-product-id="{{ $producto->id }}"
                                        data-product-name="{{ $producto->nombre }}"
                                        data-product-price="{{ (float) $producto->precio }}"
                                        data-product-stock="{{ $producto->stock }}"
                                    >
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                                        <span>Agregar</span>
                                    </button>
                                @else
                                    <p class="catalog-card-out">Agotado</p>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="col-span-full rounded-2xl border border-stone-200 bg-stone-50 p-8 text-center text-stone-600">No hay productos disponibles en este momento.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="catalog-cta">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="catalog-cta-inner">
                <div>
                    <p>Diseñado para tu ocasión</p>
                    <h2>¿Buscas una presentación especial?</h2>
                </div>
                <a href="{{ route('public.contact') }}">
                    Consultar opciones
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
        </div>
    </section>
@endsection
