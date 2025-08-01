<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar primero el seeder de roles y permisos
        $this->call(RolePermissionSeeder::class);

        // User::factory(10)->create();

        // Crear usuario administrador
        $adminUser = User::create([
            'name' => 'administrador',
            'email' => 'administrador@gmail.com',
            'password' => bcrypt('123456789'),
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole('administrador');
        Log::info('Rol del usuario administrador: ' . $adminUser->getRoleNames());

        // Crear usuario comandante
        $comandanteUser = User::create([
            'name' => 'comandante',
            'email' => 'comandante@gmail.com',
            'password' => bcrypt('123456789'),
            'email_verified_at' => now(),
        ]);
        $comandanteUser->assignRole('comandante');
        Log::info('Rol del usuario comandante: ' . $comandanteUser->getRoleNames());

        // Crear usuario sargento
        $sargentoUser = User::create([
            'name' => 'sargento',
            'email' => 'sargento@gmail.com',
            'password' => bcrypt('123456789'),
            'email_verified_at' => now(),
        ]);
        $sargentoUser->assignRole('sargento');
        Log::info('Rol del usuario sargento: ' . $sargentoUser->getRoleNames());

        // Crear usuario policia
        $policiaUser = User::create([
            'name' => 'policia',
            'email' => 'policia@gmail.com',
            'password' => bcrypt('123456789'),
            'email_verified_at' => now(),
        ]);
        $policiaUser->assignRole('policia');
        Log::info('Rol del usuario policia: ' . $policiaUser->getRoleNames());
    }
}
