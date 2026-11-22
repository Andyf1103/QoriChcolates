<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('productos')) {
            if (! Schema::hasColumn('productos', 'activo')) {
                Schema::table('productos', function (Blueprint $table) {
                    $table->boolean('activo')->default(true)->after('stock');
                });
            }
        }
    }

    public function down(): void
    {
        // activo se mantiene porque el POS valida únicamente productos activos.
    }
};
