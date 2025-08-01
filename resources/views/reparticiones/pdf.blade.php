<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaciones de Personal Policial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 28px;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .header p {
            margin: 5px 0;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .summary {
            background-color: #ecf0f1;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        
        .summary h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .summary-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        
        .stat-item {
            text-align: center;
            flex: 1;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
        }
        
        .stat-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
        }
        
        .zona-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .zona-header {
            background-color: #34495e;
            color: white;
            padding: 12px 15px;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 16px;
            border-radius: 5px;
        }
        
        .asignaciones-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .asignaciones-table th {
            background-color: #3498db;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .asignaciones-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
            vertical-align: top;
        }
        
        .asignaciones-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .policia-name {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 2px;
        }
        
        .policia-email {
            color: #7f8c8d;
            font-size: 10px;
        }
        
        .horario-badge {
            background-color: #e74c3c;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .fecha-badge {
            background-color: #27ae60;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .observaciones {
            font-style: italic;
            color: #7f8c8d;
            max-width: 150px;
            word-wrap: break-word;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(52, 73, 94, 0.1);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="watermark">POLICIA BOLIVIANA</div>
    
    <div class="header">
        <h1>Asignaciones de Personal Policial</h1>
        <p>Sistema Integrador - Repartición de Personal</p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
    
    <div class="summary">
        <h3>Resumen de Asignaciones</h3>
        <div class="summary-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $reparticiones->count() }}</div>
                <div class="stat-label">Total Asignaciones</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $zonas->count() }}</div>
                <div class="stat-label">Zonas Cubiertas</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $reparticiones->unique('user_id')->count() }}</div>
                <div class="stat-label">Policías Asignados</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $reparticiones->where('fecha_asignacion', today())->count() }}</div>
                <div class="stat-label">Asignaciones Hoy</div>
            </div>
        </div>
    </div>
    
    @if($reparticiones->count() > 0)
        @foreach($zonas as $zona => $asignacionesZona)
            <div class="zona-section">
                <div class="zona-header">
                    <i class="fas fa-map-marker-alt"></i> ZONA: {{ $zona }}
                    ({{ $asignacionesZona->count() }} asignación{{ $asignacionesZona->count() != 1 ? 'es' : '' }})
                </div>
                
                <table class="asignaciones-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Policía Asignado</th>
                            <th style="width: 15%;">Fecha</th>
                            <th style="width: 20%;">Horario de Servicio</th>
                            <th style="width: 40%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asignacionesZona as $asignacion)
                            <tr>
                                <td>
                                    <div class="policia-name">{{ $asignacion->user->name }}</div>
                                    <div class="policia-email">{{ $asignacion->user->email }}</div>
                                </td>
                                <td>
                                    <span class="fecha-badge">
                                        {{ $asignacion->fecha_asignacion->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="horario-badge">
                                        {{ $asignacion->horario_inicio }} - {{ $asignacion->horario_fin }}
                                    </span>
                                </td>
                                <td>
                                    @if($asignacion->observaciones)
                                        <div class="observaciones">{{ $asignacion->observaciones }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if(!$loop->last)
                <div style="margin-bottom: 20px;"></div>
            @endif
        @endforeach
        
        <!-- Resumen por zona -->
        <div class="zona-section page-break">
            <div class="zona-header">
                RESUMEN POR ZONA
            </div>
            
            <table class="asignaciones-table">
                <thead>
                    <tr>
                        <th>Zona</th>
                        <th>Total Asignaciones</th>
                        <th>Policías Únicos</th>
                        <th>Cobertura Horaria</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zonas as $zona => $asignacionesZona)
                        <tr>
                            <td><strong>{{ $zona }}</strong></td>
                            <td>{{ $asignacionesZona->count() }}</td>
                            <td>{{ $asignacionesZona->unique('user_id')->count() }}</td>
                            <td>
                                @php
                                    $horarios = $asignacionesZona->map(function($a) {
                                        return $a->horario_inicio . ' - ' . $a->horario_fin;
                                    })->unique()->implode(', ');
                                @endphp
                                {{ Str::limit($horarios, 40) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    @else
        <div class="no-data">
            <h3>No hay asignaciones de personal registradas</h3>
            <p>No se encontraron asignaciones activas para mostrar en este reporte.</p>
        </div>
    @endif
    
    <div class="footer">
        <p><strong>Sistema Integrador Policial</strong></p>
        <p>Generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i:s') }} | Documento confidencial</p>
        <p>Total de registros: {{ $reparticiones->count() }}</p>
    </div>
</body>
</html>
