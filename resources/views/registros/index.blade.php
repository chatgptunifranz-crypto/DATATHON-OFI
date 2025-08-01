@extends('adminlte::page')

@section('title', 'Registros')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Registros</h1>
        <div>
            @can('manage-registros')
                <a href="{{ route('registros.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Registro
                </a>
                <a href="{{ route('registros.backups') }}" class="btn btn-info">
                    <i class="fas fa-database"></i> Gestionar Backups
                </a>
                <a href="{{ route('registros.reportes') }}" class="btn btn-success">
                    <i class="fas fa-chart-line"></i> Reportes
                </a>
            @endcan
        </div>
    </div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nombre Completo</th>
                    <th>CI</th>
                    <th>Edad</th>
                    <th>Delito/Infracción</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registros as $registro)
                    <tr>
                        <td>{{ $registro->id }}</td>
                        <td>
                            @if($registro->foto)
                                <img src="{{ asset($registro->foto) }}" alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <span class="text-muted">Sin foto</span>
                            @endif
                        </td>
                        <td>{{ $registro->nombre_completo }}</td>
                        <td>{{ $registro->ci }}</td>
                        <td>
                            @if($registro->fecha_nacimiento)
                                {{ $registro->edad }} años
                            @else
                                <span class="text-muted">N/D</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $registro->cargo }}</span>
                        </td>
                        <td>{{ $registro->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('registros.show', $registro) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            @can('manage-registros')
                                <a href="{{ route('registros.edit', $registro) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('registros.destroy', $registro) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay registros disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection