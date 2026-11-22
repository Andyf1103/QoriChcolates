@extends('admin.layout')
@section('title', 'Ventas POS - Qori Chocolates')
@section('heading', 'Ventas / Punto de venta')
@section('content')
<div class="mx-auto max-w-[1600px] p-4 sm:p-6" x-data="posVentas()" x-init="init()">
    <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <p class="text-sm text-gray-500">Registrar una venta</p>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Punto de venta</h2>
        </div>
        <a href="{{ route('admin.ventas.index') }}" class="admin-secondary-action">Ver historial</a>
    </div>

    <form method="POST" action="{{ route('admin.ventas.store') }}" class="grid gap-5 lg:grid-cols-[1fr_390px]" @submit="if (!validarVenta()) $event.preventDefault()">
        @csrf
        <section class="space-y-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-950">
                <label for="buscar" class="mb-2 block text-sm font-semibold">Buscar productos</label>
                <input id="buscar" type="search" x-model="buscar" placeholder="Nombre del producto" class="w-full rounded-lg border border-gray-300 px-3 py-3 dark:border-gray-700 dark:bg-gray-900">
            </div>
            <div class="grid max-h-[650px] grid-cols-2 gap-3 overflow-y-auto sm:grid-cols-3 xl:grid-cols-4">
                <template x-for="producto in filtrados" :key="producto.id">
                    <button type="button" @click="agregar(producto)" class="rounded-xl border border-gray-200 bg-white p-3 text-left shadow-sm transition hover:border-amber-500 hover:shadow-md dark:border-gray-800 dark:bg-gray-950">
                        <p class="line-clamp-2 min-h-10 font-semibold" x-text="producto.nombre"></p>
                        <p class="mt-2 text-lg font-bold text-amber-800" x-text="money(producto.precio)"></p>
                        <p class="mt-1 text-xs text-gray-500" x-text="'Stock: ' + producto.stock"></p>
                    </button>
                </template>
                <p x-show="!filtrados.length" class="col-span-full py-10 text-center text-gray-500">No hay productos disponibles.</p>
            </div>
        </section>

        <aside class="h-fit rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-950">
            <h3 class="mb-4 text-lg font-bold">Carrito</h3>
            <div class="max-h-72 space-y-2 overflow-y-auto">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-800">
                        <div class="flex items-start justify-between gap-2">
                            <div><p class="font-semibold" x-text="item.nombre"></p><p class="text-xs text-gray-500" x-text="money(item.precio)"></p></div>
                            <button type="button" @click="cart.splice(index, 1)" class="text-lg text-red-600" aria-label="Quitar">&times;</button>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <input type="number" min="1" :max="item.stock" x-model.number="item.cantidad" @change="normalizar(item)" class="w-24 rounded-lg border border-gray-300 px-2 py-2 dark:border-gray-700 dark:bg-gray-900">
                            <span class="font-bold" x-text="money(item.precio * item.cantidad)"></span>
                        </div>
                    </div>
                </template>
                <p x-show="!cart.length" class="py-8 text-center text-sm text-gray-500">El carrito está vacío.</p>
            </div>

            <div class="mt-4 space-y-3 border-t pt-4 dark:border-gray-800">
                <div class="flex justify-between text-xl font-bold"><span>Total</span><span x-text="money(total)"></span></div>
                <template x-for="(item, index) in cart" :key="'form-'+item.id">
                    <span>
                        <input type="hidden" :name="'items['+index+'][id]'" :value="item.id">
                        <input type="hidden" :name="'items['+index+'][cantidad]'" :value="item.cantidad">
                    </span>
                </template>

                <div class="grid grid-cols-2 gap-2">
                    <div><label for="cliente_nombre" class="block text-xs font-semibold">Cliente (opcional)</label><input id="cliente_nombre" name="cliente_nombre" value="{{ old('cliente_nombre') }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"></div>
                    <div><label for="cliente_ci" class="block text-xs font-semibold">CI/NIT (opcional)</label><input id="cliente_ci" name="cliente_ci" value="{{ old('cliente_ci') }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-900"></div>
                </div>

                <fieldset>
                    <legend class="text-sm font-semibold">Método de pago</legend>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <label class="cursor-pointer rounded-lg border p-3 text-center" :class="metodo === 'efectivo' ? 'border-amber-700 bg-amber-50 dark:bg-amber-900/20' : 'border-gray-300 dark:border-gray-700'"><input type="radio" name="metodo_pago" value="efectivo" x-model="metodo" class="sr-only"> Efectivo</label>
                        <label class="cursor-pointer rounded-lg border p-3 text-center" :class="metodo === 'qr' ? 'border-amber-700 bg-amber-50 dark:bg-amber-900/20' : 'border-gray-300 dark:border-gray-700'"><input type="radio" name="metodo_pago" value="qr" x-model="metodo" class="sr-only"> QR</label>
                    </div>
                </fieldset>

                <div>
                    <label for="monto_recibido" class="block text-sm font-semibold" x-text="metodo === 'qr' ? 'Monto QR recibido' : 'Monto recibido'"></label>
                    <input id="monto_recibido" name="monto_recibido" type="number" step="0.01" min="0" x-model.number="recibido" value="{{ old('monto_recibido', '0.00') }}" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-3 dark:border-gray-700 dark:bg-gray-900">
                </div>
                <div x-show="metodo === 'efectivo'" class="flex justify-between rounded-lg px-3 py-3" :class="cambio < 0 ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300' : 'bg-gray-100 dark:bg-gray-800'">
                    <span x-text="cambio < 0 ? 'Falta por pagar' : 'Cambio'"></span>
                    <strong x-text="money(cambio)"></strong>
                </div>

                <button type="submit" class="admin-primary-action w-full justify-center">Confirmar venta</button>
            </div>
        </aside>
    </form>

    <div x-cloak x-show="avisoOpen" x-transition.opacity @keydown.escape.window="cerrarAviso()" class="fixed inset-0 z-[70] flex items-center justify-center bg-gray-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="pos-aviso-titulo">
        <div x-show="avisoOpen" x-transition @click.self="cerrarAviso()" class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl dark:bg-gray-900">
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-xl font-bold text-red-700 dark:bg-red-900/40 dark:text-red-300" aria-hidden="true">!</span>
                <div>
                    <h2 id="pos-aviso-titulo" class="text-lg font-bold text-gray-900 dark:text-white">No se puede completar la venta</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300" x-text="avisoMensaje"></p>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="button" x-ref="avisoCerrar" @click="cerrarAviso()" class="min-h-[44px] rounded-lg border border-amber-700 bg-amber-800 px-5 py-2 font-semibold text-white shadow-sm hover:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-700 focus:ring-offset-2 dark:border-amber-500 dark:bg-amber-700">Entendido</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function posVentas() {
    return {
        productos: @json($productosJson),
        buscar: '', cart: [], metodo: 'efectivo', recibido: 0,
        avisoOpen: false, avisoMensaje: '',
        get filtrados() { return this.productos.filter(p => p.nombre.toLowerCase().includes(this.buscar.toLowerCase())); },
        get total() { return this.cart.reduce((sum, i) => sum + i.precio * i.cantidad, 0); },
        get cambio() { return (Number(this.recibido) || 0) - this.total; },
        init() { this.$watch('metodo', m => { if (m === 'qr') this.recibido = Number(this.total.toFixed(2)); }); this.$watch('total', t => { if (this.metodo === 'qr') this.recibido = Number(t.toFixed(2)); }); },
        mostrarAviso(mensaje) {
            this.avisoMensaje = mensaje;
            this.avisoOpen = true;
            this.$nextTick(() => this.$refs.avisoCerrar.focus());
        },
        cerrarAviso() {
            this.avisoOpen = false;
        },
        validarVenta() {
            if (!this.cart.length) {
                this.mostrarAviso('Agrega al menos un producto.');
                return false;
            }
            if (this.metodo === 'efectivo' && this.cambio < 0) {
                this.mostrarAviso('El efectivo recibido es insuficiente.');
                return false;
            }
            return true;
        },
        agregar(p) {
            const existente = this.cart.find(i => i.id === p.id);
            if (existente) { if (existente.cantidad < p.stock) existente.cantidad++; }
            else { this.cart.push({...p, cantidad: 1}); }
        },
        normalizar(item) { item.cantidad = Math.max(1, Math.min(Number(item.cantidad) || 1, item.stock)); },
        money(value) {
            const amount = Number(value || 0);
            const formatted = Math.abs(amount).toLocaleString('es-BO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            return (amount < 0 ? '- Bs ' : 'Bs ') + formatted;
        }
    }
}
</script>
@endpush
@endsection
