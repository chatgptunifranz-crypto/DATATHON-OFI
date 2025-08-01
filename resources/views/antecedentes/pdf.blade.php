<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antecedente - {{ $registro->ci }}</title>
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
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 10px;
            border-bottom: 1px dotted #ddd;
            padding-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            width: 180px;
            color: #2c3e50;
        }
        
        .info-value {
            flex: 1;
        }
        
        .antecedentes-section {
            margin-top: 30px;
            border: 2px solid #34495e;
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .antecedentes-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
            text-transform: uppercase;
        }
        
        .antecedentes-content {
            white-space: pre-wrap;
            line-height: 1.8;
            text-align: justify;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.1);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="watermark">CONFIDENCIAL</div>
    
    <div class="header">
        <h1>REPORTE DE ANTECEDENTES</h1>
        <p>Sistema Integrador Policial</p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
    
    <div class="info-section">
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
    </div>
    
    <div class="antecedentes-section">
        <div class="antecedentes-title">Información de Antecedentes</div>
        <div class="antecedentes-content">
            @if($registro->antecedentes)
                {{ $registro->antecedentes }}
            @else
                No se han registrado antecedentes para esta persona.
            @endif
        </div>
    </div>
    
    <div class="footer">
        <p><strong>DOCUMENTO CONFIDENCIAL</strong></p>
        <p>Este documento contiene información clasificada y debe ser tratado con la debida confidencialidad.</p>
        <p>Generado por: {{ auth()->user()->name ?? 'Sistema' }} | Usuario: {{ auth()->user()->email ?? 'N/A' }}</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html>
