

<?php $__env->startSection('title', 'Firebase - Prueba de Conexión'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>
        <i class="fas fa-fire"></i>
        Firebase - Prueba de Conexión
    </h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-check-circle"></i>
                        Estado de Conexión Firebase
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-fire"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Estado Firebase</span>
                                    <span class="info-box-number" id="firebase-status">Verificando...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-project-diagram"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Project ID</span>
                                    <span class="info-box-number" id="project-id">integrador-6f90c</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Pruebas de Funcionalidad</h3>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary mr-2" onclick="testFirebaseConnection()">
                                        <i class="fas fa-plug"></i>
                                        Probar Conexión
                                    </button>
                                    <button type="button" class="btn btn-info mr-2" onclick="showFirebaseConfig()">
                                        <i class="fas fa-cog"></i>
                                        Ver Configuración
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">Log de Resultados</h3>
                                </div>
                                <div class="card-body">
                                    <div id="test-results" class="bg-dark text-light p-3" style="border-radius: 5px; min-height: 200px; font-family: monospace;">
                                        <div class="text-success">
                                            [INFO] Sistema Firebase cargado correctamente<br>
                                            [INFO] Esperando pruebas de conexión...<br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .info-box-number {
        font-size: 16px !important;
    }
    #test-results {
        max-height: 300px;
        overflow-y: auto;
    }
    .log-success { color: #28a745; }
    .log-error { color: #dc3545; }
    .log-warning { color: #ffc107; }
    .log-info { color: #17a2b8; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si Firebase está disponible
    setTimeout(() => {
        if (typeof window.Firebase !== 'undefined') {
            document.getElementById('firebase-status').textContent = 'Conectado ✓';
            addLog('Firebase está disponible globalmente', 'success');
            addLog('App inicializada: ' + window.Firebase.app.name, 'info');
        } else {
            document.getElementById('firebase-status').textContent = 'Error ✗';
            document.querySelector('.info-box').classList.remove('bg-success');
            document.querySelector('.info-box').classList.add('bg-danger');
            addLog('Error: Firebase no está disponible', 'error');
        }
    }, 1000);
});

function addLog(message, type = 'info') {
    const resultsDiv = document.getElementById('test-results');
    const timestamp = new Date().toLocaleTimeString();
    const logClass = 'log-' + type;
    const logEntry = `<div class="${logClass}">[${timestamp}] ${message}</div>`;
    resultsDiv.innerHTML += logEntry;
    resultsDiv.scrollTop = resultsDiv.scrollHeight;
}

function testFirebaseConnection() {
    addLog('Iniciando prueba de conexión...', 'info');
    
    try {
        if (window.Firebase && window.Firebase.app) {
            addLog('✓ Firebase App: OK', 'success');
            addLog('✓ Project ID: ' + window.Firebase.app.options.projectId, 'success');
            addLog('✓ Auth Domain: ' + window.Firebase.app.options.authDomain, 'success');
            
            // Llamar al endpoint del backend
            fetch('/firebase/test-connection')
                .then(response => response.json())
                .then(data => {
                    addLog('✓ Backend response: ' + data.message, 'success');
                })
                .catch(error => {
                    addLog('✗ Backend error: ' + error.message, 'error');
                });
                
        } else {
            addLog('✗ Firebase no está inicializado', 'error');
        }
    } catch (error) {
        addLog('✗ Error en la prueba: ' + error.message, 'error');
    }
}

function testFirestore() {
    addLog('Iniciando prueba de Firestore...', 'info');
    
    try {
        if (window.Firebase && window.Firebase.db) {
            addLog('✓ Firestore disponible', 'success');
            addLog('✓ Database instance: OK', 'success');
            
            // Importar funciones de Firestore dinámicamente
            import('firebase/firestore').then(({ collection, addDoc, getDocs }) => {
                // Intentar escribir un documento de prueba
                const testCollection = collection(window.Firebase.db, 'test_connection');
                
                addDoc(testCollection, {
                    message: 'Prueba de conexión desde Laravel',
                    timestamp: new Date(),
                    user: 'Sistema'
                }).then((docRef) => {
                    addLog('✓ Documento de prueba creado: ' + docRef.id, 'success');
                    
                    // Leer documentos de la colección
                    getDocs(testCollection).then((querySnapshot) => {
                        addLog('✓ Documentos leídos: ' + querySnapshot.size, 'success');
                        addLog('✓ Firestore funcionando correctamente!', 'success');
                    });
                }).catch((error) => {
                    addLog('✗ Error escribiendo en Firestore: ' + error.message, 'error');
                });
                
            }).catch((error) => {
                addLog('✗ Error importando Firestore: ' + error.message, 'error');
            });
            
        } else {
            addLog('✗ Firestore no está disponible', 'error');
        }
    } catch (error) {
        addLog('✗ Error en Firestore: ' + error.message, 'error');
    }
}

function testAnalytics() {
    addLog('Iniciando prueba de Analytics...', 'info');
    
    try {
        if (window.Firebase && window.Firebase.analytics) {
            addLog('✓ Analytics disponible', 'success');
            addLog('✓ Analytics instance: OK', 'success');
            
            // Importar funciones de Analytics dinámicamente
            import('firebase/analytics').then(({ logEvent }) => {
                // Enviar un evento de prueba
                logEvent(window.Firebase.analytics, 'test_connection', {
                    source: 'laravel_admin',
                    page: 'firebase_test',
                    timestamp: new Date().toISOString()
                });
                
                addLog('✓ Evento de prueba enviado a Analytics', 'success');
                addLog('✓ Analytics funcionando correctamente!', 'success');
                
            }).catch((error) => {
                addLog('✗ Error con Analytics: ' + error.message, 'error');
            });
            
        } else {
            addLog('✗ Analytics no está disponible', 'error');
        }    } catch (error) {
        addLog('✗ Error en Analytics: ' + error.message, 'error');
    }
}

function showFirebaseConfig() {
    addLog('Mostrando configuración de Firebase...', 'info');
    
    try {
        if (window.Firebase && window.Firebase.app) {
            const config = window.Firebase.app.options;
            addLog('=== CONFIGURACIÓN FIREBASE ===', 'info');
            addLog('Project ID: ' + config.projectId, 'info');
            addLog('Auth Domain: ' + config.authDomain, 'info');
            addLog('Database URL: ' + config.databaseURL, 'info');
            addLog('Storage Bucket: ' + config.storageBucket, 'info');
            addLog('API Key: ' + config.apiKey.substring(0, 10) + '...', 'info');
            addLog('App ID: ' + config.appId, 'info');
            addLog('Measurement ID: ' + config.measurementId, 'info');
            addLog('===============================', 'info');
        } else {
            addLog('✗ No se puede acceder a la configuración', 'error');
        }
    } catch (error) {
        addLog('✗ Error mostrando configuración: ' + error.message, 'error');
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\integrador\resources\views/firebase/index.blade.php ENDPATH**/ ?>