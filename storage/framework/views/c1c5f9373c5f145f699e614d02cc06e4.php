

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
                        <?php switch($aprobacion->estado):
                            case ('pendiente'): ?>
                                <span class="badge badge-warning badge-lg">
                                    <i class="fas fa-clock"></i> Pendiente
                                </span>
                                <?php break; ?>
                            <?php case ('aprobado'): ?>
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check"></i> Aprobado
                                </span>
                                <?php break; ?>
                            <?php case ('con_errores'): ?>
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-exclamation-triangle"></i> Con Errores
                                </span>
                                <?php break; ?>
                            <?php case ('rechazado'): ?>
                                <span class="badge badge-secondary badge-lg">
                                    <i class="fas fa-times"></i> Rechazado
                                </span>
                                <?php break; ?>
                        <?php endswitch; ?>
                    </div>
                </div>

                <!-- Usuario Creador -->
                <div class="form-group">
                    <label>Creado por:</label>
                    <div class="d-flex align-items-center">
                        <?php if($aprobacion->usuarioCreador): ?>
                            <?php if($aprobacion->usuarioCreador->foto): ?>
                                <img src="<?php echo e(asset($aprobacion->usuarioCreador->foto)); ?>" 
                                     class="img-circle elevation-2 mr-2" 
                                     style="width: 35px; height: 35px;">
                            <?php else: ?>
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2" 
                                     style="width: 35px; height: 35px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <strong><?php echo e($aprobacion->usuarioCreador->name); ?></strong><br>
                                <small class="text-muted"><?php echo e($aprobacion->created_at->format('d/m/Y H:i')); ?></small>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Sistema</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Usuario Aprobador -->
                <?php if($aprobacion->usuarioAprobador): ?>
                    <div class="form-group">
                        <label>Aprobado por:</label>
                        <div class="d-flex align-items-center">
                            <?php if($aprobacion->usuarioAprobador->foto): ?>
                                <img src="<?php echo e(asset($aprobacion->usuarioAprobador->foto)); ?>" 
                                     class="img-circle elevation-2 mr-2" 
                                     style="width: 35px; height: 35px;">
                            <?php else: ?>
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center mr-2" 
                                     style="width: 35px; height: 35px;">
                                    <i class="fas fa-user-check text-white"></i>
                                </div>
                            <?php endif; ?>                            <div>
                                <strong><?php echo e($aprobacion->usuarioAprobador->name); ?></strong>
                                <?php if($aprobacion->usuarioAprobador->hasRole('comandante')): ?>
                                    <span class="badge badge-primary">Comandante</span>
                                <?php endif; ?>
                                <br>
                                <small class="text-muted"><?php echo e($aprobacion->fecha_aprobacion->format('d/m/Y H:i')); ?></small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Fechas -->
                <div class="form-group">
                    <label>Fecha de Creación:</label>
                    <div><?php echo e($aprobacion->created_at->format('d/m/Y H:i:s')); ?></div>
                </div>

                <?php if($aprobacion->fecha_aprobacion): ?>
                    <div class="form-group">
                        <label>Fecha de Aprobación:</label>
                        <div><?php echo e($aprobacion->fecha_aprobacion->format('d/m/Y H:i:s')); ?></div>
                    </div>
                <?php endif; ?>

                <!-- Observaciones -->
                <?php if($aprobacion->observaciones): ?>
                    <div class="form-group">
                        <label>Observaciones:</label>
                        <div class="alert alert-info">
                            <?php echo e($aprobacion->observaciones); ?>

                        </div>
                    </div>
                <?php endif; ?>

                <!-- Errores Detectados -->
                <?php if($aprobacion->tieneErrores() && $aprobacion->errores_detectados): ?>
                    <div class="form-group">
                        <label>Errores Detectados:</label>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = json_decode($aprobacion->errores_detectados); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">                <div class="btn-group w-100">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-aprobaciones')): ?>
                        <?php if($aprobacion->estado !== 'aprobado' || auth()->user()->hasRole('comandante')): ?>
                            <a href="<?php echo e(route('aprobaciones.edit', $aprobacion->id)); ?>" 
                               class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        <?php endif; ?>
                        
                        <?php if(auth()->user()->hasRole('comandante') && $aprobacion->estado === 'pendiente'): ?>
                            <button type="button" class="btn btn-success" onclick="aprobarRapido()">
                                <i class="fas fa-check"></i> Aprobar
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('aprobaciones.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>        </div>        <!-- Acciones Rápidas -->
        <?php if(auth()->user()->hasRole('comandante') && $aprobacion->estado === 'pendiente'): ?>
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-success btn-block" onclick="aprobarRapido()">
                        <i class="fas fa-check"></i> Aprobar Inmediatamente
                    </button>
                    <button type="button" class="btn btn-danger btn-block" onclick="marcarConErrores()">
                        <i class="fas fa-exclamation-triangle"></i> Marcar con Errores
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Previsualización de la Orden del Día -->
    <div class="col-md-8">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-alt"></i> 
                    Previsualización: <?php echo e($aprobacion->ordenDelDia->nombre); ?>

                </h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('ordenes.show', $aprobacion->orden_del_dia_id)); ?>" 
                       class="btn btn-primary btn-sm" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Ver en Nueva Pestaña
                    </a>
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Información de la Orden -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Nombre:</strong> <?php echo e($aprobacion->ordenDelDia->nombre); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>Fecha:</strong> <?php echo e($aprobacion->ordenDelDia->fecha->format('d/m/Y')); ?>

                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Creado por:</strong> 
                        <?php if($aprobacion->ordenDelDia->user): ?>
                            <?php echo e($aprobacion->ordenDelDia->user->name); ?>

                        <?php else: ?>
                            Sistema
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha de Creación:</strong> 
                        <?php echo e($aprobacion->ordenDelDia->created_at->format('d/m/Y H:i')); ?>

                    </div>
                </div>

                <hr>

                <!-- Contenido de la Orden -->
                <div class="content-preview" style="max-height: 500px; overflow-y: auto; border: 1px solid #dee2e6; padding: 15px; border-radius: 5px; background-color: #f8f9fa;">
                    <?php echo $aprobacion->ordenDelDia->contenido; ?>

                </div>

                <!-- Información adicional -->
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Esta es una previsualización del contenido de la orden del día.
                        Para ver el documento completo y todas sus funcionalidades, 
                        <a href="<?php echo e(route('ordenes.show', $aprobacion->orden_del_dia_id)); ?>" target="_blank">abra en nueva pestaña</a>.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para marcar con errores -->
<div class="modal fade" id="erroresModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Marcar Orden con Errores
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="erroresForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Errores Detectados:</label>
                        <div id="errores-container">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="errores[]" placeholder="Descripción del error">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger" onclick="eliminarError(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="agregarError()">
                            <i class="fas fa-plus"></i> Agregar Error
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <label for="observaciones_errores">Observaciones Adicionales:</label>
                        <textarea class="form-control" id="observaciones_errores" name="observaciones" rows="3" 
                                  placeholder="Comentarios adicionales sobre los errores detectados..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save"></i> Marcar con Errores
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.badge-lg {
    font-size: 1.1em;
    padding: 8px 12px;
}

.content-preview {
    font-family: 'Times New Roman', serif;
    line-height: 1.6;
}

.content-preview h1, .content-preview h2, .content-preview h3 {
    color: #343a40;
    margin-top: 20px;
}

.content-preview p {
    margin-bottom: 10px;
    text-align: justify;
}

.user-info {
    display: flex;
    align-items: center;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
function aprobarRapido() {
    if (confirm('¿Está seguro de que desea aprobar esta orden del día?')) {
        $.ajax({
            url: `/aprobaciones/aprobar-rapido/<?php echo e($aprobacion->orden_del_dia_id); ?>`,
            type: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Aprobado!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo aprobar la orden del día'
                });
            }
        });
    }
}

function marcarConErrores() {
    $('#erroresModal').modal('show');
}

function agregarError() {
    const container = document.getElementById('errores-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" class="form-control" name="errores[]" placeholder="Descripción del error">
        <div class="input-group-append">
            <button type="button" class="btn btn-danger" onclick="eliminarError(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    container.appendChild(div);
}

function eliminarError(button) {
    const container = document.getElementById('errores-container');
    if (container.children.length > 1) {
        button.closest('.input-group').remove();
    }
}

$('#erroresForm').on('submit', function(e) {
    e.preventDefault();
    
    const errores = [];
    $('input[name="errores[]"]').each(function() {
        if ($(this).val().trim() !== '') {
            errores.push($(this).val().trim());
        }
    });
    
    if (errores.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe especificar al menos un error'
        });
        return;
    }
    
    $.ajax({
        url: `/aprobaciones/marcar-errores/<?php echo e($aprobacion->orden_del_dia_id); ?>`,
        type: 'POST',
        data: {
            _token: '<?php echo e(csrf_token()); ?>',
            errores: errores,
            observaciones: $('#observaciones_errores').val()
        },
        success: function(response) {
            if (response.success) {
                $('#erroresModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Errores Registrados!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron registrar los errores'
            });
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/aprobaciones/show.blade.php ENDPATH**/ ?>