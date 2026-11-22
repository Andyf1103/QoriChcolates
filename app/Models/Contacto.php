<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacto extends Model
{
    use SoftDeletes;

    protected $table = 'contactos';

    protected $fillable = [
        'nombre',
        'email',
        'asunto',
        'mensaje',
        'atendido',
    ];

    protected function casts(): array
    {
        return ['atendido' => 'boolean'];
    }
}
