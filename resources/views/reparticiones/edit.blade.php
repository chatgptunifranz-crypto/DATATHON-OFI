@extends('adminlte::page')

@section('title', 'Editar Asignación - Repartición de Personal')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Editar Asignación de Personal</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('reparticiones.index') }}">Repartición de Personal</a></li>
                <li class="breadcrumb-item active">Editar Asignación</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Editar Asignación de {{ $reparticion->user->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('reparticiones.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('reparticiones.update', $reparticion) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Policía <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un policía</option>
                                        @foreach($policias as $policia)
                                            <option value="{{ $policia->id }}" 
                                                {{ (old('user_id', $reparticion->user_id) == $policia->id) ? 'selected' : '' }}>
                                                {{ $policia->name }} - {{ $policia->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zona">Zona de La Paz <span class="text-danger">*</span></label>
                                    <select name="zona" id="zona" class="form-control @error('zona') is-invalid @enderror" required>
                                        <option value="">Seleccione una zona</option>
                                        @foreach($zonas as $key => $value)
                                            <option value="{{ $key }}" 
                                                {{ (old('zona', $reparticion->zona) == $key) ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('zona')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_asignacion">Fecha de Asignación <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           name="fecha_asignacion" 
                                           id="fecha_asignacion" 
                                           class="form-control @error('fecha_asignacion') is-invalid @enderror" 
                                           value="{{ old('fecha_asignacion', $reparticion->fecha_asignacion->format('Y-m-d')) }}"
                                           required>
                                    @error('fecha_asignacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="horario_inicio">Horario de Inicio <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           name="horario_inicio" 
                                           id="horario_inicio" 
                                           class="form-control @error('horario_inicio') is-invalid @enderror" 
                                           value="{{ old('horario_inicio', $reparticion->horario_inicio->format('H:i')) }}"
                                           required>
                                    @error('horario_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="horario_fin">Horario de Fin <span class="text-danger">*</span></label>
                                    <input type="time" 
                                           name="horario_fin" 
                                           id="horario_fin" 
                                           class="form-control @error('horario_fin') is-invalid @enderror" 
                                           value="{{ old('horario_fin', $reparticion->horario_fin->format('H:i')) }}"
                                           required>
                                    @error('horario_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="activo">Estado</label>
                                    <select name="activo" id="activo" class="form-control @error('activo') is-invalid @enderror">
                                        <option value="1" {{ (old('activo', $reparticion->activo) == '1') ? 'selected' : '' }}>
                                            Activo
                                        </option>
                                        <option value="0" {{ (old('activo', $reparticion->activo) == '0') ? 'selected' : '' }}>
                                            Inactivo
                                        </option>
                                    </select>
                                    @error('activo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Creado</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $reparticion->created_at->format('d/m/Y H:i') }}" 
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" 
                                      id="observaciones" 
                                      class="form-control @error('observaciones') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Observaciones adicionales sobre la asignación (opcional)"
                                      maxlength="500">{{ old('observaciones', $reparticion->observaciones) }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Máximo 500 caracteres</small>
                        </div>

                        <!-- Alert de disponibilidad -->
                        <div id="disponibilidad-alert" class="alert alert-info" style="display: none;">
                            <i class="fas fa-info-circle"></i>
                            <span id="disponibilidad-mensaje"></span>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Actualizar Asignación
                        </button>
                        <a href="{{ route('reparticiones.show', $reparticion) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Ver Detalles
                        </a>
                        <a href="{{ route('reparticiones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Historial de cambios (opcional) -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Información de la Asignación</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Creado:</strong> {{ $reparticion->created_at->format('d/m/Y H:i:s') }}<br>
                            <strong>Última actualización:</strong> {{ $reparticion->updated_at->format('d/m/Y H:i:s') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Estado actual:</strong> 
                            @if($reparticion->activo)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-secondary">Inactivo</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-header {
            background-color: #ffc107;
            color: #212529;
        }
        .form-group label {
            font-weight: 600;
        }
        .text-danger {
            color: #dc3545 !important;
        }
        .invalid-feedback {
            display: block;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            const reparticionId = {{ $reparticion->id }};

            // Validación en tiempo real de horarios
            $('#horario_inicio, #horario_fin').on('change', function() {
                validateTimeRange();
            });

            // Verificar disponibilidad cuando cambian fecha y horarios
            $('#fecha_asignacion, #horario_inicio, #horario_fin, #user_id').on('change', function() {
                checkAvailability();
            });

            function validateTimeRange() {
                const inicio = $('#horario_inicio').val();
                const fin = $('#horario_fin').val();
                
                if (inicio && fin && inicio >= fin) {
                    $('#horario_fin').addClass('is-invalid');
                    showAlert('warning', 'El horario de fin debe ser posterior al horario de inicio');
                } else {
                    $('#horario_fin').removeClass('is-invalid');
                    hideAlert();
                }
            }

            function checkAvailability() {
                const fecha = $('#fecha_asignacion').val();
                const horaInicio = $('#horario_inicio').val();
                const horaFin = $('#horario_fin').val();
                const userId = $('#user_id').val();
                
                if (fecha && horaInicio && horaFin && userId) {
                    $.ajax({
                        url: "{{ route('reparticiones.disponibles') }}",
                        method: 'GET',
                        data: {
                            fecha: fecha,
                            hora_inicio: horaInicio,
                            hora_fin: horaFin,
                            exclude_id: reparticionId
                        },
                        success: function(data) {
                            const disponible = data.some(policia => policia.id == userId);
                            
                            if (!disponible) {
                                showAlert('danger', 'El policía seleccionado ya tiene otra asignación en ese horario');
                                $('#user_id').addClass('is-invalid');
                            } else {
                                showAlert('success', 'El policía está disponible para ese horario');
                                $('#user_id').removeClass('is-invalid');
                            }
                        },
                        error: function() {
                            showAlert('warning', 'No se pudo verificar la disponibilidad');
                        }
                    });
                }
            }

            function showAlert(type, message) {
                const alertDiv = $('#disponibilidad-alert');
                alertDiv.removeClass('alert-info alert-success alert-warning alert-danger')
                        .addClass('alert-' + type)
                        .show();
                $('#disponibilidad-mensaje').text(message);
            }

            function hideAlert() {
                $('#disponibilidad-alert').hide();
            }

            // Contador de caracteres para observaciones
            $('#observaciones').on('input', function() {
                const maxLength = 500;
                const currentLength = $(this).val().length;
                const remaining = maxLength - currentLength;
                
                $(this).next('.form-text').text(`Máximo 500 caracteres (${remaining} restantes)`);
                
                if (remaining < 0) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Select2 para mejor UX
            if ($.fn.select2) {
                $('#user_id, #zona, #activo').select2({
                    theme: 'bootstrap4'
                });
            }
        });
    </script>
@stop
