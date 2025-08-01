<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BackupService;

class TestBackup extends Command
{
    protected $signature = 'test:backup';
    protected $description = 'Probar el servicio de backup';

    public function handle()
    {
        $this->info('Probando BackupService...');
        
        try {
            $backupService = new BackupService();
            $result = $backupService->generarBackupRegistros();
            
            if ($result['success']) {
                $this->info('✓ Backup generado exitosamente:');
                $this->line('  - Archivo: ' . $result['filename']);
                $this->line('  - Registros: ' . $result['records_count']);
                $this->line('  - Ruta: ' . $result['path']);
            } else {
                $this->error('✗ Error generando backup:');
                $this->line('  - Error: ' . $result['error']);
            }
        } catch (\Exception $e) {
            $this->error('✗ Excepción capturada:');
            $this->line('  - Mensaje: ' . $e->getMessage());
            $this->line('  - Archivo: ' . $e->getFile() . ':' . $e->getLine());
        }
    }
}
