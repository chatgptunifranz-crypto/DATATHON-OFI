@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('You are logged in!') }}
                </div>
            </div>
            <!-- Panel de acceso rápido a Registros -->
            <div class="card">
                <div class="card-header">Gestión de Registros</div>
                <div class="card-body">
                    <a href="{{ route('registros.index') }}" class="btn btn-primary mb-2">Ver Registros</a>
                    @can('manage-registros')
                        <a href="{{ route('registros.create') }}" class="btn btn-success mb-2">Nuevo Registro</a>
                    @endcan
                    @can('view-reportes')
                        <a href="{{ route('registros.reportes') }}" class="btn btn-secondary mb-2">Ver Reportes</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
