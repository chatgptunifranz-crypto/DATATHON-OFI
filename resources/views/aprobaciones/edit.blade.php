@extends('adminlte::page')

@section('title', 'Editar Aprobación')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Editar Aprobación</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('aprobaciones.index') }}">Aprobaciones</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Editar Aprobación #{{ $aprobacion->id }}</h3>
        </div>

        <form action="{{ route('aprobaciones.update', $aprobacion) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="orden_del_dia_id">Orden del Día <span class="text-danger">*</span></label>
                            <select name="orden_del_dia_id" id="orden_del_dia_id" 
                                    class="form-control @error('orden_del_dia_id') is-invalid @enderror" required>
                                <option value="">Seleccionar orden del día</option>                                @foreach($ordenes as $orden)
                                    <option value="{{ $orden->id }}" 
                                            {{ old('orden_del_dia_id', $aprobacion->orden_del_dia_id) == $orden->id ? 'selected' : '' }}>
                                        {{ $orden->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('orden_del_dia_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estado">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" 
                                    class="form-control @error('estado') is-invalid @enderror" required>
                                <option value="pendiente" {{ old('estado', $aprobacion->estado) == 'pendiente' ? 'selected' : '' }}>
                                    Pendiente
                                </option>
                                <option value="aprobado" {{ old('estado', $aprobacion->estado) == 'aprobado' ? 'selected' : '' }}>
                                    Aprobado
                                </option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row" id="usuario_aprobador_row" style="{{ old('estado', $aprobacion->estado) == 'aprobado' ? '' : 'display: none;' }}">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="usuario_aprobador_id">Usuario Aprobador</label>
                            <select name="usuario_aprobador_id" id="usuario_aprobador_id" 
                                    class="form-control @error('usuario_aprobador_id') is-invalid @enderror">
                                <option value="">Seleccionar usuario aprobador</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" 
                                            {{ old('usuario_aprobador_id', $aprobacion->usuario_aprobador_id) == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('usuario_aprobador_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_aprobacion">Fecha de Aprobación</label>
                            <input type="datetime-local" name="fecha_aprobacion" id="fecha_aprobacion" 
                                   class="form-control @error('fecha_aprobacion') is-invalid @enderror"
                                   value="{{ old('fecha_aprobacion', $aprobacion->fecha_aprobacion ? $aprobacion->fecha_aprobacion->format('Y-m-d\TH:i') : '') }}">
                            @error('fecha_aprobacion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="4" 
                                      class="form-control @error('observaciones') is-invalid @enderror"
                                      placeholder="Ingrese observaciones adicionales...">{{ old('observaciones', $aprobacion->observaciones) }}</textarea>
                            @error('observaciones')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="errores_detectados">Errores Detectados</label>
                            <textarea name="errores_detectados" id="errores_detectados" rows="4" 
                                      class="form-control @error('errores_detectados') is-invalid @enderror"
                                      placeholder="Detallar errores específicos encontrados...">{{ old('errores_detectados', $aprobacion->errores_detectados) }}</textarea>
                            @error('errores_detectados')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información de auditoría -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Información de Auditoría</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Creado por:</strong><br>
                                        {{ $aprobacion->usuarioCreador->name ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Fecha de creación:</strong><br>
                                        {{ $aprobacion->created_at->format('d/m/Y H:i:s') }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Última modificación:</strong><br>
                                        {{ $aprobacion->updated_at->format('d/m/Y H:i:s') }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Estado actual:</strong><br>
                                        @if($aprobacion->estado == 'pendiente')
                                            <span class="badge badge-warning">Pendiente</span>
                                        @else
                                            <span class="badge badge-success">Aprobado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('aprobaciones.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <a href="{{ route('aprobaciones.show', $aprobacion) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> Ver Detalle
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Mostrar/ocultar campos de aprobación según el estado
    $('#estado').change(function() {
        if ($(this).val() === 'aprobado') {
            $('#usuario_aprobador_row').show();
            // Establecer fecha actual si no hay una fecha establecida y es un cambio nuevo
            if (!$('#fecha_aprobacion').val() && $(this).data('original') !== 'aprobado') {
                const now = new Date();
                const formatted = now.getFullYear() + '-' + 
                    String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                    String(now.getDate()).padStart(2, '0') + 'T' + 
                    String(now.getHours()).padStart(2, '0') + ':' + 
                    String(now.getMinutes()).padStart(2, '0');
                $('#fecha_aprobacion').val(formatted);
            }
        } else {
            $('#usuario_aprobador_row').hide();
            $('#usuario_aprobador_id').val('');
            $('#fecha_aprobacion').val('');
        }
    });

    // Guardar el estado original
    $('#estado').data('original', $('#estado').val());
});
</script>
@stop
