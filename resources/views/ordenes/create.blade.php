@extends('adminlte::page')
@section('title', 'Nueva Orden del Día')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1><i class="fas fa-calendar-plus"></i> Nueva Orden del Día</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('ordenes.index') }}">Órdenes del Día</a></li>
                <li class="breadcrumb-item active">Nueva</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit"></i> Formulario de Nueva Orden del Día
                </h3>
                <div class="card-tools">
                    <a href="{{ route('ordenes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            
            <form action="{{ route('ordenes.store') }}" method="POST" id="ordenForm">
                @csrf
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
                                       value="{{ old('nombre') }}"
                                       placeholder="Ej: Orden del dia N° 001/2025"
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
                                       value="{{ old('fecha', date('Y-m-d')) }}"
                                       required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>                    <div class="form-group">
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
                            </div>
                        </div>
                        <textarea name="contenido" 
                                  id="editor" 
                                  class="form-control @error('contenido') is-invalid @enderror" 
                                  rows="15" 
                                  required>{{ old('contenido') }}</textarea>
                        <!-- Campo oculto como respaldo -->
                        <input type="hidden" name="contenido_backup" id="contenido_backup" value="{{ old('contenido') }}">
                        @error('contenido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                  <div class="card-footer">
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="fas fa-save"></i> Guardar Orden del Día
                    </button>
                    <a href="{{ route('ordenes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <small class="text-muted ml-3" id="saveStatus" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Guardando...
                    </small>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card-header {
        background: linear-gradient(45deg, #007bff, #0056b3);
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
</style>
@stop

@section('js')
<!-- TinyMCE Editor más avanzado -->
<script src="https://cdn.tiny.cloud/1/730y2nz77ljfyagyqzip070g6s9hhxi0ahyw6lbpx3sqz7g5/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<!-- Para exportar a Word -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html-docx-js/0.4.1/html-docx.js"></script>

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
    setup: function (editor) {        editor.on('change', function () {
            editor.save(); // Sincronizar automáticamente con el textarea
            // También actualizar el campo de respaldo
            setTimeout(() => {
                const contenido = editor.getContent();
                document.querySelector('input[name="contenido_backup"]').value = contenido;
            }, 100);
        });
          // Asegurar sincronización antes del envío del formulario
        editor.on('SaveContent', function () {
            console.log('Contenido guardado:', editor.getContent());
        });
        
        // Sincronizar cuando el editor pierde el foco
        editor.on('blur', function () {
            editor.save();
            const contenido = editor.getContent();
            document.querySelector('input[name="contenido_backup"]').value = contenido;
        });
    },
    // Asegurar que el contenido se sincronice al perder el foco
    auto_save: {
        interval: "30s",
        prefix: "{path}{query}-{id}-",
        retention: "2m",
        restore_when_empty: false
    },
    file_picker_callback: function(callback, value, meta) {
        // Aquí podrías implementar un selector de archivos personalizado
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

// Plantillas predefinidas
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
        tinymce.get('editor').setContent(templates[type]);
        
        // Sincronizar inmediatamente después de insertar la plantilla
        setTimeout(() => {
            tinymce.triggerSave();
            const contenido = tinymce.get('editor').getContent();
            document.querySelector('textarea[name="contenido"]').value = contenido;
            document.querySelector('input[name="contenido_backup"]').value = contenido;
        }, 500);
        
        // Mostrar mensaje de confirmación
        Swal.fire({
            title: 'Plantilla Insertada',
            text: 'La plantilla ha sido insertada en el editor.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
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
                    <p>Documento generado el ${new Date().toLocaleDateString('es-ES')} a las ${new Date().toLocaleTimeString('es-ES')}</p>
                </div>
            </body>
        </html>
    `;
    
    try {
        const converted = htmlDocx.asBlob(wordContent);
        const fileName = `${nombre.replace(/[^a-z0-9]/gi, '_')}_${fecha}.docx`;
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

// Función para verificar conectividad con el servidor
function verificarConectividad() {
    return fetch('{{ route("ordenes.index") }}', {
        method: 'HEAD',
        cache: 'no-cache'
    }).then(response => {
        return response.ok;
    }).catch(() => {
        return false;
    });
}

// Validación del formulario
document.getElementById('ordenForm').addEventListener('submit', function(e) {
    // Sincronizar el contenido de TinyMCE con el textarea antes de validar
    tinymce.triggerSave();
    
    const contenido = tinymce.get('editor').getContent();
    const nombre = document.getElementById('nombre').value.trim();
    const fecha = document.getElementById('fecha').value;
    
    console.log('Validando formulario:', { nombre, fecha, contenido: contenido.substring(0, 100) + '...' });
    
    // Validaciones del lado del cliente
    if (!nombre) {
        e.preventDefault();
        Swal.fire({
            title: 'Nombre Requerido',
            text: 'El nombre de la orden del día es obligatorio.',
            icon: 'warning'
        });
        return false;
    }
    
    if (!fecha) {
        e.preventDefault();
        Swal.fire({
            title: 'Fecha Requerida',
            text: 'La fecha de la orden del día es obligatoria.',
            icon: 'warning'
        });
        return false;
    }
    
    // Actualizar ambos campos con el contenido
    document.querySelector('textarea[name="contenido"]').value = contenido;
    document.querySelector('input[name="contenido_backup"]').value = contenido;
    
    if (!contenido.trim() || contenido.trim().length < 10) {
        e.preventDefault();
        Swal.fire({
            title: 'Contenido Requerido',
            text: 'El contenido de la orden del día es obligatorio y debe tener al menos 10 caracteres.',
            icon: 'warning'
        });
        return false;
    }
    
    // Mostrar indicador de carga
    const submitBtn = document.getElementById('submitBtn');
    const saveStatus = document.getElementById('saveStatus');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    submitBtn.disabled = true;
    saveStatus.style.display = 'inline';
    
    // En caso de error, restaurar el botón después de 10 segundos
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        saveStatus.style.display = 'none';
    }, 10000);
    
    console.log('Formulario válido, enviando...');
    return true;
});

// Auto-guardado cada 2 minutos (opcional)
setInterval(function() {
    const contenido = tinymce.get('editor').getContent();
    if (contenido.trim()) {
        localStorage.setItem('orden_draft', JSON.stringify({
            nombre: document.getElementById('nombre').value,
            fecha: document.getElementById('fecha').value,
            contenido: contenido,
            timestamp: new Date().toISOString()
        }));
        console.log('Borrador guardado automáticamente');
    }
}, 120000);

// Función para verificar el estado del formulario
function verificarEstadoFormulario() {
    const nombre = document.getElementById('nombre').value;
    const fecha = document.getElementById('fecha').value;
    const contenido = tinymce.get('editor').getContent();
    
    console.log('Estado del formulario:', {
        nombre: nombre,
        fecha: fecha,
        contenidoLength: contenido.length,
        contenidoPreview: contenido.substring(0, 100)
    });
    
    return {
        valido: nombre.trim() && fecha && contenido.trim().length >= 10,
        datos: { nombre, fecha, contenido }
    };
}

// Recuperar borrador al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const draft = localStorage.getItem('orden_draft');
    if (draft) {
        const data = JSON.parse(draft);
        const timeDiff = new Date() - new Date(data.timestamp);
        const hoursDiff = timeDiff / (1000 * 60 * 60);
        
        if (hoursDiff < 24) { // Solo si es de las últimas 24 horas
            Swal.fire({
                title: 'Borrador Encontrado',
                text: '¿Desea recuperar el borrador guardado automáticamente?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, recuperar',
                cancelButtonText: 'No, empezar nuevo'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('nombre').value = data.nombre || '';
                    document.getElementById('fecha').value = data.fecha || '';
                    setTimeout(() => {
                        tinymce.get('editor').setContent(data.contenido || '');
                    }, 1000);
                    
                    Swal.fire('Recuperado', 'Se ha restaurado el borrador.', 'success');
                } else {
                    localStorage.removeItem('orden_draft');
                }
            });
        }
    }
});
</script>
@stop
