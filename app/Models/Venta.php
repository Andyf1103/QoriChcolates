<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    public const ESTADO_COMPLETADA = 'completada';

    public const ESTADO_ANULADA = 'anulada';

    public const PAGO_EFECTIVO = 'efectivo';

    public const PAGO_QR = 'qr';

    protected $fillable = [
        'numero', 'user_id', 'cliente_nombre', 'cliente_ci', 'subtotal', 'total',
        'monto_recibido', 'cambio', 'metodo_pago', 'estado',
        'motivo_anulacion', 'anulada_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'monto_recibido' => 'decimal:2',
            'cambio' => 'decimal:2',
            'anulada_at' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(VentaDetalle::class);
    }
}
