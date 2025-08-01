@extends('adminlte::page')

@section('title', 'Ver Orden del Día')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Ver Orden del Día</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('ordenes.index') }}">Ordenes del Día</a></li>
                    <li class="breadcrumb-item active">Ver Orden</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Información del documento -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-2"></i>
                        {{ $orden->nombre }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-primary">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Fecha</span>
                                    <span class="info-box-number">{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-user"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Creado por</span>
                                    <span class="info-box-number">{{ $orden->user->name ?? 'Sistema' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                @can('manage-ordenes')
                                    <a href="{{ route('ordenes.edit', $orden) }}" class="btn btn-warning">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </a>
                                @endcan
                                <button type="button" class="btn btn-info" onclick="printDocument()">
                                    <i class="fas fa-print mr-1"></i> Imprimir
                                </button>
                                
                                <button type="button" class="btn btn-secondary" onclick="copyToClipboard()">
                                    <i class="fas fa-copy mr-1"></i> Copiar contenido
                                </button>
                            </div>

                            <div class="btn-group ml-2" role="group">
                                @can('manage-ordenes')
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                        <i class="fas fa-trash mr-1"></i> Eliminar
                                    </button>
                                @endcan
                                
                                <a href="{{ route('ordenes.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido del documento -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-text mr-2"></i>
                        Contenido del Documento
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" onclick="toggleFullscreen()" title="Pantalla completa">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" id="document-content">
                    <div class="document-viewer" style="min-height: 400px; padding: 20px; background: white; border: 1px solid #dee2e6; border-radius: 4px;">
                        {!! $orden->contenido !!}
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card card-outline card-info collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>
                       Mas Información
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Fecha de creación:</strong>
                            <p class="text-muted">{{ $orden->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Última actualización:</strong>
                            <p class="text-muted">{{ $orden->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>ID del documento:</strong>
                            <p class="text-muted">#{{ str_pad($orden->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmación de eliminación -->
@can('manage-ordenes')
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar esta Orden del Día?</p>
                <p><strong>Nombre:</strong> {{ $orden->nombre }}</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('ordenes.destroy', $orden) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i> Eliminar definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@section('css')
<style>
    .document-viewer {
        font-family: 'Times New Roman', serif;
        line-height: 1.6;
        color: #333;
    }

    .document-viewer h1, .document-viewer h2, .document-viewer h3 {
        color: #2c3e50;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .document-viewer p {
        margin-bottom: 12px;
        text-align: justify;
    }

    .document-viewer ul, .document-viewer ol {
        margin-bottom: 15px;
        padding-left: 30px;
    }

    .document-viewer table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
    }

    .document-viewer table th, .document-viewer table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .document-viewer table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .fullscreen-mode {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 9999;
        background: white;
        padding: 20px;
        overflow-y: auto;
    }

    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: .25rem;
        background-color: #fff;
        display: flex;
        margin-bottom: 1rem;
        min-height: 80px;
        padding: .5rem;
        position: relative;
        width: 100%;
    }

    .print-only {
        display: none;
    }

    @media print {
        .no-print {
            display: none !important;
        }
        
        .print-only {
            display: block !important;
        }
        
        .document-viewer {
            border: none !important;
            box-shadow: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

@section('js')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- FileSaver.js -->
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
<!-- html-docx-js -->
<script src="https://cdn.jsdelivr.net/npm/html-docx-js@0.6.1/dist/html-docx.js"></script>

<script>
$(document).ready(function() {
    // Mostrar notificación de carga exitosa
    Swal.fire({
        icon: 'success',
        title: 'Documento cargado',
        text: 'La Orden del Día se ha cargado correctamente',
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
});


    // Crear contenido HTML
    const wordContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>${documentName}</title>
            <style>
                body { font-family: 'Times New Roman', serif; line-height: 1.6; color: #333; margin: 20px; }
                h1, h2, h3 { color: #2c3e50; margin-top: 20px; margin-bottom: 10px; }
                p { margin-bottom: 12px; text-align: justify; }
                .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; margin-bottom: 20px; }
                .document-info { background-color: #f8f9fa; padding: 10px; margin-bottom: 20px; border-left: 4px solid #007bff; }
                table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f8f9fa; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>${documentName}</h1>
                <p>Fecha: ${fecha}</p>
            </div>
            <div class="document-info">
                <p><strong>Documento generado el:</strong> ${new Date().toLocaleDateString('es-ES')}</p>
                <p><strong>Sistema:</strong> Sistema Integrador de Gestión</p>
            </div>
            <div class="document-content">
                ${document.querySelector('.document-viewer').innerHTML}
            </div>
        </body>
        </html>
    `;
    
    try {
        const converted = htmlDocx.asBlob(wordContent);
        saveAs(converted, `${documentName.replace(/[^a-z0-9]/gi, '_')}_${fecha.replace(/\//g, '-')}.docx`);
        
        Swal.fire({
            icon: 'success',
            title: 'Exportación exitosa',
            text: 'El documento se ha exportado a Word correctamente',
            timer: 3000,
            showConfirmButton: false
        });
    } catch (error) {
        console.error('Error al exportar:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de exportación',
            text: 'Hubo un problema al exportar el documento'
        });
    }
}

// Imprimir documento
function printDocument() {
    // Crear ventana de impresión
    const printWindow = window.open('', '_blank');
    const documentName = '{{ $orden->nombre }}';
    const fecha = '{{ \Carbon\Carbon::parse($orden->fecha)->format("d/m/Y") }}';
    
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>${documentName}</title>
            <style>
                body { font-family: 'Times New Roman', serif; line-height: 1.6; color: #333; margin: 20px; }
                h1, h2, h3 { color: #2c3e50; margin-top: 20px; margin-bottom: 10px; }
                p { margin-bottom: 12px; text-align: justify; }
                .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; margin-bottom: 20px; }
                .document-info { background-color: #f8f9fa; padding: 10px; margin-bottom: 20px; border-left: 4px solid #007bff; }
                table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f8f9fa; font-weight: bold; }
                @media print {
                    body { margin: 0; }
                    .header { page-break-after: avoid; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>${documentName}</h1>
                <p>Fecha: ${fecha}</p>
            </div>
            <div class="document-info">
                <p><strong>Documento generado el:</strong> ${new Date().toLocaleDateString('es-ES')}</p>
                <p><strong>Sistema:</strong> Sistema Integrador de Gestión</p>
            </div>
            <div class="document-content">
                ${document.querySelector('.document-viewer').innerHTML}
            </div>
        </body>
        </html>
    `;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
}

// Copiar contenido al portapapeles
function copyToClipboard() {
    const content = document.querySelector('.document-viewer').innerText;
    
    navigator.clipboard.writeText(content).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Contenido copiado',
            text: 'El contenido se ha copiado al portapapeles',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }).catch(function() {
        // Fallback para navegadores más antiguos
        const textArea = document.createElement('textarea');
        textArea.value = content;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        Swal.fire({
            icon: 'success',
            title: 'Contenido copiado',
            text: 'El contenido se ha copiado al portapapeles',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
}

// Pantalla completa
function toggleFullscreen() {
    const documentCard = document.querySelector('.card-outline.card-primary');
    
    if (documentCard.classList.contains('fullscreen-mode')) {
        documentCard.classList.remove('fullscreen-mode');
        document.body.style.overflow = 'auto';
    } else {
        documentCard.classList.add('fullscreen-mode');
        document.body.style.overflow = 'hidden';
    }
}

// Confirmar eliminación
@can('manage-ordenes')
function confirmDelete() {
    $('#deleteModal').modal('show');
}
@endcan

// Cerrar pantalla completa con ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const documentCard = document.querySelector('.card-outline.card-primary');
        if (documentCard.classList.contains('fullscreen-mode')) {
            toggleFullscreen();
        }
    }
});

// Manejo de redimensionamiento de ventana
window.addEventListener('resize', function() {
    const documentCard = document.querySelector('.card-outline.card-primary');
    if (documentCard.classList.contains('fullscreen-mode')) {
        documentCard.style.height = window.innerHeight + 'px';
    }
});
</script>
@endsection
