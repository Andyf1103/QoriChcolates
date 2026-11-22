<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        return view('admin.dashboard', [
            'productosTotales' => Producto::count(),
            'categoriasTotales' => Categoria::count(),
            'stockBajo' => Producto::where('stock', '>', 0)->where('stock', '<=', 5)->count(),
            'agotados' => Producto::where('stock', 0)->count(),
            'valorInventario' => Producto::selectRaw('COALESCE(SUM(precio * stock), 0) as total')->value('total'),
            'productosStockBajo' => Producto::with('categoriaRelacion')
                ->where('stock', '>', 0)
                ->where('stock', '<=', 5)
                ->orderBy('stock')
                ->get(),
        ]);
    }
}
