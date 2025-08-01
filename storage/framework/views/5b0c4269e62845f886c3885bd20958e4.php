

<?php $__env->startSection('title', 'Reportes de Registros'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Reportes de Registros</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <div class="mb-4">
            <form action="<?php echo e(route('registros.reportes')); ?>" method="GET" class="form-inline">
                <div class="form-group mx-sm-3 mb-2">
                    <label for="cargo" class="sr-only">Filtrar por Cargo</label>
                    <select name="cargo" id="cargo" class="form-control">
                        <option value="">Todos los cargos</option>
                        <?php $__currentLoopData = $registros->pluck('cargo')->unique(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cargo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cargo); ?>" <?php echo e(request('cargo') == $cargo ? 'selected' : ''); ?>>
                                <?php echo e($cargo); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Filtrar</button>
            </form>
        </div>
        <!-- Mapa de Google -->
        <div class="mt-4">
            <h4>Mapa de Ubicaciones</h4>
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>

        <!-- Tabla de Reportes -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombres Completos</th>
                    <th>CI</th>
                    <th>Cargo</th>
                    <th>Ubicación</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $registros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>

                        <td><?php echo e($registro->nombres); ?> <?php echo e($registro->apellido_paterno); ?> <?php echo e($registro->apellido_materno); ?></td>
                        <td><?php echo e($registro->ci); ?></td>
                        <td><?php echo e($registro->cargo); ?></td>                        <td>
                            <?php if($registro->longitud && $registro->latitud): ?>
                                <button class="btn btn-sm btn-primary ver-mapa" 
                                    data-lat="<?php echo e($registro->latitud); ?>" 
                                    data-lng="<?php echo e($registro->longitud); ?>"
                                    data-nombre="<?php echo e($registro->nombres); ?> <?php echo e($registro->apellido_paterno); ?>"
                                    data-cargo="<?php echo e($registro->cargo); ?>">
                                    Ver en Mapa
                                </button>
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($registro->created_at->format('d/m/Y H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>


        <!-- Resumen Estadístico -->
        <div class="mt-4">
            <h4>Resumen</h4>
            <div class="row">
                <div class="col-md-3">
                    <div class="info-box">
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Registros</span>
                            <span class="info-box-number"><?php echo e($registros->count()); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <div class="info-box-content">
                            <span class="info-box-text">Registros por Cargo</span>
                            <ul class="list-unstyled">
                                <?php $__currentLoopData = $registros->groupBy('cargo'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cargo => $grupo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($cargo); ?>: <?php echo e($grupo->count()); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>        <!-- Botones de Exportación -->
        <div class="mt-4">
            <div class="btn-group" role="group">
                <a href="<?php echo e(route('registros.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-registros')): ?>
                    <a href="<?php echo e(route('registros.backups')); ?>" class="btn btn-info">
                        <i class="fas fa-database"></i> Gestionar Backups
                    </a>
                    <form action="<?php echo e(route('registros.backup.generar')); ?>" method="POST" style="display: inline-block;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success" onclick="return confirm('¿Generar backup de todos los registros?')">
                            <i class="fas fa-download"></i> Generar Backup CSV
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <!-- Google Maps JavaScript API -->    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApZs1RsAdo4vF99FvtN8Fqf5vbn0vYWG4&callback=initMap&libraries=&v=weekly" async defer></script>
    <script>
        // Inicializar el mapa
        let map;
        let markers = [];
        
        function initMap() {
            // Centrar el mapa en Bolivia como punto inicial
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: -16.290154, lng: -63.588653 }, // Centro de Bolivia
                zoom: 6
            });

            // Obtener todas las ubicaciones y crear marcadores
            document.querySelectorAll('.ver-mapa').forEach(button => {
                const lat = parseFloat(button.dataset.lat);
                const lng = parseFloat(button.dataset.lng);
                const nombre = button.dataset.nombre;
                const cargo = button.dataset.cargo;

                if (lat && lng) {
                    const marker = new google.maps.Marker({
                        position: { lat, lng },
                        map: map,
                        title: nombre
                    });

                    const infowindow = new google.maps.InfoWindow({
                        content: `<div>
                            <h6>${nombre}</h6>
                            <p>Cargo: ${cargo}</p>
                            <p>Lat: ${lat}, Long: ${lng}</p>
                        </div>`
                    });

                    marker.addListener('click', () => {
                        infowindow.open(map, marker);
                    });

                    markers.push(marker);
                }
            });

            // Evento para centrar el mapa en un marcador específico
            document.querySelectorAll('.ver-mapa').forEach(button => {
                button.addEventListener('click', (e) => {
                    const lat = parseFloat(e.target.dataset.lat);
                    const lng = parseFloat(e.target.dataset.lng);
                    
                    map.setCenter({ lat, lng });
                    map.setZoom(15);
                });
            });
        }

        // Hacer la función disponible globalmente
        window.initMap = initMap;
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/registros/reportes.blade.php ENDPATH**/ ?>