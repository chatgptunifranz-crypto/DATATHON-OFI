

<?php $__env->startSection('title', 'Gestión de Backups - Registros'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>
        <i class="fas fa-database"></i> Gestión de Backups - Registros
        <small>Sistema automático y manual de respaldos CSV</small>
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle"></i> <?php echo e(session('error')); ?>

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Información del Sistema de Backup -->
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Información del Sistema de Backup
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-robot"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Backup Automático</span>
                                <span class="info-box-number">ACTIVO</span>
                                <span class="progress-description">
                                    Se ejecuta en cada operación CRUD
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-file-csv"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Formato</span>
                                <span class="info-box-number">CSV</span>
                                <span class="progress-description">
                                    Archivo de valores separados por comas
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-archive"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Retención</span>
                                <span class="info-box-number">10 archivos</span>
                                <span class="progress-description">
                                    Se mantienen los 10 más recientes
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Panel de Control -->
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cogs"></i> Panel de Control
                </h3>
            </div>
            <div class="card-body">
                <!-- Generar Backup Manual -->
                <form action="<?php echo e(route('registros.backup.generar')); ?>" method="POST" style="margin-bottom: 15px;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success btn-block" onclick="return confirm('¿Generar backup manual de todos los registros?')">
                        <i class="fas fa-plus-circle"></i> Generar Backup Manual
                    </button>
                </form>
                
                <!-- Volver a Registros -->
                <a href="<?php echo e(route('registros.index')); ?>" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left"></i> Volver a Registros
                </a>
                
                <!-- Ver Reportes -->
                <a href="<?php echo e(route('registros.reportes')); ?>" class="btn btn-info btn-block">
                    <i class="fas fa-chart-line"></i> Ver Reportes
                </a>
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i> Estadísticas
                </h3>
            </div>
            <div class="card-body">
                <p><strong>Total de Backups:</strong> <?php echo e(count($backups)); ?></p>
                <p><strong>Espacio Total:</strong> 
                    <?php echo e(number_format(array_sum(array_column($backups, 'size')) / 1024, 2)); ?> KB
                </p>
                <?php if(count($backups) > 0): ?>
                    <p><strong>Último Backup:</strong> 
                        <?php echo e($backups[0]['created_at']->format('d/m/Y H:i:s')); ?>

                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Lista de Backups -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list"></i> Lista de Backups Disponibles
                </h3>
            </div>
            <div class="card-body">
                <?php if(count($backups) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Archivo</th>
                                    <th>Fecha/Hora</th>
                                    <th>Tamaño</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $backup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-file-csv text-success"></i>
                                            <?php echo e($backup['filename']); ?>

                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                <?php echo e($backup['created_at']->format('d/m/Y')); ?>

                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                <?php echo e($backup['created_at']->format('H:i:s')); ?>

                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                <?php echo e(number_format($backup['size'] / 1024, 2)); ?> KB
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <!-- Descargar -->
                                                <a href="<?php echo e(route('registros.backup.descargar', $backup['filename'])); ?>" 
                                                   class="btn btn-success" 
                                                   title="Descargar">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                
                                                <!-- Eliminar -->
                                                <form action="<?php echo e(route('registros.backup.eliminar', $backup['filename'])); ?>" 
                                                      method="POST" 
                                                      style="display: inline-block">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-danger" 
                                                            title="Eliminar"
                                                            onclick="return confirm('¿Está seguro de eliminar este backup?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <i class="fas fa-inbox fa-3x text-muted"></i>
                        <h4 class="text-muted">No hay backups disponibles</h4>
                        <p class="text-muted">Genere el primer backup manual o espere a que se ejecute automáticamente.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-lightbulb"></i> Información Importante
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-robot"></i> Backup Automático</h5>
                        <ul>
                            <li>Se ejecuta automáticamente cuando se <strong>crea</strong> un registro</li>
                            <li>Se ejecuta automáticamente cuando se <strong>actualiza</strong> un registro</li>
                            <li>Se ejecuta automáticamente cuando se <strong>elimina</strong> un registro</li>
                            <li>No afecta el rendimiento del sistema (se ejecuta en segundo plano)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-shield-alt"></i> Seguridad y Mantenimiento</h5>
                        <ul>
                            <li>Los archivos se almacenan de forma segura en el servidor</li>
                            <li>Solo se mantienen los <strong>10 backups más recientes</strong></li>
                            <li>Los backups incluyen <strong>todos los campos</strong> de los registros</li>
                            <li>Formato estándar CSV compatible con Excel y otras herramientas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.info-box {
    margin-bottom: 15px;
}
.table td {
    vertical-align: middle;
}
.btn-group-sm > .btn, .btn-sm {
    margin-right: 2px;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/registros/backups.blade.php ENDPATH**/ ?>