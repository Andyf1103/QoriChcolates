@extends('public.layout')

@section('title', 'Qori Chocolates | Chocolate artesanal de Sucre')
@section('meta_description', 'Conoce el catálogo de chocolates de Qori Chocolates en Sucre y consulta disponibilidad de barras, bombones y cajas de regalo.')

@section('content')
    <section class="hero-section">
        <div class="hero-glow" aria-hidden="true"></div>
        <div class="mx-auto grid min-h-[650px] max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[1.05fr_.95fr] lg:px-8 lg:py-20">
            <div class="hero-copy">
                <p class="eyebrow">
                    <span class="eyebrow-line"></span>
                    Chocolate con identidad sucreña
                </p>
                <h1>Chocolate para regalar, <em>compartir y celebrar</em></h1>
                <p class="hero-lead">Explora 12 presentaciones de Qori Chocolates y consulta disponibilidad para encontrar la opción adecuada para tu ocasión.</p>
                <div class="hero-actions">
                    <a href="{{ route('public.catalog') }}" class="button button-primary">
                        Explorar el catálogo
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    <a href="{{ route('public.process') }}" class="button button-secondary">
                        Conocer nuestro proceso
                    </a>
                </div>
                <div class="hero-proof">
                    <div class="proof-item">
                        <span class="proof-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 22V8M12 8C12 4 8 3 5 3c0 4 2 7 7 7v-2ZM12 8c0-4 4-5 7-5 0 4-2 7-7 7"/></svg>
                        </span>
                        <span><strong>Catálogo completo</strong><small>12 productos disponibles</small></span>
                    </div>
                    <div class="proof-divider"></div>
                    <div class="proof-item">
                        <span class="proof-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 7c-5-4-11-4-16 0l8 4 8-4Z"/><path d="m4 7 8 4v10l-8-4V7ZM20 7l-8 4v10l8-4V7Z"/></svg>
                        </span>
                        <span><strong>Compra sencilla</strong><small>Selección y consulta directa</small></span>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-frame">
                    <img src="{{ asset('tailadmin/assets/images/product/presentacion.jpg') }}" alt="Presentación artesanal de chocolates Qori" width="900" height="1050" fetchpriority="high">
                </div>
                <div class="hero-badge">
                    <span class="hero-badge-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M12 21s7-3.7 7-10V5l-7-2-7 2v6c0 6.3 7 10 7 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                    </span>
                    <span><small>Presentación</small><strong>Sucre, Bolivia</strong></span>
                </div>
                <div class="hero-caption">
                    <span>01</span>
                    <p>Creaciones pensadas para regalar, celebrar y compartir.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="trust-strip" aria-label="Características de Qori Chocolates">
        <div class="mx-auto grid max-w-7xl grid-cols-2 px-4 sm:px-6 md:grid-cols-4 lg:px-8">
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M12 22V8M12 8C12 4 8 3 5 3c0 4 2 7 7 7ZM12 8c0-4 4-5 7-5 0 4-2 7-7 7Z"/></svg>
                <span><strong>12 productos</strong><small>Cuatro categorías</small></span>
            </div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m12 3 2.5 5.2L20 9l-4 4 1 6-5-3-5 3 1-6-4-4 5.5-.8L12 3Z"/></svg>
                <span><strong>Precios claros</strong><small>Consultados en catálogo</small></span>
            </div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 20V8l8-5 8 5v12M8 20v-7h8v7M3 20h18"/></svg>
                <span><strong>Sucre, Bolivia</strong><small>Presencia local</small></span>
            </div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M20 7c-5-4-11-4-16 0l8 4 8-4ZM4 7l8 4v10l-8-4V7ZM20 7l-8 4v10l8-4V7Z"/></svg>
                <span><strong>Stock visible</strong><small>Disponibilidad actual</small></span>
            </div>
        </div>
    </section>

    <section class="collection-section">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8 lg:py-28">
            <div class="section-heading">
                <div>
                    <p class="section-kicker">Nuestras creaciones</p>
                    <h2>Una selección para compartir</h2>
                </div>
                <a href="{{ route('public.catalog') }}" class="text-link">
                    Ver todo el catálogo
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>

            <div class="collection-grid">
                <article class="collection-card collection-card-featured">
                    <div class="collection-image">
                        <img src="{{ asset('tailadmin/assets/images/product/amargo1.jpg') }}" alt="Chocolate oscuro artesanal" loading="lazy" width="700" height="560">
                        <span class="collection-tag">Intensidad</span>
                    </div>
                    <div class="collection-copy">
                        <p>Barra artesanal</p>
                        <h3>Chocolate oscuro 70%</h3>
                        <span>Perfil profundo con final persistente.</span>
                        <a href="{{ route('public.catalog') }}" aria-label="Ver chocolate oscuro 70%">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </article>
                <article class="collection-card">
                    <div class="collection-image">
                        <img src="{{ asset('tailadmin/assets/images/product/manjar4.jpg') }}" alt="Bombones artesanales" loading="lazy" width="700" height="560">
                        <span class="collection-tag">Tradición</span>
                    </div>
                    <div class="collection-copy">
                        <p>Bombonería</p>
                        <h3>Bombones artesanales</h3>
                        <span>Rellenos equilibrados y presentación cuidada.</span>
                        <a href="{{ route('public.catalog') }}" aria-label="Ver bombones artesanales">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </article>
                <article class="collection-card">
                    <div class="collection-image">
                        <img src="{{ asset('tailadmin/assets/images/product/premium8.jpg') }}" alt="Caja premium de chocolates" loading="lazy" width="700" height="560">
                        <span class="collection-tag">Regalo</span>
                    </div>
                    <div class="collection-copy">
                        <p>Presentación especial</p>
                        <h3>Cajas para celebrar</h3>
                        <span>Una experiencia completa hecha para sorprender.</span>
                        <a href="{{ route('public.catalog') }}" aria-label="Ver cajas para celebrar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="craft-section">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-24 sm:px-6 lg:grid-cols-[.92fr_1.08fr] lg:px-8 lg:py-28">
            <div class="craft-image">
                <img src="{{ asset('tailadmin/assets/images/product/presentacion2.jpg') }}" alt="Selección variada de chocolates artesanales" loading="lazy" width="800" height="920">
                <div class="craft-stamp">
                    <span>Desde</span>
                    <strong>Sucre</strong>
                    <span>con carácter</span>
                </div>
            </div>
            <div class="craft-copy">
                <p class="section-kicker section-kicker-light">Nuestro compromiso</p>
                <h2>Presentaciones para cada momento</h2>
                <p class="craft-lead">Conoce las variedades disponibles y elige barras, bombones, cajas o souvenirs según tu ocasión.</p>
                <div class="craft-values">
                    <article>
                        <span>01</span>
                        <div><h3>Variedad real</h3><p>Información basada en los productos registrados.</p></div>
                    </article>
                    <article>
                        <span>02</span>
                        <div><h3>Compra transparente</h3><p>Precio y stock visibles antes de consultar.</p></div>
                    </article>
                    <article>
                        <span>03</span>
                        <div><h3>Atención directa</h3><p>Disponibilidad y detalles coordinados por consulta.</p></div>
                    </article>
                </div>
                <a href="{{ route('public.about') }}" class="button button-cream">Conocer nuestra historia</a>
            </div>
        </div>
    </section>

    <section class="bg-white py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-end gap-8 md:grid-cols-[.75fr_1.25fr]">
                <div>
                    <p class="section-kicker">Cómo comprar</p>
                    <h2 class="mt-4 max-w-md font-serif text-4xl font-bold leading-tight text-amber-950 sm:text-5xl">Tu pedido, en tres pasos sencillos</h2>
                </div>
                <p class="max-w-xl text-base leading-7 text-stone-600 md:justify-self-end">Selecciona tus chocolates y coordina directamente con nosotros. Confirmaremos disponibilidad, método de pago y entrega antes de cerrar el pedido.</p>
            </div>
            <div class="mt-12 grid overflow-hidden rounded-2xl border border-amber-900/10 md:grid-cols-3">
                <article class="order-step">
                    <span>01</span>
                    <h3>Elige</h3>
                    <p>Explora el catálogo y agrega tus productos al carrito.</p>
                </article>
                <article class="order-step">
                    <span>02</span>
                    <h3>Confirma</h3>
                    <p>Envía tu selección desde la página de contacto para revisar disponibilidad.</p>
                </article>
                <article class="order-step">
                    <span>03</span>
                    <h3>Coordina</h3>
                    <p>El equipo te indicará el pago y la opción de entrega disponibles.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="border-y border-amber-900/10 bg-[#f7efe2] py-24">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            <div class="text-center">
                <p class="section-kicker justify-center">Resolvemos tus dudas</p>
                <h2 class="mt-4 font-serif text-4xl font-bold text-amber-950 sm:text-5xl">Preguntas frecuentes</h2>
            </div>
            <div class="mt-10 divide-y divide-amber-900/10 border-y border-amber-900/10">
                <details class="faq-item">
                    <summary>¿Cómo puedo realizar un pedido?<span aria-hidden="true">+</span></summary>
                    <p>Selecciona los productos en el catálogo, revisa tu carrito y utiliza la opción de consulta para compartir tu pedido con el equipo.</p>
                </details>
                <details class="faq-item">
                    <summary>¿Dónde puedo consultar disponibilidad y precios?<span aria-hidden="true">+</span></summary>
                    <p>Los productos disponibles y sus precios se muestran en el catálogo. Si necesitas una presentación especial, escribe desde la sección de contacto.</p>
                </details>
                <details class="faq-item">
                    <summary>¿Aceptan pagos en línea o realizan entregas?<span aria-hidden="true">+</span></summary>
                    <p>Los métodos de pago y entrega deben confirmarse directamente antes de finalizar cada pedido. Esta información puede variar según ubicación y cantidad.</p>
                </details>
                <details class="faq-item">
                    <summary>¿Puedo solicitar cajas personalizadas o pedidos para empresas?<span aria-hidden="true">+</span></summary>
                    <p>Sí puedes consultar posibilidades de personalización, eventos y pedidos corporativos desde el formulario de contacto.</p>
                </details>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-card mx-auto max-w-7xl px-6 py-14 sm:px-10 lg:px-14 lg:py-16">
            <div>
                <p class="section-kicker">Hagamos algo especial</p>
                <h2>¿Buscas una presentación para una ocasión especial?</h2>
            </div>
            <a href="{{ route('public.contact') }}" class="button button-primary">
                Consultar ahora
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>
    </section>
@endsection
