

<?php $__env->startSection('title', 'Repartición de Personal'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between align-items-center">
        <h1>Repartición de Personal</h1>
        <div>
            <a href="<?php echo e(route('reparticiones.pdf')); ?>" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </a>
            <a href="<?php echo e(route('reparticiones.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Asignación
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fas fa-check"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestión de Asignaciones de Personal</h3>                    <div class="card-tools">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-reparticiones')): ?>
                            <a href="<?php echo e(route('reparticiones.create')); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nueva Asignación
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Filtros -->
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('reparticiones.filtrar')); ?>" class="row">
                        <div class="col-md-3">
                            <label for="zona">Zona:</label>
                            <select name="zona" id="zona" class="form-control">
                                <option value="">Todas las zonas</option>
                                <?php $__currentLoopData = $zonas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(request('zona') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($value); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha">Fecha:</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo e(request('fecha')); ?>">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                                <a href="<?php echo e(route('reparticiones.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Policía</th>
                                    <th>Zona</th>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Estado</th>
                                    <th>Observaciones</th>
                                    <th width="200px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $reparticiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reparticion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($reparticion->user->name); ?></strong><br>
                                            <small class="text-muted"><?php echo e($reparticion->user->email); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?php echo e($reparticion->zona); ?></span>
                                        </td>
                                        <td><?php echo e($reparticion->fecha_asignacion->format('d/m/Y')); ?></td>
                                        <td>
                                            <?php echo e($reparticion->horario_inicio->format('H:i')); ?> - 
                                            <?php echo e($reparticion->horario_fin->format('H:i')); ?>

                                        </td>
                                        <td>
                                            <?php if($reparticion->activo): ?>
                                                <span class="badge badge-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($reparticion->observaciones): ?>
                                                <span class="text-truncate" style="max-width: 150px; display: inline-block;" 
                                                      title="<?php echo e($reparticion->observaciones); ?>">
                                                    <?php echo e(Str::limit($reparticion->observaciones, 50)); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('reparticiones.show', $reparticion)); ?>" 
                                                   class="btn btn-info btn-sm" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-reparticiones')): ?>
                                                    <a href="<?php echo e(route('reparticiones.edit', $reparticion)); ?>" 
                                                       class="btn btn-warning btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="<?php echo e(route('reparticiones.toggle', $reparticion)); ?>" 
                                                          method="POST" style="display: inline-block;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" 
                                                                class="btn btn-<?php echo e($reparticion->activo ? 'secondary' : 'success'); ?> btn-sm"
                                                                title="<?php echo e($reparticion->activo ? 'Desactivar' : 'Activar'); ?>"
                                                                onclick="return confirm('¿Confirma el cambio de estado?')">
                                                            <i class="fas fa-<?php echo e($reparticion->activo ? 'times' : 'check'); ?>"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="<?php echo e(route('reparticiones.destroy', $reparticion)); ?>" 
                                                          method="POST" style="display: inline-block;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                title="Eliminar"
                                                                onclick="return confirm('¿Seguro que desea eliminar esta asignación?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No hay asignaciones registradas</h5>
                                                <p class="text-muted">Comience creando una nueva asignación de personal</p>                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-reparticiones')): ?>
                                                    <a href="<?php echo e(route('reparticiones.create')); ?>" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Nueva Asignación
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <?php if($reparticiones->hasPages()): ?>
                    <div class="card-footer">
                        <?php echo e($reparticiones->appends(request()->query())->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Resumen estadístico -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?php echo e($reparticiones->where('activo', true)->count()); ?></h3>
                    <p>Asignaciones Activas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?php echo e(collect($zonas)->count()); ?></h3>
                    <p>Zonas Disponibles</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?php echo e($reparticiones->where('fecha_asignacion', today())->count()); ?></h3>
                    <p>Asignaciones Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?php echo e($reparticiones->where('activo', false)->count()); ?></h3>
                    <p>Asignaciones Inactivas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .table th {
            border-top: none;
        }
        .empty-state {
            padding: 2rem;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        // Auto-submit del formulario de filtros cuando cambian los valores
        $('#zona, #fecha').on('change', function() {
            $(this).closest('form').submit();
        });
        
        // Confirmación para cambios de estado
        $('form[action*="toggle"]').on('submit', function(e) {
            if (!confirm('¿Confirma el cambio de estado de esta asignación?')) {
                e.preventDefault();
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/reparticiones/index.blade.php ENDPATH**/ ?>