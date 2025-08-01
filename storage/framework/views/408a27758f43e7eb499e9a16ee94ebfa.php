

<?php $__env->startSection('title', 'Aprobaciones'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="row">
        <div class="col-sm-6">
            <h1>Aprobaciones</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Inicio</a></li>
                <li class="breadcrumb-item active">Aprobaciones</li>
            </ol>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Lista de Aprobaciones</h3>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?php echo e(route('aprobaciones.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Aprobación
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('aprobaciones.index')); ?>" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" <?php echo e(request('estado') == 'pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                                <option value="aprobado" <?php echo e(request('estado') == 'aprobado' ? 'selected' : ''); ?>>Aprobado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="orden_del_dia_id">Orden del Día</label>
                            <select name="orden_del_dia_id" id="orden_del_dia_id" class="form-control">
                                <option value="">Todas las órdenes</option>                                <?php $__currentLoopData = $ordenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orden): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($orden->id); ?>" <?php echo e(request('orden_del_dia_id') == $orden->id ? 'selected' : ''); ?>>
                                        <?php echo e($orden->nombre); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_desde">Desde</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" 
                                   value="<?php echo e(request('fecha_desde')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_hasta">Hasta</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" 
                                   value="<?php echo e(request('fecha_hasta')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Tabla de aprobaciones -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Orden del Día</th>
                            <th>Estado</th>
                            <th>Creador</th>
                            <th>Aprobador</th>
                            <th>Fecha Creación</th>
                            <th>Fecha Aprobación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $aprobaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aprobacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>                            <tr>
                                <td><?php echo e($aprobacion->id); ?></td>
                                <td><?php echo e($aprobacion->ordenDelDia->nombre ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($aprobacion->estado == 'pendiente'): ?>
                                        <span class="badge badge-warning">Pendiente</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Aprobado</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($aprobacion->usuarioCreador->name ?? 'N/A'); ?></td>
                                <td><?php echo e($aprobacion->usuarioAprobador->name ?? 'N/A'); ?></td>
                                <td><?php echo e($aprobacion->created_at->format('d/m/Y H:i')); ?></td>
                                <td><?php echo e($aprobacion->fecha_aprobacion ? $aprobacion->fecha_aprobacion->format('d/m/Y H:i') : 'N/A'); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('aprobaciones.show', $aprobacion)); ?>" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('aprobaciones.edit', $aprobacion)); ?>" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <?php if($aprobacion->esPendiente()): ?>
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    onclick="aprobarAprobacion(<?php echo e($aprobacion->id); ?>)" title="Aprobar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="eliminarAprobacion(<?php echo e($aprobacion->id); ?>)" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center">No se encontraron aprobaciones</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                <?php echo e($aprobaciones->withQueryString()->links()); ?>

            </div>
        </div>
    </div>

    <!-- Modal para aprobar -->
    <div class="modal fade" id="aprobarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Aprobar Aprobación</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="aprobarForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="observaciones_aprobar">Observaciones (opcional)</label>
                            <textarea class="form-control" id="observaciones_aprobar" 
                                      name="observaciones" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Aprobar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar -->
    <div class="modal fade" id="eliminarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar Eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea eliminar esta aprobación?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form id="eliminarForm" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
function aprobarAprobacion(id) {
    $('#aprobarForm').attr('action', '/aprobaciones/' + id + '/aprobar');
    $('#aprobarModal').modal('show');
}

function eliminarAprobacion(id) {
    $('#eliminarForm').attr('action', '/aprobaciones/' + id);
    $('#eliminarModal').modal('show');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/aprobaciones/index.blade.php ENDPATH**/ ?>