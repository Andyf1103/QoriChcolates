<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('productos', 'categoria') && Schema::hasColumn('productos', 'categoria_id')) {
            $productos = DB::table('productos')->whereNull('categoria_id')->get(['id', 'categoria']);
            foreach ($productos as $producto) {
                $categoriaId = $producto->categoria
                    ? DB::table('categorias')->where('nombre', $producto->categoria)->value('id')
                    : null;
                if ($categoriaId) {
                    DB::table('productos')->where('id', $producto->id)->update(['categoria_id' => $categoriaId]);
                }
            }

            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn('categoria');
            });
        }

        Schema::table('productos', function (Blueprint $table) {
            $table->index(['activo', 'stock']);
            $table->index(['categoria_id', 'activo']);
            $table->index('nombre');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->index(['activo', 'nombre']);
        });

        Schema::table('audits', function (Blueprint $table) {
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropIndex(['activo', 'stock']);
            $table->dropIndex(['categoria_id', 'activo']);
            $table->dropIndex(['nombre']);
        });
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropIndex(['activo', 'nombre']);
        });
        Schema::table('audits', function (Blueprint $table) {
            $table->dropIndex(['action', 'created_at']);
        });

        if (! Schema::hasColumn('productos', 'categoria')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('categoria')->nullable()->after('nombre');
            });
            $productos = DB::table('productos')->join('categorias', 'categorias.id', '=', 'productos.categoria_id')
                ->get(['productos.id', 'categorias.nombre']);
            foreach ($productos as $producto) {
                DB::table('productos')->where('id', $producto->id)->update(['categoria' => $producto->nombre]);
            }
        }
    }
};
