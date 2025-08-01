<?php

namespace App\Observers;

use App\Models\Registro;
use App\Services\BackupService;
use Illuminate\Support\Facades\Log;

class RegistroObserver
{
    protected $backupService;
    
    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }
    
    /**
     * Después de crear un registro
     */
    public function created(Registro $registro)
    {
        $this->generarBackup('created', $registro->id);
    }
    
    /**
     * Después de actualizar un registro
     */
    public function updated(Registro $registro)
    {
        $this->generarBackup('updated', $registro->id);
    }
    
    /**
     * Después de eliminar un registro
     */
    public function deleted(Registro $registro)
    {
        $this->generarBackup('deleted', $registro->id);
    }
      /**
     * Generar backup automático
     */
    private function generarBackup($action, $registroId)
    {
        try {
            // Ejecutar backup de forma síncrona pero con try-catch para no afectar la operación principal
            $backupService = new BackupService();
            $result = $backupService->generarBackupRegistros();
            
            if ($result['success']) {
                Log::info("Backup automático generado después de {$action} en registro {$registroId}", [
                    'action' => $action,
                    'registro_id' => $registroId,
                    'backup_file' => $result['filename'],
                    'records_count' => $result['records_count']
                ]);
            } else {
                Log::warning("Error en backup automático después de {$action} en registro {$registroId}", [
                    'error' => $result['error']
                ]);
            }
            
        } catch (\Exception $e) {
            // Solo registrar el error, no interrumpir la operación principal
            Log::error("Error ejecutando backup automático: " . $e->getMessage(), [
                'action' => $action,
                'registro_id' => $registroId
            ]);
        }
    }
}
