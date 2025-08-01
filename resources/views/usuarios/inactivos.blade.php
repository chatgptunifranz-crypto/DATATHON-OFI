@extends('adminlte::page')

@section('title', 'Usuarios Inactivos')

@section('content_header')
    <h1>Usuarios Inactivos</h1>
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
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->rol }}</td>            <td>
                @can('manage-users')
                    <form action="{{ route('usuarios.activar', $usuario->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Activar</button>
                    </form>
                @else
                    <span class="text-muted">Sin permisos</span>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-2">Volver a Activos</a>
@endsection
