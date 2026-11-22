@extends('public.layout')

@section('title', 'Nuestro Proceso - Qori Chocolates')
@section('meta_description', 'Conoce los pasos para seleccionar, validar y coordinar tu pedido en Qori Chocolates.')

@section('content')
    @include('public.partials.breadcrumb', ['label' => 'Nuestro proceso'])
    <section class="process-hero overflow-hidden border-b border-amber-900/10 text-white">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 md:grid-cols-[1.05fr_.95fr] sm:py-20 lg:px-8">
            <div>
                <p class="section-kicker section-kicker-light">Compra clara y directa</p>
                <h1 class="mt-5 text-5xl font-bold leading-[1.02] sm:text-6xl">Cómo realizar tu pedido</h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-amber-100/70">Sigue cuatro pasos simples para elegir tus productos y coordinar la confirmación con Qori Chocolates.</p>
            </div>
            <div class="process-hero-image">
                <img src="{{ asset('tailadmin/assets/images/product/clasica7.jpg') }}" alt="Proceso de elaboración del chocolate" loading="lazy" width="760" height="760">
            </div>
        </div>
    </section>

    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-5">
                <article class="grid overflow-hidden rounded-3xl border border-stone-200 md:grid-cols-[90px_1fr]">
                    <div class="flex min-h-24 items-center justify-center bg-[#f2e7d5] text-2xl font-bold text-amber-900 md:min-h-full">01</div>
                    <div class="p-6 sm:p-8">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-700">Selección</p>
                        <h2 class="mt-2 text-2xl font-bold text-amber-950">Explora el catálogo</h2>
                        <p class="mt-3 max-w-3xl leading-7 text-stone-600">Revisa las categorías, nombres, precios y cantidades disponibles de cada producto.</p>
                    </div>
                </article>
                <article class="grid overflow-hidden rounded-3xl border border-stone-200 md:grid-cols-[90px_1fr]">
                    <div class="flex min-h-24 items-center justify-center bg-[#f2e7d5] text-2xl font-bold text-amber-900 md:min-h-full">02</div>
                    <div class="p-6 sm:p-8">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-700">Carrito</p>
                        <h2 class="mt-2 text-2xl font-bold text-amber-950">Añade las cantidades</h2>
                        <p class="mt-3 max-w-3xl leading-7 text-stone-600">Agrega los productos que deseas y ajusta las cantidades dentro del stock disponible.</p>
                    </div>
                </article>
                <article class="grid overflow-hidden rounded-3xl border border-stone-200 md:grid-cols-[90px_1fr]">
                    <div class="flex min-h-24 items-center justify-center bg-[#f2e7d5] text-2xl font-bold text-amber-900 md:min-h-full">03</div>
                    <div class="p-6 sm:p-8">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-700">Validación</p>
                        <h2 class="mt-2 text-2xl font-bold text-amber-950">Revisa tu pedido</h2>
                        <p class="mt-3 max-w-3xl leading-7 text-stone-600">El sistema verifica los productos y recalcula la disponibilidad antes de continuar.</p>
                    </div>
                </article>
                <article class="grid overflow-hidden rounded-3xl border border-stone-200 md:grid-cols-[90px_1fr]">
                    <div class="flex min-h-24 items-center justify-center bg-[#f2e7d5] text-2xl font-bold text-amber-900 md:min-h-full">04</div>
                    <div class="p-6 sm:p-8">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-700">Contacto</p>
                        <h2 class="mt-2 text-2xl font-bold text-amber-950">Coordina la confirmación</h2>
                        <p class="mt-3 max-w-3xl leading-7 text-stone-600">Envía tu consulta para confirmar disponibilidad y acordar los detalles del pedido.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="border-y border-amber-900/10 bg-[#f7efe2] py-16">
        <div class="mx-auto flex max-w-5xl flex-col items-center px-4 text-center sm:px-6">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-amber-700">Siguiente paso</p>
            <h2 class="mt-4 text-3xl font-bold text-amber-950">Encuentra tu selección</h2>
            <p class="mt-4 max-w-2xl leading-7 text-stone-600">Explora las variedades disponibles y coordina tu pedido con el equipo.</p>
            <a href="{{ route('public.catalog') }}" class="mt-7 rounded-full bg-amber-900 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-amber-800">Ver catálogo</a>
        </div>
    </section>
@endsection
