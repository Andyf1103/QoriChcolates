<nav class="catalog-breadcrumb" aria-label="Ruta de navegación">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-3 sm:px-6 lg:px-8">
        <ol>
            <li><a href="{{ route('public.home') }}">Inicio</a></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page">{{ $label }}</li>
        </ol>
        <a class="breadcrumb-back" href="{{ route('public.home') }}">Volver al inicio</a>
    </div>
</nav>
