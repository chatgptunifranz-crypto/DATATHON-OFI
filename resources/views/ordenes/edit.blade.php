@extends('adminlte::page')
@section('title', 'Editar Orden del Día')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1><i class="fas fa-edit"></i> Editar Orden del Día</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ordenes.index') }}">Órdenes del Día</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit"></i> Editando: {{ $orden->nombre }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('ordenes.show', $orden) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Ver
                    </a>
                    <a href="{{ route('ordenes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            
            <form action="{{ route('ordenes.update', $orden) }}" method="POST" id="ordenEditForm">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre">Nombre de la Orden <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="nombre" 
                                       id="nombre"
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       value="{{ old('nombre', $orden->nombre) }}"
                                       placeholder="Ej: Orden del Día - Operativo Especial"
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha">Fecha <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="fecha" 
                                       id="fecha"
                                       class="form-control @error('fecha') is-invalid @enderror" 
                                       value="{{ old('fecha', $orden->fecha) }}"
                                       required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenido">Contenido <span class="text-danger">*</span></label>
                        <div class="mb-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="insertTemplate('operativo')">
                                    <i class="fas fa-shield-alt"></i> Plantilla Operativo
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="insertTemplate('reunion')">
                                    <i class="fas fa-users"></i> Plantilla Reunión
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="insertTemplate('comunicado')">
                                    <i class="fas fa-bullhorn"></i> Plantilla Comunicado
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportToWord()">
                                    <i class="fas fa-file-word"></i> Exportar a Word
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="generatePdf()">
                                    <i class="fas fa-file-pdf"></i> Generar PDF
                                </button>
                            </div>
                        </div>
                        <textarea name="contenido" 
                                  id="editor" 
                                  class="form-control @error('contenido') is-invalid @enderror" 
                                  rows="15" 
                                  required>{{ old('contenido', $orden->contenido) }}</textarea>
                        @error('contenido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Use el editor para formatear el contenido. Puede insertar plantillas predefinidas o exportar directamente a Word/PDF.
                        </small>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Actualizar Orden del Día
                    </button>
                    <button type="button" class="btn btn-info" onclick="previewContent()">
                        <i class="fas fa-eye"></i> Vista Previa
                    </button>
                    <a href="{{ route('ordenes.show', $orden) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> Ver Original
                    </a>
                    <a href="{{ route('ordenes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Información adicional -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Información del Documento</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Creado:</strong> {{ $orden->created_at->format('d/m/Y H:i:s') }}<br>
                        <strong>Última actualización:</strong> {{ $orden->updated_at->format('d/m/Y H:i:s') }}
                    </div>
                    <div class="col-md-6">
                        <strong>ID del documento:</strong> {{ $orden->id }}<br>
                        <strong>Última modificación por:</strong> Sistema
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Vista Previa -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vista Previa - {{ $orden->nombre }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Contenido de vista previa -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="exportToWord()">
                    <i class="fas fa-file-word"></i> Exportar a Word
                </button>
                <button type="button" class="btn btn-danger" onclick="generatePdf()">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card-header {
        background: linear-gradient(45deg, #ffc107, #ff8f00);
        color: white;
    }
    
    .cke_top {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .form-group label {
        font-weight: 600;
        color: #495057;
    }
    
    .btn-group .btn {
        margin-right: 5px;
    }
    
    .preview-content {
        background: white;
        padding: 20px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        min-height: 200px;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .invalid-feedback {
        display: block;
    }
    
    #previewContent {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .card-secondary .card-header {
        background: #6c757d;
        color: white;
    }
</style>
@stop

@section('js')
<!-- TinyMCE Editor más avanzado -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<!-- Para exportar a Word -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html-docx-js/0.4.1/html-docx.js"></script>
<!-- Para generar PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
tinymce.init({
    selector: '#editor',
    height: 400,
    language: 'es',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount', 'export'
    ],
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
             'alignleft aligncenter alignright alignjustify | ' +
             'bullist numlist outdent indent | removeformat | ' +
             'forecolor backcolor | table | link image | ' +
             'preview code fullscreen | help',
    menubar: 'file edit view insert format tools table help',
    branding: false,
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    },
    file_picker_callback: function(callback, value, meta) {
        if (meta.filetype === 'image') {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function() {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function () {
                    callback(reader.result, {
                        alt: file.name
                    });
                };
                reader.readAsDataURL(file);
            };
            input.click();
        }
    }
});

// Plantillas predefinidas (mismas que en create)
const templates = {
    operativo: `
        <h2><strong>ORDEN DEL DÍA - OPERATIVO ESPECIAL</strong></h2>
        <p><strong>Fecha:</strong> ${new Date().toLocaleDateString('es-ES')}</p>
        <p><strong>Unidad:</strong> Policía La Paz</p>
        
        <h3>1. OBJETIVO</h3>
        <p>Descripción del objetivo del operativo...</p>
        
        <h3>2. PERSONAL ASIGNADO</h3>
        <ul>
            <li>Oficial a cargo:</li>
            <li>Personal de apoyo:</li>
        </ul>
        
        <h3>3. HORARIOS Y UBICACIONES</h3>
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <tr>
                <th>Hora</th>
                <th>Actividad</th>
                <th>Responsable</th>
            </tr>
            <tr>
                <td>08:00</td>
                <td>Inicio de operativo</td>
                <td></td>
            </tr>
        </table>
        
        <h3>4. RECURSOS NECESARIOS</h3>
        <ul>
            <li>Vehículos:</li>
            <li>Equipamiento:</li>
            <li>Comunicaciones:</li>
        </ul>
        
        <h3>5. OBSERVACIONES</h3>
        <p>Indicaciones especiales...</p>
    `,
    reunion: `
        <h2><strong>ORDEN DEL DÍA - REUNIÓN</strong></h2>
        <p><strong>Fecha:</strong> ${new Date().toLocaleDateString('es-ES')}</p>
        <p><strong>Hora:</strong> </p>
        <p><strong>Lugar:</strong> </p>
        
        <h3>AGENDA</h3>
        <ol>
            <li><strong>Verificación de asistencia</strong></li>
            <li><strong>Lectura del acta anterior</strong></li>
            <li><strong>Asuntos tratados:</strong>
                <ul>
                    <li>Punto 1</li>
                    <li>Punto 2</li>
                    <li>Punto 3</li>
                </ul>
            </li>
            <li><strong>Asuntos varios</strong></li>
            <li><strong>Próxima reunión</strong></li>
        </ol>
        
        <h3>PARTICIPANTES</h3>
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <tr>
                <th>Nombre</th>
                <th>Cargo</th>
                <th>Firma</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    `,
    comunicado: `
        <div style="text-align: center;">
            <h2><strong>COMUNICADO OFICIAL</strong></h2>
            <p><strong>POLICÍA LA PAZ</strong></p>
        </div>
        
        <p><strong>Fecha:</strong> ${new Date().toLocaleDateString('es-ES')}</p>
        <p><strong>Para:</strong> Todo el personal</p>
        <p><strong>De:</strong> Comando General</p>
        <p><strong>Asunto:</strong> </p>
        
        <h3>COMUNICACIÓN:</h3>
        <p>Se comunica a todo el personal que...</p>
        
        <h3>DISPOSICIONES:</h3>
        <ol>
            <li>Primera disposición</li>
            <li>Segunda disposición</li>
            <li>Tercera disposición</li>
        </ol>
        
        <p><strong>Esta orden tiene vigencia inmediata.</strong></p>
        
        <div style="margin-top: 50px;">
            <p style="text-align: center;">
                <strong>____________________</strong><br>
                Firma y Sello<br>
                Comando General
            </p>
        </div>
    `
};

function insertTemplate(type) {
    if (templates[type]) {
        Swal.fire({
            title: '¿Reemplazar contenido?',
            text: 'Esto reemplazará todo el contenido actual. ¿Está seguro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, reemplazar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                tinymce.get('editor').setContent(templates[type]);
                Swal.fire('Plantilla Insertada', 'La plantilla ha sido insertada en el editor.', 'success');
            }
        });
    }
}

function previewContent() {
    const nombre = document.getElementById('nombre').value;
    const fecha = document.getElementById('fecha').value;
    const contenido = tinymce.get('editor').getContent();
    
    if (!nombre || !fecha || !contenido) {
        Swal.fire({
            title: 'Campos Incompletos',
            text: 'Por favor complete todos los campos antes de la vista previa.',
            icon: 'warning'
        });
        return;
    }
    
    const previewHtml = `
        <div style="font-family: Arial, sans-serif; line-height: 1.6;">
            <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px;">
                <h2 style="margin: 0; color: #333;">${nombre}</h2>
                <p style="margin: 5px 0; color: #666;">Fecha: ${new Date(fecha).toLocaleDateString('es-ES')}</p>
            </div>
            <div style="margin-top: 20px;">
                ${contenido}
            </div>
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = previewHtml;
    $('#previewModal').modal('show');
}

function exportToWord() {
    const nombre = document.getElementById('nombre').value;
    const fecha = document.getElementById('fecha').value;
    const contenido = tinymce.get('editor').getContent();
    
    if (!nombre || !fecha || !contenido) {
        Swal.fire({
            title: 'Campos Incompletos',
            text: 'Por favor complete todos los campos antes de exportar.',
            icon: 'warning'
        });
        return;
    }
    
    const wordContent = `
        <html>
            <head>
                <meta charset="utf-8">
                <title>${nombre}</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        line-height: 1.6; 
                        margin: 40px;
                        color: #333;
                    }
                    h1, h2, h3 { color: #2c3e50; }
                    table { border-collapse: collapse; width: 100%; margin: 15px 0; }
                    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                    th { background-color: #f8f9fa; font-weight: bold; }
                    .header { 
                        text-align: center; 
                        margin-bottom: 30px; 
                        border-bottom: 3px solid #2c3e50; 
                        padding-bottom: 20px; 
                    }
                    .footer {
                        margin-top: 50px;
                        border-top: 1px solid #ddd;
                        padding-top: 20px;
                        font-size: 12px;
                        color: #666;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>${nombre}</h1>
                    <p><strong>Fecha:</strong> ${new Date(fecha).toLocaleDateString('es-ES')}</p>
                    <p><strong>Policía La Paz</strong></p>
                </div>
                <div class="content">
                    ${contenido}
                </div>
                <div class="footer">
                    <p>Documento actualizado el ${new Date().toLocaleDateString('es-ES')} a las ${new Date().toLocaleTimeString('es-ES')}</p>
                    <p>ID del documento: {{ $orden->id }}</p>
                </div>
            </body>
        </html>
    `;
    
    try {
        const converted = htmlDocx.asBlob(wordContent);
        const fileName = `${nombre.replace(/[^a-z0-9]/gi, '_')}_${fecha}_actualizado.docx`;
        saveAs(converted, fileName);
        
        Swal.fire({
            title: 'Exportación Exitosa',
            text: 'El documento Word ha sido descargado.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    } catch (error) {
        console.error('Error al exportar:', error);
        Swal.fire({
            title: 'Error de Exportación',
            text: 'Hubo un problema al generar el documento Word.',
            icon: 'error'
        });
    }
}

function generatePdf() {
    const nombre = document.getElementById('nombre').value;
    const fecha = document.getElementById('fecha').value;
    const contenido = tinymce.get('editor').getContent();
    
    if (!nombre || !fecha || !contenido) {
        Swal.fire({
            title: 'Campos Incompletos',
            text: 'Por favor complete todos los campos antes de generar PDF.',
            icon: 'warning'
        });
        return;
    }
    
    // Crear ventana de impresión
    const printWindow = window.open('', '_blank');
    const printContent = `
        <!DOCTYPE html>
        <html>
            <head>
                <title>${nombre}</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        line-height: 1.6; 
                        margin: 40px;
                        color: #333;
                    }
                    h1, h2, h3 { color: #2c3e50; }
                    table { border-collapse: collapse; width: 100%; margin: 15px 0; }
                    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                    th { background-color: #f8f9fa; font-weight: bold; }
                    .header { 
                        text-align: center; 
                        margin-bottom: 30px; 
                        border-bottom: 3px solid #2c3e50; 
                        padding-bottom: 20px; 
                    }
                    .footer {
                        margin-top: 50px;
                        border-top: 1px solid #ddd;
                        padding-top: 20px;
                        font-size: 12px;
                        color: #666;
                    }
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>${nombre}</h1>
                    <p><strong>Fecha:</strong> ${new Date(fecha).toLocaleDateString('es-ES')}</p>
                    <p><strong>Policía La Paz</strong></p>
                </div>
                <div class="content">
                    ${contenido}
                </div>
                <div class="footer">
                    <p>Documento generado el ${new Date().toLocaleDateString('es-ES')} a las ${new Date().toLocaleTimeString('es-ES')}</p>
                    <p>ID del documento: {{ $orden->id }}</p>
                </div>
            </body>
        </html>
    `;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.print();
}

// Validación del formulario
document.getElementById('ordenEditForm').addEventListener('submit', function(e) {
    const contenido = tinymce.get('editor').getContent();
    if (!contenido.trim()) {
        e.preventDefault();
        Swal.fire({
            title: 'Contenido Requerido',
            text: 'El contenido de la orden del día es obligatorio.',
            icon: 'warning'
        });
        return false;
    }
});

// Auto-guardado cada 3 minutos para edición
setInterval(function() {
    const contenido = tinymce.get('editor').getContent();
    if (contenido.trim()) {
        localStorage.setItem('orden_edit_{{ $orden->id }}', JSON.stringify({
            nombre: document.getElementById('nombre').value,
            fecha: document.getElementById('fecha').value,
            contenido: contenido,
            timestamp: new Date().toISOString()
        }));
        
        // Mostrar indicador de guardado
        const indicator = document.createElement('div');
        indicator.className = 'alert alert-success alert-dismissible fade show position-fixed';
        indicator.style.cssText = 'top: 10px; right: 10px; z-index: 9999; min-width: 200px;';
        indicator.innerHTML = `
            <small><i class="fas fa-check"></i> Guardado automático</small>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        `;
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            if (indicator.parentNode) {
                indicator.parentNode.removeChild(indicator);
            }
        }, 2000);
    }
}, 180000);
</script>
@stop
