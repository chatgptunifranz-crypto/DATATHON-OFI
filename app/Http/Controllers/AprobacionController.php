<?php

namespace App\Http\Controllers;

use App\Models\Aprobacion;
use App\Models\OrdenDelDia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AprobacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-aprobaciones');
    }

    public function index(Request $request): View
    {
        $query = Aprobacion::with(['ordenDelDia', 'usuarioCreador', 'usuarioAprobador']);

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        if ($request->filled('orden_del_dia_id')) {
            $query->where('orden_del_dia_id', $request->orden_del_dia_id);
        }        $aprobaciones = $query->orderBy('created_at', 'desc')->paginate(15);

        $ordenes = OrdenDelDia::select('id', 'nombre')->orderBy('nombre')->get();

        return view('aprobaciones.index', compact('aprobaciones', 'ordenes'));
    }

    public function create(): View
    {
        $ordenes = OrdenDelDia::select('id', 'nombre')->orderBy('nombre')->get();
        $usuarios = User::select('id', 'name')->orderBy('name')->get();

        return view('aprobaciones.create', compact('ordenes', 'usuarios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'orden_del_dia_id' => 'required|exists:ordenes_del_dia,id',
            'estado' => 'required|in:pendiente,aprobado',
            'usuario_aprobador_id' => 'nullable|exists:users,id',
            'observaciones' => 'nullable|string|max:1000',
            'errores_detectados' => 'nullable|string|max:1000',
            'fecha_aprobacion' => 'nullable|date',
        ]);

        // Si el estado es aprobado, requerir fecha de aprobación
        if ($validated['estado'] === 'aprobado' && empty($validated['fecha_aprobacion'])) {
            $validated['fecha_aprobacion'] = now();
        }

        $validated['usuario_creador_id'] = Auth::id();

        Aprobacion::create($validated);

        return redirect()->route('aprobaciones.index')
            ->with('success', 'Aprobación creada exitosamente.');
    }

    public function show(Aprobacion $aprobacion): View
    {
        $aprobacion->load(['ordenDelDia', 'usuarioCreador', 'usuarioAprobador']);
        
        return view('aprobaciones.show', compact('aprobacion'));
    }    public function edit(Aprobacion $aprobacion): View
    {
        $ordenes = OrdenDelDia::select('id', 'nombre')->orderBy('nombre')->get();
        $usuarios = User::select('id', 'name')->orderBy('name')->get();

        return view('aprobaciones.edit', compact('aprobacion', 'ordenes', 'usuarios'));
    }

    public function update(Request $request, Aprobacion $aprobacion): RedirectResponse
    {
        $validated = $request->validate([
            'orden_del_dia_id' => 'required|exists:ordenes_del_dia,id',
            'estado' => 'required|in:pendiente,aprobado',
            'usuario_aprobador_id' => 'nullable|exists:users,id',
            'observaciones' => 'nullable|string|max:1000',
            'errores_detectados' => 'nullable|string|max:1000',
            'fecha_aprobacion' => 'nullable|date',
        ]);

        // Si el estado es aprobado, requerir fecha de aprobación
        if ($validated['estado'] === 'aprobado' && empty($validated['fecha_aprobacion'])) {
            $validated['fecha_aprobacion'] = now();
        }

        $aprobacion->update($validated);

        return redirect()->route('aprobaciones.index')
            ->with('success', 'Aprobación actualizada exitosamente.');
    }

    public function destroy(Aprobacion $aprobacion): RedirectResponse
    {
        $aprobacion->delete();

        return redirect()->route('aprobaciones.index')
            ->with('success', 'Aprobación eliminada exitosamente.');
    }

    public function aprobar(Request $request, Aprobacion $aprobacion): RedirectResponse
    {
        $request->validate([
            'observaciones' => 'nullable|string|max:1000',
        ]);

        $aprobacion->aprobar(Auth::id(), $request->observaciones);

        return redirect()->route('aprobaciones.index')
            ->with('success', 'Aprobación aprobada exitosamente.');
    }

    public function rechazar(Request $request, Aprobacion $aprobacion): RedirectResponse
    {
        $request->validate([
            'observaciones' => 'required|string|max:1000',
        ]);

        $aprobacion->rechazar($request->observaciones);

        return redirect()->route('aprobaciones.index')
            ->with('success', 'Aprobación rechazada exitosamente.');
    }
}
