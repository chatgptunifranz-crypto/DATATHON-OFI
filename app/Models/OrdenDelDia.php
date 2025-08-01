<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrdenDelDia extends Model
{
    protected $table = 'ordenes_del_dia';

    protected $fillable = [
        'nombre', 
        'fecha', 
        'contenido',
        'user_id'
    ];

    protected $casts = [
        'fecha' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que creó la orden del día
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con las aprobaciones
     */
    public function aprobaciones(): HasMany
    {
        return $this->hasMany(Aprobacion::class, 'orden_del_dia_id');
    }

    /**
     * Scope para órdenes del mes actual
     */
    public function scopeEsteMes($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Scope para órdenes de esta semana
     */
    public function scopeEstaSemana($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope para órdenes de hoy
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeBuscarPorNombre($query, $nombre)
    {
        return $query->where('nombre', 'like', '%' . $nombre . '%');
    }

    /**
     * Scope para filtrar por rango de fechas
     */
    public function scopeFiltrarPorFecha($query, $fechaInicio, $fechaFin)
    {
        if ($fechaInicio) {
            $query->where('fecha', '>=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $query->where('fecha', '<=', $fechaFin);
        }
        
        return $query;
    }

    /**
     * Accessor para obtener la fecha formateada
     */
    public function getFechaFormateadaAttribute(): string
    {
        return $this->fecha ? $this->fecha->format('d/m/Y') : '';
    }

    /**
     * Accessor para obtener el resumen del contenido
     */
    public function getResumenContenidoAttribute(): string    {
        return Str::limit(strip_tags($this->contenido), 150);
    }

    /**
     * Accessor para obtener el nombre completo con ID
     */
    public function getNombreCompletoAttribute(): string
    {
        return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT) . ' - ' . $this->nombre;
    }

    /**
     * Método para obtener estadísticas generales
     */
    public static function getEstadisticas(): array
    {
        return [
            'total' => self::count(),
            'este_mes' => self::esteMes()->count(),
            'esta_semana' => self::estaSemana()->count(),
            'hoy' => self::hoy()->count(),
            'aprobadas' => self::conEstadoAprobacion('aprobado')->count(),
            'pendientes' => self::whereDoesntHave('aprobaciones')
                              ->orWhereHas('aprobaciones', function ($q) {
                                  $q->where('estado', 'pendiente')->latest();
                              })->count(),
            'rechazadas' => self::conEstadoAprobacion('rechazado')->count(),
        ];
    }

    /**
     * Boot method para eventos del modelo
     */
    protected static function boot()
    {
        parent::boot();        // Auto-asignar usuario actual al crear
        static::creating(function ($model) {
            if (Auth::check() && !$model->user_id) {
                $model->user_id = Auth::id();
            }
        });
    }
}
