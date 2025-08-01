@extends('adminlte::page')

@section('title', 'Detalle de Aprobación')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Detalle de Aprobación</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('aprobaciones.index') }}">Aprobaciones</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Aprobación #{{ $aprobacion->id }}</h3>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('aprobaciones.edit', $aprobacion) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('aprobaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Información Principal -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Información Principal</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">ID:</dt>
                                <dd class="col-sm-8">{{ $aprobacion->id }}</dd>                                <dt class="col-sm-4">Orden del Día:</dt>
                                <dd class="col-sm-8">{{ $aprobacion->ordenDelDia->nombre ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Estado:</dt>
                                <dd class="col-sm-8">
                                    @if($aprobacion->estado == 'pendiente')
                                        <span class="badge badge-warning badge-lg">Pendiente</span>
                                    @else
                                        <span class="badge badge-success badge-lg">Aprobado</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Creado por:</dt>
                                <dd class="col-sm-8">{{ $aprobacion->usuarioCreador->name ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Fecha de creación:</dt>
                                <dd class="col-sm-8">{{ $aprobacion->created_at->format('d/m/Y H:i:s') }}</dd>

                                <dt class="col-sm-4">Última actualización:</dt>
                                <dd class="col-sm-8">{{ $aprobacion->updated_at->format('d/m/Y H:i:s') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Información de Aprobación</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-5">Aprobado por:</dt>
                                <dd class="col-sm-7">{{ $aprobacion->usuarioAprobador->name ?? 'N/A' }}</dd>

                                <dt class="col-sm-5">Fecha de aprobación:</dt>
                                <dd class="col-sm-7">
                                    {{ $aprobacion->fecha_aprobacion ? $aprobacion->fecha_aprobacion->format('d/m/Y H:i:s') : 'N/A' }}
                                </dd>

                                <dt class="col-sm-5">Tiene errores:</dt>
                                <dd class="col-sm-7">
                                    @if($aprobacion->tieneErrores())
                                        <span class="badge badge-danger">Sí</span>
                                    @else
                                        <span class="badge badge-success">No</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            @if($aprobacion->observaciones)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Observaciones</h3>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $aprobacion->observaciones }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Errores Detectados -->
            @if($aprobacion->errores_detectados)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Errores Detectados</h3>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $aprobacion->errores_detectados }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Información de la Orden del Día -->
            @if($aprobacion->ordenDelDia)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Información de la Orden del Día</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-2">Título:</dt>
                                <dd class="col-sm-10">{{ $aprobacion->ordenDelDia->nombre }}</dd>                                @if($aprobacion->ordenDelDia->contenido)
                                <dt class="col-sm-2">Contenido:</dt>
                                <dd class="col-sm-10">{{ substr($aprobacion->ordenDelDia->contenido, 0, 200) . (strlen($aprobacion->ordenDelDia->contenido) > 200 ? '...' : '') }}</dd>
                                @endif

                                @if($aprobacion->ordenDelDia->fecha)
                                <dt class="col-sm-2">Fecha:</dt>
                                <dd class="col-sm-10">{{ $aprobacion->ordenDelDia->fecha->format('d/m/Y') }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    @if($aprobacion->esPendiente())
                        <button type="button" class="btn btn-success" onclick="aprobarAprobacion({{ $aprobacion->id }})">
                            <i class="fas fa-check"></i> Aprobar
                        </button>
                        <button type="button" class="btn btn-warning" onclick="rechazarAprobacion({{ $aprobacion->id }})">
                            <i class="fas fa-times"></i> Rechazar
                        </button>
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('aprobaciones.edit', $aprobacion) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger" onclick="eliminarAprobacion({{ $aprobacion->id }})">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
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

    <!-- Modal para rechazar -->
    <div class="modal fade" id="rechazarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Rechazar Aprobación</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="rechazarForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="observaciones_rechazar">Motivo del rechazo <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="observaciones_rechazar" 
                                      name="observaciones" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Rechazar</button>
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

function rechazarAprobacion(id) {
    $('#rechazarForm').attr('action', '/aprobaciones/' + id + '/rechazar');
    $('#rechazarModal').modal('show');
}

function eliminarAprobacion(id) {
    $('#eliminarForm').attr('action', '/aprobaciones/' + id);
    $('#eliminarModal').modal('show');
}
</script>
@stop
