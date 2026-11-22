<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administración - Qori Chocolates')</title>
    <link rel="icon" href="{{ asset('tailadmin/assets/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('tailadmin/assets/style.css') }}?v={{ filemtime(public_path('tailadmin/assets/style.css')) }}">
    <link rel="stylesheet" href="{{ asset('tailadmin/assets/admin.css') }}?v={{ filemtime(public_path('tailadmin/assets/admin.css')) }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (JSON.parse(localStorage.getItem('darkMode') || 'false')) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @stack('styles')
</head>
<body x-data="{ darkMode: false, sidebarOpen: false, sidebarCollapsed: false, modalOpen: false, confirmForm: null, confirmMessage: '', previousFocus: null, requestConfirm(form, message) { this.previousFocus = document.activeElement; this.confirmForm = form; this.confirmMessage = message; this.modalOpen = true; this.$nextTick(() => this.$refs.confirmButton.focus()) }, closeConfirmation() { this.modalOpen = false; this.$nextTick(() => { if (this.previousFocus) this.previousFocus.focus() }) }, cycleModalFocus(event) { if (event.shiftKey && document.activeElement === this.$refs.cancelButton) { event.preventDefault(); this.$refs.confirmButton.focus() } else if (!event.shiftKey && document.activeElement === this.$refs.confirmButton) { event.preventDefault(); this.$refs.cancelButton.focus() } }, submitConfirmation() { if (this.confirmForm) this.confirmForm.submit(); this.closeConfirmation() } }"
      x-init="darkMode = JSON.parse(localStorage.getItem('darkMode') || 'false'); document.documentElement.classList.toggle('dark', darkMode)"
      :class="{ 'dark bg-gray-900': darkMode }"
      class="bg-gray-50 text-gray-800 antialiased dark:text-gray-100">
<a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-amber-800 focus:px-4 focus:py-3 focus:text-white">Saltar al contenido</a>
<div class="min-h-screen">
    @auth
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"></div>
    <aside :class="{ 'admin-sidebar-collapsed': sidebarCollapsed, 'admin-sidebar-open': sidebarOpen }"
           class="admin-sidebar fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-gray-200 bg-white transition-all duration-200 dark:border-gray-800 dark:bg-gray-950">
        <div class="flex h-20 items-center justify-between border-b border-gray-200 px-5 dark:border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-3 font-bold text-amber-900 dark:text-amber-300">
                <img src="{{ asset('images/logoqori.png') }}" alt="Logo de Qori Chocolates" class="h-10 w-10 shrink-0 rounded-xl object-contain">
                <span x-show="!sidebarCollapsed" class="truncate">Qori Chocolates</span>
            </a>
            <button type="button" @click="sidebarOpen = false" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden" aria-label="Cerrar menú">×</button>
        </div>
        <nav class="flex-1 space-y-1 overflow-y-auto p-4" aria-label="Navegación administrativa">
            @php($links = [
                ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '▦'],
                ['route' => 'admin.categorias.index', 'label' => 'Categorías', 'icon' => '◈'],
                ['route' => 'admin.productos.index', 'label' => 'Productos', 'icon' => '□'],
                ['route' => 'admin.auditoria.index', 'label' => 'Auditoría', 'icon' => '◷'],
            ])
            @foreach($links as $link)
                <a href="{{ route($link['route']) }}" @click="sidebarOpen = false"
                   class="flex min-h-[44px] items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium transition hover:bg-amber-50 hover:text-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-700 dark:hover:bg-gray-800 {{ request()->routeIs($link['route']) ? 'bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-200' : 'text-gray-600 dark:text-gray-300' }}"
                   :title="sidebarCollapsed ? '{{ $link['label'] }}' : ''">
                    <span class="w-6 shrink-0 text-center text-lg" aria-hidden="true">{{ $link['icon'] }}</span>
                    <span x-show="!sidebarCollapsed">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>
        <div class="border-t border-gray-200 p-4 dark:border-gray-800">
            <div x-show="!sidebarCollapsed" class="mb-3 truncate text-xs text-gray-500">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex min-h-[44px] w-full items-center gap-3 rounded-lg px-3 py-3 text-sm text-gray-600 transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 dark:text-gray-300" :title="sidebarCollapsed ? 'Cerrar sesión' : ''">
                    <span class="w-6 text-center" aria-hidden="true">↪</span><span x-show="!sidebarCollapsed">Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>
    @endauth
    <div :class="{ 'admin-content-collapsed': sidebarCollapsed }" class="admin-content @guest admin-content-guest @endguest transition-all duration-200">
        <header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-gray-200 bg-white/95 px-4 backdrop-blur sm:px-6 dark:border-gray-800 dark:bg-gray-950/95">
            <div class="flex items-center gap-3">
                <button type="button" @click="sidebarOpen = true" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-amber-700 lg:hidden" aria-label="Abrir menú">☰</button>
                <button type="button" @click="sidebarCollapsed = !sidebarCollapsed" class="hidden rounded-lg p-2 text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-amber-700 lg:block" :aria-label="sidebarCollapsed ? 'Expandir menú' : 'Colapsar menú'">☰</button>
                <div>
                    <p class="text-xs text-gray-500">Administración</p>
                    <h1 class="font-semibold text-gray-900 dark:text-white">@yield('heading', 'Panel de administración')</h1>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); document.documentElement.classList.toggle('dark', darkMode)" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-amber-700 dark:text-gray-300" :aria-label="darkMode ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'" :title="darkMode ? 'Modo claro' : 'Modo oscuro'">◐</button>
                @auth<span class="hidden text-sm text-gray-600 sm:block dark:text-gray-300">{{ auth()->user()->name }}</span>@endauth
            </div>
        </header>
        @if (session('success'))
            <div role="status" aria-live="polite" class="mx-4 mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 sm:mx-6">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div role="alert" aria-live="assertive" class="mx-4 mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 sm:mx-6">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="mx-4 mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 sm:mx-6">
                <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <main id="main-content">@yield('content')</main>
    </div>
</div>
<div x-cloak x-show="modalOpen" x-transition.opacity @keydown.escape.window="closeConfirmation()" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div x-show="modalOpen" x-transition @click.self="closeConfirmation()" @keydown.tab="cycleModalFocus($event)" class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl dark:bg-gray-900">
        <div class="flex items-start gap-3"><span class="rounded-full bg-red-100 p-2 text-red-700" aria-hidden="true">!</span><div><h2 id="confirm-title" class="text-lg font-bold text-gray-900 dark:text-white">Confirmar acción</h2><p class="mt-2 text-sm text-gray-600 dark:text-gray-300" x-text="confirmMessage"></p></div></div>
        <div class="mt-6 flex justify-end gap-3"><button type="button" x-ref="cancelButton" @click="closeConfirmation()" class="min-h-[44px] rounded-lg border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-amber-700 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700">Cancelar</button><button type="button" x-ref="confirmButton" @click="submitConfirmation()" class="min-h-[44px] rounded-lg border border-red-800 bg-red-700 px-4 py-2 font-semibold text-white shadow-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:border-red-500 dark:bg-red-600 dark:text-white dark:hover:bg-red-500">Confirmar</button></div>
    </div>
</div>
@stack('scripts')
</body>
</html>
