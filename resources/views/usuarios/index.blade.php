@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
    @can('manage-users')
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Crear Usuario</a>
        <a href="{{ route('usuarios.inactivos') }}" class="btn btn-secondary">Ver Inactivos</a>
    @endcan
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($usuarios as $usuario)
        <tr>
            <td>{{ $usuario->id }}</td>
            <td>{{ $usuario->name }}</td>
            <td>{{ $usuario->email }}</td>            <td>{{ $usuario->roles->first()->name ?? 'Sin rol' }}</td>
            <td>
                @can('manage-users')
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                    </form>
                @else
                    <span class="text-muted">Sin permisos</span>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
