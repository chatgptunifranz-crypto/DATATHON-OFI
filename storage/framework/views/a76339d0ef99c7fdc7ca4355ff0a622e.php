

<?php $__env->startSection('title', 'Antecedentes'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="row">
        <div class="col-sm-6">
            <h1>Gestión de Antecedentes</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Inicio</a></li>
                <li class="breadcrumb-item active">Antecedentes</li>
            </ol>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">                <div class="card-header">
                    <h3 class="card-title">Lista de Antecedentes</h3>
                    <div class="card-tools">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-antecedentes')): ?>
                            <a href="<?php echo e(route('antecedentes.create')); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nuevo Antecedente
                            </a>
                        <?php endif; ?>
                        <span class="badge badge-info ml-2"><?php echo e($registros->total()); ?> registros</span>
                    </div>
                </div>
                
                <div class="card-body">                    <!-- Formulario de búsqueda -->
                    <form method="GET" action="<?php echo e(route('antecedentes.index')); ?>" class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" name="ci" class="form-control" placeholder="CI" value="<?php echo e(request('ci')); ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="nombres" class="form-control" placeholder="Nombres" value="<?php echo e(request('nombres')); ?>">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="apellido_paterno" class="form-control" placeholder="Apellido Paterno" value="<?php echo e(request('apellido_paterno')); ?>">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="apellido_materno" class="form-control" placeholder="Apellido Materno" value="<?php echo e(request('apellido_materno')); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="<?php echo e(route('antecedentes.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>

                    <?php if($registros->count() > 0): ?>                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Información:</strong> Esta sección muestra todos los registros del sistema. 
                            Puede generar PDFs de antecedentes para cualquier registro.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th>CI</th>
                                        <th>Nombre Completo</th>
                                        <th>Antecedentes</th>
                                        <th>Fecha de Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $registros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="registros[]" value="<?php echo e($registro->id); ?>" class="registro-checkbox">
                                            </td>
                                            <td><?php echo e($registro->ci); ?></td>                            <td><?php echo e($registro->nombres); ?> <?php echo e($registro->apellido_paterno); ?> <?php echo e($registro->apellido_materno); ?></td>
                            <td>
                                <?php if($registro->antecedentes): ?>
                                    <?php echo e(Str::limit($registro->antecedentes, 50)); ?>

                                <?php else: ?>
                                    <span class="text-muted">Sin antecedentes</span>
                                <?php endif; ?>
                            </td>
                                            <td><?php echo e($registro->created_at->format('d/m/Y H:i')); ?></td>                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('antecedentes.show', $registro->id)); ?>" class="btn btn-info btn-sm" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-antecedentes')): ?>
                                                        <a href="<?php echo e(route('antecedentes.edit', $registro->id)); ?>" class="btn btn-warning btn-sm" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="<?php echo e(route('antecedentes.pdf', $registro->id)); ?>" class="btn btn-danger btn-sm" title="Generar PDF">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-antecedentes')): ?>
                                                        <form action="<?php echo e(route('antecedentes.destroy', $registro->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este antecedente?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-secondary btn-sm" title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Botón para generar PDF múltiple -->
                        <div class="mt-3">
                            <button type="button" class="btn btn-success" id="btn-pdf-multiple" disabled>
                                <i class="fas fa-file-pdf"></i> Generar PDF de Seleccionados
                            </button>
                        </div>
                        
                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-3">
                            <?php echo e($registros->appends(request()->query())->links()); ?>

                        </div>                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No se encontraron registros.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(document).ready(function() {
        // Seleccionar/deseleccionar todos
        $('#select-all').change(function() {
            $('.registro-checkbox').prop('checked', this.checked);
            togglePdfButton();
        });
        
        // Cambio en checkboxes individuales
        $('.registro-checkbox').change(function() {
            togglePdfButton();
        });
        
        // Función para habilitar/deshabilitar botón PDF múltiple
        function togglePdfButton() {
            const checkedBoxes = $('.registro-checkbox:checked').length;
            $('#btn-pdf-multiple').prop('disabled', checkedBoxes === 0);
        }
        
        // Generar PDF múltiple
        $('#btn-pdf-multiple').click(function() {
            const selectedIds = $('.registro-checkbox:checked').map(function() {
                return this.value;
            }).get();
            
            if (selectedIds.length > 0) {
                const form = $('<form>', {
                    'method': 'POST',
                    'action': '<?php echo e(route("antecedentes.pdf-multiple")); ?>'
                });
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '<?php echo e(csrf_token()); ?>'
                }));
                
                selectedIds.forEach(function(id) {
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': 'registros[]',
                        'value': id
                    }));
                });
                
                $('body').append(form);
                form.submit();
                form.remove();
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/antecedentes/index.blade.php ENDPATH**/ ?>