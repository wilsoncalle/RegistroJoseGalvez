@extends('layouts.app')

@section('title', 'Eliminar Estudiantes en Bloque - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Eliminar Estudiantes en Bloque</h1>
        <div>
        <a href="{{ route('estudiantes.import') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a Importación
            </a>
            <a href="{{ route('estudiantes.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left me-1"></i> Volver a Estudiantes
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-trash me-2"></i>Eliminar Estudiantes por Aula y Fecha</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('estudiantes.bulk-delete') }}" method="POST" id="deleteForm">
                        @csrf
                        <input type="hidden" name="confirm_delete" id="confirm_delete" value="0">
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>¡Atención!</strong> Esta operación eliminará permanentemente los estudiantes seleccionados.
                            <ul class="mt-2">
                                <li>Los estudiantes sin registros académicos serán eliminados permanentemente.</li>
                                <li>Los estudiantes con calificaciones o asistencias serán marcados como "Retirados".</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label for="id_importacion" class="form-label">Seleccione la importación a revertir</label>
                            @if(count($historialImportaciones) > 0)
                                <select class="form-select" id="id_importacion" name="id_importacion" required>
                                    <option value="">Seleccione una importación</option>
                                    @foreach($historialImportaciones as $importacion)
                                        <option value="{{ $importacion['id'] }}" 
                                            data-aula="{{ $importacion['aula_id'] }}"
                                            data-nivel="{{ $importacion['nivel_id'] }}"
                                            data-archivo="{{ $importacion['nombre_archivo'] }}"
                                            data-total="{{ $importacion['total'] }}"
                                            data-anio="{{ $importacion['anio_academico'] }}"
                                            data-usuario="{{ $importacion['usuario'] }}"
                                            data-aula-nombre="{{ $importacion['aula_nombre'] }}"
                                            data-nivel-nombre="{{ $importacion['nivel_nombre'] }}">
                                            Fecha de importación {{ date('d/m/Y', strtotime($importacion['fecha'])) }}, {{ $importacion['nivel_nombre'] }} {{ $importacion['aula_nombre'] }}, Año académico {{ $importacion['anio_academico'] }}, Total {{ $importacion['total'] }} estudiantes
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Seleccione la importación que desea revertir de la lista.</div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    No hay registros de importaciones en el historial.
                                </div>
                            @endif
                        </div>
                        
                        <div id="detallesImportacion" class="card mb-3" style="display: none;">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Detalles de la importación seleccionada</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                    <p><strong><i class="bi bi-file-earmark me-1"></i>Archivo:</strong> <span id="infoArchivo">-</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong><i class="bi bi-people me-1"></i>Total importados:</strong> <span id="infoTotal">-</span> estudiantes</p>
                                        <p><strong><i class="bi bi-calendar me-1"></i>Año académico:</strong> <span id="infoAnio">-</span></p>
                                        <p><strong><i class="bi bi-layers me-1"></i>Nivel:</strong> <span id="infoNivel">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong><i class="bi bi-door-open me-1"></i>Aula:</strong> <span id="infoAula">-</span></p>
                                        <p><strong><i class="bi bi-person me-1"></i>Importado por:</strong> <span id="infoUsuario">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="button" id="btnDelete" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal">
                                <i class="bi bi-trash me-1"></i> Eliminar Estudiantes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información</h5>
                </div>
                <div class="card-body">
                    <p>Use esta herramienta solo para revertir importaciones erróneas.</p>
                    <p>Para eliminar estudiantes individualmente, utilice la opción de eliminar en la lista de estudiantes.</p>
                    <div class="alert alert-primary">
                        <i class="bi bi-lightbulb me-2"></i>
                        <strong>Consejo:</strong> Verifique cuidadosamente el aula y la fecha de importación antes de confirmar.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@php
// Preparar datos de niveles para JavaScript
$nivelesJson = [];
foreach ($niveles as $nivel) {
    $nivelesJson[$nivel->id_nivel] = $nivel->nombre;
}
@endphp

@push('styles')
<style>
    /* Estilos para el select de importaciones */
    #id_importacion {
        width: 50% !important;
        max-width: 50% !important;
        white-space: normal;
    }
    
    #id_importacion option {
        white-space: normal;
        word-wrap: break-word;
        padding: 5px;
        line-height: 1.4;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Para navegadores específicos que necesitan esto */
    @-moz-document url-prefix() {
        #id_importacion {
            width: 50% !important;
        }
    }
    
    @media screen and (-webkit-min-device-pixel-ratio:0) {
        #id_importacion {
            width: 50% !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Referencias a elementos DOM
        const importacionSelect = document.getElementById('id_importacion');
        const detallesImportacion = document.getElementById('detallesImportacion');
        const infoArchivo = document.getElementById('infoArchivo');
        const infoTotal = document.getElementById('infoTotal');
        const infoNivel = document.getElementById('infoNivel');
        const infoAula = document.getElementById('infoAula');
        const infoAnio = document.getElementById('infoAnio');
        const infoUsuario = document.getElementById('infoUsuario');
        const deleteForm = document.getElementById('deleteForm');
        const confirmDelete = document.getElementById('confirm_delete');
        const btnConfirmDelete = document.getElementById('btnConfirmDelete');
        const btnDelete = document.getElementById('btnDelete');
        
        // Mapa de niveles para mostrar el nombre (cargado desde PHP)
        const nivelesData = JSON.parse('{!! json_encode($nivelesJson) !!}');
        
        // Deshabilitar el botón de eliminar si no hay importaciones
        if (importacionSelect && importacionSelect.options.length <= 1) {
            btnDelete.disabled = true;
            btnDelete.classList.add('disabled');
            btnDelete.innerHTML = '<i class="bi bi-trash me-1"></i> No hay importaciones para eliminar';
        }
        
        // Cuando cambia la selección de importación, mostrar los detalles
        if (importacionSelect) {
            importacionSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (this.value) {
                    // Mostrar tarjeta de detalles
                    detallesImportacion.style.display = 'block';
                    
                    // Obtener datos del data-attribute
                    const archivo = selectedOption.dataset.archivo || 'No disponible';
                    const total = selectedOption.dataset.total || '0';
                    const nivelId = selectedOption.dataset.nivel;
                    const aulaId = selectedOption.dataset.aula;
                    const anioAcademico = selectedOption.dataset.anio || 'No disponible';
                    const usuario = selectedOption.dataset.usuario || 'Sistema';
                    
                    // Actualizar la información mostrada
                    infoArchivo.textContent = archivo;
                    infoTotal.textContent = total;
                    infoAnio.textContent = anioAcademico;
                    infoUsuario.textContent = usuario;
                    
                    // Mostrar el nombre del nivel si está disponible
                    if (nivelId && nivelesData[nivelId]) {
                        infoNivel.textContent = nivelesData[nivelId];
                    } else {
                        infoNivel.textContent = 'No especificado';
                    }
                    
                    // Obtener el nombre completo del aula directamente del atributo data
                    const aulaNombre = selectedOption.dataset.aulaNombre;
                    if (aulaNombre) {
                        infoAula.textContent = aulaNombre;
                    } else {
                        // Fallback al método anterior en caso de que no esté disponible
                        const descripcion = selectedOption.textContent.trim();
                        const aulaMatch = descripcion.match(/Aula: ([^-]+)/);
                        
                        if (aulaMatch && aulaMatch[1]) {
                            infoAula.textContent = aulaMatch[1].trim();
                        } else {
                            infoAula.textContent = 'No especificada';
                        }
                    }
                    
                    // Actualizar el texto del botón para mostrar el número de estudiantes
                    btnDelete.innerHTML = `<i class="bi bi-trash me-1"></i> Eliminar ${total} Estudiantes`;
                    
                    // Actualizar información en el modal
                    document.getElementById('selectedImportacion').textContent = descripcion;
                } else {
                    // Ocultar tarjeta de detalles si no hay selección
                    detallesImportacion.style.display = 'none';
                    btnDelete.innerHTML = '<i class="bi bi-trash me-1"></i> Eliminar Estudiantes';
                }
            });
        }
        
        // Al hacer clic en el botón de confirmar en el modal
        btnConfirmDelete.addEventListener('click', function() {
            confirmDelete.value = '1';
            deleteForm.submit();
        });
    });
</script>
@endpush

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>¡Advertencia!</strong> Está a punto de eliminar estudiantes de forma masiva.
                </div>
                <p>Esta acción no se puede deshacer. ¿Está seguro que desea continuar?</p>
                <div id="deleteDetails">
                    <p><strong>Importación seleccionada:</strong> <span id="selectedImportacion">No seleccionada</span></p>
                </div>
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>
                        Los estudiantes con registros académicos (calificaciones o asistencias) serán marcados como retirados en lugar de ser eliminados permanentemente para mantener la integridad de los registros históricos.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnConfirmDelete" class="btn btn-danger">Confirmar Eliminación</button>
            </div>
        </div>
    </div>
</div>
