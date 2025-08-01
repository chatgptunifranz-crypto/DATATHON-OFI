<?php

namespace App\Http\Controllers;

use App\Models\OrdenDelDia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class OrdenDelDiaController extends Controller
{
    /**
     * Mostrar listado de órdenes del día
     */
    public function index()
    {
        try {
            $ordenes = OrdenDelDia::with('user')
                        ->orderBy('fecha', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->get();
            
            return view('ordenes.index', compact('ordenes'));
        } catch (Exception $e) {
            Log::error('Error al cargar órdenes del día: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las órdenes del día');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('ordenes.create');
    }    /**
     * Almacenar nueva orden del día
     */    public function store(Request $request)
    {
        try {
            // Debug: Log de datos recibidos (sin contenido completo por tamaño)
            Log::info('Iniciando creación de orden del día', [
                'nombre' => $request->input('nombre'),
                'fecha' => $request->input('fecha'),
                'contenido_length' => strlen($request->input('contenido', '')),
                'contenido_backup_length' => strlen($request->input('contenido_backup', '')),
                'usuario' => Auth::user()->name ?? 'Sistema',
                'ip' => $request->ip()
            ]);
            
            $request->validate([
                'nombre' => 'required|string|max:255',
                'fecha' => 'required|date',
                'contenido' => 'required|string|min:10',
            ], [
                'nombre.required' => 'El nombre de la orden del día es obligatorio',
                'nombre.max' => 'El nombre no puede exceder 255 caracteres',
                'fecha.required' => 'La fecha es obligatoria',
                'fecha.date' => 'La fecha debe tener un formato válido',
                'contenido.required' => 'El contenido es obligatorio',
                'contenido.min' => 'El contenido debe tener al menos 10 caracteres',
            ]);

            $data = $request->all();
            
            // Si el contenido principal está vacío, usar el backup
            if (empty($data['contenido']) && !empty($request->input('contenido_backup'))) {
                $data['contenido'] = $request->input('contenido_backup');
            }
            
            // Agregar usuario actual si está autenticado
            if (Auth::check()) {
                $data['user_id'] = Auth::id();
            }

            $orden = OrdenDelDia::create($data);
            
            Log::info('Orden del día creada', [
                'orden_id' => $orden->id,
                'nombre' => $orden->nombre,
                'usuario' => Auth::user()->name ?? 'Sistema'
            ]);

            return redirect()->route('ordenes.index')
                           ->with('success', 'Orden del día creada correctamente');
                             } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Error de validación al crear orden del día', [
                'errors' => $e->errors(),
                'input_nombre' => $request->input('nombre'),
                'input_fecha' => $request->input('fecha'),
                'contenido_length' => strlen($request->input('contenido', '')),
                'contenido_backup_length' => strlen($request->input('contenido_backup', '')),
                'usuario' => Auth::user()->name ?? 'Sistema'
            ]);
            
            return redirect()->back()
                           ->withInput()
                           ->withErrors($e->errors())
                           ->with('error', 'Por favor revise los datos ingresados.');
                           
        } catch (Exception $e) {
            Log::error('Error al crear orden del día: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input_nombre' => $request->input('nombre'),
                'input_fecha' => $request->input('fecha'),
                'usuario' => Auth::user()->name ?? 'Sistema'
            ]);
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error interno del servidor. Por favor intente nuevamente.');
        }
    }

    /**
     * Mostrar orden del día específica
     */
    public function show($id)
    {
        try {
            $orden = OrdenDelDia::with('user')->findOrFail($id);
            return view('ordenes.show', compact('orden'));
        } catch (Exception $e) {
            Log::error('Error al mostrar orden del día: ' . $e->getMessage());
            return redirect()->route('ordenes.index')
                           ->with('error', 'No se pudo encontrar la orden del día solicitada');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $orden = OrdenDelDia::with('user')->findOrFail($id);
            return view('ordenes.edit', compact('orden'));
        } catch (Exception $e) {
            Log::error('Error al cargar formulario de edición: ' . $e->getMessage());
            return redirect()->route('ordenes.index')
                           ->with('error', 'No se pudo cargar la orden del día para edición');
        }
    }

    /**
     * Actualizar orden del día
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'fecha' => 'required|date',
                'contenido' => 'required|string|min:10',
            ], [
                'nombre.required' => 'El nombre de la orden del día es obligatorio',
                'nombre.max' => 'El nombre no puede exceder 255 caracteres',
                'fecha.required' => 'La fecha es obligatoria',
                'fecha.date' => 'La fecha debe tener un formato válido',
                'contenido.required' => 'El contenido es obligatorio',
                'contenido.min' => 'El contenido debe tener al menos 10 caracteres',
            ]);

            $orden = OrdenDelDia::findOrFail($id);
            $nombreAnterior = $orden->nombre;
            
            $orden->update($request->all());
            
            Log::info('Orden del día actualizada', [
                'orden_id' => $orden->id,
                'nombre_anterior' => $nombreAnterior,
                'nombre_nuevo' => $orden->nombre,
                'usuario' => Auth::user()->name ?? 'Sistema'
            ]);

            return redirect()->route('ordenes.index')
                           ->with('success', 'Orden del día actualizada correctamente');
                           
        } catch (Exception $e) {
            Log::error('Error al actualizar orden del día: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar la orden del día: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar orden del día
     */
    public function destroy($id)
    {
        try {
            $orden = OrdenDelDia::findOrFail($id);
            $nombreOrden = $orden->nombre;
            
            $orden->delete();
            
            Log::info('Orden del día eliminada', [
                'orden_id' => $id,
                'nombre' => $nombreOrden,
                'usuario' => Auth::user()->name ?? 'Sistema'
            ]);

            return redirect()->route('ordenes.index')
                           ->with('success', 'Orden del día eliminada correctamente');
                           
        } catch (Exception $e) {
            Log::error('Error al eliminar orden del día: ' . $e->getMessage());
            return redirect()->route('ordenes.index')
                           ->with('error', 'Error al eliminar la orden del día');
        }
    }

    /**
     * Buscar órdenes del día (para API/AJAX)
     */
    public function search(Request $request)
    {
        try {
            $query = OrdenDelDia::with('user');
            
            if ($request->has('nombre') && $request->nombre) {
                $query->where('nombre', 'like', '%' . $request->nombre . '%');
            }
            
            if ($request->has('fecha_desde') && $request->fecha_desde) {
                $query->where('fecha', '>=', $request->fecha_desde);
            }
            
            if ($request->has('fecha_hasta') && $request->fecha_hasta) {
                $query->where('fecha', '<=', $request->fecha_hasta);
            }
            
            $ordenes = $query->orderBy('fecha', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $ordenes
            ]);
            
        } catch (Exception $e) {
            Log::error('Error en búsqueda de órdenes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de órdenes del día
     */
    public function estadisticas()
    {
        try {
            $stats = [
                'total' => OrdenDelDia::count(),
                'este_mes' => OrdenDelDia::whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count(),
                'esta_semana' => OrdenDelDia::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'hoy' => OrdenDelDia::whereDate('created_at', today())->count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (Exception $e) {
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }
}
