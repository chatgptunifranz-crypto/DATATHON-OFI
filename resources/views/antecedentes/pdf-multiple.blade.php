<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Múltiple de Antecedentes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #7f8c8d;
        }
        
        .registro {
            margin-bottom: 40px;
            border: 1px solid #ddd;
            padding: 20px;
            page-break-inside: avoid;
        }
        
        .registro-header {
            background-color: #34495e;
            color: white;
            padding: 10px;
            margin: -20px -20px 20px -20px;
            font-weight: bold;
            font-size: 16px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
            border-bottom: 1px dotted #ddd;
            padding-bottom: 3px;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #2c3e50;
        }
        
        .info-value {
            flex: 1;
        }
        
        .antecedentes-content {
            margin-top: 15px;
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #3498db;
            white-space: pre-wrap;
            line-height: 1.6;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .summary {
            background-color: #ecf0f1;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        
        .summary h3 {
            margin-top: 0;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE MÚLTIPLE DE ANTECEDENTES</h1>
        <p>Sistema Integrador Policial</p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
    
    <div class="summary">
        <h3>Resumen del Reporte</h3>
        <p><strong>Total de registros:</strong> {{ $registros->count() }}</p>
        <p><strong>Generado por:</strong> {{ auth()->user()->name ?? 'Sistema' }}</p>
        <p><strong>Usuario:</strong> {{ auth()->user()->email ?? 'N/A' }}</p>
    </div>
    
    @foreach($registros as $index => $registro)
        @if($index > 0)
            <div class="page-break"></div>
        @endif
        
        <div class="registro">
            <div class="registro-header">
                Registro {{ $index + 1 }} de {{ $registros->count() }} - CI: {{ $registro->ci }} - {{ $registro->nombres }} {{ $registro->apellido_paterno }} {{ $registro->apellido_materno }}
            </div>
            
            <div class="info-row">
                <span class="info-label">Cédula de Identidad:</span>
                <span class="info-value">{{ $registro->ci }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Nombre Completo:</span>
                <span class="info-value">{{ $registro->nombres }} {{ $registro->apellido_paterno }} {{ $registro->apellido_materno }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Cargo:</span>
                <span class="info-value">{{ $registro->cargo }}</span>
            </div>
            
            @if($registro->descripcion)
            <div class="info-row">
                <span class="info-label">Descripción:</span>
                <span class="info-value">{{ $registro->descripcion }}</span>
            </div>
            @endif
            
            @if($registro->longitud && $registro->latitud)
            <div class="info-row">
                <span class="info-label">Coordenadas:</span>
                <span class="info-value">Lat: {{ $registro->latitud }}, Lng: {{ $registro->longitud }}</span>
            </div>
            @endif
            
            <div class="info-row">
                <span class="info-label">Fecha de Registro:</span>
                <span class="info-value">{{ $registro->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Última Actualización:</span>
                <span class="info-value">{{ $registro->updated_at->format('d/m/Y H:i:s') }}</span>
            </div>
            
            <div class="antecedentes-content">
                <strong>ANTECEDENTES:</strong><br>
                @if($registro->antecedentes)
                    {{ $registro->antecedentes }}
                @else
                    No se han registrado antecedentes para esta persona.
                @endif
            </div>
        </div>
    @endforeach
    
    <div class="footer">
        <p><strong>DOCUMENTO CONFIDENCIAL</strong></p>
        <p>Este documento contiene información clasificada y debe ser tratado con la debida confidencialidad.</p>
        <p>Total de páginas: {{ $registros->count() + 1 }}</p>
    </div>
</body>
</html>
