<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reparticion extends Model
{
    protected $table = 'reparticiones';
    
    protected $fillable = [
        'user_id',
        'zona',
        'horario_inicio',
        'horario_fin',
        'fecha_asignacion',
        'activo',
        'observaciones'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_asignacion' => 'date',
        'horario_inicio' => 'datetime:H:i',
        'horario_fin' => 'datetime:H:i',
    ];

    /**
     * Relación con el usuario (policía)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener las zonas disponibles de La Paz
     */
    public static function getZonasLaPaz(): array
    {
        return [
            'Centro' => 'Centro',
            'Sopocachi' => 'Sopocachi',
            'San Pedro' => 'San Pedro',
            'Rosario' => 'Rosario',
            'Villa Fátima' => 'Villa Fátima',
            'El Alto Centro' => 'El Alto Centro',
            'Villa Adela' => 'Villa Adela',
            'Tembladerani' => 'Tembladerani',
            'Achumani' => 'Achumani',
            'Calacoto' => 'Calacoto',
            'La Florida' => 'La Florida',
            'Obrajes' => 'Obrajes',
            'Cota Cota' => 'Cota Cota',
            'Irpavi' => 'Irpavi',
            'Seguencoma' => 'Seguencoma',
            'Villa Salomé' => 'Villa Salomé',
            'Max Paredes' => 'Max Paredes',
            'Periférica' => 'Periférica',
            'San Antonio' => 'San Antonio',
            'Mallasa' => 'Mallasa'
        ];
    }

    /**
     * Scope para obtener asignaciones activas
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para obtener asignaciones por zona
     */
    public function scopePorZona($query, $zona)
    {
        return $query->where('zona', $zona);
    }

    /**
     * Scope para obtener asignaciones por fecha
     */
    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha_asignacion', $fecha);
    }
}
