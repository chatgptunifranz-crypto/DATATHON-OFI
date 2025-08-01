<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\BackupService;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registros = Registro::all();
        return view('registros.index', compact('registros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registros.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Datos personales básicos
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:registros,ci',
            'fecha_nacimiento' => 'required|date|before:today',
            'expedido' => 'required|string|max:255',
            'estado_civil' => 'required|in:soltero,casado,divorciado,viudo,union_libre',
            'profesion' => 'nullable|string|max:255',
            'domicilio' => 'required|string|max:500',
            
            // Datos del registro policial
            'cargo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'antecedentes' => 'nullable|string',
            
            // Ubicación geográfica
            'longitud' => 'nullable|numeric|between:-180,180',
            'latitud' => 'nullable|numeric|between:-90,90',
        ], [
            'ci.unique' => 'Ya existe un registro con este número de CI.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'expedido.required' => 'El lugar de expedición del CI es obligatorio.',
            'domicilio.required' => 'El domicilio es obligatorio.',
            'estado_civil.required' => 'El estado civil es obligatorio.',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreFoto = time() . '_' . $request->ci . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('fotos'), $nombreFoto);
            $data['foto'] = 'fotos/' . $nombreFoto;
        }
        
        Registro::create($data);
        return redirect()->route('registros.index')->with('success', 'Registro creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Registro $registro)
    {
        return view('registros.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Registro $registro)
    {
        return view('registros.edit', compact('registro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registro $registro)
    {
        $request->validate([
            // Datos personales básicos
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'ci' => 'required|string|max:20|unique:registros,ci,' . $registro->id,
            'fecha_nacimiento' => 'required|date|before:today',
            'expedido' => 'required|string|max:255',
            'estado_civil' => 'required|in:soltero,casado,divorciado,viudo,union_libre',
            'profesion' => 'nullable|string|max:255',
            'domicilio' => 'required|string|max:500',
            
            // Datos del registro policial
            'cargo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'antecedentes' => 'nullable|string',
            
            // Ubicación geográfica
            'longitud' => 'nullable|numeric|between:-180,180',
            'latitud' => 'nullable|numeric|between:-90,90',
        ], [
            'ci.unique' => 'Ya existe un registro con este número de CI.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'expedido.required' => 'El lugar de expedición del CI es obligatorio.',
            'domicilio.required' => 'El domicilio es obligatorio.',
            'estado_civil.required' => 'El estado civil es obligatorio.',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($registro->foto && file_exists(public_path($registro->foto))) {
                unlink(public_path($registro->foto));
            }
            
            $foto = $request->file('foto');
            $nombreFoto = time() . '_' . $request->ci . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('fotos'), $nombreFoto);
            $data['foto'] = 'fotos/' . $nombreFoto;
        }
        
        $registro->update($data);
        return redirect()->route('registros.index')->with('success', 'Registro actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registro $registro)
    {
        $registro->delete();
        return redirect()->route('registros.index')->with('success', 'Registro eliminado correctamente.');
    }

    /**
     * Método para enviar reportes (puede ser por email o exportar PDF, aquí solo ejemplo de vista)
     */
    public function reportes(Request $request)
    {
        $query = Registro::query();
        
        // Aplicar filtro por cargo si se especifica
        if ($request->filled('cargo')) {
            $query->where('cargo', $request->cargo);
        }
        
        $registros = $query->get();
        return view('registros.reportes', compact('registros'));
    }

    /**
     * Buscar antecedentes por CI
     */
    public function buscarPorCi(Request $request)
    {
        $ci = $request->get('ci');
        $registros = [];
        
        if ($ci) {
            $registros = Registro::where('ci', $ci)
                ->orderBy('created_at', 'desc')
                ->get();
                
            Log::info('Búsqueda de antecedentes', [
                'ci' => $ci,
                'cantidad_encontrada' => $registros->count(),
                'registros' => $registros->toArray()
            ]);
        }
        
        return response()->json($registros);
    }
    
    /**
     * Generar backup manual de registros en CSV
     */
    public function generarBackup()
    {
        try {
            $backupService = new BackupService();
            $result = $backupService->generarBackupRegistros();
            
            if ($result['success']) {
                return redirect()->back()->with('success', 
                    "Backup generado exitosamente: {$result['filename']} ({$result['records_count']} registros)"
                );
            } else {
                return redirect()->back()->with('error', 
                    'Error al generar backup: ' . $result['error']
                );
            }
        } catch (\Exception $e) {
            Log::error('Error en backup manual: ' . $e->getMessage());
            return redirect()->back()->with('error', 
                'Error inesperado al generar backup: ' . $e->getMessage()
            );
        }
    }
    
    /**
     * Descargar backup específico
     */
    public function descargarBackup($filename)
    {
        try {
            $filePath = "backups/registros/{$filename}";
            
            if (!Storage::exists($filePath)) {
                return redirect()->back()->with('error', 'El archivo de backup no existe.');
            }
            
            return Storage::download($filePath);
            
        } catch (\Exception $e) {
            Log::error('Error al descargar backup: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al descargar el backup.');
        }
    }
    
    /**
     * Listar backups disponibles
     */
    public function listarBackups()
    {
        try {
            $backupService = new BackupService();
            $backups = $backupService->obtenerListaBackups();
            
            return view('registros.backups', compact('backups'));
            
        } catch (\Exception $e) {
            Log::error('Error al listar backups: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la lista de backups.');
        }
    }
    
    /**
     * Eliminar backup específico
     */
    public function eliminarBackup($filename)
    {
        try {
            $filePath = "backups/registros/{$filename}";
            
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
                return redirect()->back()->with('success', 'Backup eliminado correctamente.');
            } else {
                return redirect()->back()->with('error', 'El archivo no existe.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar backup: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el backup.');
        }
    }
}
