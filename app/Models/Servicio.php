<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'codigo_servicio',
        'nombre',
        'descripcion',
        'destino',
        'fecha',
        'precio',
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio' => 'decimal:2',
    ];

    public function paquetes(): BelongsToMany
    {
        return $this->belongsToMany(Paquete::class)
                    ->withTimestamps();
    }
}
