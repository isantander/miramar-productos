<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paquete extends Model
{
    use SoftDeletes;

    /**
   * NOTA TÉCNICA: Se añadieron campos 'codigo' y 'nombre' para:
   * 1. Facilitar identificación única de cada paquetes
   * 2. Mejorar experiencia de usuario con los listados
   * 3. Preparar el sistema para un futura expansión de funcionalidades
   * 
   * Aunque no están en las especificaciones originales del TP, mejoran la usabilidad
   * sin afectar la lógica de negocio especificada.
   */
    protected $fillable = [
        'codigo',
        'nombre',
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
        $costoTotal = $this->servicios->sum('costo');
        $costoConDescuento = $costoTotal * 0.9; // descuento del 10%

        return number_format($costoConDescuento, 2, '.', '');
    }

    public function scopeConServicios($query)
    {
        return $query->has('servicios');
    }
 
    public function scopePorRangoPrecio($query, $min, $max)
    {
        return $query->whereHas('servicios', function ($q) use ($min, $max) {
            $q->havingRaw('SUM(costo) * 0.9 BETWEEN ? AND ?', [$min, $max]);
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
