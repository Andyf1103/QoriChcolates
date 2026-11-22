<?php

use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\ContactoController;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/', function () {
    return view('public.home');
})->name('public.home');

Route::view('/nosotros', 'public.about')->name('public.about');
Route::view('/nuestro-proceso', 'public.process')->name('public.process');
Route::get('/catalogo', function () {
    $productos = Producto::with('categoriaRelacion')
        ->where('stock', '>', 0)
        ->whereHas('categoriaRelacion')
        ->orderBy('id')
        ->get();
    $categorias = $productos->pluck('categoriaRelacion')
        ->filter()
        ->unique('id')
        ->sortBy('nombre')
        ->values();

    return view('public.catalog', compact('productos', 'categorias'));
})->name('public.catalog');
Route::get('/catalogo/{producto}', function (Producto $producto) {
    abort_unless($producto->stock > 0 && $producto->categoriaRelacion, 404);

    return view('public.product', compact('producto'));
})->name('public.product');
Route::view('/contacto', 'public.contact')->name('public.contact');
Route::post('/contacto', [ContactoController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('public.contact.store');
Route::post('/carrito/validar', function (Request $request) {
    $validated = $request->validate([
        'items' => ['required', 'array', 'min:1', 'max:50'],
        'items.*.id' => ['required', 'integer', 'distinct', Rule::exists('productos', 'id')->where(fn ($query) => $query->where('stock', '>', 0)->whereNotNull('categoria_id'))],
        'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
    ]);

    $products = Producto::where('stock', '>', 0)
        ->whereNotNull('categoria_id')
        ->whereHas('categoriaRelacion')
        ->whereIn('id', collect($validated['items'])->pluck('id'))
        ->get()
        ->keyBy('id');
    $items = collect($validated['items'])->map(function (array $item) use ($products) {
        $product = $products->get($item['id']);

        return [
            'id' => $product->id,
            'name' => $product->nombre,
            'price' => (float) $product->precio,
            'quantity' => min($item['quantity'], $product->stock),
            'stock' => $product->stock,
            'available' => $product->stock >= $item['quantity'],
        ];
    })->values();

    return response()->json([
        'items' => $items,
        'valid' => $items->every(fn (array $item) => $item['available']),
    ]);
})->middleware('throttle:30,1')->name('public.cart.validate');

Route::get('/sitemap.xml', function () {
    $urls = [
        route('public.home'),
        route('public.about'),
        route('public.process'),
        route('public.catalog'),
        route('public.contact'),
    ];

    return response()->view('public.sitemap', ['urls' => $urls], 200)
        ->header('Content-Type', 'application/xml');
})->name('public.sitemap');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/admin/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->middleware('guest')->name('login.store');
Route::post('/admin/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('productos', ProductoController::class)->parameters(['productos' => 'producto']);
    Route::patch('/productos/{producto}/restaurar', [ProductoController::class, 'restore'])->name('productos.restore');
    Route::patch('/productos/{producto}/stock', [ProductoController::class, 'updateStock'])->name('productos.stock');
    Route::resource('categorias', CategoriaController::class)->parameters(['categorias' => 'categoria']);
    Route::patch('/categorias/{categoria}/restaurar', [CategoriaController::class, 'restore'])->name('categorias.restore');
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
    Route::get('/auditoria/{audit}', [AuditoriaController::class, 'show'])->name('auditoria.show');
});
