<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('productos')) {
            Schema::table('productos', function (Blueprint $table) {
                try {
                    $table->dropIndex(['activo', 'stock']);
                } catch (Throwable) {
                }
                try {
                    $table->dropIndex(['categoria_id', 'activo']);
                } catch (Throwable) {
                }
            });

            if (Schema::hasColumn('productos', 'activo')) {
                Schema::table('productos', function (Blueprint $table) {
                    $table->dropColumn('activo');
                });
            }

            Schema::table('productos', function (Blueprint $table) {
                $table->index(['categoria_id', 'stock']);
            });
        }

        if (Schema::hasTable('categorias')) {
            Schema::table('categorias', function (Blueprint $table) {
                try {
                    $table->dropIndex(['activo', 'nombre']);
                } catch (Throwable) {
                }
            });

            if (Schema::hasColumn('categorias', 'activo')) {
                Schema::table('categorias', function (Blueprint $table) {
                    $table->dropColumn('activo');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('productos')) {
            Schema::table('productos', function (Blueprint $table) {
                try {
                    $table->dropIndex(['categoria_id', 'stock']);
                } catch (Throwable) {
                }
                $table->boolean('activo')->default(true)->after('stock');
                $table->index(['activo', 'stock']);
                $table->index(['categoria_id', 'activo']);
            });
        }

        if (Schema::hasTable('categorias')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->boolean('activo')->default(true);
                $table->index(['activo', 'nombre']);
            });
        }
    }
};
