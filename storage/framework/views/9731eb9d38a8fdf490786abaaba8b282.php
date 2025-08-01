

<?php $__env->startSection('title', 'Registros'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="d-flex justify-content-between align-items-center">
        <h1>Registros</h1>
        <div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-registros')): ?>
                <a href="<?php echo e(route('registros.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Registro
                </a>
                <a href="<?php echo e(route('registros.backups')); ?>" class="btn btn-info">
                    <i class="fas fa-database"></i> Gestionar Backups
                </a>
                <a href="<?php echo e(route('registros.reportes')); ?>" class="btn btn-success">
                    <i class="fas fa-chart-line"></i> Reportes
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nombre Completo</th>
                    <th>CI</th>
                    <th>Edad</th>
                    <th>Delito/Infracción</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $registros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($registro->id); ?></td>
                        <td>
                            <?php if($registro->foto): ?>
                                <img src="<?php echo e(asset($registro->foto)); ?>" alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted">Sin foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($registro->nombre_completo); ?></td>
                        <td><?php echo e($registro->ci); ?></td>
                        <td>
                            <?php if($registro->fecha_nacimiento): ?>
                                <?php echo e($registro->edad); ?> años
                            <?php else: ?>
                                <span class="text-muted">N/D</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-info"><?php echo e($registro->cargo); ?></span>
                        </td>
                        <td><?php echo e($registro->created_at->format('d/m/Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('registros.show', $registro)); ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-registros')): ?>
                                <a href="<?php echo e(route('registros.edit', $registro)); ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="<?php echo e(route('registros.destroy', $registro)); ?>" method="POST" style="display:inline-block">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center">No hay registros disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/registros/index.blade.php ENDPATH**/ ?>