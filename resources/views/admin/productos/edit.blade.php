@extends('admin.layout')
@section('title', 'Editar producto')
@section('heading', 'Editar producto')
@section('content')
<main class="mx-auto max-w-3xl p-4 sm:p-6">
    <a href="{{ route('admin.productos.index') }}" class="text-sm text-amber-800 hover:underline">← Volver a productos</a>
    <form method="POST" action="{{ route('admin.productos.update', $producto) }}" class="mt-4 grid gap-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6 md:grid-cols-2">
        @csrf @method('PUT')
        <div class="md:col-span-2"><label for="nombre" class="font-medium">Nombre <span class="text-red-700">*</span></label><input id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required class="mt-1 w-full rounded-lg border-gray-300 px-3 py-3 focus:border-amber-700 focus:ring-amber-700">@error('nombre')<p class="text-sm text-red-700">{{ $message }}</p>@enderror</div>
        <div><label for="categoria_id" class="font-medium">Categoría <span class="text-red-700">*</span></label><select id="categoria_id" name="categoria_id" required class="mt-1 w-full rounded-lg border-gray-300 px-3 py-3 focus:border-amber-700 focus:ring-amber-700">@foreach($categorias as $categoria)<option value="{{ $categoria->id }}" @selected(old('categoria_id', $producto->categoria_id) == $categoria->id)>{{ $categoria->nombre }}</option>@endforeach</select>@error('categoria_id')<p class="text-sm text-red-700">{{ $message }}</p>@enderror</div>
        <div><label for="precio" class="font-medium">Precio (Bs) <span class="text-red-700">*</span></label><input id="precio" type="number" step="0.01" min="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" required class="mt-1 w-full rounded-lg border-gray-300 px-3 py-3 focus:border-amber-700 focus:ring-amber-700">@error('precio')<p class="text-sm text-red-700">{{ $message }}</p>@enderror</div>
        <div class="md:col-span-2 flex flex-col gap-3 sm:flex-row"><button class="min-h-[44px] rounded-lg bg-amber-800 px-5 py-3 font-semibold text-white hover:bg-amber-900">Actualizar producto</button><a href="{{ route('admin.productos.index') }}" class="min-h-[44px] rounded-lg border px-5 py-3 text-center">Cancelar</a></div>
    </form>
    <form method="POST" action="{{ route('admin.productos.stock', $producto) }}" class="mt-4 grid gap-3 rounded-xl border border-gray-200 bg-white p-5 sm:grid-cols-3 dark:border-gray-800 dark:bg-gray-950">
        @csrf @method('PATCH')
        <label class="block">Nuevo stock<input type="number" name="stock" min="0" value="{{ $producto->stock }}" required class="mt-1 w-full rounded-lg border-gray-300 px-3 py-3"></label>
        <label class="block">Motivo <span class="text-xs text-gray-500">(obligatorio)</span><input type="text" name="motivo" maxlength="255" required minlength="3" placeholder="Reposición, venta, ajuste..." class="mt-1 w-full rounded-lg border-gray-300 px-3 py-3"></label>
        <button class="min-h-[44px] self-end rounded-lg border px-4 py-3 font-semibold hover:bg-gray-50">Registrar ajuste</button>
    </form>
    <section class="mt-4 overflow-x-auto rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-950">
        <h2 class="font-semibold">Movimientos recientes</h2>
        <table class="mt-3 w-full min-w-[600px] text-left text-sm"><thead><tr class="border-b text-xs uppercase text-gray-500 dark:border-gray-800"><th class="py-2">Fecha</th><th>Tipo</th><th>Cantidad</th><th>Stock</th><th>Motivo</th><th>Usuario</th></tr></thead><tbody>
        @forelse($movimientos as $movimiento)<tr class="border-b last:border-0 dark:border-gray-800"><td class="py-3">{{ $movimiento->created_at->format('d/m/Y H:i') }}</td><td class="{{ $movimiento->tipo === 'entrada' ? 'text-green-700' : 'text-red-700' }}">{{ ucfirst($movimiento->tipo) }}</td><td>{{ $movimiento->cantidad }}</td><td>{{ $movimiento->stock_anterior }} → {{ $movimiento->stock_nuevo }}</td><td>{{ $movimiento->motivo ?: 'Sin motivo' }}</td><td>{{ $movimiento->user?->name ?: 'Sistema' }}</td></tr>@empty<tr><td colspan="6" class="py-5 text-center text-gray-500">Aún no hay movimientos.</td></tr>@endforelse
        </tbody></table>
        <div class="mt-3">{{ $movimientos->links() }}</div>
    </section>
</main>
@endsection
