

<?php $__env->startSection('title', 'Crear Registro'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/registros-fix.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Crear Nuevo Registro</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2>Crear Nuevo Registro</h2>
    <div class="card">
        <div class="card-body">
            <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo e(route('registros.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                <!-- DATOS PERSONALES -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Datos Personales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" name="nombres" id="nombres" class="form-control <?php $__errorArgs = ['nombres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('nombres')); ?>" required>
                                    <?php $__errorArgs = ['nombres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="apellido_paterno">Apellido Paterno <span class="text-danger">*</span></label>
                                    <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control <?php $__errorArgs = ['apellido_paterno'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('apellido_paterno')); ?>" required>
                                    <?php $__errorArgs = ['apellido_paterno'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="apellido_materno">Apellido Materno <span class="text-danger">*</span></label>
                                    <input type="text" name="apellido_materno" id="apellido_materno" class="form-control <?php $__errorArgs = ['apellido_materno'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('apellido_materno')); ?>" required>
                                    <?php $__errorArgs = ['apellido_materno'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ci">Carnet de Identidad <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="ci" id="ci" class="form-control <?php $__errorArgs = ['ci'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('ci')); ?>" required pattern="[0-9]+" title="Por favor ingrese solo números">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-info" id="buscarAntecedentes">
                                                <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                <span id="btnText">Buscar Antecedentes</span>
                                            </button>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['ci'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Ingrese el CI sin puntos ni guiones</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="expedido">Expedido en <span class="text-danger">*</span></label>
                                    <select name="expedido" id="expedido" class="form-control <?php $__errorArgs = ['expedido'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">Seleccione el departamento</option>
                                        <?php $__currentLoopData = \App\Models\Registro::getDepartamentosBolivia(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $departamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>" <?php echo e(old('expedido') == $key ? 'selected' : ''); ?>><?php echo e($departamento); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['expedido'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control <?php $__errorArgs = ['fecha_nacimiento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('fecha_nacimiento')); ?>" required>
                                    <?php $__errorArgs = ['fecha_nacimiento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado_civil">Estado Civil <span class="text-danger">*</span></label>
                                    <select name="estado_civil" id="estado_civil" class="form-control <?php $__errorArgs = ['estado_civil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">Seleccione el estado civil</option>
                                        <?php $__currentLoopData = \App\Models\Registro::getEstadosCiviles(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>" <?php echo e(old('estado_civil') == $key ? 'selected' : ''); ?>><?php echo e($estado); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['estado_civil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="profesion">Profesión</label>
                                    <input type="text" name="profesion" id="profesion" class="form-control <?php $__errorArgs = ['profesion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('profesion')); ?>" placeholder="Ej: Comerciante, Estudiante, etc.">
                                    <?php $__errorArgs = ['profesion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="foto">Fotografía</label>
                                    <input type="file" name="foto" id="foto" class="form-control-file <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
                                    <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Formatos: JPG, PNG, GIF. Máximo 2MB</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="domicilio">Domicilio <span class="text-danger">*</span></label>
                                    <textarea name="domicilio" id="domicilio" class="form-control <?php $__errorArgs = ['domicilio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2" required placeholder="Dirección completa del domicilio"><?php echo e(old('domicilio')); ?></textarea>
                                    <?php $__errorArgs = ['domicilio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DATOS DEL REGISTRO POLICIAL -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Registro Policial</h5>
                    </div>                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cargo">Tipo de Delito/Infracción <span class="text-danger">*</span></label>
                                    <input type="text" name="cargo" id="cargo" class="form-control <?php $__errorArgs = ['cargo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('cargo')); ?>" required placeholder="Ej: Robo, Hurto, Violencia doméstica, etc.">
                                    <?php $__errorArgs = ['cargo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Descripción del Incidente</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3" placeholder="Descripción detallada del incidente"><?php echo e(old('descripcion')); ?></textarea>
                                    <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="antecedentes">Antecedentes Encontrados</label>
                                    <textarea name="antecedentes" id="antecedentes" class="form-control <?php $__errorArgs = ['antecedentes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="6" readonly placeholder="Los antecedentes aparecerán aquí cuando busque por CI..."></textarea>
                                    <?php $__errorArgs = ['antecedentes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Este campo se llena automáticamente al buscar antecedentes por CI
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UBICACIÓN DEL INCIDENTE -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Ubicación del Incidente</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Seleccione la ubicación en el mapa</label>
                            <div id="map" style="height: 400px; width: 100%; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px;"></div>
                            <input type="hidden" name="latitud" id="latitud" value="<?php echo e(old('latitud')); ?>">
                            <input type="hidden" name="longitud" id="longitud" value="<?php echo e(old('longitud')); ?>">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Haga clic en el mapa para marcar la ubicación exacta donde ocurrió el incidente
                            </small>
                        </div>
                    </div>
                </div>                <!-- BOTONES DE ACCIÓN -->
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Guardar Registro
                    </button>
                    <a href="<?php echo e(route('registros.index')); ?>" class="btn btn-secondary btn-lg ml-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <!-- Google Maps JavaScript API -->    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApZs1RsAdo4vF99FvtN8Fqf5vbn0vYWG4&callback=initMap&libraries=&v=weekly" async defer></script>
    <script>
        let map;
        let marker;

        function initMap() {
            // Centrar el mapa en Bolivia como punto inicial
            const defaultLocation = { lat: -16.290154, lng: -63.588653 };
            
            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLocation,
                zoom: 6
            });

            // Si hay valores previos, usar esos
            const latInput = document.getElementById('latitud');
            const lngInput = document.getElementById('longitud');
            
            if (latInput.value && lngInput.value) {
                const position = {
                    lat: parseFloat(latInput.value),
                    lng: parseFloat(lngInput.value)
                };
                placeMarker(position);
                map.setCenter(position);
                map.setZoom(15);
            }

            // Añadir evento de clic al mapa
            map.addListener('click', function(e) {
                placeMarker(e.latLng);
            });
        }

        function placeMarker(location) {
            if (marker) {
                marker.setMap(null);
            }
            
            marker = new google.maps.Marker({
                position: location,
                map: map
            });

            // Actualizar campos ocultos
            document.getElementById('latitud').value = location.lat();
            document.getElementById('longitud').value = location.lng();
        }

        // Esperar a que el DOM esté completamente cargado
        $(document).ready(function() {
            // Inicializar el mapa cuando la API de Google esté lista
            window.initMap = initMap;
            
            // También inicializar cuando el DOM esté listo (por si la API ya está cargada)
            setTimeout(initMap, 100);// Funcionalidad de búsqueda de antecedentes
        document.getElementById('buscarAntecedentes').addEventListener('click', async function() {
            const ciInput = document.getElementById('ci');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btnText');
            const antecedentesTextarea = document.getElementById('antecedentes');
            const searchButton = document.getElementById('buscarAntecedentes');

            // Validar CI
            const ci = ciInput.value.trim();
            if (!ci) {
                alert('Por favor, ingrese un CI para buscar antecedentes');
                ciInput.focus();
                return;
            }

            if (!/^\d+$/.test(ci)) {
                alert('Por favor, ingrese solo números en el CI');
                ciInput.focus();
                return;
            }

            // Mostrar spinner y deshabilitar botón
            spinner.classList.remove('d-none');
            btnText.textContent = 'Buscando...';
            searchButton.disabled = true;

            try {
                const response = await fetch(`<?php echo e(route('registros.buscar-antecedentes')); ?>?ci=${ci}`);
                
                if (!response.ok) {
                    throw new Error('Error en la búsqueda');
                }

                const data = await response.json();

                if (data.length === 0) {
                    antecedentesTextarea.value = `No se encontraron antecedentes para el CI: ${ci}`;
                } else {
                    let texto = `ANTECEDENTES ENCONTRADOS PARA CI: ${ci}\n`;
                    texto += '='.repeat(50) + '\n\n';
                    
                    data.forEach((registro, index) => {
                        const fecha = new Date(registro.created_at).toLocaleDateString('es-ES', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                        
                        texto += `${index + 1}. REGISTRO DEL ${fecha}\n`;
                        texto += `   Nombre: ${registro.nombres} ${registro.apellido_paterno} ${registro.apellido_materno}\n`;
                        texto += `   Cargo: ${registro.cargo}\n`;
                        if (registro.descripcion) {
                            texto += `   Descripción: ${registro.descripcion}\n`;
                        }
                        if (registro.antecedentes) {
                            texto += `   Antecedentes: ${registro.antecedentes}\n`;
                        }
                        texto += '\n' + '-'.repeat(40) + '\n\n';
                    });
                    
                    antecedentesTextarea.value = texto;
                }

            } catch (error) {
                console.error('Error:', error);
                antecedentesTextarea.value = 'Ocurrió un error al buscar los antecedentes. Por favor intente nuevamente.';
            } finally {
                // Restaurar botón
                spinner.classList.add('d-none');
                btnText.textContent = 'Buscar Antecedentes';
                searchButton.disabled = false;
            }        });

        // Manejar entrada del CI
        document.getElementById('ci').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, ''); // Remover caracteres no numéricos
        });

        // Inicializar el mapa cuando se carga la página
        window.addEventListener('load', initMap);
    });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/registros/create.blade.php ENDPATH**/ ?>