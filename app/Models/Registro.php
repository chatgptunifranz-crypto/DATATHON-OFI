<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $fillable = [
        // Datos personales básicos
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'ci',
        'fecha_nacimiento',
        'expedido',
        'estado_civil',
        'profesion',
        'domicilio',
        
        // Datos del registro policial
        'cargo',
        'descripcion',
        'foto',
        'antecedentes',
        
        // Ubicación geográfica
        'longitud',
        'latitud',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Obtener la edad calculada a partir de la fecha de nacimiento
     */
    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento ? $this->fecha_nacimiento->age : null;
    }

    /**
     * Obtener el nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombres . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno;
    }

    /**
     * Obtener los departamentos de Bolivia para el campo expedido
     */
    public static function getDepartamentosBolivia()
    {
        return [
            'La Paz' => 'La Paz',
            'Cochabamba' => 'Cochabamba',
            'Santa Cruz' => 'Santa Cruz',
            'Potosí' => 'Potosí',
            'Chuquisaca' => 'Chuquisaca',
            'Tarija' => 'Tarija',
            'Oruro' => 'Oruro',
            'Beni' => 'Beni',
            'Pando' => 'Pando'
        ];
    }

    /**
     * Obtener los estados civiles disponibles
     */
    public static function getEstadosCiviles()
    {
        return [
            'soltero' => 'Soltero(a)',
            'casado' => 'Casado(a)',
            'divorciado' => 'Divorciado(a)',
            'viudo' => 'Viudo(a)',
            'union_libre' => 'Unión Libre'
        ];
    }
}
