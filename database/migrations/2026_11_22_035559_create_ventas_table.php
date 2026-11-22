<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 30)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cliente_nombre', 150)->nullable();
            $table->string('cliente_ci', 50)->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('monto_recibido', 10, 2)->default(0);
            $table->decimal('cambio', 10, 2)->default(0);
            $table->string('metodo_pago', 10);
            $table->string('estado', 10)->default('completada');
            $table->string('motivo_anulacion', 255)->nullable();
            $table->timestamp('anulada_at')->nullable();
            $table->timestamps();
            $table->index(['estado', 'created_at']);
            $table->index(['metodo_pago', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
