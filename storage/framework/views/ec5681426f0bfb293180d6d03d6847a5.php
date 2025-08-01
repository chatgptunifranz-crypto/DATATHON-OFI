<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><?php echo e(__('Dashboard')); ?></div>
                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>
                    <?php echo e(__('You are logged in!')); ?>

                </div>
            </div>
            <!-- Panel de acceso rápido a Registros -->
            <div class="card">
                <div class="card-header">Gestión de Registros</div>
                <div class="card-body">
                    <a href="<?php echo e(route('registros.index')); ?>" class="btn btn-primary mb-2">Ver Registros</a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-registros')): ?>
                        <a href="<?php echo e(route('registros.create')); ?>" class="btn btn-success mb-2">Nuevo Registro</a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-reportes')): ?>
                        <a href="<?php echo e(route('registros.reportes')); ?>" class="btn btn-secondary mb-2">Ver Reportes</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/home.blade.php ENDPATH**/ ?>