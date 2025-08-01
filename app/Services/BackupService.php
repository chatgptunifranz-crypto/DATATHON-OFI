<?php

namespace App\Services;

use App\Models\Registro;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupService
{
    private $backupPath = 'backups/registros';
      /**
     * Generar backup completo de registros en CSV
     */    public function generarBackupRegistros()
    {
        try {
            \Log::info('Iniciando generación de backup de registros');
            
            // Obtener todos los registros
            $registros = Registro::all();
            \Log::info('Obtenidos ' . $registros->count() . ' registros de la base de datos');
            
            // Crear el contenido CSV
            \Log::info('Generando contenido CSV');
            $csvContent = $this->generarContenidoCSV($registros);
            
            // Generar nombre del archivo con timestamp
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "registros_backup_{$timestamp}.csv";
            
            // Crear directorio si no existe
            Storage::makeDirectory($this->backupPath);
            
            // Guardar el archivo
            $fullPath = "{$this->backupPath}/{$filename}";
            Storage::put($fullPath, $csvContent);
            
            // Limpiar backups antiguos (mantener solo los últimos 10)
            $this->limpiarBackupsAntiguos();
            
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $fullPath,
                'records_count' => $registros->count()
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error generando backup de registros: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
      /**
     * Generar contenido CSV
     */    private function generarContenidoCSV($registros)
    {
        \Log::info('Iniciando generación de contenido CSV para ' . $registros->count() . ' registros');
        
        $headers = [
            'ID',
            'Nombres',
            'Apellido Paterno',
            'Apellido Materno',
            'CI',
            'Fecha Nacimiento',
            'Expedido',
            'Estado Civil',
            'Profesion',
            'Domicilio',
            'Cargo',
            'Descripcion',
            'Antecedentes',
            'Latitud',
            'Longitud',
            'Fecha Creación',
            'Fecha Actualización'
        ];
        
        // Agregar headers
        $csv = implode(',', $headers) . "\n";
        
        \Log::info('Headers CSV generados');
        
        // Agregar datos
        $counter = 0;
        foreach ($registros as $registro) {
            \Log::info('Procesando registro ' . ($counter + 1) . ' - ID: ' . $registro->id);
            
            $row = [
                $registro->id,
                '"' . str_replace('"', '""', $registro->nombres ?? '') . '"',
                '"' . str_replace('"', '""', $registro->apellido_paterno ?? '') . '"',
                '"' . str_replace('"', '""', $registro->apellido_materno ?? '') . '"',
                $registro->ci ?? '',
                $registro->fecha_nacimiento ? $registro->fecha_nacimiento->format('Y-m-d') : '',
                '"' . str_replace('"', '""', $registro->expedido ?? '') . '"',
                '"' . str_replace('"', '""', $registro->estado_civil ?? '') . '"',
                '"' . str_replace('"', '""', $registro->profesion ?? '') . '"',
                '"' . str_replace('"', '""', $registro->domicilio ?? '') . '"',
                '"' . str_replace('"', '""', $registro->cargo ?? '') . '"',
                '"' . str_replace('"', '""', $registro->descripcion ?? '') . '"',
                '"' . str_replace('"', '""', $registro->antecedentes ?? '') . '"',
                $registro->latitud ?? '',
                $registro->longitud ?? '',
                $registro->created_at ? $registro->created_at->format('Y-m-d H:i:s') : '',
                $registro->updated_at ? $registro->updated_at->format('Y-m-d H:i:s') : ''
            ];
            
            $csv .= implode(',', $row) . "\n";
            $counter++;
        }
        
        \Log::info('Contenido CSV generado exitosamente para ' . $counter . ' registros');
        
        return $csv;
    }
    
    /**
     * Limpiar backups antiguos para no saturar el storage
     */
    private function limpiarBackupsAntiguos()
    {
        $files = Storage::allFiles($this->backupPath);
        
        // Filtrar solo archivos CSV de backup
        $backupFiles = array_filter($files, function($file) {
            return strpos($file, 'registros_backup_') !== false && pathinfo($file, PATHINFO_EXTENSION) === 'csv';
        });
        
        // Ordenar por fecha de modificación (más recientes primero)
        usort($backupFiles, function($a, $b) {
            return Storage::lastModified($b) - Storage::lastModified($a);
        });
        
        // Mantener solo los últimos 10 backups
        $filesToDelete = array_slice($backupFiles, 10);
        
        foreach ($filesToDelete as $file) {
            Storage::delete($file);
        }
    }
      /**
     * Obtener lista de backups disponibles
     */
    public function obtenerListaBackups()
    {
        $files = Storage::allFiles($this->backupPath);
        
        $backups = [];
        foreach ($files as $file) {
            if (strpos($file, 'registros_backup_') !== false && pathinfo($file, PATHINFO_EXTENSION) === 'csv') {
                $backups[] = [
                    'filename' => basename($file),
                    'path' => $file,
                    'size' => Storage::size($file),
                    'created_at' => Carbon::createFromTimestamp(Storage::lastModified($file))
                ];
            }
        }
        
        // Ordenar por fecha más reciente
        usort($backups, function($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });
        
        return $backups;
    }
}
