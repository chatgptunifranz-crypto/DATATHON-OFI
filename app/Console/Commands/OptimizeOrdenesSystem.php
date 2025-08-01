<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrdenDelDia;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class OptimizeOrdenesSystem extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'ordenes:optimize 
                            {--clean : Limpiar cache y archivos temporales}
                            {--assign-users : Asignar usuarios a órdenes sin user_id}
                            {--stats : Mostrar estadísticas del sistema}';

    /**
     * The console command description.
     */
    protected $description = 'Optimizar y limpiar el sistema de órdenes del día';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando optimización del sistema de Órdenes del Día...');
        $this->newLine();

        if ($this->option('clean')) {
            $this->cleanSystem();
        }

        if ($this->option('assign-users')) {
            $this->assignUsersToOrders();
        }

        if ($this->option('stats')) {
            $this->showStatistics();
        }

        if (!$this->option('clean') && !$this->option('assign-users') && !$this->option('stats')) {
            $this->performFullOptimization();
        }

        $this->newLine();
        $this->info('✅ Optimización completada exitosamente!');
    }
    private function cleanSystem()
    {
        $this->info('🧹 Limpiando sistema...');
        
        // Limpiar cache
        $this->line('   - Limpiando cache...');
        Cache::flush();
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        // Optimizar autoloader
        $this->line('   - Optimizando autoloader...');
        Artisan::call('optimize');
        
        $this->info('   ✓ Sistema limpiado');
    }

    /**
     * Asignar usuarios a órdenes sin user_id
     */
    private function assignUsersToOrders()
    {
        $this->info('👥 Asignando usuarios a órdenes del día...');
        
        $ordenesVacias = OrdenDelDia::whereNull('user_id')->count();
        
        if ($ordenesVacias > 0) {
            $this->line("   - Encontradas {$ordenesVacias} órdenes sin usuario asignado");
            
            // Buscar el primer usuario admin o el primer usuario disponible
            $adminUser = User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })->first();
            
            $defaultUser = $adminUser ?? User::first();
            
            if ($defaultUser) {
                OrdenDelDia::whereNull('user_id')->update(['user_id' => $defaultUser->id]);
                $this->info("   ✓ Asignadas {$ordenesVacias} órdenes al usuario: {$defaultUser->name}");
            } else {
                $this->warn('   ⚠ No se encontraron usuarios en el sistema');
            }
        } else {
            $this->info('   ✓ Todas las órdenes ya tienen usuario asignado');
        }
    }

    /**
     * Mostrar estadísticas del sistema
     */
    private function showStatistics()
    {
        $this->info('📊 Estadísticas del sistema:');
        $this->newLine();

        $stats = OrdenDelDia::getEstadisticas();
        
        $this->table([
            'Métrica', 'Cantidad'
        ], [
            ['Total de órdenes', $stats['total']],
            ['Órdenes este mes', $stats['este_mes']],
            ['Órdenes esta semana', $stats['esta_semana']],
            ['Órdenes hoy', $stats['hoy']],
            ['Órdenes aprobadas', $stats['aprobadas'] ?? 0],
            ['Órdenes pendientes', $stats['pendientes'] ?? 0],
            ['Órdenes rechazadas', $stats['rechazadas'] ?? 0],
        ]);

        $this->newLine();

        // Estadísticas adicionales
        $usuariosActivos = User::whereHas('ordenes')->count();
        $ordenesRecientes = OrdenDelDia::where('created_at', '>=', now()->subDays(7))->count();
        $tamañoPromedio = DB::table('ordenes_del_dia')
            ->selectRaw('AVG(LENGTH(contenido)) as promedio')
            ->first()->promedio ?? 0;

        $this->info("👥 Usuarios con órdenes: {$usuariosActivos}");
        $this->info("📅 Órdenes últimos 7 días: {$ordenesRecientes}");
        $this->info("📏 Tamaño promedio contenido: " . number_format($tamañoPromedio, 0) . " caracteres");

        // Top usuarios por número de órdenes
        $this->newLine();
        $this->info('🏆 Top usuarios por número de órdenes:');
        
        $topUsers = DB::table('ordenes_del_dia')
            ->join('users', 'ordenes_del_dia.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(*) as total_ordenes'))
            ->whereNotNull('ordenes_del_dia.user_id')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_ordenes')
            ->limit(5)
            ->get();

        if ($topUsers->count() > 0) {
            foreach ($topUsers as $index => $user) {
                $position = $index + 1;
                $this->line("   {$position}. {$user->name}: {$user->total_ordenes} órdenes");
            }
        } else {
            $this->line('   No hay datos disponibles');
        }
    }

    /**
     * Optimización completa
     */
    private function performFullOptimization()
    {
        $this->cleanSystem();
        $this->assignUsersToOrders();
        $this->showStatistics();
        
        $this->newLine();
        $this->info('🔧 Optimizaciones adicionales:');
        
        // Verificar integridad de datos
        $this->line('   - Verificando integridad de datos...');
        $ordenesCorruptas = OrdenDelDia::whereNull('nombre')
                                     ->orWhereNull('fecha')
                                     ->orWhereNull('contenido')
                                     ->count();
        
        if ($ordenesCorruptas > 0) {
            $this->warn("   ⚠ Encontradas {$ordenesCorruptas} órdenes con datos incompletos");
        } else {
            $this->info('   ✓ Integridad de datos verificada');
        }
        
        // Cache de estadísticas
        $this->line('   - Generando cache de estadísticas...');
        Cache::remember('ordenes_stats', now()->addHours(1), function () {
            return OrdenDelDia::getEstadisticas();
        });
        $this->info('   ✓ Cache de estadísticas generado');
    }
}
