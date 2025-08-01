@extends('adminlte::page')

@section('title', 'Aprobaciones')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Aprobaciones</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Aprobaciones</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Lista de Aprobaciones</h3>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('aprobaciones.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Aprobación
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card-body">
            <form method="GET" action="{{ route('aprobaciones.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="orden_del_dia_id">Orden del Día</label>
                            <select name="orden_del_dia_id" id="orden_del_dia_id" class="form-control">
                                <option value="">Todas las órdenes</option>                                @foreach($ordenes as $orden)
                                    <option value="{{ $orden->id }}" {{ request('orden_del_dia_id') == $orden->id ? 'selected' : '' }}>
                                        {{ $orden->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_desde">Desde</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" 
                                   value="{{ request('fecha_desde') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_hasta">Hasta</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" 
                                   value="{{ request('fecha_hasta') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Tabla de aprobaciones -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Orden del Día</th>
                            <th>Estado</th>
                            <th>Creador</th>
                            <th>Aprobador</th>
                            <th>Fecha Creación</th>
                            <th>Fecha Aprobación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aprobaciones as $aprobacion)                            <tr>
                                <td>{{ $aprobacion->id }}</td>
                                <td>{{ $aprobacion->ordenDelDia->nombre ?? 'N/A' }}</td>
                                <td>
                                    @if($aprobacion->estado == 'pendiente')
                                        <span class="badge badge-warning">Pendiente</span>
                                    @else
                                        <span class="badge badge-success">Aprobado</span>
                                    @endif
                                </td>
                                <td>{{ $aprobacion->usuarioCreador->name ?? 'N/A' }}</td>
                                <td>{{ $aprobacion->usuarioAprobador->name ?? 'N/A' }}</td>
                                <td>{{ $aprobacion->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $aprobacion->fecha_aprobacion ? $aprobacion->fecha_aprobacion->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('aprobaciones.show', $aprobacion) }}" 
                                           class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('aprobaciones.edit', $aprobacion) }}" 
                                           class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if($aprobacion->esPendiente())
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    onclick="aprobarAprobacion({{ $aprobacion->id }})" title="Aprobar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="eliminarAprobacion({{ $aprobacion->id }})" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No se encontraron aprobaciones</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $aprobaciones->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Modal para aprobar -->
    <div class="modal fade" id="aprobarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Aprobar Aprobación</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="aprobarForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="observaciones_aprobar">Observaciones (opcional)</label>
                            <textarea class="form-control" id="observaciones_aprobar" 
                                      name="observaciones" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Aprobar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar -->
    <div class="modal fade" id="eliminarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar Eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea eliminar esta aprobación?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form id="eliminarForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
function aprobarAprobacion(id) {
    $('#aprobarForm').attr('action', '/aprobaciones/' + id + '/aprobar');
    $('#aprobarModal').modal('show');
}

function eliminarAprobacion(id) {
    $('#eliminarForm').attr('action', '/aprobaciones/' + id);
    $('#eliminarModal').modal('show');
}
</script>
@stop
