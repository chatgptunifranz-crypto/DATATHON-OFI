

<?php $__env->startSection('title', 'Detalles de Aprobación'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="row">
        <div class="col-sm-6">
            <h1><i class="fas fa-eye"></i> Detalles de Aprobación #<?php echo e($aprobacion->id); ?></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('aprobaciones.index')); ?>">Aprobaciones</a></li>
                <li class="breadcrumb-item active">Detalles</li>
            </ol>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Información de la Aprobación -->
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-check-circle"></i> Información de Aprobación</h3>
            </div>
            <div class="card-body">
                <!-- Estado -->
                <div class="form-group">
                    <label>Estado Actual:</label>
                    <div>
                        <?php if($aprobacion->estado === 'pendiente'): ?>
                            <span class="badge badge-warning badge-lg">
                                <i class="fas fa-clock"></i> Pendiente
                            </span>
                        <?php else: ?>
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-check"></i> Aprobado
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Fecha de Creación -->
                <div class="form-group">
                    <label>Fecha de Creación:</label>
                    <div>
                        <span class="text-muted">
                            <i class="fas fa-calendar"></i> <?php echo e($aprobacion->created_at->format('d/m/Y H:i')); ?>

                        </span>
                    </div>
                </div>

                <!-- Fecha de Aprobación -->
                <?php if($aprobacion->fecha_aprobacion): ?>
                <div class="form-group">
                    <label>Fecha de Aprobación:</label>
                    <div>
                        <span class="text-muted">
                            <i class="fas fa-calendar-check"></i> <?php echo e($aprobacion->fecha_aprobacion->format('d/m/Y H:i')); ?>

                        </span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Usuario Creador -->
                <div class="form-group">
                    <label>Creado por:</label>
                    <div>
                        <?php if($aprobacion->usuarioCreador): ?>
                            <span class="text-primary">
                                <i class="fas fa-user"></i> <?php echo e($aprobacion->usuarioCreador->name); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-muted">Sistema</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Usuario Aprobador -->
                <?php if($aprobacion->usuarioAprobador): ?>
                <div class="form-group">
                    <label>Aprobado por:</label>
                    <div>
                        <span class="text-success">
                            <i class="fas fa-user-check"></i> <?php echo e($aprobacion->usuarioAprobador->name); ?>

                            <?php if($aprobacion->usuarioAprobador->hasRole('comandante')): ?>
                                <span class="badge badge-primary">Comandante</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Observaciones -->
                <?php if($aprobacion->observaciones): ?>
                <div class="form-group">
                    <label>Observaciones:</label>
                    <div class="p-2 bg-light border rounded">
                        <?php echo e($aprobacion->observaciones); ?>

                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <div class="btn-group">
                    <a href="<?php echo e(route('aprobaciones.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <?php if($aprobacion->estado === 'pendiente' || auth()->user()->hasRole('comandante')): ?>
                        <a href="<?php echo e(route('aprobaciones.edit', $aprobacion->id)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la Orden del Día -->
    <div class="col-md-8">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-alt"></i> Orden del Día</h3>
            </div>
            <div class="card-body">
                <h4><?php echo e($aprobacion->ordenDelDia->nombre); ?></h4>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong><i class="fas fa-calendar"></i> Fecha:</strong> 
                        <?php echo e($aprobacion->ordenDelDia->fecha->format('d/m/Y')); ?>

                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-user"></i> Creado por:</strong>
                        <?php echo e($aprobacion->ordenDelDia->user->name ?? 'Sistema'); ?>

                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-clock"></i> Creado:</strong>
                        <?php echo e($aprobacion->ordenDelDia->created_at->format('d/m/Y')); ?>

                    </div>
                </div>
                <hr>
                <h5><i class="fas fa-file-alt"></i> Contenido:</h5>
                <div class="content-preview border rounded p-3 bg-light">
                    <?php echo $aprobacion->ordenDelDia->contenido; ?>

                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo e(route('ordenes.show', $aprobacion->ordenDelDia->id)); ?>" class="btn btn-primary" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Ver Orden Completa
                </a>
            </div>
        </div>
    </div>
</div>

<?php if(auth()->user()->hasRole('comandante') && $aprobacion->estado === 'pendiente'): ?>
<div class="row mt-3">
    <div class="col-12">
        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle"></i> Acción Pendiente</h5>
            <p>Como Comandante, puede aprobar esta orden del día.</p>
            <form action="<?php echo e(route('aprobaciones.update', $aprobacion->id)); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="orden_del_dia_id" value="<?php echo e($aprobacion->orden_del_dia_id); ?>">
                <input type="hidden" name="estado" value="aprobado">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Aprobar Orden
                </button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.content-preview {
    max-height: 500px;
    overflow-y: auto;
}
.badge-lg {
    font-size: 100%;
    padding: 0.4em 0.6em;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/aprobaciones/show_fixed.blade.php ENDPATH**/ ?>