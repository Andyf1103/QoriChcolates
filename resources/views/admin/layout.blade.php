<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Qori Chocolates')</title>

    <link rel="icon" href="{{ asset('tailadmin/assets/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('tailadmin/assets/style.css') }}">
    @stack('styles')
</head>

<body
    x-data="{ darkMode: false, sidebarToggle: false }"
    x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')) ?? false"
    :class="{ 'dark bg-gray-900': darkMode }"
>
    @yield('content')

    <script src="{{ asset('tailadmin/assets/bundle.js') }}"></script>
    @stack('scripts')
</body>
</html>