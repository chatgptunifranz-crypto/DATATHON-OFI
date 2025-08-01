@extends('adminlte::page')

@section('title', 'Ver Antecedente')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Detalle del Antecedente</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('antecedentes.index') }}">Antecedentes</a></li>
                <li class="breadcrumb-item active">Ver</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información del Antecedente - CI: {{ $registro->ci }}</h3>
                    <div class="card-tools">                        <a href="{{ route('antecedentes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <a href="{{ route('antecedentes.pdf', $registro->id) }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-id-card"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cédula de Identidad</span>
                                    <span class="info-box-number">{{ $registro->ci }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">                                    <span class="info-box-text">Nombre Completo</span>
                                    <span class="info-box-number">{{ $registro->nombres }} {{ $registro->apellido_paterno }} {{ $registro->apellido_materno }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Cargo:</strong></label>
                                <p class="form-control-static">{{ $registro->cargo }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Descripción:</strong></label>
                                <p class="form-control-static">{{ $registro->descripcion ?: 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($registro->longitud && $registro->latitud)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Longitud:</strong></label>
                                <p class="form-control-static">{{ $registro->longitud }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Latitud:</strong></label>
                                <p class="form-control-static">{{ $registro->latitud }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label><strong>Antecedentes:</strong></label>
                                <div class="card">
                                    <div class="card-body">
                                        <p style="white-space: pre-wrap;">{{ $registro->antecedentes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Fecha de Registro:</strong></label>
                                <p class="form-control-static">{{ $registro->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Última Actualización:</strong></label>
                                <p class="form-control-static">{{ $registro->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                <a href="{{ route('antecedentes.edit', $registro->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="{{ route('antecedentes.pdf', $registro->id) }}" class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Generar PDF
                                </a>
                                <button type="button" class="btn btn-info" onclick="printAntecedente()">
                                    <i class="fas fa-print"></i> Imprimir
                                </button>
                            </div>
                            
                            <div class="float-right">
                                <form action="{{ route('antecedentes.destroy', $registro->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de eliminar este antecedente? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-trash"></i> Eliminar Antecedente
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    function printAntecedente() {
        // Crear ventana de impresión con el contenido del antecedente
        const printWindow = window.open('', '_blank');
        const content = `
            <html>
                <head>
                    <title>Antecedente - CI: {{ $registro->ci }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .info-row { display: flex; margin-bottom: 15px; }
                        .info-label { font-weight: bold; width: 150px; }
                        .antecedentes { border: 1px solid #ccc; padding: 15px; margin-top: 20px; }
                        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>REPORTE DE ANTECEDENTES</h2>
                        <p>Fecha de generación: ${new Date().toLocaleDateString('es-ES')}</p>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">CI:</span>
                        <span>{{ $registro->ci }}</span>
                    </div>
                      <div class="info-row">
                        <span class="info-label">Nombre:</span>
                        <span>{{ $registro->nombres }} {{ $registro->apellido_paterno }} {{ $registro->apellido_materno }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Fecha de Nacimiento:</span>
                        <span>{{ $registro->fecha_nacimiento ? $registro->fecha_nacimiento->format('d/m/Y') : 'No especificada' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Estado Civil:</span>
                        <span>{{ ucfirst(str_replace('_', ' ', $registro->estado_civil)) ?: 'No especificado' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Profesión:</span>
                        <span>{{ $registro->profesion ?: 'No especificada' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Domicilio:</span>
                        <span>{{ $registro->domicilio ?: 'No especificado' }}</span>
                    </div>
                    
                    <div class="antecedentes">
                        <strong>ANTECEDENTES:</strong><br><br>
                        <div style="white-space: pre-wrap;">{{ $registro->antecedentes }}</div>
                    </div>
                    
                    <div class="footer">
                        <p>Registro generado el {{ $registro->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </body>
            </html>
        `;
        
        printWindow.document.write(content);
        printWindow.document.close();
        printWindow.print();
    }
</script>
@stop
