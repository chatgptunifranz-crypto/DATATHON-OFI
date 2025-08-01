<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CheckPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar roles y permisos del sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== VERIFICACIÓN DE ROLES Y PERMISOS ===');
        
        // Mostrar todos los roles y sus permisos
        $roles = Role::with('permissions')->get();
        
        foreach ($roles as $role) {
            $this->info("Rol: {$role->name}");
            $permissions = $role->permissions->pluck('name')->toArray();
            if (empty($permissions)) {
                $this->warn("  - Sin permisos asignados");
            } else {
                foreach ($permissions as $permission) {
                    $this->line("  - {$permission}");
                }
            }
            $this->line('');
        }
        
        // Verificar usuario comandante específicamente
        $comandante = User::where('email', 'comandante@gmail.com')->first();
        if ($comandante) {
            $this->info("=== USUARIO COMANDANTE ===");
            $this->info("Email: {$comandante->email}");
            $userPermissions = $comandante->getAllPermissions()->pluck('name')->toArray();
            if (empty($userPermissions)) {
                $this->warn("Sin permisos asignados");
            } else {
                foreach ($userPermissions as $permission) {
                    $this->line("  - {$permission}");
                }
            }
        }
    }
}
