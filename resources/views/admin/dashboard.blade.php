@extends('admin.layout')

@section('title', 'Dashboard - Qori Chocolates')

@section('content')
    <div class="flex h-screen overflow-hidden">
        <aside class="w-72 border-r border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-black">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                Qori Chocolates
            </h1>

            <nav class="mt-8 space-y-2">
                <a href="#" class="menu-item menu-item-active block px-4 py-3">
                    Dashboard
                </a>
                <a href="#" class="menu-item menu-item-inactive block px-4 py-3">
                    Categorías
                </a>
                <a href="#" class="menu-item menu-item-inactive block px-4 py-3">
                    Productos
                </a>
                <a href="#" class="menu-item menu-item-inactive block px-4 py-3">
                    Ventas
                </a>
                <a href="#" class="menu-item menu-item-inactive block px-4 py-3">
                    Reportes
                </a>
            </nav>
        </aside>

        <main class="flex-1 overflow-y-auto p-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Panel de Administración
            </h2>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-800">
                    <p class="text-sm text-gray-500">Productos</p>
                    <p class="mt-2 text-3xl font-bold">0</p>
                </div>

                <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-800">
                    <p class="text-sm text-gray-500">Ventas</p>
                    <p class="mt-2 text-3xl font-bold">0</p>
                </div>

                <div class="rounded-lg border border-gray-200 p-6 dark:border-gray-800">
                    <p class="text-sm text-gray-500">Stock bajo</p>
                    <p class="mt-2 text-3xl font-bold">0</p>
                </div>
            </div>
        </main>
    </div>
@endsection