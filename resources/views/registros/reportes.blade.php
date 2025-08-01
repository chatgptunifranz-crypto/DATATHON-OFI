@extends('adminlte::page')

@section('title', 'Reportes de Registros')

@section('content_header')
    <h1>Reportes de Registros</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <!-- Filtros -->
        <div class="mb-4">
            <form action="{{ route('registros.reportes') }}" method="GET" class="form-inline">
                <div class="form-group mx-sm-3 mb-2">
                    <label for="cargo" class="sr-only">Filtrar por Cargo</label>
                    <select name="cargo" id="cargo" class="form-control">
                        <option value="">Todos los cargos</option>
                        @foreach($registros->pluck('cargo')->unique() as $cargo)
                            <option value="{{ $cargo }}" {{ request('cargo') == $cargo ? 'selected' : '' }}>
                                {{ $cargo }}
                            </option>
                        @endforeach
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
                @foreach($registros as $registro)
                    <tr>

                        <td>{{ $registro->nombres }} {{ $registro->apellido_paterno }} {{ $registro->apellido_materno }}</td>
                        <td>{{ $registro->ci }}</td>
                        <td>{{ $registro->cargo }}</td>                        <td>
                            @if($registro->longitud && $registro->latitud)
                                <button class="btn btn-sm btn-primary ver-mapa" 
                                    data-lat="{{ $registro->latitud }}" 
                                    data-lng="{{ $registro->longitud }}"
                                    data-nombre="{{ $registro->nombres }} {{ $registro->apellido_paterno }}"
                                    data-cargo="{{ $registro->cargo }}">
                                    Ver en Mapa
                                </button>
                            @else
                                No disponible
                            @endif
                        </td>
                        <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
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
                            <span class="info-box-number">{{ $registros->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <div class="info-box-content">
                            <span class="info-box-text">Registros por Cargo</span>
                            <ul class="list-unstyled">
                                @foreach($registros->groupBy('cargo') as $cargo => $grupo)
                                    <li>{{ $cargo }}: {{ $grupo->count() }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>        <!-- Botones de Exportación -->
        <div class="mt-4">
            <div class="btn-group" role="group">
                <a href="{{ route('registros.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                @can('manage-registros')
                    <a href="{{ route('registros.backups') }}" class="btn btn-info">
                        <i class="fas fa-database"></i> Gestionar Backups
                    </a>
                    <form action="{{ route('registros.backup.generar') }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('¿Generar backup de todos los registros?')">
                            <i class="fas fa-download"></i> Generar Backup CSV
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
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
@endsection