<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AntecedenteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-antecedentes');
    }    /**
     * Mostrar lista de todos los registros para gestión de antecedentes
     */
    public function index(Request $request)
    {
        $query = Registro::query();

        // Búsqueda por CI
        if ($request->filled('ci')) {
            $query->where('ci', 'like', '%' . $request->ci . '%');
        }

        // Búsqueda por nombres
        if ($request->filled('nombres')) {
            $query->where('nombres', 'like', '%' . $request->nombres . '%');
        }

        // Búsqueda por apellido paterno
        if ($request->filled('apellido_paterno')) {
            $query->where('apellido_paterno', 'like', '%' . $request->apellido_paterno . '%');
        }

        // Búsqueda por apellido materno
        if ($request->filled('apellido_materno')) {
            $query->where('apellido_materno', 'like', '%' . $request->apellido_materno . '%');
        }

        $registros = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('antecedentes.index', compact('registros'));
    }    /**
     * Mostrar un registro específico para ver/gestionar antecedentes
     */
    public function show($id)
    {
        $registro = Registro::findOrFail($id);
        
        return view('antecedentes.show', compact('registro'));
    }    /**
     * Buscar por CI
     */
    public function buscarPorCi(Request $request)
    {
        $request->validate([
            'ci' => 'required|string|max:20',
        ]);

        $registro = Registro::where('ci', $request->ci)->first();

        if ($registro) {
            return response()->json([
                'success' => true,
                'data' => $registro
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontró ningún registro con este CI.'
        ]);
    }    /**
     * Generar PDF de antecedentes
     */
    public function generarPdf($id)
    {
        $registro = Registro::findOrFail($id);
        
        $pdf = Pdf::loadView('antecedentes.pdf', compact('registro'));
        
        $filename = 'antecedentes_' . $registro->ci . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }    /**
     * Generar PDF de múltiples antecedentes
     */
    public function generarPdfMultiple(Request $request)
    {
        $request->validate([
            'registros' => 'required|array',
            'registros.*' => 'exists:registros,id'
        ]);

        $registros = Registro::whereIn('id', $request->registros)->get();
        
        if ($registros->isEmpty()) {
            return redirect()->route('antecedentes.index')
                ->with('error', 'No se encontraron los registros seleccionados.');
        }
        
        $pdf = Pdf::loadView('antecedentes.pdf-multiple', compact('registros'));
        
        $filename = 'antecedentes_multiple_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    } /**
     * Mostrar el formulario para crear un nuevo antecedente
     */
    public function create()
    {
        return view('antecedentes.create');
    }

    /**
     * Almacenar un nuevo antecedente
     */
    public function store(Request $request)
    {
        $request->validate([
            // Datos personales básicos
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:registros,ci',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'expedido' => 'nullable|string|max:255',
            'estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,union_libre',
            'profesion' => 'nullable|string|max:255',
            'domicilio' => 'nullable|string|max:500',
            
            // Datos del registro policial
            'cargo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'antecedentes' => 'required|string',
            
            // Ubicación geográfica
            'longitud' => 'nullable|numeric|between:-180,180',
            'latitud' => 'nullable|numeric|between:-90,90',
        ]);

        $registro = Registro::create($request->all());

        return redirect()->route('antecedentes.show', $registro->id)
            ->with('success', 'Antecedente registrado exitosamente.');
    }

    /**
     * Mostrar el formulario para editar un antecedente
     */
    public function edit($id)
    {
        $registro = Registro::findOrFail($id);
        
        return view('antecedentes.edit', compact('registro'));
    }

    /**
     * Actualizar un antecedente específico
     */
    public function update(Request $request, $id)
    {
        $registro = Registro::findOrFail($id);
        
        $request->validate([
            // Datos personales básicos
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:registros,ci,' . $registro->id,
            'fecha_nacimiento' => 'nullable|date|before:today',
            'expedido' => 'nullable|string|max:255',
            'estado_civil' => 'nullable|in:soltero,casado,divorciado,viudo,union_libre',
            'profesion' => 'nullable|string|max:255',
            'domicilio' => 'nullable|string|max:500',
            
            // Datos del registro policial
            'cargo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'antecedentes' => 'required|string',
            
            // Ubicación geográfica
            'longitud' => 'nullable|numeric|between:-180,180',
            'latitud' => 'nullable|numeric|between:-90,90',
        ]);

        $registro->update($request->all());

        return redirect()->route('antecedentes.show', $registro->id)
            ->with('success', 'Antecedente actualizado exitosamente.');
    }

    /**
     * Eliminar un antecedente específico
     */
    public function destroy($id)
    {
        $registro = Registro::findOrFail($id);
        
        $registro->delete();
        
        return redirect()->route('antecedentes.index')
            ->with('success', 'Antecedente eliminado exitosamente.');
    }
}
