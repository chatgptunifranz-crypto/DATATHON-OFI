@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
@stop

@section('content_header')
    <h1 class="text-dark">Crear Usuario</h1>
@stop

@section('content')
    <div class="user-form-container">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="photo-container">
                <label for="foto">
                    <i class="fas fa-camera mr-2"></i>Foto del Usuario
                </label>
                <input type="file" name="foto" id="foto" class="form-control" accept="image/*" onchange="previewImage(event)">
                <img id="foto-preview" src="#" alt="Previsualización" class="photo-preview" />
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user mr-2"></i>Nombre
                        </label>
                        <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="apellido_paterno">
                            <i class="fas fa-user mr-2"></i>Apellido Paterno
                        </label>
                        <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control" required value="{{ old('apellido_paterno') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="apellido_materno">
                            <i class="fas fa-user mr-2"></i>Apellido Materno
                        </label>
                        <input type="text" name="apellido_materno" id="apellido_materno" class="form-control" required value="{{ old('apellido_materno') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ci">
                            <i class="fas fa-id-card mr-2"></i>CI
                        </label>
                        <input type="text" name="ci" id="ci" class="form-control" required value="{{ old('ci') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="placa">
                            <i class="fas fa-shield-alt mr-2"></i>Placa
                        </label>
                        <input type="text" name="placa" id="placa" class="form-control" required value="{{ old('placa') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </label>
                        <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="rol">
                            <i class="fas fa-user-shield mr-2"></i>Rol
                        </label>
                        <select name="rol" id="rol" class="form-control" required>
                            <option value="">Seleccione un rol</option>
                            <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="comandante" {{ old('rol') == 'comandante' ? 'selected' : '' }}>Comandante</option>
                            <option value="Sargento" {{ old('rol') == 'Sargento' ? 'selected' : '' }}>Sargento</option>
                            <option value="policia" {{ old('rol') == 'policia' ? 'selected' : '' }}>Policía</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-2"></i>Guardar
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary ml-2">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const output = document.getElementById('foto-preview');
            
            reader.onload = function() {
                output.src = reader.result;
                output.style.display = 'block';
            }
            
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        // Validación del CI
        document.getElementById('ci').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Validación de la Placa
        document.getElementById('placa').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    </script>
@stop
