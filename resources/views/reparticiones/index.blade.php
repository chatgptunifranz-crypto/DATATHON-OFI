@extends('adminlte::page')

@section('title', 'Repartición de Personal')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Repartición de Personal</h1>
        <div>
            <a href="{{ route('reparticiones.pdf') }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </a>
            <a href="{{ route('reparticiones.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Asignación
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fas fa-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestión de Asignaciones de Personal</h3>                    <div class="card-tools">
                        @can('manage-reparticiones')
                            <a href="{{ route('reparticiones.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nueva Asignación
                            </a>
                        @endcan
                    </div>
                </div>
                
                <!-- Filtros -->
                <div class="card-body">
                    <form method="GET" action="{{ route('reparticiones.filtrar') }}" class="row">
                        <div class="col-md-3">
                            <label for="zona">Zona:</label>
                            <select name="zona" id="zona" class="form-control">
                                <option value="">Todas las zonas</option>
                                @foreach($zonas as $key => $value)
                                    <option value="{{ $key }}" {{ request('zona') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha">Fecha:</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha') }}">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                                <a href="{{ route('reparticiones.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Policía</th>
                                    <th>Zona</th>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Estado</th>
                                    <th>Observaciones</th>
                                    <th width="200px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reparticiones as $reparticion)
                                    <tr>
                                        <td>
                                            <strong>{{ $reparticion->user->name }}</strong><br>
                                            <small class="text-muted">{{ $reparticion->user->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $reparticion->zona }}</span>
                                        </td>
                                        <td>{{ $reparticion->fecha_asignacion->format('d/m/Y') }}</td>
                                        <td>
                                            {{ $reparticion->horario_inicio->format('H:i') }} - 
                                            {{ $reparticion->horario_fin->format('H:i') }}
                                        </td>
                                        <td>
                                            @if($reparticion->activo)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($reparticion->observaciones)
                                                <span class="text-truncate" style="max-width: 150px; display: inline-block;" 
                                                      title="{{ $reparticion->observaciones }}">
                                                    {{ Str::limit($reparticion->observaciones, 50) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('reparticiones.show', $reparticion) }}" 
                                                   class="btn btn-info btn-sm" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                  @can('manage-reparticiones')
                                                    <a href="{{ route('reparticiones.edit', $reparticion) }}" 
                                                       class="btn btn-warning btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('reparticiones.toggle', $reparticion) }}" 
                                                          method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-{{ $reparticion->activo ? 'secondary' : 'success' }} btn-sm"
                                                                title="{{ $reparticion->activo ? 'Desactivar' : 'Activar' }}"
                                                                onclick="return confirm('¿Confirma el cambio de estado?')">
                                                            <i class="fas fa-{{ $reparticion->activo ? 'times' : 'check' }}"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('reparticiones.destroy', $reparticion) }}" 
                                                          method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                title="Eliminar"
                                                                onclick="return confirm('¿Seguro que desea eliminar esta asignación?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No hay asignaciones registradas</h5>
                                                <p class="text-muted">Comience creando una nueva asignación de personal</p>                                                @can('manage-reparticiones')
                                                    <a href="{{ route('reparticiones.create') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Nueva Asignación
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($reparticiones->hasPages())
                    <div class="card-footer">
                        {{ $reparticiones->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Resumen estadístico -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $reparticiones->where('activo', true)->count() }}</h3>
                    <p>Asignaciones Activas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ collect($zonas)->count() }}</h3>
                    <p>Zonas Disponibles</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $reparticiones->where('fecha_asignacion', today())->count() }}</h3>
                    <p>Asignaciones Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $reparticiones->where('activo', false)->count() }}</h3>
                    <p>Asignaciones Inactivas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th {
            border-top: none;
        }
        .empty-state {
            padding: 2rem;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@stop

@section('js')
    <script>
        // Auto-submit del formulario de filtros cuando cambian los valores
        $('#zona, #fecha').on('change', function() {
            $(this).closest('form').submit();
        });
        
        // Confirmación para cambios de estado
        $('form[action*="toggle"]').on('submit', function(e) {
            if (!confirm('¿Confirma el cambio de estado de esta asignación?')) {
                e.preventDefault();
            }
        });
    </script>
@stop
