<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Chocolates artesanales elaborados con dedicacion en Sucre, Bolivia.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Qori Chocolates')">
    <meta property="og:description" content="@yield('meta_description', 'Chocolates artesanales de Qori Chocolates.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Qori Chocolates')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logoqori.png') }}">
    <link rel="stylesheet" href="{{ asset('tailadmin/assets/style.css') }}?v={{ filemtime(public_path('tailadmin/assets/style.css')) }}">
    <link rel="stylesheet" href="{{ asset('tailadmin/assets/public.css') }}?v={{ filemtime(public_path('tailadmin/assets/public.css')) }}">
    @vite(['resources/js/app.js'])
    @stack('styles')
</head>

<body
    x-data="{ mobileMenuOpen: false, cartOpen: false }"
    x-effect="document.body.style.overflow = cartOpen ? 'hidden' : ''"
    x-on:keydown.escape.window="cartOpen = false"
    x-on:cart-open.window="cartOpen = true"
    class="site-shell min-h-screen bg-[#faf8f4] text-stone-900 antialiased"
>
    <a class="skip-link" href="#main-content">Saltar al contenido principal</a>
    <div class="site-announcement">
        <div class="mx-auto flex max-w-7xl items-center justify-center gap-2 px-4 text-center sm:px-6 lg:px-8">
            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 21s7-3.7 7-10V5l-7-2-7 2v6c0 6.3 7 10 7 10Z"/><path d="m9 12 2 2 4-4"/></svg>
            <span>Chocolate artesanal elaborado en Sucre, Bolivia</span>
        </div>
    </div>
    <header class="site-header sticky top-0 z-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-[78px] items-center justify-between">
                <a href="{{ route('public.home') }}" class="brand-link" aria-label="Ir al inicio de Qori Chocolates">
                    <img
                        src="{{ asset('images/logoqori.png') }}"
                        alt="Qori Chocolates"
                        class="brand-logo brand-logo-header"
                        width="744"
                        height="818"
                        loading="eager"
                    >
                </a>

                <nav class="hidden items-center gap-0.5 lg:flex" aria-label="Navegacion principal">
                    <a href="{{ route('public.home') }}"
                        class="nav-link {{ request()->routeIs('public.home') ? 'is-active' : '' }}">
                        Inicio
                    </a>
                    <a href="{{ route('public.about') }}"
                        class="nav-link {{ request()->routeIs('public.about') ? 'is-active' : '' }}">
                        Nosotros
                    </a>
                    <a href="{{ route('public.process') }}"
                        class="nav-link {{ request()->routeIs('public.process') ? 'is-active' : '' }}">
                        Nuestro proceso
                    </a>
                    <a href="{{ route('public.catalog') }}"
                        class="nav-link {{ request()->routeIs('public.catalog') ? 'is-active' : '' }}">
                        Catálogo
                    </a>
                    <a href="{{ route('public.contact') }}"
                        class="nav-contact">
                        Contacto
                    </a>
                    @auth
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="nav-link ml-1">Administración</a>
                        @else
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link ml-1">Cerrar sesión</button>
                            </form>
                        @endif
                    @endauth
                </nav>

                @auth
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="header-login" aria-label="Abrir panel administrativo">
                            Administración
                        </a>
                    @endif
                @endauth

                <button
                    type="button"
                    class="menu-button inline-flex size-11 items-center justify-center rounded-full lg:hidden"
                    aria-controls="mobile-navigation"
                    :aria-expanded="mobileMenuOpen.toString()"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    <span class="sr-only">Abrir menú</span>
                    <svg x-show="!mobileMenuOpen" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </button>

                <button
                    type="button"
                    class="cart-button"
                    @click="cartOpen = true"
                    aria-controls="cart-drawer"
                    :aria-expanded="cartOpen.toString()"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M3 4h2l2.4 10.2A2 2 0 0 0 9.3 16H17a2 2 0 0 0 1.9-1.4L20 9H7"/>
                        <circle cx="9" cy="20" r="1"/>
                        <circle cx="18" cy="20" r="1"/>
                    </svg>
                    <span class="sr-only">Abrir carrito</span>
                    <span id="cart-count" class="cart-count" aria-live="polite">0</span>
                </button>
            </div>

            <nav
                id="mobile-navigation"
                x-show="mobileMenuOpen"
                x-cloak
                x-transition
                class="mobile-navigation space-y-1 pb-5 lg:hidden"
                aria-label="Navegacion movil"
            >
                <a href="{{ route('public.home') }}" @click="mobileMenuOpen=false" class="block rounded-lg px-4 py-3 text-sm font-semibold {{ request()->routeIs('public.home') ? 'bg-amber-900 text-white' : 'text-stone-700 hover:bg-amber-100' }}">Inicio</a>
                <a href="{{ route('public.about') }}" @click="mobileMenuOpen=false" class="block rounded-lg px-4 py-3 text-sm font-semibold {{ request()->routeIs('public.about') ? 'bg-amber-900 text-white' : 'text-stone-700 hover:bg-amber-100' }}">Nosotros</a>
                <a href="{{ route('public.process') }}" @click="mobileMenuOpen=false" class="block rounded-lg px-4 py-3 text-sm font-semibold {{ request()->routeIs('public.process') ? 'bg-amber-900 text-white' : 'text-stone-700 hover:bg-amber-100' }}">Nuestro Proceso</a>
                <a href="{{ route('public.catalog') }}" @click="mobileMenuOpen=false" class="block rounded-lg px-4 py-3 text-sm font-semibold {{ request()->routeIs('public.catalog') ? 'bg-amber-900 text-white' : 'text-stone-700 hover:bg-amber-100' }}">Catálogo</a>
                <a href="{{ route('public.contact') }}" @click="mobileMenuOpen=false" class="block rounded-lg bg-amber-700 px-4 py-3 text-sm font-semibold text-white">Contacto</a>
                @auth
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen=false" class="block rounded-lg border border-amber-900 px-4 py-3 text-sm font-semibold text-amber-950">Administración</a>
                    @else
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg border border-stone-300 px-4 py-3 text-left text-sm font-semibold text-stone-700">Cerrar sesión</button>
                        </form>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <div
        id="cart-drawer"
        class="cart-overlay"
        x-show="cartOpen"
        x-cloak
        role="dialog"
        aria-modal="true"
        aria-labelledby="cart-title"
    >
        <button type="button" class="cart-backdrop" @click="cartOpen = false" aria-label="Cerrar carrito"></button>
        <aside class="cart-panel" x-transition>
            <div class="cart-header">
                <div>
                    <p class="cart-kicker">Tu selección</p>
                    <h2 id="cart-title">Carrito</h2>
                </div>
                <button type="button" class="cart-close" @click="cartOpen = false" aria-label="Cerrar carrito">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>
            <div id="cart-items" class="cart-items"></div>
            <div class="cart-summary">
                <div class="cart-total"><span>Total</span><strong id="cart-total">Bs 0.00</strong></div>
                <p id="cart-status" class="cart-status" role="status" aria-live="polite"></p>
                <button id="cart-whatsapp" type="button" class="cart-checkout">Revisar pedido</button>
                <button id="cart-clear" type="button" class="cart-clear">Vaciar carrito</button>
                <p class="cart-note">El pedido se confirma directamente con el equipo antes del pago.</p>
            </div>
        </aside>
    </div>

    <main id="main-content">
        @yield('content')
    </main>

    <footer class="mt-auto text-amber-50">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-10 md:grid-cols-[1.5fr_.7fr_.7fr]">
                <div>
                    <a href="{{ route('public.home') }}" class="brand-link footer-brand-link" aria-label="Ir al inicio de Qori Chocolates">
                        <img
                            src="{{ asset('images/logoqori.png') }}"
                            alt="Qori Chocolates"
                            class="brand-logo brand-logo-footer"
                            width="744"
                            height="818"
                            loading="lazy"
                        >
                    </a>
                    <p class="footer-copy mt-5 max-w-md text-sm leading-7">Chocolate artesanal de Qori Chocolates, con presencia en Sucre, Bolivia.</p>
                </div>
                <div>
                    <p class="footer-label text-xs font-black uppercase tracking-[0.18em]">Explorar</p>
                    <nav class="footer-nav mt-5 space-y-3 text-sm" aria-label="Navegacion secundaria">
                        <a class="block hover:text-white" href="{{ route('public.about') }}">Nosotros</a>
                        <a class="block hover:text-white" href="{{ route('public.process') }}">Proceso</a>
                        <a class="block hover:text-white" href="{{ route('public.catalog') }}">Catálogo</a>
                    </nav>
                </div>
                <div>
                    <p class="footer-label text-xs font-black uppercase tracking-[0.18em]">Contacto</p>
                    <p class="footer-copy mt-5 text-sm leading-7">Para consultas sobre productos y disponibilidad:</p>
                    <a class="mt-3 inline-flex items-center gap-2 text-sm font-bold text-white" href="tel:+59169682107">+591 69682107</a>
                    <a class="mt-4 inline-block text-sm font-bold text-white" href="{{ route('public.contact') }}">Enviar consulta</a>
                </div>
            </div>
            <div class="footer-meta mt-12 flex flex-col gap-3 border-t pt-6 text-xs sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ date('Y') }} Qori Chocolates.</p>
                <p>Sucre · Bolivia</p>
            </div>
        </div>
    </footer>

    <button id="back-to-top" type="button" class="back-to-top" aria-label="Volver arriba" hidden>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 15 6-6 6 6"/></svg>
    </button>
    <a
        class="whatsapp-float"
        href="https://wa.me/59169682107?text={{ urlencode('Hola, quiero consultar sobre los chocolates de Qori Chocolates.') }}"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Consultar por WhatsApp al +591 69682107"
    >
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M20.52 3.48A11.82 11.82 0 0 0 12.07 0C5.5 0 .16 5.34.16 11.91c0 2.1.55 4.15 1.59 5.96L.05 24l6.28-1.65a11.9 11.9 0 0 0 5.73 1.46h.01c6.57 0 11.91-5.34 11.91-11.91 0-3.18-1.24-6.17-3.46-8.42Zm-8.45 18.3h-.01a9.9 9.9 0 0 1-5.04-1.38l-.36-.21-3.72.98 1-3.63-.24-.37a9.86 9.86 0 0 1-1.52-5.26c0-5.44 4.43-9.87 9.88-9.87a9.8 9.8 0 0 1 7 2.9 9.8 9.8 0 0 1 2.89 7c0 5.45-4.43 9.88-9.88 9.88Zm5.42-7.4c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.64.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.75-1.64-2.05-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.47 1.07 2.89 1.22 3.09.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.19 1.87.12.57-.09 1.76-.72 2.01-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35Z"/>
        </svg>
        <span>WhatsApp</span>
    </a>
    @push('scripts')
    <script>
        (() => {
            const backToTop = document.getElementById('back-to-top');
            if (backToTop) {
                const updateBackToTop = () => {
                    backToTop.hidden = window.scrollY < 600;
                };
                window.addEventListener('scroll', updateBackToTop, { passive: true });
                backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
                updateBackToTop();
            }

            const storageKey = 'qoriCart';
            let cart = [];
            let validatingCart = false;

            try {
                const stored = JSON.parse(localStorage.getItem(storageKey) || '[]');
                cart = Array.isArray(stored) ? stored.filter(item =>
                    item && /^\d+$/.test(String(item.id)) &&
                    typeof item.name === 'string' &&
                    Number.isFinite(Number(item.price)) &&
                    Number.isInteger(Number(item.stock)) && Number(item.stock) > 0 &&
                    Number.isInteger(Number(item.quantity)) && Number(item.quantity) > 0
                ).map(item => ({
                    id: String(item.id),
                    name: item.name.slice(0, 200),
                price: Math.max(0, Number(item.price)),
                    stock: Number(item.stock),
                quantity: Math.min(Number(item.quantity), Number(item.stock), 100)
                })) : [];
            } catch (error) {
                cart = [];
            }

            const formatPrice = (value) => `Bs ${Number(value).toFixed(2)}`;
            const getCartCount = () => cart.reduce((sum, item) => sum + item.quantity, 0);
            const getCartTotal = () => cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const escapeHtml = (value) => String(value).replace(/[&<>"']/g, character => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
            })[character]);

            const saveCart = () => {
                localStorage.setItem(storageKey, JSON.stringify(cart));
                renderCart();
            };

            window.qoriAddToCart = (product) => {
                const existing = cart.find(item => item.id === product.id);
                if (existing) {
                    if (existing.quantity < product.stock && existing.quantity < 100) existing.quantity += 1;
                } else if (cart.length < 50) {
                    cart.push({ ...product, quantity: 1 });
                }
                saveCart();
                document.querySelector('[x-data]')?.dispatchEvent(new CustomEvent('cart-open'));
            };

            const renderCart = () => {
                const itemsContainer = document.getElementById('cart-items');
                const countElement = document.getElementById('cart-count');
                const totalElement = document.getElementById('cart-total');
                const checkout = document.getElementById('cart-whatsapp');
                if (!itemsContainer || !countElement || !totalElement || !checkout) return;

                countElement.textContent = getCartCount();
                totalElement.textContent = formatPrice(getCartTotal());

                if (cart.length === 0) {
                    itemsContainer.innerHTML = '<div class="cart-empty"><p>Tu carrito está vacío.</p><span>Explora el catálogo y agrega una selección.</span></div>';
                    checkout.textContent = 'Ver catálogo';
                    checkout.disabled = true;
                    return;
                }

                itemsContainer.innerHTML = cart.map((item, index) => `
                    <article class="cart-item">
                        <div>
                            <h3>${escapeHtml(item.name)}</h3>
                            <p>${formatPrice(item.price)}</p>
                        </div>
                        <div class="cart-quantity" aria-label="Cantidad de ${item.name}">
                            <button type="button" data-cart-action="decrease" data-cart-index="${index}" aria-label="Disminuir cantidad">−</button>
                            <span>${item.quantity}</span>
                            <button type="button" data-cart-action="increase" data-cart-index="${index}" aria-label="Aumentar cantidad" ${item.quantity >= item.stock ? 'disabled' : ''}>+</button>
                        </div>
                        <button type="button" class="cart-remove" data-cart-action="remove" data-cart-index="${index}" aria-label="Eliminar ${item.name}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 7h16M9 7V4h6v3M8 7l1 13h6l1-13M10 11v5M14 11v5"/></svg>
                        </button>
                    </article>
                `).join('');

                checkout.disabled = false;
                checkout.textContent = 'Revisar pedido';
            };

            document.addEventListener('click', (event) => {
                const addButton = event.target.closest('.catalog-add');
                if (addButton) {
                    window.qoriAddToCart({
                        id: String(addButton.dataset.productId),
                        name: addButton.dataset.productName,
                        price: Number(addButton.dataset.productPrice),
                        stock: Number(addButton.dataset.productStock)
                    });
                    const addLabel = addButton.querySelector('span');
                    if (addLabel) addLabel.textContent = 'Agregado';
                    window.setTimeout(() => {
                        if (addLabel) addLabel.textContent = 'Agregar';
                    }, 900);
                }

                const cartAction = event.target.closest('[data-cart-action]');
                if (cartAction) {
                    const index = Number(cartAction.dataset.cartIndex);
                    const action = cartAction.dataset.cartAction;
                    if (action === 'increase' && cart[index].quantity < cart[index].stock && cart[index].quantity < 100) cart[index].quantity += 1;
                    if (action === 'decrease') {
                        cart[index].quantity -= 1;
                        if (cart[index].quantity <= 0) cart.splice(index, 1);
                    }
                    if (action === 'remove') cart.splice(index, 1);
                    saveCart();
                }
            });

            document.getElementById('cart-clear')?.addEventListener('click', () => {
                cart = [];
                saveCart();
            });

            document.getElementById('cart-whatsapp')?.addEventListener('click', async () => {
                if (!cart.length || validatingCart) return;
                const checkout = document.getElementById('cart-whatsapp');
                const status = document.getElementById('cart-status');
                validatingCart = true;
                checkout.disabled = true;
                checkout.textContent = 'Validando disponibilidad...';
                if (status) {
                    status.textContent = 'Validando cantidades y precios actuales.';
                    status.className = 'cart-status';
                }
                try {
                    const response = await fetch(@json(route('public.cart.validate')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ items: cart.map(item => ({ id: item.id, quantity: item.quantity })) })
                    });
                    if (!response.ok) throw new Error('No se pudo validar el carrito.');
                    const result = await response.json();
                    cart = result.items.filter(item => item.quantity > 0);
                    saveCart();
                    if (!result.valid) {
                        if (status) {
                            status.textContent = 'Algunas cantidades se ajustaron porque el stock disponible es menor.';
                            status.className = 'cart-status cart-status-warning';
                        }
                        return;
                    }
                    const lines = [
                        'Hola, quiero realizar un pedido en Qori Chocolates:',
                        ...cart.map(item => `- ${item.name}: ${item.quantity} x Bs ${item.price.toFixed(2)}`),
                        `Total estimado: Bs ${getCartTotal().toFixed(2)}`,
                        'Quisiera confirmar disponibilidad y coordinación de entrega.'
                    ];
                    const whatsappUrl = 'https://wa.me/59169682107?text=' + encodeURIComponent(lines.join('\n'));
                    window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
                    if (status) {
                        status.textContent = 'Pedido listo para confirmar por WhatsApp.';
                        status.className = 'cart-status cart-status-success';
                    }
                    checkout.disabled = false;
                    checkout.textContent = 'Revisar pedido';
                } catch (error) {
                    checkout.disabled = false;
                    checkout.textContent = 'Revisar pedido';
                    if (status) {
                        status.textContent = 'No pudimos validar la disponibilidad. Intenta nuevamente.';
                        status.className = 'cart-status cart-status-error';
                    }
                } finally {
                    validatingCart = false;
                    const checkout = document.getElementById('cart-whatsapp');
                    if (checkout) {
                        checkout.disabled = cart.length === 0;
                        checkout.textContent = cart.length === 0 ? 'Ver catálogo' : 'Revisar pedido';
                    }
                }
            });

            renderCart();
        })();
    </script>
    @endpush
    @stack('scripts')
</body>
</html>
