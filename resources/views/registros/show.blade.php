@extends('adminlte::page')

@section('title', 'Ver Registro')



@section('content_header')
    <h1>Detalles del Registro</h1>
@endsection

@section('content')
<div class="container">
    <!-- DATOS PERSONALES -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-user"></i> Datos Personales</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($registro->foto)
                        <div class="text-center mb-3">
                            <img src="{{ asset($registro->foto) }}" alt="Foto del registro" class="img-thumbnail" style="max-height: 200px; max-width: 200px;">
                            <p class="text-muted mt-2">Fotografía</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><strong>Nombre Completo:</strong></label>
                                <p>{{ $registro->nombres }} {{ $registro->apellido_paterno }} {{ $registro->apellido_materno }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>CI:</strong></label>
                                <p>{{ $registro->ci }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Expedido en:</strong></label>
                                <p>{{ $registro->expedido ?? 'No especificado' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Fecha de Nacimiento:</strong></label>
                                <p>
                                    @if($registro->fecha_nacimiento)
                                        {{ $registro->fecha_nacimiento->format('d/m/Y') }}
                                        <small class="text-muted">({{ $registro->edad }} años)</small>
                                    @else
                                        No especificado
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Estado Civil:</strong></label>
                                <p>
                                    @if($registro->estado_civil)
                                        {{ \App\Models\Registro::getEstadosCiviles()[$registro->estado_civil] }}
                                    @else
                                        No especificado
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label><strong>Profesión:</strong></label>
                                <p>{{ $registro->profesion ?? 'No especificado' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><strong>Domicilio:</strong></label>
                                <p>{{ $registro->domicilio ?? 'No especificado' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DATOS DEL REGISTRO POLICIAL -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Registro Policial</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Tipo de Delito/Infracción:</strong></label>
                        <p>{{ $registro->cargo }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Fecha de Registro:</strong></label>
                        <p>{{ $registro->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            @if($registro->descripcion)
            <div class="form-group">
                <label><strong>Descripción del Incidente:</strong></label>
                <p>{{ $registro->descripcion }}</p>
            </div>
            @endif

            @if($registro->antecedentes)
            <div class="form-group">
                <label><strong>Antecedentes:</strong></label>
                <div class="alert alert-info">
                    <p>{{ $registro->antecedentes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- UBICACIÓN DEL INCIDENTE -->
    @if($registro->longitud && $registro->latitud)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Ubicación del Incidente</h5>
        </div>
        <div class="card-body">
            <div class="form-group">
                <p><strong>Coordenadas:</strong> Latitud: {{ $registro->latitud }}, Longitud: {{ $registro->longitud }}</p>
                <div id="map" style="height: 300px; width: 100%; border: 1px solid #ddd; border-radius: 5px;"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- BOTONES DE ACCIÓN -->
    <div class="card">
        <div class="card-body text-center">
            @can('manage-registros')
                <a href="{{ route('registros.edit', $registro) }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-edit"></i> Editar
                </a>
            @endcan
            <a href="{{ route('registros.index') }}" class="btn btn-secondary btn-lg ml-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @can('manage-registros')
                <form action="{{ route('registros.destroy', $registro) }}" method="POST" style="display:inline-block" class="ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>

@if($registro->longitud && $registro->latitud)
@section('js')
    <!-- Google Maps JavaScript API -->    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApZs1RsAdo4vF99FvtN8Fqf5vbn0vYWG4&callback=initMap&libraries=&v=weekly" async defer></script>
    <script>
        function initMap() {
            const ubicacion = { 
                lat: parseFloat({{ $registro->latitud }}), 
                lng: parseFloat({{ $registro->longitud }}) 
            };
            
            const map = new google.maps.Map(document.getElementById('map'), {
                center: ubicacion,
                zoom: 15
            });

            const marker = new google.maps.Marker({
                position: ubicacion,
                map: map,
                title: 'Ubicación del incidente'
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div>
                        <h6>Ubicación del Incidente</h6>
                        <p><strong>Delito:</strong> {{ $registro->cargo }}</p>
                        <p><strong>Fecha:</strong> {{ $registro->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                `
            });

            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        }

        // Hacer la función disponible globalmente
        window.initMap = initMap;
    </script>
@endsection
@endif
@endsection