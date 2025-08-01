<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{    public function run()
    {        // Crear permisos
        $permissions = [
            'manage-users',
            'manage-ordenes',
            'manage-registros',
            'manage-antecedentes',
            'manage-reparticiones',
            'manage-aprobaciones',
            'view-reportes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }        // Crear roles y asignar permisos
        $administrador = Role::firstOrCreate(['name' => 'administrador']);
        // Asignar todos los permisos al rol administrador
        $administrador->syncPermissions(Permission::all());        $comandante = Role::firstOrCreate(['name' => 'comandante']);
        $comandante->syncPermissions(['manage-aprobaciones','manage-registros']);
        
        $sargento = Role::firstOrCreate(['name' => 'sargento']);
        $sargento->syncPermissions(['manage-ordenes', 'manage-registros', 'manage-antecedentes', 'manage-reparticiones', 'view-reportes']);

        $policia = Role::firstOrCreate(['name' => 'policia']);
        $policia->syncPermissions(['manage-ordenes', 'manage-registros', 'manage-antecedentes']);
    }
}