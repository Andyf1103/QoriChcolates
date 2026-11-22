@extends('public.layout')

@section('title', 'Nosotros - Qori Chocolates')
@section('meta_description', 'Conoce la presentación de Qori Chocolates y su catálogo disponible en Sucre, Bolivia.')

@section('content')
    @include('public.partials.breadcrumb', ['label' => 'Nosotros'])
    <section class="public-page-header">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <p class="section-kicker">Nuestra historia</p>
            <h1 class="mt-5 max-w-4xl text-5xl font-bold text-amber-950 sm:text-6xl">Qori Chocolates desde Sucre</h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-600">Presentamos una selección de chocolates organizada por categorías, precios y disponibilidad.</p>
        </div>
    </section>

    <section class="bg-white py-20 lg:py-24">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 md:grid-cols-[.92fr_1.08fr] lg:px-8">
            <div class="story-image">
                <img src="{{ asset('tailadmin/assets/images/product/surtidas12.jpg') }}" alt="Selección artesanal de chocolates de Qori" loading="lazy" width="720" height="820">
                <span>Hecho con paciencia</span>
            </div>
            <div class="story-copy">
                <p class="section-kicker">Quiénes somos</p>
                <h2 class="mt-4 text-4xl font-bold text-amber-950 sm:text-5xl">Una selección pensada para compartir</h2>
                <div class="mt-7 space-y-5 leading-7 text-stone-600">
                    <p>El catálogo reúne barras, bombones, cajas de regalo y souvenirs con información clara de categoría, precio y stock.</p>
                    <p>La compra se coordina directamente para confirmar disponibilidad y los detalles necesarios antes de cerrar el pedido.</p>
                </div>
                <div class="story-signature">
                    <span></span>
                    <p><strong>Qori Chocolates</strong><small>Sucre, Bolivia</small></p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-amber-950 py-20 text-white lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="section-kicker section-kicker-light">Lo que nos guía</p>
                <h2 class="mt-4 font-serif text-4xl font-bold sm:text-5xl">Nuestros valores</h2>
            </div>
            <div class="values-grid mt-10">
                <article>
                    <span>01</span>
                    <h3>Presentación</h3>
                    <p>Opciones claras para regalar, celebrar o disfrutar.</p>
                </article>
                <article>
                    <span>02</span>
                    <h3>Información</h3>
                    <p>Precios y cantidades visibles en el catálogo.</p>
                </article>
                <article>
                    <span>03</span>
                    <h3>Disponibilidad</h3>
                    <p>La consulta confirma el stock antes de continuar.</p>
                </article>
                <article>
                    <span>04</span>
                    <h3>Contacto</h3>
                    <p>Coordinación directa sobre cada pedido.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-[#f7efe2] py-16">
        <div class="mx-auto flex max-w-5xl flex-col items-center px-4 text-center sm:px-6">
            <h2 class="text-3xl font-bold text-amber-950">Elige la presentación adecuada</h2>
            <p class="mt-4 max-w-2xl leading-7 text-stone-600">Consulta el proceso de compra y encuentra la opción que mejor se adapte a tu ocasión.</p>
            <a href="{{ route('public.process') }}" class="mt-7 rounded-full bg-amber-900 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-amber-800">Cómo comprar</a>
        </div>
    </section>
@endsection
