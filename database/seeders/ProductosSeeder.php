<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = collect(['Barras', 'Bombones', 'Cajas de Regalo', 'Souvenirs'])->mapWithKeys(function (string $nombre) {
            $categoria = Categoria::updateOrCreate(
                ['slug' => Str::slug($nombre)],
                ['nombre' => $nombre]
            );

            return [$nombre => $categoria->id];
        });

        $productos = [
            ['id' => 1, 'nombre' => 'Barra de Chocolate Amargo 70% Cacao', 'categoria' => 'Barras', 'precio' => 15.00, 'stock' => 20],
            ['id' => 2, 'nombre' => 'Barra de Chocolate con Leche', 'categoria' => 'Barras', 'precio' => 12.00, 'stock' => 25],
            ['id' => 3, 'nombre' => 'Barra de Chocolate con Almendras', 'categoria' => 'Barras', 'precio' => 18.00, 'stock' => 15],
            ['id' => 4, 'nombre' => 'Bombón Relleno de Manjar', 'categoria' => 'Bombones', 'precio' => 5.00, 'stock' => 40],
            ['id' => 5, 'nombre' => 'Bombón Relleno de Menta', 'categoria' => 'Bombones', 'precio' => 5.00, 'stock' => 30],
            ['id' => 6, 'nombre' => 'Bombón con Nuez', 'categoria' => 'Bombones', 'precio' => 6.00, 'stock' => 25],
            ['id' => 7, 'nombre' => 'Caja de Regalo "Clásica" (12 bombones)', 'categoria' => 'Cajas de Regalo', 'precio' => 60.00, 'stock' => 10],
            ['id' => 8, 'nombre' => 'Caja de Regalo "Premium" (24 bombones)', 'categoria' => 'Cajas de Regalo', 'precio' => 110.00, 'stock' => 8],
            ['id' => 9, 'nombre' => 'Caja de Regalo "Sucre" (Barras Surtidas)', 'categoria' => 'Cajas de Regalo', 'precio' => 80.00, 'stock' => 12],
            ['id' => 10, 'nombre' => 'Souvenir Corazón de Chocolate', 'categoria' => 'Souvenirs', 'precio' => 25.00, 'stock' => 20],
            ['id' => 11, 'nombre' => 'Souvenir Chocolate con Mensaje', 'categoria' => 'Souvenirs', 'precio' => 10.00, 'stock' => 50],
            ['id' => 12, 'nombre' => 'Souvenir Mini Barras Surtidas', 'categoria' => 'Souvenirs', 'precio' => 20.00, 'stock' => 15],
        ];

        foreach ($productos as $producto) {
            $producto['categoria_id'] = $categorias[$producto['categoria']] ?? null;
            unset($producto['categoria']);
            Producto::updateOrCreate(['id' => $producto['id']], $producto);
        }
    }
}
