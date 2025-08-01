<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aprobacion extends Model
{
    use HasFactory;

    protected $table = 'aprobacion_del_dias';

    protected $fillable = [
        'orden_del_dia_id',
        'estado',
        'usuario_creador_id',
        'usuario_aprobador_id',
        'observaciones',
        'errores_detectados',
        'fecha_aprobacion',
    ];

    protected $casts = [
        'fecha_aprobacion' => 'datetime',
    ];

    // Estados disponibles
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_APROBADO = 'aprobado';

    public static function getEstados()
    {
        return [
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_APROBADO => 'Aprobado',
        ];
    }

    // Relaciones
    public function ordenDelDia(): BelongsTo
    {
        return $this->belongsTo(OrdenDelDia::class, 'orden_del_dia_id');
    }

    public function usuarioCreador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_creador_id');
    }

    public function usuarioAprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_aprobador_id');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', self::ESTADO_APROBADO);
    }

    // Métodos de utilidad
    public function esPendiente(): bool
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    public function esAprobado(): bool
    {
        return $this->estado === self::ESTADO_APROBADO;
    }

    public function getEstadoFormateado(): string
    {
        return self::getEstados()[$this->estado] ?? $this->estado;
    }

    public function tieneErrores(): bool
    {
        return !empty($this->errores_detectados);
    }

    public function aprobar($usuarioId, $observaciones = null)
    {
        $this->update([
            'estado' => self::ESTADO_APROBADO,
            'usuario_aprobador_id' => $usuarioId,
            'fecha_aprobacion' => now(),
            'observaciones' => $observaciones,
        ]);
    }

    public function rechazar($observaciones)
    {
        $this->update([
            'estado' => self::ESTADO_PENDIENTE,
            'observaciones' => $observaciones,
            'usuario_aprobador_id' => null,
            'fecha_aprobacion' => null,
        ]);
    }
}
