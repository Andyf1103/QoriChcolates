@extends('public.layout')

@section('title', 'Página no encontrada - Qori Chocolates')
@section('meta_description', 'La página solicitada no existe en Qori Chocolates.')

@section('content')
    <section class="mx-auto flex min-h-[60vh] max-w-3xl items-center justify-center px-5 py-20 text-center">
        <div>
            <p class="text-sm font-black uppercase tracking-[0.24em] text-amber-700">Error 404</p>
            <h1 class="mt-5 text-5xl font-bold text-amber-950">Esta página no existe</h1>
            <p class="mt-5 text-lg leading-8 text-stone-600">Puedes volver al inicio o explorar el catálogo disponible.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('public.home') }}" class="button button-primary">Ir al inicio</a>
                <a href="{{ route('public.catalog') }}" class="button button-secondary">Ver catálogo</a>
            </div>
        </div>
    </section>
@endsection
