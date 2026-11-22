@extends('public.layout')

@section('title', 'Contacto - Qori Chocolates')
@section('meta_description', 'Consulta sobre pedidos, presentaciones especiales y información de Qori Chocolates.')

@section('content')
    @include('public.partials.breadcrumb', ['label' => 'Contacto'])
    <section class="public-page-header border-b border-amber-900/10">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <p class="section-kicker">Hablemos</p>
            <h1 class="mt-5 text-5xl font-bold text-amber-950 sm:text-6xl">Contacto</h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-600">Estamos disponibles para conversar sobre chocolates, presentaciones especiales y oportunidades de colaboración.</p>
        </div>
    </section>

    <section class="bg-white py-16 sm:py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[.72fr_1.28fr] lg:px-8">
            <aside class="space-y-5">
                <article class="rounded-2xl border border-stone-200 bg-stone-50 p-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-700">¿En qué podemos ayudarte?</p>
                    <p class="mt-3 text-sm leading-6 text-stone-600">Consulta disponibilidad, precios o presentaciones de los productos del catálogo. Te responderemos con información confirmada.</p>
                </article>
                <article class="rounded-2xl border border-stone-200 bg-white p-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-700">Atención directa</p>
                    <a href="tel:+59169682107" class="mt-3 block text-xl font-bold text-amber-950">+591 69682107</a>
                    <a
                        href="https://wa.me/59169682107?text={{ urlencode('Hola, quiero realizar una consulta sobre los chocolates de Qori Chocolates.') }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-4 inline-flex min-h-11 items-center rounded-full bg-green-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-green-800"
                    >
                        Escribir por WhatsApp
                    </a>
                </article>
            </aside>

            <div class="rounded-[2rem] border border-amber-900/10 bg-[#f7efe2] p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-amber-950">Envía tu consulta</h2>
                @if (session('success'))
                    <div role="status" class="mt-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div role="alert" class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <p class="font-bold">Revisa los campos indicados:</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('public.contact.store') }}" method="post" class="mt-7 grid gap-5 sm:grid-cols-2" aria-label="Formulario de contacto">
                    @csrf
                    <div>
                        <label for="name" class="mb-2 block text-sm font-bold text-stone-700">Nombre</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" aria-describedby="name-error" class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-900 outline-none transition focus:border-amber-700 focus:ring-4 focus:ring-amber-700/10" placeholder="Tu nombre">
                        @error('name')
                            <p id="name-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="subject" class="mb-2 block text-sm font-bold text-stone-700">Tipo de consulta</label>
                        <select id="subject" name="subject" required aria-describedby="subject-help" class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-900 outline-none transition focus:border-amber-700 focus:ring-4 focus:ring-amber-700/10">
                            <option value="">Selecciona una opción</option>
                            <option value="Disponibilidad" @selected(old('subject') === 'Disponibilidad')>Disponibilidad</option>
                            <option value="Pedido especial" @selected(old('subject') === 'Pedido especial')>Pedido especial</option>
                            <option value="Otra consulta" @selected(old('subject') === 'Otra consulta')>Otra consulta</option>
                        </select>
                        <p id="subject-help" class="mt-2 text-xs text-stone-600">Elige el motivo principal de tu mensaje.</p>
                        @error('subject')
                            <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-bold text-stone-700">Correo electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" aria-describedby="email-error" class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-900 outline-none transition focus:border-amber-700 focus:ring-4 focus:ring-amber-700/10" placeholder="tu@correo.com">
                        @error('email')
                            <p id="email-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="message" class="mb-2 block text-sm font-bold text-stone-700">Mensaje</label>
                        <textarea id="message" name="message" rows="5" required aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}" aria-describedby="message-error" class="w-full resize-y rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-900 outline-none transition focus:border-amber-700 focus:ring-4 focus:ring-amber-700/10" placeholder="Cuéntanos cómo podemos ayudarte">{{ old('message') }}</textarea>
                        @error('message')
                            <p id="message-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="hidden" aria-hidden="true">
                        <label for="website">No completar</label>
                        <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="sm:col-span-2">
                        <button type="submit" class="inline-flex min-h-12 items-center justify-center rounded-full bg-amber-900 px-7 py-3.5 text-sm font-bold text-white shadow-md transition hover:bg-amber-800 disabled:cursor-wait disabled:opacity-70">
                            Enviar consulta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    @push('scripts')
    <script>
        (() => {
            const form = document.querySelector('form[action="{{ route('public.contact.store') }}"]');
            if (!form) return;
            form.addEventListener('submit', () => {
                const button = form.querySelector('button[type="submit"]');
                if (!button) return;
                button.disabled = true;
                button.textContent = 'Enviando...';
            });
        })();
    </script>
    @endpush
@endsection
