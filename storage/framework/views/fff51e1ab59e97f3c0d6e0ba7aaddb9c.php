

<?php $__env->startSection('title', 'Órdenes del Día'); ?>

<?php $__env->startSection('content_header'); ?>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Órdenes del Día</h1>
            </div>
            
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <?php echo e(session('error')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <!-- Estadísticas de la parte superior de ordenes del dia -->
    <div class="row mb-3">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?php echo e($ordenes->count()); ?></h3>
                    <p>Total de Órdenes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?php echo e($ordenes->where('created_at', '>=', now()->startOfMonth())->count()); ?></h3>
                    <p>Este Mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?php echo e($ordenes->where('created_at', '>=', now()->startOfWeek())->count()); ?></h3>
                    <p>Esta Semana</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?php echo e($ordenes->where('created_at', '>=', now()->startOfDay())->count()); ?></h3>
                    <p>Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y acciones -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-search mr-2"></i>
                Filtros y Búsqueda
            </h3>
            <div class="card-tools">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-ordenes')): ?>
                    <a href="<?php echo e(route('ordenes.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Nueva Orden del Día
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <form id="filterForm" class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="searchName">Buscar por nombre:</label>
                        <div class="input-group">
                            <input type="text" id="searchName" class="form-control" placeholder="Nombre de la orden...">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filterMonth">Filtrar por mes:</label>
                        <select id="filterMonth" class="form-control">
                            <option value="">Todos los meses</option>
                            <option value="01">Enero</option>
                            <option value="02">Febrero</option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filterYear">Filtrar por año:</label>
                        <select id="filterYear" class="form-control">
                            <option value="">Todos los años</option>
                            <?php for($year = date('Y'); $year >= date('Y') - 5; $year--): ?>
                                <option value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-outline-secondary btn-block" onclick="clearFilters()">
                                <i class="fas fa-times mr-1"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de órdenes -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-2"></i>
                Listado de Órdenes del Día
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" onclick="exportTableToExcel()">
                    <i class="fas fa-file-excel"></i> Exportar
                </button>
                <button type="button" class="btn btn-tool" onclick="printTable()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="ordenesTable" class="table table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">N°</th>
                            <th width="40%">
                                <i class="fas fa-file-alt mr-1"></i>
                                Nombre
                                <i class="fas fa-sort ml-1 text-muted" style="cursor: pointer;" onclick="sortTable(1)"></i>
                            </th>
                            <th width="15%">
                                <i class="fas fa-calendar mr-1"></i>
                                Fecha
                                <i class="fas fa-sort ml-1 text-muted" style="cursor: pointer;" onclick="sortTable(2)"></i>
                            </th>
                            <th width="15%">
                                <i class="fas fa-user mr-1"></i>
                                Creado por
                            </th>
                            <th width="15%">
                                <i class="fas fa-clock mr-1"></i>
                                Última actualización
                            </th>
                            <th width="10%" class="text-center">
                                <i class="fas fa-cogs mr-1"></i>
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $ordenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $orden): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-nombre="<?php echo e(strtolower($orden->nombre)); ?>" 
                                data-fecha="<?php echo e($orden->fecha); ?>" 
                                data-mes="<?php echo e(\Carbon\Carbon::parse($orden->fecha)->format('m')); ?>"
                                data-ano="<?php echo e(\Carbon\Carbon::parse($orden->fecha)->format('Y')); ?>">
                                <td>
                                    <span class="badge badge-primary">#<?php echo e(str_pad($orden->id, 3, '0', STR_PAD_LEFT)); ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-alt text-primary mr-2"></i>
                                        <div>
                                            <strong><?php echo e(Str::limit($orden->nombre, 50)); ?></strong>
                                            <?php if(strlen($orden->nombre) > 50): ?>
                                                <small class="text-muted d-block" title="<?php echo e($orden->nombre); ?>">
                                                    <?php echo e($orden->nombre); ?>

                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo e(\Carbon\Carbon::parse($orden->fecha)->format('d/m/Y')); ?>

                                    </span>
                                    <small class="text-muted d-block">
                                        <?php echo e(\Carbon\Carbon::parse($orden->fecha)->locale('es')->isoFormat('dddd')); ?>

                                    </small>
                                </td>
                                <td>
                                    <div class="user-panel d-flex">
                                        <div class="image">
                                            <i class="fas fa-user-circle fa-lg text-secondary"></i>
                                        </div>
                                        <div class="info">
                                            <span class="d-block"><?php echo e($orden->user->name ?? 'Sistema'); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo e($orden->updated_at->diffForHumans()); ?>

                                        <br>
                                        <?php echo e($orden->updated_at->format('d/m/Y H:i')); ?>

                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo e(route('ordenes.show', $orden)); ?>" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-ordenes')): ?>
                                            <a href="<?php echo e(route('ordenes.edit', $orden)); ?>" 
                                               class="btn btn-warning btn-sm" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Eliminar"
                                                    onclick="confirmDelete(<?php echo e($orden->id); ?>, '<?php echo e(addslashes($orden->nombre)); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr id="noResultsRow">
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay órdenes del día registradas</h5>
                                        <p class="text-muted">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-ordenes')): ?>
                                                <a href="<?php echo e(route('ordenes.create')); ?>" class="btn btn-primary">
                                                    <i class="fas fa-plus mr-1"></i> Crear primera orden del día
                                                </a>
                                            <?php else: ?>
                                                No se encontraron documentos para mostrar.
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($ordenes->count() > 0): ?>
            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col-sm-6">
                        <p class="text-muted mb-0">
                            Mostrando <?php echo e($ordenes->count()); ?> de <?php echo e($ordenes->count()); ?> registros
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right">
                            <span class="badge badge-secondary">Total: <?php echo e($ordenes->count()); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para confirmación de eliminación -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-ordenes')): ?>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar esta Orden del Día?</p>
                <p><strong>Nombre:</strong> <span id="deleteOrderName"></span></p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i> Eliminar definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .small-box {
        border-radius: 0.5rem;
        transition: transform 0.2s;
    }

    .small-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
    }

    .table td {
        vertical-align: middle;
    }

    .user-panel .info {
        padding-left: 10px;
        line-height: 1.2;
    }

    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.2rem;
    }

    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
    }

    .badge {
        font-size: 0.75em;
    }

    .card-tools .btn-tool {
        color: #6c757d;
    }

    .card-tools .btn-tool:hover {
        color: #495057;
    }

    @media (max-width: 768px) {
        .small-box {
            margin-bottom: 1rem;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-group-sm > .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.8rem;
        }
    }

    .no-results {
        display: none;
    }

    .filtered-out {
        display: none !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- SheetJS para exportar a Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializar filtros
    initializeFilters();
    
    // Mostrar notificación si hay mensajes de sesión
    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Operación exitosa',
            text: '<?php echo e(session('success')); ?>',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    <?php endif; ?>
});

// Inicializar filtros
function initializeFilters() {
    // Búsqueda por nombre
    $('#searchName').on('keyup', function() {
        filterTable();
    });
    
    // Filtros de fecha
    $('#filterMonth, #filterYear').on('change', function() {
        filterTable();
    });
}

// Filtrar tabla
function filterTable() {
    const searchName = $('#searchName').val().toLowerCase();
    const filterMonth = $('#filterMonth').val();
    const filterYear = $('#filterYear').val();
    
    let visibleCount = 0;
    
    $('#ordenesTable tbody tr').each(function() {
        if ($(this).attr('id') === 'noResultsRow') return;
        
        const nombre = $(this).data('nombre') || '';
        const mes = $(this).data('mes') || '';
        const ano = $(this).data('ano') || '';
        
        let show = true;
        
        // Filtro por nombre
        if (searchName && !nombre.includes(searchName)) {
            show = false;
        }
        
        // Filtro por mes
        if (filterMonth && mes.toString().padStart(2, '0') !== filterMonth) {
            show = false;
        }
        
        // Filtro por año
        if (filterYear && ano.toString() !== filterYear) {
            show = false;
        }
        
        if (show) {
            $(this).removeClass('filtered-out');
            visibleCount++;
        } else {
            $(this).addClass('filtered-out');
        }
    });
    
    // Mostrar mensaje si no hay resultados
    if (visibleCount === 0 && $('#ordenesTable tbody tr').length > 1) {
        if ($('#noResultsFiltered').length === 0) {
            $('#ordenesTable tbody').append(`
                <tr id="noResultsFiltered">
                    <td colspan="6" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No se encontraron resultados</h5>
                            <p class="text-muted">Intente modificar los criterios de búsqueda</p>
                        </div>
                    </td>
                </tr>
            `);
        }
        $('#noResultsFiltered').show();
    } else {
        $('#noResultsFiltered').hide();
    }
    
    // Actualizar contador
    updateCounter(visibleCount);
}

// Limpiar filtros
function clearFilters() {
    $('#searchName').val('');
    $('#filterMonth').val('');
    $('#filterYear').val('');
    $('#ordenesTable tbody tr').removeClass('filtered-out');
    $('#noResultsFiltered').hide();
    updateCounter($('#ordenesTable tbody tr').not('#noResultsRow, #noResultsFiltered').length);
}

// Actualizar contador
function updateCounter(count) {
    $('.card-footer .text-muted').text(`Mostrando ${count} de <?php echo e($ordenes->count()); ?> registros`);
    $('.card-footer .badge-secondary').text(`Total: ${count}`);
}

// Ordenar tabla
let sortDirection = {};
function sortTable(columnIndex) {
    const table = document.getElementById('ordenesTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => 
        !row.id.includes('noResults')
    );
    
    const direction = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
    sortDirection[columnIndex] = direction;
    
    rows.sort((a, b) => {
        let aVal = a.cells[columnIndex].textContent.trim();
        let bVal = b.cells[columnIndex].textContent.trim();
        
        // Para fechas, convertir a formato comparable
        if (columnIndex === 2) {
            aVal = a.dataset.fecha;
            bVal = b.dataset.fecha;
        }
        
        if (direction === 'asc') {
            return aVal.localeCompare(bVal);
        } else {
            return bVal.localeCompare(aVal);
        }
    });
    
    // Reordenar filas
    rows.forEach(row => tbody.appendChild(row));
    
    // Actualizar iconos de ordenamiento
    document.querySelectorAll('.fas.fa-sort').forEach(icon => {
        icon.className = 'fas fa-sort ml-1 text-muted';
    });
    
    const currentIcon = document.querySelector(`thead tr th:nth-child(${columnIndex + 1}) .fas`);
    if (direction === 'asc') {
        currentIcon.className = 'fas fa-sort-up ml-1 text-primary';
    } else {
        currentIcon.className = 'fas fa-sort-down ml-1 text-primary';
    }
}

// Exportar a Excel
function exportTableToExcel() {
    const table = document.getElementById('ordenesTable');
    const wb = XLSX.utils.table_to_book(table, {sheet: "Órdenes del Día"});
    const fileName = `ordenes_del_dia_${new Date().toISOString().split('T')[0]}.xlsx`;
    XLSX.writeFile(wb, fileName);
    
    Swal.fire({
        icon: 'success',
        title: 'Exportación exitosa',
        text: 'La tabla se ha exportado a Excel correctamente',
        timer: 3000,
        showConfirmButton: false
    });
}

// Imprimir tabla
function printTable() {
    const printWindow = window.open('', '_blank');
    const tableHTML = document.getElementById('ordenesTable').outerHTML;
    
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Órdenes del Día - Listado</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { text-align: center; color: #333; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f8f9fa; font-weight: bold; }
                .btn-group { display: none; }
                .badge { background-color: #007bff; color: white; padding: 2px 6px; border-radius: 3px; }
            </style>
        </head>
        <body>
            <h1>Órdenes del Día - Listado</h1>
            <p>Generado el: ${new Date().toLocaleDateString('es-ES')}</p>
            ${tableHTML}
        </body>
        </html>
    `;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

// Confirmar eliminación
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-ordenes')): ?>
function confirmDelete(ordenId, ordenNombre) {
    $('#deleteOrderName').text(ordenNombre);
    $('#deleteForm').attr('action', `/ordenes/${ordenId}`);
    $('#deleteModal').modal('show');
}
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/ordenes/index.blade.php ENDPATH**/ ?>