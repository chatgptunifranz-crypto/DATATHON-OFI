

<?php $__env->startSection('title', 'Nueva Aprobación'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="row">
        <div class="col-sm-6">
            <h1><i class="fas fa-plus-circle"></i> Nueva Aprobación</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('aprobaciones.index')); ?>">Aprobaciones</a></li>
                <li class="breadcrumb-item active">Nueva</li>
            </ol>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Formulario de Aprobación -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit"></i> Datos de la Aprobación</h3>
            </div>
            <form action="<?php echo e(route('aprobaciones.store')); ?>" method="POST" id="aprobacionForm">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <!-- Selección de Orden -->
                    <div class="form-group">
                        <label for="orden_del_dia_id">Orden del Día <span class="text-danger">*</span></label>
                        <select name="orden_del_dia_id" id="orden_del_dia_id" class="form-control <?php $__errorArgs = ['orden_del_dia_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">Seleccione una orden del día</option>
                            <?php $__currentLoopData = $ordenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orden): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($orden->id); ?>" <?php echo e(old('orden_del_dia_id') == $orden->id ? 'selected' : ''); ?>

                                        data-nombre="<?php echo e($orden->nombre); ?>"
                                        data-fecha="<?php echo e($orden->fecha->format('d/m/Y')); ?>"
                                        data-creador="<?php echo e($orden->user->name ?? 'Sistema'); ?>"
                                        data-contenido="<?php echo e(strip_tags($orden->contenido)); ?>">
                                    <?php echo e($orden->nombre); ?> - <?php echo e($orden->fecha->format('d/m/Y')); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['orden_del_dia_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Estado -->
                    <div class="form-group">
                        <label for="estado">Estado <span class="text-danger">*</span></label>
                        <select name="estado" id="estado" class="form-control <?php $__errorArgs = ['estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="pendiente" <?php echo e(old('estado') == 'pendiente' ? 'selected' : ''); ?>>
                                <i class="fas fa-clock"></i> Pendiente
                            </option>
                            <?php if(auth()->user()->hasRole('comandante')): ?>
                                <option value="aprobado" <?php echo e(old('estado') == 'aprobado' ? 'selected' : ''); ?>>
                                    <i class="fas fa-check"></i> Aprobado
                                </option>
                            <?php endif; ?>
                        </select>
                        <?php $__errorArgs = ['estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php if(!auth()->user()->hasRole('comandante')): ?>
                            <small class="text-info">
                                <i class="fas fa-info-circle"></i> 
                                Solo los comandantes pueden aprobar órdenes del día
                            </small>
                        <?php endif; ?>
                    </div>

                    <!-- Observaciones -->
                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  rows="4" placeholder="Comentarios adicionales sobre la aprobación..."><?php echo e(old('observaciones')); ?></textarea>
                        <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Aprobación
                    </button>
                    <a href="<?php echo e(route('aprobaciones.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Previsualización de la Orden -->
    <div class="col-md-6">
        <div class="card card-info" id="preview-card" style="display: none;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-eye"></i> Previsualización de la Orden
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="orden-info">
                    <div class="row">
                        <div class="col-12">
                            <h5 id="orden-nombre"></h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Fecha:</strong> <span id="orden-fecha"></span>
                        </div>
                        <div class="col-6">
                            <strong>Creador:</strong> <span id="orden-creador"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="content-preview" style="max-height: 300px; overflow-y: auto;">
                        <div id="orden-contenido"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary btn-sm" onclick="verOrdenCompleta()">
                    <i class="fas fa-external-link-alt"></i> Ver Orden Completa
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.content-preview {
    border: 1px solid #dee2e6;
    padding: 15px;
    border-radius: 5px;
    background-color: #f8f9fa;
    font-family: 'Times New Roman', serif;
    line-height: 1.6;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
let ordenSeleccionadaId = null;

$(document).ready(function() {
    // Previsualización de orden seleccionada
    $('#orden_del_dia_id').on('change', function() {
        const option = $(this).find(':selected');
        if (option.val()) {
            ordenSeleccionadaId = option.val();
            $('#orden-nombre').text(option.data('nombre'));
            $('#orden-fecha').text(option.data('fecha'));
            $('#orden-creador').text(option.data('creador'));
            $('#orden-contenido').text(option.data('contenido').substring(0, 500) + '...');
            $('#preview-card').show();
        } else {
            $('#preview-card').hide();
        }
    });

    // Trigger inicial si hay valor seleccionado
    if ($('#orden_del_dia_id').val()) {
        $('#orden_del_dia_id').trigger('change');
    }
});

function verOrdenCompleta() {
    if (ordenSeleccionadaId) {
        window.open(`/ordenes/${ordenSeleccionadaId}`, '_blank');
    }
}

// Validación del formulario básica
$('#aprobacionForm').on('submit', function(e) {
    if (!$('#orden_del_dia_id').val()) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe seleccionar una orden del día'
        });
        return false;
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/aprobaciones/create_fixed.blade.php ENDPATH**/ ?>