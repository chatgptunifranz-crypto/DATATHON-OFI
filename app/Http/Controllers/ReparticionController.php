<?php

namespace App\Http\Controllers;

use App\Models\Reparticion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReparticionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reparticiones = Reparticion::with('user')
            ->orderBy('fecha_asignacion', 'desc')
            ->orderBy('zona')
            ->paginate(15);
        
        $zonas = Reparticion::getZonasLaPaz();
        
        return view('reparticiones.index', compact('reparticiones', 'zonas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener solo usuarios con rol de policía que estén activos
        $policias = User::whereHas('roles', function($query) {
            $query->where('name', 'policia');
        })->where('activo', true)->orderBy('name')->get();
        
        $zonas = Reparticion::getZonasLaPaz();
        
        return view('reparticiones.create', compact('policias', 'zonas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'zona' => 'required|string|max:100',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio',
            'fecha_asignacion' => 'required|date|after_or_equal:today',
            'observaciones' => 'nullable|string|max:500'
        ], [
            'user_id.required' => 'Debe seleccionar un policía',
            'user_id.exists' => 'El policía seleccionado no existe',
            'zona.required' => 'Debe seleccionar una zona',
            'horario_inicio.required' => 'El horario de inicio es obligatorio',
            'horario_fin.required' => 'El horario de fin es obligatorio',
            'horario_fin.after' => 'El horario de fin debe ser posterior al horario de inicio',
            'fecha_asignacion.required' => 'La fecha de asignación es obligatoria',
            'fecha_asignacion.after_or_equal' => 'La fecha de asignación no puede ser anterior a hoy'
        ]);

        // Verificar que el policía no tenga otra asignación activa en la misma fecha
        $asignacionExistente = Reparticion::where('user_id', $request->user_id)
            ->where('fecha_asignacion', $request->fecha_asignacion)
            ->where('activo', true)
            ->exists();

        if ($asignacionExistente) {
            return back()->withErrors(['user_id' => 'El policía ya tiene una asignación activa para esa fecha'])
                ->withInput();
        }

        Reparticion::create($request->all());

        return redirect()->route('reparticiones.index')
            ->with('success', 'Asignación de repartición creada correctamente');
    }    /**
     * Display the specified resource.
     */
    public function show(Reparticion $reparticion)
    {
        $reparticion->load('user.roles');
        
        // Estadísticas para la vista
        $totalAsignaciones = Reparticion::where('user_id', $reparticion->user_id)->count();
        $asignacionesZona = Reparticion::where('user_id', $reparticion->user_id)
                                    ->where('zona', $reparticion->zona)
                                    ->count();
        
        // Historial de asignaciones del policía (últimas 10)
        $historialAsignaciones = Reparticion::where('user_id', $reparticion->user_id)
                                          ->with('user')
                                          ->orderBy('fecha_asignacion', 'desc')
                                          ->take(10)
                                          ->get();
        
        return view('reparticiones.show', compact(
            'reparticion', 
            'totalAsignaciones', 
            'asignacionesZona', 
            'historialAsignaciones'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reparticion $reparticion)
    {
        $policias = User::whereHas('roles', function($query) {
            $query->where('name', 'policia');
        })->where('activo', true)->orderBy('name')->get();
        
        $zonas = Reparticion::getZonasLaPaz();
        
        return view('reparticiones.edit', compact('reparticion', 'policias', 'zonas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reparticion $reparticion)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'zona' => 'required|string|max:100',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio',
            'fecha_asignacion' => 'required|date',
            'activo' => 'boolean',
            'observaciones' => 'nullable|string|max:500'
        ], [
            'user_id.required' => 'Debe seleccionar un policía',
            'user_id.exists' => 'El policía seleccionado no existe',
            'zona.required' => 'Debe seleccionar una zona',
            'horario_inicio.required' => 'El horario de inicio es obligatorio',
            'horario_fin.required' => 'El horario de fin es obligatorio',
            'horario_fin.after' => 'El horario de fin debe ser posterior al horario de inicio',
            'fecha_asignacion.required' => 'La fecha de asignación es obligatoria'
        ]);

        // Verificar que el policía no tenga otra asignación activa en la misma fecha (excluyendo la actual)
        $asignacionExistente = Reparticion::where('user_id', $request->user_id)
            ->where('fecha_asignacion', $request->fecha_asignacion)
            ->where('activo', true)
            ->where('id', '!=', $reparticion->id)
            ->exists();

        if ($asignacionExistente) {
            return back()->withErrors(['user_id' => 'El policía ya tiene otra asignación activa para esa fecha'])
                ->withInput();
        }

        $reparticion->update($request->all());

        return redirect()->route('reparticiones.index')
            ->with('success', 'Asignación de repartición actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reparticion $reparticion)
    {
        $reparticion->delete();

        return redirect()->route('reparticiones.index')
            ->with('success', 'Asignación de repartición eliminada correctamente');
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActivo(Reparticion $reparticion)
    {
        $reparticion->update(['activo' => !$reparticion->activo]);

        $estado = $reparticion->activo ? 'activada' : 'desactivada';
        
        return redirect()->route('reparticiones.index')
            ->with('success', "Asignación {$estado} correctamente");
    }

    /**
     * Filter reparticiones by zone
     */
    public function filtrarPorZona(Request $request)
    {
        $zona = $request->input('zona');
        $fecha = $request->input('fecha');
        
        $query = Reparticion::with('user');
        
        if ($zona) {
            $query->porZona($zona);
        }
        
        if ($fecha) {
            $query->porFecha($fecha);
        }
        
        $reparticiones = $query->orderBy('fecha_asignacion', 'desc')
            ->orderBy('zona')
            ->paginate(15);
        
        $zonas = Reparticion::getZonasLaPaz();
        
        return view('reparticiones.index', compact('reparticiones', 'zonas', 'zona', 'fecha'));
    }

    /**
     * Get policías available for a specific date and time
     */
    public function policiasDisponibles(Request $request)
    {
        $fecha = $request->input('fecha');
        $horaInicio = $request->input('hora_inicio');
        $horaFin = $request->input('hora_fin');
        
        // Obtener policías que NO tienen asignación activa para esa fecha/hora
        $policiasOcupados = Reparticion::where('fecha_asignacion', $fecha)
            ->where('activo', true)
            ->where(function($query) use ($horaInicio, $horaFin) {
                $query->whereBetween('horario_inicio', [$horaInicio, $horaFin])
                    ->orWhereBetween('horario_fin', [$horaInicio, $horaFin])
                    ->orWhere(function($subQuery) use ($horaInicio, $horaFin) {
                        $subQuery->where('horario_inicio', '<=', $horaInicio)
                                ->where('horario_fin', '>=', $horaFin);
                    });
            })
            ->pluck('user_id');
        
        $policiasDisponibles = User::whereHas('roles', function($query) {
            $query->where('name', 'policia');
        })
        ->where('activo', true)
        ->whereNotIn('id', $policiasOcupados)
        ->orderBy('name')
        ->get(['id', 'name']);
          return response()->json($policiasDisponibles);
    }    /**
     * Filtrar reparticiones por zona y fecha
     */
    public function filtrar(Request $request)
    {
        $zona = $request->input('zona');
        $fecha = $request->input('fecha');
        
        $query = Reparticion::with('user');
        
        if ($zona) {
            $query->where('zona', $zona);
        }
        
        if ($fecha) {
            $query->whereDate('fecha_asignacion', $fecha);
        }
        
        $reparticiones = $query->orderBy('fecha_asignacion', 'desc')
            ->orderBy('zona')
            ->paginate(15);
        
        $zonas = Reparticion::getZonasLaPaz();
        
        return view('reparticiones.index', compact('reparticiones', 'zonas', 'zona', 'fecha'));
    }

    /**
     * Generar PDF con las asignaciones de policías
     */
    public function generarPdf(Request $request)
    {
        $query = Reparticion::with('user');
        
        // Aplicar filtros si existen
        if ($request->filled('zona')) {
            $query->where('zona', $request->zona);
        }
        
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_asignacion', $request->fecha);
        }
        
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }
        
        $reparticiones = $query->where('activo', true) // Solo asignaciones activas por defecto
                              ->orderBy('zona')
                              ->orderBy('fecha_asignacion')
                              ->orderBy('horario_inicio')
                              ->get();
          $zonas = $reparticiones->groupBy('zona');
        
        $pdf = Pdf::loadView('reparticiones.pdf', compact('reparticiones', 'zonas'));
        
        return $pdf->download('asignaciones_policia_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
