@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar Usuario</h1>
@endsection

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('usuarios.update', $usuario) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="name" class="form-control" value="{{ $usuario->name }}" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $usuario->email }}" required>
    </div>
    <div class="form-group">
        <label>Nueva Contraseña (opcional)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="form-group">
        <label>Rol</label>        <select name="rol" class="form-control" required>
            <option value="administrador" @if($usuario->hasRole('administrador')) selected @endif>Administrador</option>
            <option value="comandante" @if($usuario->hasRole('comandante')) selected @endif>Comandante</option>
            <option value="sargento" @if($usuario->hasRole('sargento')) selected @endif>Sargento</option>
            <option value="policia" @if($usuario->hasRole('policia')) selected @endif>Policía</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success mt-2">Actualizar</button>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-2">Cancelar</a>
</form>
@endsection
