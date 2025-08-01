<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paquete extends Model
{
    use SoftDeletes;

    protected $fillable = [
    ];

    protected $casts = [
        'precio_calculado' => 'decimal:2',
    ];

    protected $appends = [
        'precio_calculado', // Para incluirlo en el json
    ];

    public function servicios(): BelongsToMany 
    {
        return $this->belongsToMany(Servicio::class)
            ->withTimestamps();            
    }

    public function getPrecioCalculadoAttribute(): string 
    {
        $precioTotal = $this->servicios->sum('precio');
        $precioConDescuento = $precioTotal * 0.9; // descuento del 10%

        return number_format($precioConDescuento, 2, '.', '');
    }

    public function scopeConServicios($query)
    {
        return $query->has('servicios');
    }
 
    public function scopePorRangoPrecio($query, $min, $max)
    {
        return $query->whereHas('servicios', function ($q) use ($min, $max) {
            $q->havingRaw('SUM(precio) * 0.9 BETWEEN ? AND ?', [$min, $max]);
        });
    }

    /*
        COMENTARIO: Se podría generar automáticamente un nombre para hacer más 
        fácil la identificación del paquete, por ejemplo: "PAQ-001", "PAQ-002", etc.
        public function getCodigoAttribute(): string 
        {
            return 'PAQ-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
        }
    */
    
}
