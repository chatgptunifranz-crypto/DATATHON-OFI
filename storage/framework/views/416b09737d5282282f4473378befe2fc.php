

<?php $__env->startSection('title', 'Usuarios'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Usuarios</h1>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users')): ?>
        <a href="<?php echo e(route('usuarios.create')); ?>" class="btn btn-primary">Crear Usuario</a>
        <a href="<?php echo e(route('usuarios.inactivos')); ?>" class="btn btn-secondary">Ver Inactivos</a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($usuario->id); ?></td>
            <td><?php echo e($usuario->name); ?></td>
            <td><?php echo e($usuario->email); ?></td>            <td><?php echo e($usuario->roles->first()->name ?? 'Sin rol'); ?></td>
            <td>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users')): ?>
                    <a href="<?php echo e(route('usuarios.edit', $usuario)); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <form action="<?php echo e(route('usuarios.destroy', $usuario)); ?>" method="POST" style="display:inline-block">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                    </form>
                <?php else: ?>
                    <span class="text-muted">Sin permisos</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/usuarios/index.blade.php ENDPATH**/ ?>