@extends('adminlte::page')

@section('title', 'Editar Antecedente')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Editar Antecedente</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('antecedentes.index') }}">Antecedentes</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Antecedente - CI: {{ $registro->ci }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('antecedentes.show', $registro->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('antecedentes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('antecedentes.update', $registro->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ci">CI <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('ci') is-invalid @enderror" 
                                           id="ci" 
                                           name="ci" 
                                           value="{{ old('ci', $registro->ci) }}" 
                                           required 
                                           placeholder="Ej: 12345678">
                                    @error('ci')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Se verificará si ya existe otro registro con este CI</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('nombres') is-invalid @enderror" 
                                           id="nombres" 
                                           name="nombres" 
                                           value="{{ old('nombres', $registro->nombres) }}" 
                                           required 
                                           placeholder="Nombres de la persona">
                                    @error('nombres')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellido_paterno">Apellido Paterno <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('apellido_paterno') is-invalid @enderror" 
                                           id="apellido_paterno" 
                                           name="apellido_paterno" 
                                           value="{{ old('apellido_paterno', $registro->apellido_paterno) }}" 
                                           required 
                                           placeholder="Apellido paterno">
                                    @error('apellido_paterno')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellido_materno">Apellido Materno <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('apellido_materno') is-invalid @enderror" 
                                           id="apellido_materno" 
                                           name="apellido_materno" 
                                           value="{{ old('apellido_materno', $registro->apellido_materno) }}" 
                                           required 
                                           placeholder="Apellido materno">
                                    @error('apellido_materno')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expedido">Expedido en</label>
                                    <select class="form-control @error('expedido') is-invalid @enderror" 
                                            id="expedido" 
                                            name="expedido">
                                        <option value="">Seleccione departamento</option>
                                        <option value="La Paz" {{ old('expedido', $registro->expedido) == 'La Paz' ? 'selected' : '' }}>La Paz</option>
                                        <option value="Cochabamba" {{ old('expedido', $registro->expedido) == 'Cochabamba' ? 'selected' : '' }}>Cochabamba</option>
                                        <option value="Santa Cruz" {{ old('expedido', $registro->expedido) == 'Santa Cruz' ? 'selected' : '' }}>Santa Cruz</option>
                                        <option value="Oruro" {{ old('expedido', $registro->expedido) == 'Oruro' ? 'selected' : '' }}>Oruro</option>
                                        <option value="Potosí" {{ old('expedido', $registro->expedido) == 'Potosí' ? 'selected' : '' }}>Potosí</option>
                                        <option value="Tarija" {{ old('expedido', $registro->expedido) == 'Tarija' ? 'selected' : '' }}>Tarija</option>
                                        <option value="Chuquisaca" {{ old('expedido', $registro->expedido) == 'Chuquisaca' ? 'selected' : '' }}>Chuquisaca</option>
                                        <option value="Beni" {{ old('expedido', $registro->expedido) == 'Beni' ? 'selected' : '' }}>Beni</option>
                                        <option value="Pando" {{ old('expedido', $registro->expedido) == 'Pando' ? 'selected' : '' }}>Pando</option>
                                    </select>
                                    @error('expedido')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                    <input type="date" 
                                           class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                                           id="fecha_nacimiento" 
                                           name="fecha_nacimiento" 
                                           value="{{ old('fecha_nacimiento', $registro->fecha_nacimiento ? $registro->fecha_nacimiento->format('Y-m-d') : '') }}">
                                    @error('fecha_nacimiento')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado_civil">Estado Civil</label>
                                    <select class="form-control @error('estado_civil') is-invalid @enderror" 
                                            id="estado_civil" 
                                            name="estado_civil">
                                        <option value="">Seleccione estado civil</option>
                                        <option value="soltero" {{ old('estado_civil', $registro->estado_civil) == 'soltero' ? 'selected' : '' }}>Soltero/a</option>
                                        <option value="casado" {{ old('estado_civil', $registro->estado_civil) == 'casado' ? 'selected' : '' }}>Casado/a</option>
                                        <option value="divorciado" {{ old('estado_civil', $registro->estado_civil) == 'divorciado' ? 'selected' : '' }}>Divorciado/a</option>
                                        <option value="viudo" {{ old('estado_civil', $registro->estado_civil) == 'viudo' ? 'selected' : '' }}>Viudo/a</option>
                                        <option value="union_libre" {{ old('estado_civil', $registro->estado_civil) == 'union_libre' ? 'selected' : '' }}>Unión Libre</option>
                                    </select>
                                    @error('estado_civil')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profesion">Profesión</label>
                                    <input type="text" 
                                           class="form-control @error('profesion') is-invalid @enderror" 
                                           id="profesion" 
                                           name="profesion" 
                                           value="{{ old('profesion', $registro->profesion) }}" 
                                           placeholder="Profesión u ocupación">
                                    @error('profesion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="domicilio">Domicilio <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('domicilio') is-invalid @enderror" 
                                              id="domicilio" 
                                              name="domicilio" 
                                              rows="2" 
                                              required 
                                              placeholder="Dirección completa del domicilio">{{ old('domicilio', $registro->domicilio) }}</textarea>
                                    @error('domicilio')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cargo">Cargo/Delito <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('cargo') is-invalid @enderror" 
                                           id="cargo" 
                                           name="cargo" 
                                           value="{{ old('cargo', $registro->cargo) }}" 
                                           required 
                                           placeholder="Tipo de delito o cargo">
                                    @error('cargo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <input type="text" 
                                           class="form-control @error('descripcion') is-invalid @enderror" 
                                           id="descripcion" 
                                           name="descripcion" 
                                           value="{{ old('descripcion', $registro->descripcion) }}" 
                                           placeholder="Descripción adicional">
                                    @error('descripcion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitud">Longitud</label>
                                    <input type="number" 
                                           class="form-control @error('longitud') is-invalid @enderror" 
                                           id="longitud" 
                                           name="longitud" 
                                           value="{{ old('longitud', $registro->longitud) }}" 
                                           step="0.0000001"
                                           min="-180"
                                           max="180"
                                           placeholder="Ej: -68.1193">
                                    @error('longitud')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Coordenada de longitud del incidente</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitud">Latitud</label>
                                    <input type="number" 
                                           class="form-control @error('latitud') is-invalid @enderror" 
                                           id="latitud" 
                                           name="latitud" 
                                           value="{{ old('latitud', $registro->latitud) }}" 
                                           step="0.0000001"
                                           min="-90"
                                           max="90"
                                           placeholder="Ej: -16.5000">
                                    @error('latitud')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Coordenada de latitud del incidente</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="antecedentes">Antecedentes <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('antecedentes') is-invalid @enderror" 
                                              id="antecedentes" 
                                              name="antecedentes" 
                                              rows="5" 
                                              required 
                                              placeholder="Describa los antecedentes de la persona...">{{ old('antecedentes', $registro->antecedentes) }}</textarea>
                                    @error('antecedentes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Proporcione información detallada sobre los antecedentes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Antecedente
                        </button>
                        <a href="{{ route('antecedentes.show', $registro->id) }}" class="btn btn-info ml-2">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('antecedentes.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
