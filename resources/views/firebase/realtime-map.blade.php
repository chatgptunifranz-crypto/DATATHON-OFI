@extends('adminlte::page')

@section('title', 'Firebase Realtime Database - Mapa de Ubicaciones')

@section('content_header')
    <h1>
        <i class="fas fa-map-marker-alt text-primary"></i>
        Firebase Realtime Database - Mapa de Ubicaciones
    </h1>
@stop

@section('content')
<div class="row">
    <!-- Panel de Control -->
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-database"></i> Control de Datos en Tiempo Real
                </h3>
            </div>
            <div class="card-body">
                <!-- Estado de Conexión -->
                <div class="info-box bg-success mb-3">
                    <span class="info-box-icon">
                        <i class="fas fa-wifi"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Estado Realtime DB</span>
                        <span class="info-box-number" id="realtime-status">Conectando...</span>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="row">
                    <div class="col-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon">
                                <i class="fas fa-map-pin"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Ubicaciones</span>
                                <span class="info-box-number" id="total-locations">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon">
                                <i class="fas fa-clock"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Última Act.</span>
                                <span class="info-box-number" id="last-update">--</span>
                            </div>
                        </div>
                    </div>             
                
            </div>
          <!-- Lista de Ubicaciones -->
                <div class="mt-4">
                    <h5><i class="fas fa-list"></i> Ubicaciones Activas</h5>
                    <div id="locations-list" class="list-group" style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem;">
                        <!-- Las ubicaciones se cargarán dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapa -->
    <div class="col-md-8">
        <div class="card card-success">
            <div class="card-header">                <h3 class="card-title">
                    <i class="fas fa-globe"></i> Mapa en Tiempo Real
                    <small id="map-mode-indicator" class="badge badge-success ml-2">Última Ubicación</small>
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" id="toggle-mode-btn" onclick="toggleMapMode()" title="Alternar entre última ubicación y todas las ubicaciones">
                        <i class="fas fa-history"></i>
                    </button>
                    <button type="button" class="btn btn-tool" onclick="centerMapToBolivia()">
                        <i class="fas fa-home"></i>
                    </button>
                    <button type="button" class="btn btn-tool" onclick="toggleFullscreen()">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="realtime-map" style="height: 600px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Log de Actividad -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-terminal"></i> Log de Actividad en Tiempo Real
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" onclick="clearLog()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="activity-log" style="height: 200px; overflow-y: auto; background: #1e1e1e; color: #00ff00; font-family: 'Courier New', monospace; padding: 10px;">
                    <!-- Los logs se mostrarán aquí -->
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.log-success { color: #28a745 !important; }
.log-error { color: #dc3545 !important; }
.log-warning { color: #ffc107 !important; }
.log-info { color: #17a2b8 !important; }

#activity-log {
    border: 1px solid #ddd;
    border-radius: 4px;
}

.location-item {
    transition: all 0.3s ease;
}

.location-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.marker-info {
    max-width: 300px;
}

#realtime-map {
    border-radius: 0 0 0.375rem 0.375rem;
}

.fullscreen-map {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    background: white;
}

/* Estilos para la lista de ubicaciones con scroll */
#locations-list {
    scrollbar-width: thin;
    scrollbar-color: #007bff #f1f1f1;
}

#locations-list::-webkit-scrollbar {
    width: 8px;
}

#locations-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#locations-list::-webkit-scrollbar-thumb {
    background: #007bff;
    border-radius: 4px;
}

#locations-list::-webkit-scrollbar-thumb:hover {
    background: #0056b3;
}

/* Mejorar el aspecto cuando la lista está vacía */
.locations-empty {
    text-align: center;
    padding: 20px;
    color: #6c757d;
    font-style: italic;
}

/* Estilos para el indicador de modo */
#map-mode-indicator {
    font-size: 0.75rem;
    vertical-align: middle;
}

/* Efecto hover para el botón de alternar modo */
#toggle-mode-btn:hover {
    background-color: rgba(0, 123, 255, 0.1);
    border-radius: 0.375rem;
}

/* Destacar la última ubicación en la lista */
.location-item .badge-success {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>
@stop

@section('js')
<!-- Cargar Firebase con Vite -->
@vite(['resources/js/app.js'])

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApZs1RsAdo4vF99FvtN8Fqf5vbn0vYWG4&callback=initRealtimeMap&libraries=&v=weekly" async defer></script>

<script>
// Variables globales
let map;
let markers = [];
let realtimeListener = null;
let locationCount = 0;
let showOnlyLastLocation = true; // Por defecto mostrar solo la última ubicación
let allLocationsData = null; // Almacenar todos los datos

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        if (typeof window.Firebase !== 'undefined' && window.Firebase.realtimeDb) {
            addLog('Firebase Realtime Database disponible', 'success');
            document.getElementById('realtime-status').textContent = 'Disponible ✓';
        } else {
            addLog('Error: Firebase Realtime Database no disponible', 'error');
            document.getElementById('realtime-status').textContent = 'Error ✗';
            document.querySelector('.info-box.bg-success').classList.remove('bg-success');
            document.querySelector('.info-box.bg-success').classList.add('bg-danger');
        }
    }, 1000);
});

// Inicializar el mapa
function initRealtimeMap() {
    addLog('Inicializando Google Maps...', 'info');
    
    const laPazCenter = { lat: -16.5000, lng: -68.1193 };
    
    map = new google.maps.Map(document.getElementById('realtime-map'), {
        center: laPazCenter,
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.HYBRID,
        streetViewControl: false,
        fullscreenControl: false,
        zoomControl: true,
        mapTypeControl: true
    });

    addLog('Mapa inicializado correctamente', 'success');
    
    // Auto-conectar al Realtime Database
    setTimeout(connectToRealtimeDB, 1000);
}

// Conectar al Realtime Database
async function connectToRealtimeDB() {
    addLog('Conectando a Firebase Realtime Database...', 'info');
    
    if (!window.Firebase || !window.Firebase.realtimeDb) {
        addLog('Error: Firebase no está disponible', 'error');
        return;
    }

    try {
        // Importar funciones de Firebase Realtime Database
        const { ref, onValue, off } = await import('https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js');
        
        const locationsRef = ref(window.Firebase.realtimeDb, 'ubicaciones');
        
        // Detener listener anterior si existe
        if (realtimeListener) {
            off(locationsRef, 'value', realtimeListener);
        }
          // Crear nuevo listener
        realtimeListener = onValue(locationsRef, (snapshot) => {
            const data = snapshot.val();
            addLog('Datos recibidos del Realtime Database', 'success');
            
            // Almacenar todos los datos
            allLocationsData = data;
            
            // Actualizar mapa según el modo actual
            updateMapBasedOnMode();
            updateLocationsList(data);
            updateStats(data);
        }, (error) => {
            addLog('Error al escuchar datos: ' + error.message, 'error');
        });
        
        addLog('Listener del Realtime Database activado', 'success');
        document.getElementById('realtime-status').textContent = 'Conectado ✓';
        
    } catch (error) {
        addLog('Error conectando: ' + error.message, 'error');
    }
}

// Función para alternar entre modos
function toggleMapMode() {
    showOnlyLastLocation = !showOnlyLastLocation;
    updateMapBasedOnMode();
    
    // Actualizar indicador visual
    const indicator = document.getElementById('map-mode-indicator');
    const toggleBtn = document.getElementById('toggle-mode-btn');
    
    if (showOnlyLastLocation) {
        indicator.textContent = 'Última Ubicación';
        indicator.className = 'badge badge-success ml-2';
        toggleBtn.innerHTML = '<i class="fas fa-history"></i>';
        toggleBtn.title = 'Mostrar todas las ubicaciones';
        addLog('Modo cambiado: Mostrando solo la última ubicación', 'info');
    } else {
        indicator.textContent = 'Todas las Ubicaciones';
        indicator.className = 'badge badge-primary ml-2';
        toggleBtn.innerHTML = '<i class="fas fa-map-pin"></i>';
        toggleBtn.title = 'Mostrar solo la última ubicación';
        addLog('Modo cambiado: Mostrando todas las ubicaciones', 'info');
    }
}

// Función para actualizar el mapa según el modo actual
function updateMapBasedOnMode() {
    if (showOnlyLastLocation) {
        const lastLocation = getLastLocation(allLocationsData);
        updateMap(lastLocation);
    } else {
        updateMap(allLocationsData);
    }
}

// Función para obtener la última ubicación basada en fecha
function getLastLocation(locations) {
    if (!locations) return null;
    
    let lastKey = null;
    let lastDate = null;
    
    Object.keys(locations).forEach(key => {
        const location = locations[key];
        const locationDate = new Date(location.fecha_registrada || 0);
        
        if (!lastDate || locationDate > lastDate) {
            lastDate = locationDate;
            lastKey = key;
        }
    });
    
    if (lastKey) {
        return { [lastKey]: locations[lastKey] };
    }
    
    return null;
}

// Actualizar mapa con las ubicaciones
function updateMap(locations) {
    // Limpiar marcadores existentes
    markers.forEach(marker => marker.setMap(null));
    markers = [];
    
    if (!locations) {
        addLog('No hay ubicaciones para mostrar', 'warning');
        return;
    }
    
    let count = 0;
    let lastMarker = null;
    
    Object.keys(locations).forEach(key => {
        const location = locations[key];
        
        if (location.lat && location.lng) {
            // Determinar el icono según el modo
            let iconUrl, iconSize;
            if (showOnlyLastLocation) {
                // Icono más grande y llamativo para la última ubicación
                iconUrl = 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png';
                iconSize = new google.maps.Size(40, 40);
            } else {
                // Icono normal para todas las ubicaciones
                iconUrl = 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
                iconSize = new google.maps.Size(32, 32);
            }
            
            const marker = new google.maps.Marker({
                position: { 
                    lat: parseFloat(location.lat), 
                    lng: parseFloat(location.lng) 
                },
                map: map,
                title: location.nombre || 'Ubicación sin nombre',
                icon: {
                    url: iconUrl,
                    scaledSize: iconSize
                }
            });
            
            // Crear ventana de información
            const infoWindow = new google.maps.InfoWindow({
                content: createMarkerContent(location, key)
            });
            
            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
            
            markers.push(marker);
            lastMarker = marker;
            count++;
        }
    });
    
    // Si estamos en modo "última ubicación" y hay un marcador, centrar el mapa en él
    if (showOnlyLastLocation && lastMarker && count === 1) {
        map.setCenter(lastMarker.getPosition());
        map.setZoom(15);
        addLog('Mapa centrado en la última ubicación', 'info');
    }
    
    addLog(`${count} marcador(es) actualizados en el mapa`, 'info');
}

// Crear contenido para el marcador
function createMarkerContent(location, key) {
    const fecha = location.fecha_registrada || 'No especificada';
    return `
        <div class="marker-info">
            <h6><i class="fas fa-map-pin text-danger"></i> ${location.nombre || 'Sin nombre'}</h6>
            <p><strong>ID:</strong> ${key}</p>
            <p><strong>Coordenadas:</strong><br>
               Lat: ${location.lat}<br>
               Lng: ${location.lng}</p>
            <p><strong>Fecha:</strong> ${fecha}</p>
            ${location.descripcion ? `<p><strong>Descripción:</strong> ${location.descripcion}</p>` : ''}
            <div class="text-center mt-2">
                <button class="btn btn-sm btn-primary" onclick="centerMapToLocation(${location.lat}, ${location.lng})">
                    <i class="fas fa-crosshairs"></i> Centrar
                </button>
                <button class="btn btn-sm btn-danger" onclick="removeLocation('${key}')">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    `;
}

// Actualizar lista de ubicaciones
function updateLocationsList(locations) {
    const listContainer = document.getElementById('locations-list');
    listContainer.innerHTML = '';
    
    if (!locations) {
        listContainer.innerHTML = '<div class="list-group-item text-muted locations-empty"><i class="fas fa-map-pin"></i><br>No hay ubicaciones</div>';
        return;
    }
    
    // Mostrar las ubicaciones según el modo actual
    let locationsToShow;
    if (showOnlyLastLocation) {
        locationsToShow = getLastLocation(locations);
    } else {
        locationsToShow = locations;
    }
    
    if (!locationsToShow) {
        listContainer.innerHTML = '<div class="list-group-item text-muted locations-empty"><i class="fas fa-map-pin"></i><br>No hay ubicaciones</div>';
        return;
    }
    
    Object.keys(locationsToShow).forEach(key => {
        const location = locationsToShow[key];
        const listItem = document.createElement('div');
        listItem.className = 'list-group-item location-item';
        
        // Agregar distintivo si es la última ubicación en modo "todas"
        const isLast = !showOnlyLastLocation && isLastLocationByDate(location, locations);
        const badgeHtml = isLast ? '<span class="badge badge-success ml-2">Última</span>' : '';
        
        listItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">${location.nombre || 'Sin nombre'}${badgeHtml}</h6>
                    <small class="text-muted">${location.lat}, ${location.lng}</small>
                    ${location.fecha_registrada ? `<br><small class="text-info">${new Date(location.fecha_registrada).toLocaleString()}</small>` : ''}
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-primary" onclick="centerMapToLocation(${location.lat}, ${location.lng})">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        `;
        listContainer.appendChild(listItem);
    });
}

// Función auxiliar para verificar si es la última ubicación por fecha
function isLastLocationByDate(targetLocation, allLocations) {
    const targetDate = new Date(targetLocation.fecha_registrada || 0);
    
    for (let key in allLocations) {
        const locationDate = new Date(allLocations[key].fecha_registrada || 0);
        if (locationDate > targetDate) {
            return false;
        }
    }
    return true;
}

// Actualizar estadísticas
function updateStats(locations) {
    const count = locations ? Object.keys(locations).length : 0;
    document.getElementById('total-locations').textContent = count;
    document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
    locationCount = count;
}

// Agregar ubicación de prueba
function addTestLocation() {
    addLog('Agregando ubicación de prueba...', 'info');
    
    if (!window.Firebase || !window.Firebase.realtimeDb) {
        addLog('Error: Firebase no está disponible', 'error');
        return;
    }

    import('firebase/database').then(({ ref, push, set }) => {
        const locationsRef = ref(window.Firebase.realtimeDb, 'ubicaciones');
          // Generar coordenadas aleatorias en La Paz
        const laPazCoords = {
            lat: -16.5000 + (Math.random() - 0.5) * 0.1,
            lng: -68.1193 + (Math.random() - 0.5) * 0.1
        };
        
        const testLocation = {
            nombre: `Ubicación de Prueba ${locationCount + 1}`,
            lat: boliviaCoords.lat.toFixed(6),
            lng: boliviaCoords.lng.toFixed(6),
            fecha_registrada: new Date().toISOString(),
            descripcion: 'Ubicación generada automáticamente para pruebas'
        };
        
        const newLocationRef = push(locationsRef);
        set(newLocationRef, testLocation).then(() => {
            addLog('Ubicación de prueba agregada correctamente', 'success');
        }).catch((error) => {
            addLog('Error agregando ubicación: ' + error.message, 'error');
        });
        
    }).catch((error) => {
        addLog('Error importando Firebase Database: ' + error.message, 'error');
    });
}

// Centrar mapa en una ubicación específica
function centerMapToLocation(lat, lng) {
    map.setCenter({ lat: parseFloat(lat), lng: parseFloat(lng) });
    map.setZoom(15);
    addLog(`Mapa centrado en: ${lat}, ${lng}`, 'info');
}

// Centrar mapa en Bolivia
function centerMapToBolivia() {
    map.setCenter({ lat: -16.290154, lng: -63.588653 });
    map.setZoom(6);
    addLog('Mapa centrado en Bolivia', 'info');
}

// Limpiar todos los datos
function clearAllData() {
    if (!confirm('¿Está seguro de que desea eliminar todas las ubicaciones?')) {
        return;
    }
    
    addLog('Limpiando todos los datos...', 'warning');
    
    if (!window.Firebase || !window.Firebase.realtimeDb) {
        addLog('Error: Firebase no está disponible', 'error');
        return;
    }

    import('firebase/database').then(({ ref, remove }) => {
        const locationsRef = ref(window.Firebase.realtimeDb, 'ubicaciones');
        
        remove(locationsRef).then(() => {
            addLog('Todos los datos han sido eliminados', 'success');
        }).catch((error) => {
            addLog('Error eliminando datos: ' + error.message, 'error');
        });
        
    }).catch((error) => {
        addLog('Error importando Firebase Database: ' + error.message, 'error');
    });
}

// Eliminar una ubicación específica
function removeLocation(key) {
    if (!confirm('¿Eliminar esta ubicación?')) {
        return;
    }
    
    addLog(`Eliminando ubicación: ${key}`, 'warning');
    
    import('firebase/database').then(({ ref, remove }) => {
        const locationRef = ref(window.Firebase.realtimeDb, `ubicaciones/${key}`);
        
        remove(locationRef).then(() => {
            addLog('Ubicación eliminada correctamente', 'success');
        }).catch((error) => {
            addLog('Error eliminando ubicación: ' + error.message, 'error');
        });
        
    }).catch((error) => {
        addLog('Error importando Firebase Database: ' + error.message, 'error');
    });
}

// Alternar pantalla completa
function toggleFullscreen() {
    const mapContainer = document.getElementById('realtime-map');
    
    if (mapContainer.classList.contains('fullscreen-map')) {
        mapContainer.classList.remove('fullscreen-map');
        mapContainer.style.height = '600px';
    } else {
        mapContainer.classList.add('fullscreen-map');
        mapContainer.style.height = '100vh';
    }
    
    // Redimensionar el mapa
    setTimeout(() => {
        google.maps.event.trigger(map, 'resize');
    }, 100);
}

// Función para agregar logs
function addLog(message, type = 'info') {
    const logContainer = document.getElementById('activity-log');
    const timestamp = new Date().toLocaleTimeString();
    const logClass = 'log-' + type;
    const logEntry = `<div class="${logClass}">[${timestamp}] ${message}</div>`;
    logContainer.innerHTML += logEntry;
    logContainer.scrollTop = logContainer.scrollHeight;
}

// Limpiar log
function clearLog() {
    document.getElementById('activity-log').innerHTML = '';
    addLog('Log limpiado', 'info');
}

// Hacer la función initRealtimeMap disponible globalmente
window.initRealtimeMap = initRealtimeMap;
window.centerMapToLocation = centerMapToLocation;
window.removeLocation = removeLocation;
window.toggleMapMode = toggleMapMode;
</script>
@stop
