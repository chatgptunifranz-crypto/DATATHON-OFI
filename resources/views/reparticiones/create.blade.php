@extends('adminlte::page')

@section('title', 'Nueva Asignación - Repartición de Personal')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Nueva Asignación de Personal</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('reparticiones.index') }}">Repartición de Personal</a></li>
                <li class="breadcrumb-item active">Nueva Asignación</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulario de Nueva Asignación</h3>
                    <div class="card-tools">
                        <a href="{{ route('reparticiones.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('reparticiones.store') }}" method="POST">
                    @csrf
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
                                            <option value="{{ $policia->id }}" {{ old('user_id') == $policia->id ? 'selected' : '' }}>
                                                {{ $policia->name }} - {{ $policia->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Solo se muestran usuarios con rol de policía activos
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zona">Zona de La Paz <span class="text-danger">*</span></label>
                                    <select name="zona" id="zona" class="form-control @error('zona') is-invalid @enderror" required>
                                        <option value="">Seleccione una zona</option>
                                        @foreach($zonas as $key => $value)
                                            <option value="{{ $key }}" {{ old('zona') == $key ? 'selected' : '' }}>
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
                                           value="{{ old('fecha_asignacion', date('Y-m-d')) }}" 
                                           min="{{ date('Y-m-d') }}"
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
                                           value="{{ old('horario_inicio', '08:00') }}"
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
                                           value="{{ old('horario_fin', '16:00') }}"
                                           required>
                                    @error('horario_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                      maxlength="500">{{ old('observaciones') }}</textarea>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Asignación
                        </button>
                        <a href="{{ route('reparticiones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-header {
            background-color: #007bff;
            color: white;
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
                            hora_fin: horaFin
                        },
                        success: function(data) {
                            const disponible = data.some(policia => policia.id == userId);
                            
                            if (!disponible) {
                                showAlert('danger', 'El policía seleccionado ya tiene una asignación en ese horario');
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
                $('#user_id, #zona').select2({
                    theme: 'bootstrap4',
                    placeholder: function() {
                        return $(this).data('placeholder');
                    }
                });
            }
        });
    </script>
@stop
