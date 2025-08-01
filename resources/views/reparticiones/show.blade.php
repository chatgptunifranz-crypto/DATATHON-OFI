@extends('adminlte::page')

@section('title', 'Detalles de Asignación')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalles de Asignación</h1>
        <div>
            <a href="{{ route('reparticiones.edit', $reparticion) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('reparticiones.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> 
                        Información de la Asignación
                    </h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Policía Asignado:</dt>
                        <dd class="col-sm-8">
                            <i class="fas fa-user text-primary"></i>
                            {{ $reparticion->user->name }}
                        </dd>

                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8">
                            <i class="fas fa-envelope text-info"></i>
                            {{ $reparticion->user->email }}
                        </dd>

                        <dt class="col-sm-4">Zona Asignada:</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-primary badge-lg">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $reparticion->zona }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Fecha de Asignación:</dt>
                        <dd class="col-sm-8">
                            <i class="fas fa-calendar text-success"></i>
                            {{ $reparticion->fecha_asignacion->format('d/m/Y') }}
                        </dd>

                        <dt class="col-sm-4">Horario de Servicio:</dt>
                        <dd class="col-sm-8">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-warning mr-2"></i>
                                <span class="badge badge-info mr-2">Inicio: {{ $reparticion->horario_inicio }}</span>
                                <span class="badge badge-danger">Fin: {{ $reparticion->horario_fin }}</span>
                            </div>
                        </dd>

                        <dt class="col-sm-4">Estado:</dt>
                        <dd class="col-sm-8">
                            @if($reparticion->activo)
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                            @else
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-times-circle"></i> Inactivo
                                </span>
                            @endif
                        </dd>

                        @if($reparticion->observaciones)
                        <dt class="col-sm-4">Observaciones:</dt>
                        <dd class="col-sm-8">
                            <div class="alert alert-info">
                                <i class="fas fa-sticky-note"></i>
                                {{ $reparticion->observaciones }}
                            </div>
                        </dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Información del Policía -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-shield"></i> 
                        Información del Policía
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="user-image-wrapper">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h5 class="mt-2">{{ $reparticion->user->name }}</h5>
                        <p class="text-muted">{{ $reparticion->user->email }}</p>
                    </div>
                    
                    <div class="border-top pt-3">
                        <strong>Roles:</strong>
                        <div class="mt-1">
                            @foreach($reparticion->user->roles as $role)
                                <span class="badge badge-secondary">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i> 
                        Estadísticas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Asignaciones del Policía</span>
                                    <span class="info-box-number">{{ $totalAsignaciones }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-map-marked-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Asignaciones en {{ $reparticion->zona }}</span>
                                    <span class="info-box-number">{{ $asignacionesZona }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de asignaciones -->
    @if($historialAsignaciones->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i> 
                        Historial de Asignaciones del Policía
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Zona</th>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historialAsignaciones as $asignacion)
                                <tr class="{{ $asignacion->id == $reparticion->id ? 'table-primary' : '' }}">
                                    <td>
                                        @if($asignacion->id == $reparticion->id)
                                            <i class="fas fa-arrow-right text-primary"></i>
                                        @endif
                                        {{ $asignacion->zona }}
                                    </td>
                                    <td>{{ $asignacion->fecha_asignacion->format('d/m/Y') }}</td>
                                    <td>
                                        <small>
                                            {{ $asignacion->horario_inicio }} - {{ $asignacion->horario_fin }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($asignacion->activo)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@stop

@section('css')
<style>
    .badge-lg {
        font-size: 0.9em;
        padding: 0.5em 0.8em;
    }
    
    .user-image-wrapper {
        margin-bottom: 1rem;
    }
    
    .info-box {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .table-primary {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    .alert-info {
        border-left: 4px solid #17a2b8;
        background-color: #f8f9fa;
    }
</style>
@stop
