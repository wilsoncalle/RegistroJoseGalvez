@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4"> 
        <div class="col">
            <!-- Sección de breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('calificaciones-old.index') }}">Niveles</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('calificaciones-old.index-nivel', $aula->nivel->nombre) }}">{{ $aula->nivel->nombre }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $aula->grado->nombre }} "{{ $aula->seccion->nombre }}"
                    </li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <!-- Título -->
                <div>
                    <h2>Gestión de Calificaciones - {{ $aula->nivel->nombre }} {{ $aula->grado->nombre }} "{{ $aula->seccion->nombre }}"</h2>
                </div>

                <!-- Botón de exportar -->
                <div class="d-flex align-items-center">
                    <button class="btn btn-primary text-white me-2 shadow-sm" type="button" id="estadisticasBtn" disabled>
                        <i class="bi bi-bar-chart-fill me-1"></i> Estadísticas
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-warning dropdown-toggle text-white" type="button" id="exportDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false" disabled>
                            <i class="bi bi-download me-1"></i> Exportar
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                            <li>
                                <a class="dropdown-item text-success" href="#" id="export-excel-btn">
                                    <i class="bi bi-file-earmark-excel me-2 text-success"></i> Exportar a Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" id="export-pdf-btn">
                                    <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> Exportar a PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Botones de modo -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="btn-group mode-selector" role="group">
                <input type="radio" class="btn-check" name="viewMode" id="modeRead" value="read" checked>
                <label class="btn btn-outline-primary" for="modeRead">Modo Lectura</label>
                
                <input type="radio" class="btn-check" name="viewMode" id="modeEdit" value="edit">
                <label class="btn btn-outline-success" for="modeEdit">Modo Edición</label>
            </div>
        </div>
    </div>

    <!-- Contenedor para mensajes de alerta -->
    <div id="alertContainer" class="row mb-3" style="display: none;">
        <div class="col-md-12">
            <div id="alertMessage" class="alert alert-dismissible fade show" role="alert">
                <span id="alertText"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Filtros superiores -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="form-group">
                <label for="promocion">Promoción (Año de Ingreso)</label>
                <select class="form-control" id="promocion" name="promocion">
                    <option value="">Todas las promociones</option>
                    @php
                        $currentYear = date('Y');
                        for ($year = $currentYear; $year >= $currentYear - 30; $year--) {
                            echo "<option value=\"$year\">$year</option>";
                        }
                    @endphp
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="año">Año Académico</label>
                <select class="form-control" id="año" name="año" required>
                    <option value="">Seleccione un año</option>
                    @php
                        $currentYear = date('Y');
                        for ($year = $currentYear; $year >= $currentYear - 30; $year--) {
                            echo "<option value=\"$year\">$year</option>";
                        }
                    @endphp
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="id_trimestre">Trimestre</label>
                <select class="form-control" id="id_trimestre" name="id_trimestre" required>
                    <option value="">Seleccione un trimestre</option>
                    @foreach($trimestres as $trimestre)
                        <option value="{{ $trimestre->id_trimestre }}">
                            {{ $trimestre->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>  
    </div>

    <!-- Mensaje de carga -->
    <div id="loading-message" class="alert alert-info" style="width: 425px; text-align: center">
        Seleccione los filtros para cargar las calificaciones
    </div>

    <!-- Contenedor de la tabla de calificaciones -->
    <div id="calificaciones-container" class="d-none">
        <div id="edit-mode-controls" class="mb-3">
            <button id="save-calificaciones-btn" class="btn btn-primary">Guardar Cambios</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="calificaciones-table">
                <thead class="bg-primary text-white">
                    <tr>
                        <th rowspan="2" class="align-middle text-left" style="width: 45px; padding: 6px">
                            <div class="vertical-text">N° Orden</div>
                        </th>
                        <th rowspan="2" class="align-middle text-left" style="width: 45px; padding: 6px">
                            <div class="vertical-text">N° Matrícula</div>
                        </th>
                        <th rowspan="2" class="align-middle text-left" style="width: 45px; padding: 6px">
                            <div class="vertical-text">Condición</div>
                        </th>
                        <th rowspan="2" class="align-bottom text-center">Apellidos y Nombres</th>
                        <th id="asignaturas-header" colspan="1" class="align-bottom text-center">Asignaturas</th>
                        <th rowspan="2" class="align-middle text-left" style="width: 60px;">
                            <div class="vertical-text">Comportamiento</div>
                        </th>
                        <th rowspan="2" class="align-middle text-left" style="width: 45px; padding: 6px">
                            <div class="vertical-text">N° asignaturas desaprobadas</div>
                        </th>
                        <th rowspan="2" class="align-middle text-left" style="width: 45px; padding: 6px">
                            <div class="vertical-text">Situación Final</div>
                        </th>
                    </tr>
                    <tr id="materias-row">
                        <!-- Materias se agregarán dinámicamente aquí -->
                    </tr>
                </thead>
                <tbody>
                    <tr id="letras-row">
                        <td colspan="4"></td>
                        <!-- Letras del abecedario se agregarán dinámicamente aquí -->
                        <td colspan="3"></td>
                    </tr>
                </tbody>
                <tbody id="calificaciones-body">
                    <!-- Los datos se cargarán dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Formulario para exportar a Excel -->
<form id="export-form" action="{{ route('calificaciones-old.export.excel') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="id_aula" value="{{ $aula->id_aula }}">
    <input type="hidden" name="año" id="export-año">
    <input type="hidden" name="id_trimestre" id="export-trimestre">
</form>

<!-- Formulario para exportar a PDF -->
<form id="export-pdf-form" action="{{ route('calificaciones-old.export.pdf') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="id_aula" value="{{ $aula->id_aula }}">
    <input type="hidden" name="año" id="export-pdf-año">
    <input type="hidden" name="id_trimestre" id="export-pdf-trimestre">
</form>

<!-- Modal para mostrar estadísticas -->
<div class="modal fade" id="estadisticasModal" tabindex="-1" aria-labelledby="estadisticasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title" id="estadisticasModalLabel">
                    <i></i>Resumen Estadístico
                 <!--    <i class="bi bi-bar-chart-fill me-2"></i>Resumen Estadístico -->
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Columna izquierda: Gráfico de pastel -->
                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="card-title text-center mb-3">Distribución de Estudiantes</h6>
                                <div class="chart-container" style="position: relative; height: 260px;">
                                    <canvas id="grafico-pastel"></canvas>
                                </div>
                                <div class="text-center mt-3">
                                    <h4 class="mb-0" id="total-matriculados">0</h4>
                                    <p class="text-muted">Total de Estudiantes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Columna derecha: Tarjetas de estadísticas -->
                    <div class="col-md-7">
                        <div class="row g-3">
                            <!-- Promovidos -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-success">Promovidos</h6>
                                                <small class="text-muted">(P)</small>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="mb-0" id="total-promovidos">0</h4>
                                                <small class="text-success" id="porcentaje-promovidos">0%</small>
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" id="barra-promovidos" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Aplazados -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-warning">Aplazados</h6>
                                                <small class="text-muted">(A)</small>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="mb-0" id="total-aplazados">0</h4>
                                                <small class="text-warning" id="porcentaje-aplazados">0%</small>
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-warning" id="barra-aplazados" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Repitentes -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-danger">Repitentes</h6>
                                                <small class="text-muted">(R)</small>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="mb-0" id="total-repitentes">0</h4>
                                                <small class="text-danger" id="porcentaje-repitentes">0%</small>
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-danger" id="barra-repitentes" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Retirados -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-secondary">Retirados</h6>
                                                <small class="text-muted">(RET)</small>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="mb-0" id="total-retirados">0</h4>
                                                <small class="text-secondary" id="porcentaje-retirados">0%</small>
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-secondary" id="barra-retirados" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Trasladados -->
                            <div class="col-12">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-info">Trasladados</h6>
                                                <small class="text-muted">(TRA)</small>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="mb-0" id="total-trasladados">0</h4>
                                                <small class="text-info" id="porcentaje-trasladados">0%</small>
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-info" id="barra-trasladados" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para observaciones de situación final -->
<div class="modal fade" id="observacionModal" tabindex="-1" aria-labelledby="observacionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="observacionModalLabel">Observación del Estudiante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="observacionForm">
                    <input type="hidden" id="estudiante-id" name="estudiante_id">
                    <div class="mb-3">
                        <label for="situacion" class="form-label">Situación</label>
                        <select class="form-select" id="situacion" name="situacion">
                            <option value="P">Promovido (P)</option>
                            <option value="A">Aplazado (A)</option>
                            <option value="R">Reprobado (R)</option>
                            <option value="RET">Retirado (RET)</option>
                            <option value="TRA">Trasladado (TRA)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observación</label>
                        <textarea class="form-control" id="observacion" name="observacion" rows="3" placeholder="Ingrese una observación sobre el estudiante"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarObservacion">Guardar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Incluir Chart.js para el gráfico de pastel (versión más ligera) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos de PHP que necesitamos en JavaScript
    const aulaId = "{{ $aula->id_aula }}";
    const nivelId = "{{ $aula->nivel->id_nivel }}";
    const getCalificacionesUrl = "{{ route('calificaciones-old.get-calificaciones') }}";
    
    // Función para mostrar mensajes de alerta
    function mostrarAlerta(mensaje, tipo) {
        const alertContainer = document.getElementById('alertContainer');
        const alertMessage = document.getElementById('alertMessage');
        const alertText = document.getElementById('alertText');
        
        // Establecer el texto del mensaje
        alertText.textContent = mensaje;
        
        // Establecer el tipo de alerta (success, danger, warning, info)
        alertMessage.className = 'alert alert-' + tipo + ' alert-dismissible fade show';
        
        // Mostrar el contenedor
        alertContainer.style.display = 'block';
        
        // Desplazarse al inicio de la página para ver la alerta
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Ocultar automáticamente después de 5 segundos
        setTimeout(function() {
            alertContainer.style.display = 'none';
        }, 5000);
    }
    const saveCalificacionesUrl = "{{ route('calificaciones-old.save-calificaciones') }}";
    
    // Elementos del DOM
    const añoSelect = document.getElementById('año');
    const trimestreSelect = document.getElementById('id_trimestre');
    const promocionSelect = document.getElementById('promocion');
    const calificacionesContainer = document.getElementById('calificaciones-container');
    const loadingMessage = document.getElementById('loading-message');
    const calificacionesBody = document.getElementById('calificaciones-body');
    const materiasRow = document.getElementById('materias-row');
    const saveCalificacionesBtn = document.getElementById('save-calificaciones-btn');
    const asignaturasHeader = document.getElementById('asignaturas-header');
    const modeRead = document.getElementById('modeRead');
    const modeEdit = document.getElementById('modeEdit');
    const editModeControls = document.getElementById('edit-mode-controls');
    
    // Elementos para exportación y estadísticas
    const exportDropdownBtn = document.getElementById('exportDropdown');
    const exportExcelBtn = document.getElementById('export-excel-btn');
    const exportPdfBtn = document.getElementById('export-pdf-btn');
    const estadisticasBtn = document.getElementById('estadisticasBtn');
    
    // Estado de las calificaciones
    let estudiantes = [];
    let materias = [];
    let calificacionesData = {};
    
    // Función para cargar las calificaciones cuando se seleccionan los filtros
    function cargarCalificaciones() {
        const año = añoSelect.value;
        const idTrimestre = trimestreSelect.value;
        const promocion = promocionSelect.value;
        
        if (!año || !idTrimestre) {
            return;
        }
        
        loadingMessage.textContent = 'Cargando calificaciones...';
        loadingMessage.classList.remove('d-none');
        calificacionesContainer.classList.add('d-none');
        exportDropdownBtn.disabled = true;
        estadisticasBtn.disabled = true;
        
        // Realizar la petición AJAX para obtener los datos
        fetch(getCalificacionesUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                id_aula: aulaId,
                id_nivel: nivelId,
                año: año,
                id_trimestre: idTrimestre,
                promocion: promocion || null
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                estudiantes = data.estudiantes || [];
                materias = data.materias || [];
                calificacionesData = data.calificaciones || {};
                
                // Mostrar los datos
                // Cargar el orden guardado de materias antes de mostrarlas
                cargarOrdenMaterias();
                mostrarCalificaciones();
                
                loadingMessage.classList.add('d-none');
                calificacionesContainer.classList.remove('d-none');
                exportDropdownBtn.disabled = false;
                estadisticasBtn.disabled = false;
                
                // Actualizar formularios de exportación
                document.getElementById('export-año').value = año;
                document.getElementById('export-trimestre').value = idTrimestre;
                document.getElementById('export-pdf-año').value = año;
                document.getElementById('export-pdf-trimestre').value = idTrimestre;
            } else {
                loadingMessage.textContent = 'Error: ' + data.message;
                calificacionesContainer.classList.add('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loadingMessage.textContent = 'Error al cargar las calificaciones';
            calificacionesContainer.classList.add('d-none');
        });
    }
    
    // Función para mostrar las calificaciones en la tabla (optimizada)
    function mostrarCalificaciones() {
        // Actualizar el encabezado de asignaturas para abarcar todas las materias
        asignaturasHeader.colSpan = materias.length;
        
        // Aplicar el modo actual (lectura o edición)
        if (currentMode === 'read') {
            setReadOnlyMode(true);
        } else {
            setReadOnlyMode(false);
        }
        
        // Usar DocumentFragment para mejorar el rendimiento
        const materiasFragment = document.createDocumentFragment();
        const letrasFragment = document.createDocumentFragment();
        const bodyFragment = document.createDocumentFragment();
        
        // Crear elementos iniciales para la fila de letras
        const tdInicio = document.createElement('td');
        tdInicio.colSpan = 4;
        const tdFin = document.createElement('td');
        tdFin.colSpan = 3;
        
        // Limpiar filas anteriores
        materiasRow.innerHTML = '';
        document.getElementById('letras-row').innerHTML = '';
        calificacionesBody.innerHTML = '';
        
        // Agregar elementos iniciales al fragmento
        letrasFragment.appendChild(tdInicio);
        
        // Agregar columnas de materias rotadas
        materias.forEach((materia, index) => {
            const th = document.createElement('th');
            th.className = 'text-center materia-column';
            th.setAttribute('data-id-materia', materia.id_materia);
            th.setAttribute('data-id-asignacion', materia.id_asignacion);
            th.setAttribute('data-index', index);
            
            const divContainer = document.createElement('div');
            divContainer.className = 'vertical-text';
            divContainer.textContent = materia.nombre;
            
            // Agregar indicador de arrastrable
            const dragIndicator = document.createElement('div');
            dragIndicator.className = 'drag-indicator';
            dragIndicator.innerHTML = '⋮⋮';
            dragIndicator.title = 'Arrastrar para reordenar';
            
            // Hacer la columna arrastrable
            th.draggable = true;
            th.classList.add('draggable-column');
            
            // Eventos para el sistema moderno de arrastrar y soltar
            th.addEventListener('mousedown', handleDragStart);
            
            // Mantener estos eventos para compatibilidad, pero serán pasivos
            th.addEventListener('dragstart', (e) => e.preventDefault());
            th.addEventListener('dragover', handleDragOver);
            th.addEventListener('dragenter', handleDragEnter);
            th.addEventListener('dragleave', handleDragLeave);
            th.addEventListener('drop', handleDrop);
            th.addEventListener('dragend', handleDragEnd);
            
            th.appendChild(divContainer);
            th.appendChild(dragIndicator);
            materiasRow.appendChild(th);
            
            // Agregar letras del abecedario en el fragmento
            const tdLetra = document.createElement('td');
            tdLetra.className = 'text-center';
            tdLetra.style.fontWeight = 'bold';
            tdLetra.style.backgroundColor = '#f8f9fa'; // Fondo gris claro para destacar
            
            // Obtener la letra correspondiente (a, b, c, ...)
            const letra = String.fromCharCode(97 + index); // 97 es el código ASCII para 'a'
            tdLetra.textContent = letra;
            
            // Agregar la letra al fragmento
            letrasFragment.appendChild(tdLetra);
        });
        
        // Agregar filas de estudiantes
        estudiantes.forEach((estudiante, index) => {
            const tr = document.createElement('tr');
            tr.setAttribute('data-id-estudiante', estudiante.id_estudiante);
            
            // N° Orden
            const tdOrden = document.createElement('td');
            tdOrden.className = 'text-center';
            tdOrden.textContent = index + 1;
            tr.appendChild(tdOrden);
            
            // N° Matrícula (editable)
            const tdMatricula = document.createElement('td');
            tdMatricula.className = 'text-center';
            
            const inputMatricula = document.createElement('input');
            inputMatricula.type = 'text';
            inputMatricula.className = 'form-control form-control-sm matricula-input';
            inputMatricula.value = estudiante.codigo || '';
            inputMatricula.placeholder = 'N°';
            inputMatricula.setAttribute('data-id-estudiante', estudiante.id_estudiante);
            inputMatricula.maxLength = 2;
            // Limitar a 2 dígitos (máximo 99)
            inputMatricula.addEventListener('input', function() {
                if (this.value.length > 2) {
                    this.value = this.value.slice(0, 2);
                }
            });
            
            tdMatricula.appendChild(inputMatricula);
            tr.appendChild(tdMatricula);
            
            // Condición
            const tdCondicion = document.createElement('td');
            tdCondicion.className = 'text-center';
            tdCondicion.textContent = estudiante.condicion || 'R'; //Regular
            tr.appendChild(tdCondicion);
            
            // Apellidos y Nombres
            const tdNombre = document.createElement('td');
            tdNombre.textContent = `${estudiante.apellido}, ${estudiante.nombre}`;
            tr.appendChild(tdNombre);
            
            // Crear celdas para cada materia
            let asignaturasDesaprobadas = 0;
            
            materias.forEach(materia => {
                const tdMateria = document.createElement('td');
                tdMateria.className = 'text-center nota-cell';
                tdMateria.setAttribute('data-id-materia', materia.id_materia);
                tdMateria.setAttribute('data-id-asignacion', materia.id_asignacion);
                tdMateria.setAttribute('data-id-estudiante', estudiante.id_estudiante);
                
                const input = document.createElement('input');
                input.type = 'number';
                input.className = 'form-control form-control-sm nota-input';
                input.min = '0';
                input.max = '20';
                input.step = '0.01';
                input.maxLength = 2;
                // Limitar a 2 dígitos (máximo 99)
                input.addEventListener('input', function() {
                    if (this.value.length > 2) {
                        this.value = this.value.slice(0, 2);
                    }
                });
                
                // Agregar evento para manejar la navegación con Enter
                input.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault(); // Evitar el comportamiento predeterminado del Enter
                        
                        const currentCell = this.closest('td');
                        const currentRow = currentCell.parentElement;
                        
                        // Buscar la siguiente celda de nota en la misma fila
                        let nextCell = currentCell.nextElementSibling;
                        while (nextCell && !nextCell.classList.contains('nota-cell')) {
                            nextCell = nextCell.nextElementSibling;
                        }
                        
                        if (nextCell) {
                            // Si hay una siguiente celda de nota en la misma fila, enfocarla
                            const nextInput = nextCell.querySelector('input');
                            if (nextInput) {
                                nextInput.focus();
                                nextInput.select(); // Seleccionar el texto para facilitar la edición
                            }
                        } else {
                            // Si no hay más celdas de nota en esta fila, buscar el input de comportamiento en la misma fila
                            const comportamientoInput = currentRow.querySelector('.comportamiento-input');
                            if (comportamientoInput) {
                                comportamientoInput.focus();
                                comportamientoInput.select();
                            } else {
                                // Si no hay input de comportamiento, buscar la primera celda de nota en la siguiente fila
                                const nextRow = currentRow.nextElementSibling;
                                if (nextRow) {
                                    const firstNotaCell = nextRow.querySelector('.nota-cell');
                                    if (firstNotaCell) {
                                        const nextInput = firstNotaCell.querySelector('input');
                                        if (nextInput) {
                                            nextInput.focus();
                                            nextInput.select();
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
                
                // Verificar si hay calificación para esta materia y este estudiante
                // Intentar primero con id_asignacion y luego con id_materia para compatibilidad
                const calificacionKeyAsignacion = `${estudiante.id_estudiante}_${materia.id_asignacion}`;
                const calificacionKeyMateria = `${estudiante.id_estudiante}_${materia.id_materia}`;
                
                if (calificacionesData[calificacionKeyAsignacion]) {
                    const nota = calificacionesData[calificacionKeyAsignacion].nota;
                    input.value = nota;
                    
                    // Contar asignaturas desaprobadas
                    if (parseFloat(nota) < 11) {
                        asignaturasDesaprobadas++;
                    }
                } else if (calificacionesData[calificacionKeyMateria]) {
                    // Compatibilidad con datos antiguos que usan id_materia
                    const nota = calificacionesData[calificacionKeyMateria].nota;
                    input.value = nota;
                    
                    // Contar asignaturas desaprobadas
                    if (parseFloat(nota) < 11) {
                        asignaturasDesaprobadas++;
                    }
                }
                
                tdMateria.appendChild(input);
                tr.appendChild(tdMateria);
            });
            
            // Comportamiento
            const tdComportamiento = document.createElement('td');
            tdComportamiento.className = 'text-center';
            
            const inputComportamiento = document.createElement('input');
            inputComportamiento.type = 'number';
            inputComportamiento.className = 'form-control form-control-sm comportamiento-input';
            inputComportamiento.min = '0';
            inputComportamiento.max = '20';
            inputComportamiento.setAttribute('data-id-estudiante', estudiante.id_estudiante);
            inputComportamiento.maxLength = 2;
            // Limitar a 2 dígitos (máximo 99)
            inputComportamiento.addEventListener('input', function() {
                if (this.value.length > 2) {
                    this.value = this.value.slice(0, 2);
                }
            });
            
            // Agregar evento para manejar la navegación con Enter en el input de comportamiento
            inputComportamiento.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Evitar el comportamiento predeterminado del Enter
                    
                    const currentRow = this.closest('tr');
                    const nextRow = currentRow.nextElementSibling;
                    
                    if (nextRow) {
                        // Buscar el primer input de nota en la siguiente fila
                        const firstNotaCell = nextRow.querySelector('.nota-cell');
                        if (firstNotaCell) {
                            const nextInput = firstNotaCell.querySelector('input');
                            if (nextInput) {
                                nextInput.focus();
                                nextInput.select();
                            }
                        }
                    }
                }
            });
            
            // Verificar si hay dato de comportamiento para este estudiante
            const comportamientoValue = estudiante.comportamiento || '';
            if (comportamientoValue) {
                inputComportamiento.value = comportamientoValue;
            }
            
            tdComportamiento.appendChild(inputComportamiento);
            tr.appendChild(tdComportamiento);
            
            // N° asignaturas desaprobadas
            const tdDesaprobadas = document.createElement('td');
            tdDesaprobadas.className = 'text-center asignaturas-desaprobadas';
            tdDesaprobadas.setAttribute('data-id-estudiante', estudiante.id_estudiante);
            tdDesaprobadas.textContent = asignaturasDesaprobadas;
            tr.appendChild(tdDesaprobadas);
            
            // Situación Final
            const tdSituacion = document.createElement('td');
            tdSituacion.className = 'text-center situacion-final';
            tdSituacion.setAttribute('data-id-estudiante', estudiante.id_estudiante);
            tdSituacion.setAttribute('data-bs-toggle', 'tooltip');
            
            // El tooltip y el estilo del cursor dependen del modo
            if (currentMode === 'edit') {
                tdSituacion.setAttribute('title', 'Haga clic para agregar o editar observación');
                tdSituacion.style.cursor = 'pointer';
            } else {
                tdSituacion.setAttribute('title', 'Ver observación (si existe)');
                tdSituacion.style.cursor = 'default';
            }
            
            // Determinar situación final (P = Promovido, A = Aplazado, R = Reprobado)
            let situacion;
            if (asignaturasDesaprobadas === 0) {
                situacion = 'P'; // Promovido (0 cursos desaprobados)
            } else if (asignaturasDesaprobadas >= 1 && asignaturasDesaprobadas <= 3) {
                situacion = 'A'; // Aplazado (1 a 3 cursos desaprobados)
            } else {
                situacion = 'R'; // Reprobado (4 o más cursos desaprobados)
            }
            
            // Verificar si hay observaciones guardadas para este estudiante
            let observacionGuardada = '';
            let situacionGuardada = situacion; // Por defecto, usar la situación calculada
            
            // Buscar la observación en los datos de calificaciones de la base de datos
            // Intentar encontrar la calificación para este estudiante usando la primera asignación
            if (materias.length > 0) {
                const primeraAsignacion = materias[0].id_asignacion;
                const calificacionKey = `${estudiante.id_estudiante}_${primeraAsignacion}`;
                
                if (calificacionesData[calificacionKey]) {
                    // Usar la observación de la base de datos si existe
                    observacionGuardada = calificacionesData[calificacionKey].observacion || '';
                    
                    // Si hay una conclusión guardada, verificar si es un caso especial
                    if (calificacionesData[calificacionKey].conclusion) {
                        const conclusion = calificacionesData[calificacionKey].conclusion;
                        // Solo usar casos especiales de la base de datos (Retirado o Trasladado)
                        // Para Promovido, Aplazado o Reprobado, usar el cálculo basado en asignaturas desaprobadas
                        if (conclusion === 'Retirado') {
                            situacionGuardada = 'RET';
                        } else if (conclusion === 'Trasladado') {
                            situacionGuardada = 'TRA';
                        } else {
                            // Para los demás casos, priorizar el cálculo basado en asignaturas desaprobadas
                            situacionGuardada = situacion;
                        }
                    }
                }
            }
            
            tdSituacion.textContent = situacionGuardada;
            tdSituacion.setAttribute('data-observacion', observacionGuardada);
            
            // Colorear según situación
            if (situacionGuardada === 'P') {
                tdSituacion.classList.add('text-success');
            } else if (situacionGuardada === 'A') {
                tdSituacion.classList.add('text-warning');
            } else if (situacionGuardada === 'RET') {
                tdSituacion.classList.add('text-secondary');
            } else if (situacionGuardada === 'TRA') {
                tdSituacion.classList.add('text-info');
            } else {
                tdSituacion.classList.add('text-danger');
            }
            
            // Agregar icono de observación si hay una observación guardada
            if (observacionGuardada) {
                tdSituacion.classList.add('has-observacion');
            }
            
            // Agregar evento de clic para mostrar el modal
            tdSituacion.addEventListener('click', function() {
                // Verificar si estamos en modo edición
                if (currentMode === 'edit') {
                    const estudianteId = this.getAttribute('data-id-estudiante');
                    const observacion = this.getAttribute('data-observacion') || '';
                    const situacion = this.textContent;
                    
                    // Llenar el modal con los datos actuales
                    document.getElementById('estudiante-id').value = estudianteId;
                    document.getElementById('observacion').value = observacion;
                    document.getElementById('situacion').value = situacion;
                    
                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('observacionModal'));
                    modal.show();
                } else {
                    // En modo lectura, mostrar un panel pequeño con la observación
                    const observacion = this.getAttribute('data-observacion');
                    if (observacion) {
                        // Crear un panel flotante para mostrar la observación
                        const rect = this.getBoundingClientRect();
                        const panel = document.createElement('div');
                        panel.className = 'observacion-panel';
                        panel.innerHTML = `
                            <div class="observacion-header">Observación</div>
                            <div class="observacion-content">${observacion}</div>
                            <div class="observacion-footer">
                                <button class="btn btn-sm btn-secondary">Cerrar</button>
                            </div>
                        `;
                        
                        // Agregar al DOM primero para poder calcular dimensiones
                        document.body.appendChild(panel);
                        
                        // Ahora podemos obtener las dimensiones reales
                        const panelHeight = panel.offsetHeight;
                        const panelWidth = panel.offsetWidth;
                        
                        // Posicionar el panel en la parte superior izquierda de la celda
                        panel.style.top = (rect.top + window.scrollY - panelHeight - 5) + 'px';
                        panel.style.left = (rect.left + window.scrollX - 300) + 'px';
                        
                        // Asegurarse de que el panel no se salga de la pantalla por arriba
                        if (rect.top - panelHeight < 0) {
                            panel.style.top = (rect.bottom + window.scrollY + 5) + 'px';
                        }
                        
                        // Asegurarse de que el panel no se salga de la pantalla por la izquierda
                        if (rect.left - 20 < 0) {
                            panel.style.left = rect.left + 'px';
                        }
                        
                        // Configurar el botón de cerrar
                        panel.querySelector('button').addEventListener('click', function() {
                            document.body.removeChild(panel);
                        });
                        
                        // Cerrar el panel si se hace clic fuera de él
                        document.addEventListener('click', function closePanel(e) {
                            if (!panel.contains(e.target) && e.target !== tdSituacion) {
                                document.body.removeChild(panel);
                                document.removeEventListener('click', closePanel);
                            }
                        });
                    }
                }
            });
            
            tr.appendChild(tdSituacion);
            
            // Agregar fila al fragmento
            bodyFragment.appendChild(tr);
        });
        
        // Agregar los fragmentos al DOM
        materiasRow.appendChild(materiasFragment);
        
        const letrasRow = document.getElementById('letras-row');
        letrasRow.appendChild(letrasFragment);
        letrasRow.appendChild(tdFin);
        
        calificacionesBody.appendChild(bodyFragment);
        
        // Configurar evento para recalcular asignaturas desaprobadas (delegación de eventos)
        calificacionesBody.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('nota-input')) {
                actualizarDesaprobadas(e);
            }
        });
    }
    
    // Variables para el modo de visualización
    let currentMode = 'edit'; // Por defecto en modo edición
    
    // Variables para el sistema moderno de arrastrar y soltar
    let isDragging = false;
    let draggedColumn = null;
    let draggedIndex = -1;
    let dragClone = null;
    let initialX = 0;
    let currentX = 0;
    let columnsArray = [];
    let columnPositions = [];
    let headerRow = null;
    
    // Event listeners para cambio de modo
    modeRead.addEventListener('change', function() {
        if (this.checked) {
            currentMode = 'read';
            editModeControls.classList.add('d-none');
            // Desactivar drag and drop y hacer inputs readonly
            setReadOnlyMode(true);
        }
    });
    
    modeEdit.addEventListener('change', function() {
        if (this.checked) {
            currentMode = 'edit';
            editModeControls.classList.remove('d-none');
            // Activar drag and drop y hacer inputs editables
            setReadOnlyMode(false);
        }
    });
    
    // Función para activar/desactivar el modo de solo lectura
    function setReadOnlyMode(isReadOnly) {
        // Actualizar tooltips y cursor de las celdas de situación final
        document.querySelectorAll('.situacion-final').forEach(cell => {
            if (isReadOnly) {
                cell.setAttribute('title', 'Ver observación (si existe)');
                cell.style.cursor = 'default';
            } else {
                cell.setAttribute('title', 'Haga clic para agregar o editar observación');
                cell.style.cursor = 'pointer';
            }
        });
        // Hacer todos los inputs de notas readonly o editables
        document.querySelectorAll('.nota-input').forEach(input => {
            input.readOnly = isReadOnly;
            if (isReadOnly) {
                input.classList.add('readonly-input');
            } else {
                input.classList.remove('readonly-input');
            }
        });
        
        // Hacer los inputs de comportamiento readonly o editables
        document.querySelectorAll('.comportamiento-input').forEach(input => {
            input.readOnly = isReadOnly;
            if (isReadOnly) {
                input.classList.add('readonly-input');
            } else {
                input.classList.remove('readonly-input');
            }
        });
        
        // Si estamos en modo lectura, desactivar el drag and drop
        if (isReadOnly) {
            document.querySelectorAll('.materia-column').forEach(col => {
                col.removeEventListener('mousedown', handleDragStart);
                col.classList.remove('draggable');
            });
        } else {
            // Si estamos en modo edición, activar el drag and drop
            document.querySelectorAll('.materia-column').forEach((col, index) => {
                col.addEventListener('mousedown', handleDragStart);
                col.classList.add('draggable');
            });
        }
    }
    
    // Inicializar el sistema de arrastrar y soltar
    function initDragAndDrop() {
        // Obtener la fila de encabezados
        headerRow = document.getElementById('materias-row');
        
        // Actualizar el array de columnas y sus posiciones
        updateColumnsArray();
        
        // Agregar evento de mouse move al documento
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseup', handleMouseUp);
        
        // Inicializar el modo según el botón seleccionado
        if (modeRead.checked) {
            currentMode = 'read';
            editModeControls.classList.add('d-none');
            setReadOnlyMode(true);
        } else {
            currentMode = 'edit';
            editModeControls.classList.remove('d-none');
            setReadOnlyMode(false);
        }
    }
    
    // Actualizar el array de columnas y sus posiciones
    function updateColumnsArray() {
        columnsArray = Array.from(document.querySelectorAll('.materia-column'));
        columnPositions = columnsArray.map(col => {
            const rect = col.getBoundingClientRect();
            return {
                left: rect.left,
                right: rect.right,
                width: rect.width,
                center: rect.left + rect.width / 2
            };
        });
    }
    
    // Funciones para manejar el drag and drop moderno
    function handleDragStart(e) {
        // Si estamos en modo lectura, no permitir arrastrar
        if (currentMode === 'read') return;
        
        // Prevenir el comportamiento por defecto del navegador
        e.preventDefault();
        
        // Obtener la columna que se está arrastrando
        draggedColumn = this;
        draggedIndex = parseInt(this.getAttribute('data-index'));
        
        // Crear un clon de la columna para arrastrar
        dragClone = draggedColumn.cloneNode(true);
        dragClone.classList.add('column-clone');
        
        // Posicionar el clon en la posición exacta de la columna original
        const rect = draggedColumn.getBoundingClientRect();
        dragClone.style.width = `${rect.width}px`;
        dragClone.style.height = `${rect.height}px`;
        dragClone.style.top = `${rect.top}px`;
        dragClone.style.left = `${rect.left}px`;
        
        // Agregar el clon al DOM
        document.body.appendChild(dragClone);
        
        // Guardar la posición inicial del mouse
        initialX = e.clientX;
        currentX = initialX;
        
        // Marcar que estamos arrastrando
        isDragging = true;
        
        // Agregar clase visual a la columna original
        draggedColumn.classList.add('being-dragged');
        
        // Actualizar las posiciones de las columnas
        updateColumnsArray();
    }
    
    function handleMouseMove(e) {
        if (!isDragging || !dragClone) return;
        
        // Calcular el desplazamiento horizontal
        const deltaX = e.clientX - initialX;
        currentX = e.clientX;
        
        // Mover el clon horizontalmente - movimiento más directo
        dragClone.style.left = `${e.clientX - (dragClone.offsetWidth / 2)}px`;
        
        // Determinar la nueva posición basada en el centro del clon
        const cloneRect = dragClone.getBoundingClientRect();
        const cloneCenter = cloneRect.left + cloneRect.width / 2;
        
        // Encontrar la columna más cercana
        let closestColumnIndex = -1;
        let minDistance = Infinity;
        
        columnPositions.forEach((pos, index) => {
            if (index !== draggedIndex) {
                const distance = Math.abs(cloneCenter - pos.center);
                if (distance < minDistance) {
                    minDistance = distance;
                    closestColumnIndex = index;
                }
            }
        });
        
        // Resaltar visualmente la posición de destino
        columnsArray.forEach((col, index) => {
            if (index === closestColumnIndex) {
                col.classList.add('potential-drop-target');
            } else {
                col.classList.remove('potential-drop-target');
            }
        });
    }
    
    function handleMouseUp(e) {
        if (!isDragging) return;
        
        // Determinar la columna de destino
        const cloneRect = dragClone ? dragClone.getBoundingClientRect() : null;
        if (cloneRect) {
            const cloneCenter = cloneRect.left + cloneRect.width / 2;
            
            // Encontrar la columna más cercana
            let targetIndex = draggedIndex;
            let minDistance = Infinity;
            
            columnPositions.forEach((pos, index) => {
                if (index !== draggedIndex) {
                    const distance = Math.abs(cloneCenter - pos.center);
                    if (distance < minDistance) {
                        minDistance = distance;
                        targetIndex = index;
                    }
                }
            });
            
            // Mover la columna si el destino es diferente
            if (targetIndex !== draggedIndex) {
                moverColumna(draggedIndex, targetIndex);
            }
        }
        
        // Limpiar
        finalizarArrastre();
    }
    
    function finalizarArrastre() {
        // Eliminar el clon
        if (dragClone && dragClone.parentNode) {
            dragClone.parentNode.removeChild(dragClone);
        }
        
        // Restaurar clases visuales
        if (draggedColumn) {
            draggedColumn.classList.remove('being-dragged');
        }
        
        document.querySelectorAll('.materia-column').forEach(col => {
            col.classList.remove('potential-drop-target');
        });
        
        // Resetear variables
        isDragging = false;
        draggedColumn = null;
        draggedIndex = -1;
        dragClone = null;
    }
    
    // Reemplazar los eventos estándar de drag and drop con eventos de mouse
    function handleDragOver(e) { e.preventDefault(); }
    function handleDragEnter(e) {}
    function handleDragLeave(e) {}
    function handleDrop(e) { e.preventDefault(); }
    function handleDragEnd(e) {}
    
    // Función para mover una columna de asignatura a la posición deseada
    function moverColumna(indiceActual, nuevoIndice) {
        // No hacer nada si no hay materias o solo hay una
        if (!materias || materias.length <= 1) return;
        
        // Validar índices
        if (isNaN(indiceActual) || isNaN(nuevoIndice) || 
            indiceActual < 0 || indiceActual >= materias.length || 
            nuevoIndice < 0 || nuevoIndice >= materias.length) {
            return; // Índices inválidos
        }
        
        // No hacer nada si el índice no cambia
        if (nuevoIndice === indiceActual) return;
        
        // Reordenar el array de materias
        const materia = materias.splice(indiceActual, 1)[0];
        materias.splice(nuevoIndice, 0, materia);
        
        // Volver a mostrar las calificaciones con el nuevo orden
        mostrarCalificaciones();
        
        // Guardar el orden actual en localStorage para persistencia
        guardarOrdenMaterias();
    }
    
    // Función para guardar el orden actual de materias en localStorage
    function guardarOrdenMaterias() {
        const ordenMaterias = materias.map(m => m.id_asignacion || m.id_materia);
        const trimestreActual = trimestreSelect.value;
        localStorage.setItem(`orden_materias_${aulaId}_${año}_${trimestreActual}`, JSON.stringify(ordenMaterias));
    }
    
    // Función para cargar el orden guardado de materias desde localStorage
    function cargarOrdenMaterias() {
        const trimestreActual = trimestreSelect.value;
        const ordenGuardado = localStorage.getItem(`orden_materias_${aulaId}_${año}_${trimestreActual}`);
        if (ordenGuardado) {
            try {
                const ordenIds = JSON.parse(ordenGuardado);
                // Reordenar el array de materias según el orden guardado
                const materiasOrdenadas = [];
                const materiasNoOrdenadas = [...materias];
                
                ordenIds.forEach(id => {
                    const idx = materiasNoOrdenadas.findIndex(m => 
                        (m.id_asignacion && m.id_asignacion.toString() === id.toString()) || 
                        (m.id_materia && m.id_materia.toString() === id.toString())
                    );
                    
                    if (idx !== -1) {
                        materiasOrdenadas.push(materiasNoOrdenadas.splice(idx, 1)[0]);
                    }
                });
                
                // Agregar cualquier materia que no estaba en el orden guardado
                materias = [...materiasOrdenadas, ...materiasNoOrdenadas];
            } catch (e) {
                console.error('Error al cargar el orden de materias:', e);
            }
        }
    }
    
    // Función para actualizar conteo de asignaturas desaprobadas
    function actualizarDesaprobadas(event) {
        const input = event.target;
        const row = input.closest('tr');
        const idEstudiante = row.getAttribute('data-id-estudiante');
        
        // Contar asignaturas desaprobadas
        let asignaturasDesaprobadas = 0;
        const notaInputs = row.querySelectorAll('.nota-input');
        
        notaInputs.forEach(notaInput => {
            const nota = parseFloat(notaInput.value);
            if (!isNaN(nota) && nota < 11) {
                asignaturasDesaprobadas++;
            }
        });
        
        // Actualizar el contador de asignaturas desaprobadas
        const tdDesaprobadas = row.querySelector('.asignaturas-desaprobadas');
        tdDesaprobadas.textContent = asignaturasDesaprobadas;
        
        // Actualizar la situación final
        const tdSituacion = row.querySelector('.situacion-final');
        const situacionActual = tdSituacion.textContent;
        
        // Solo actualizar si no es un caso especial (RET o TRA)
        if (situacionActual !== 'RET' && situacionActual !== 'TRA') {
            let nuevaSituacion;
            
            if (asignaturasDesaprobadas === 0) {
                nuevaSituacion = 'P'; // Siempre promovido si no hay asignaturas desaprobadas
            } else if (asignaturasDesaprobadas >= 1 && asignaturasDesaprobadas <= 3) {
                nuevaSituacion = 'A';
            } else {
                nuevaSituacion = 'R';
            }
            
            // Actualizar el texto y las clases
            tdSituacion.textContent = nuevaSituacion;
            
            // Limpiar clases de color
            tdSituacion.classList.remove('text-success', 'text-danger', 'text-warning', 'text-info', 'text-secondary');
            
            // Aplicar clase según situación
            if (nuevaSituacion === 'P') {
                tdSituacion.classList.add('text-success');
            } else if (nuevaSituacion === 'A') {
                tdSituacion.classList.add('text-warning');
            } else {
                tdSituacion.classList.add('text-danger');
            }
        }
    }
    
    // Guardar calificaciones
    saveCalificacionesBtn.addEventListener('click', function() {
        const año = añoSelect.value;
        const idTrimestre = trimestreSelect.value;
        
        if (!año || !idTrimestre) {
            alert('Debe seleccionar año y trimestre');
            return;
        }
        
        // Recopilar todos los datos de calificaciones
        const calificaciones = [];
        const filas = calificacionesBody.querySelectorAll('tr');
        
        filas.forEach(fila => {
            const idEstudiante = fila.getAttribute('data-id-estudiante');
            const notaCells = fila.querySelectorAll('.nota-cell');
            const comportamientoInput = fila.querySelector('.comportamiento-input');
            const asignaturasDesaprobadas = fila.querySelector('.asignaturas-desaprobadas').textContent;
            const situacionFinalCell = fila.querySelector('.situacion-final');
            const situacionFinal = situacionFinalCell.textContent;
            // Obtener la observación existente si existe
            const observacionExistente = situacionFinalCell.getAttribute('data-observacion') || '';
            
            // Para cada materia de este estudiante
            notaCells.forEach(cell => {
                const idMateria = cell.getAttribute('data-id-materia');
                const idAsignacion = cell.getAttribute('data-id-asignacion');
                const notaInput = cell.querySelector('input');
                const nota = notaInput.value.trim();
                
                if (nota) { // Solo guardar si hay una nota
                    // Determinar la conclusión basada en la situación final
                    let conclusion;
                    if (situacionFinal === 'P') {
                        conclusion = 'Promovido';
                    } else if (situacionFinal === 'A') {
                        conclusion = 'Aplazado';
                    } else if (situacionFinal === 'R') {
                        conclusion = 'Reprobado';
                    } else if (situacionFinal === 'TRA') {
                        conclusion = 'Trasladado';
                    } else if (situacionFinal === 'RET') {
                        conclusion = 'Retirado';
                    } else {
                        conclusion = 'Promovido'; // Valor por defecto (cambiado de 'Reprobado' a 'Promovido')
                    }
                    
                    calificaciones.push({
                        id_estudiante: idEstudiante,
                        id_asignacion: idAsignacion, // Usar el id_asignacion correcto
                        id_trimestre: idTrimestre,
                        nota: nota,
                        comportamiento: comportamientoInput.value || 0,
                        asignaturas_reprobadas: asignaturasDesaprobadas,
                        conclusion: conclusion,
                        grado: "{{ $aula->grado->nombre }} '{{ $aula->seccion->nombre }}'",
                        fecha: new Date().toISOString().split('T')[0],
                        año: año,
                        observacion: observacionExistente // Preservar la observación existente
                    });
                }
            });
        });
        
        // Enviar datos al servidor
        fetch(saveCalificacionesUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                calificaciones: calificaciones
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito en el contenedor de alertas
                mostrarAlerta('Calificaciones guardadas correctamente', 'success');
                // Recargar datos para mostrar cambios
                cargarCalificaciones();
            } else {
                // Mostrar mensaje de error en el contenedor de alertas
                mostrarAlerta('Error al guardar calificaciones: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Mostrar mensaje de error en el contenedor de alertas
            mostrarAlerta('Error al guardar calificaciones', 'danger');
        });
    });
    
    // Eventos para exportación
    exportExcelBtn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('export-form').submit();
    });
    
    exportPdfBtn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('export-pdf-form').submit();
    });
    
    // Evento para mostrar estadísticas (optimizado)
    estadisticasBtn.addEventListener('click', function() {
        // Mostrar el modal primero para mejor percepción de velocidad
        const modal = new bootstrap.Modal(document.getElementById('estadisticasModal'));
        modal.show();
        
        // Usar setTimeout para permitir que el modal se muestre antes de calcular
        setTimeout(() => {
            calcularEstadisticas();
        }, 50);
    });
    
    // Variable para almacenar el gráfico de pastel
    let graficoEstadisticas = null;
    
    // Función para calcular estadísticas
    function calcularEstadisticas() {
        // Obtener todas las filas de estudiantes
        const filas = document.querySelectorAll('#calificaciones-body tr');
        
        // Inicializar contadores
        let totalMatriculados = filas.length;
        let totalPromovidos = 0;
        let totalAplazados = 0;
        let totalRepitentes = 0;
        let totalRetirados = 0;
        let totalTrasladados = 0;
        
        // Contar cada categoría
        filas.forEach(fila => {
            const situacionCell = fila.querySelector('.situacion-final');
            if (situacionCell) {
                const situacion = situacionCell.textContent.trim();
                
                switch(situacion) {
                    case 'P':
                        totalPromovidos++;
                        break;
                    case 'A':
                        totalAplazados++;
                        break;
                    case 'R':
                        totalRepitentes++;
                        break;
                    case 'RET':
                        totalRetirados++;
                        break;
                    case 'TRA':
                        totalTrasladados++;
                        break;
                }
            }
        });
        
        // Calcular porcentajes
        const calcularPorcentaje = (valor) => {
            return totalMatriculados > 0 ? ((valor / totalMatriculados) * 100).toFixed(1) : 0;
        };
        
        // Actualizar la tarjeta principal
        document.getElementById('total-matriculados').textContent = totalMatriculados;
        
        // Actualizar las tarjetas de estadísticas
        document.getElementById('total-promovidos').textContent = totalPromovidos;
        document.getElementById('porcentaje-promovidos').textContent = calcularPorcentaje(totalPromovidos) + '%';
        document.getElementById('barra-promovidos').style.width = calcularPorcentaje(totalPromovidos) + '%';
        document.getElementById('barra-promovidos').setAttribute('aria-valuenow', calcularPorcentaje(totalPromovidos));
        
        document.getElementById('total-aplazados').textContent = totalAplazados;
        document.getElementById('porcentaje-aplazados').textContent = calcularPorcentaje(totalAplazados) + '%';
        document.getElementById('barra-aplazados').style.width = calcularPorcentaje(totalAplazados) + '%';
        document.getElementById('barra-aplazados').setAttribute('aria-valuenow', calcularPorcentaje(totalAplazados));
        
        document.getElementById('total-repitentes').textContent = totalRepitentes;
        document.getElementById('porcentaje-repitentes').textContent = calcularPorcentaje(totalRepitentes) + '%';
        document.getElementById('barra-repitentes').style.width = calcularPorcentaje(totalRepitentes) + '%';
        document.getElementById('barra-repitentes').setAttribute('aria-valuenow', calcularPorcentaje(totalRepitentes));
        
        document.getElementById('total-retirados').textContent = totalRetirados;
        document.getElementById('porcentaje-retirados').textContent = calcularPorcentaje(totalRetirados) + '%';
        document.getElementById('barra-retirados').style.width = calcularPorcentaje(totalRetirados) + '%';
        document.getElementById('barra-retirados').setAttribute('aria-valuenow', calcularPorcentaje(totalRetirados));
        
        document.getElementById('total-trasladados').textContent = totalTrasladados;
        document.getElementById('porcentaje-trasladados').textContent = calcularPorcentaje(totalTrasladados) + '%';
        document.getElementById('barra-trasladados').style.width = calcularPorcentaje(totalTrasladados) + '%';
        document.getElementById('barra-trasladados').setAttribute('aria-valuenow', calcularPorcentaje(totalTrasladados));
        
        // Efecto de animación para las barras de progreso (optimizado)
        document.querySelectorAll('.progress-bar').forEach(barra => {
            barra.style.transition = 'width 0.3s ease-out';
        });
        
        // Actualizar el gráfico de pastel
        actualizarGraficoPastel({
            promovidos: totalPromovidos,
            aplazados: totalAplazados,
            repitentes: totalRepitentes,
            retirados: totalRetirados,
            trasladados: totalTrasladados
        });
    }
    
    // Función para crear o actualizar el gráfico de pastel (optimizada)
    function actualizarGraficoPastel(datos) {
        const ctx = document.getElementById('grafico-pastel').getContext('2d');
        
        // Actualizar datos si el gráfico ya existe (mejor rendimiento)
        if (graficoEstadisticas) {
            graficoEstadisticas.data.datasets[0].data = [
                datos.promovidos,
                datos.aplazados,
                datos.repitentes,
                datos.retirados,
                datos.trasladados
            ];
            graficoEstadisticas.update('none'); // Actualizar sin animación
            return;
        }
        
        // Datos para el gráfico
        const datosGrafico = {
            labels: ['Promovidos', 'Aplazados', 'Repitentes', 'Retirados', 'Trasladados'],
            datasets: [{
                data: [
                    datos.promovidos,
                    datos.aplazados,
                    datos.repitentes,
                    datos.retirados,
                    datos.trasladados
                ],
                backgroundColor: [
                    '#2ec4b6', // Verde para Promovidos
                    '#ffbe0b', // Amarillo para Aplazados
                    '#f43f5e', // Rojo para Repitentes
                    '#6c757d', // Gris para Retirados
                    '#3abff8'  // Azul claro para Trasladados
                ],
                borderWidth: 1,
                borderColor: '#fff'
            }]
        };
        
        // Opciones del gráfico
        const opciones = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const porcentaje = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${porcentaje}%)`;
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: false,
                duration: 400
            }
        };
        
        // Crear el gráfico
        graficoEstadisticas = new Chart(ctx, {
            type: 'pie',
            data: datosGrafico,
            options: opciones
        });
    }
    
    // Eventos para cargar datos cuando cambien los filtros
    añoSelect.addEventListener('change', cargarCalificaciones);
    trimestreSelect.addEventListener('change', cargarCalificaciones);
    promocionSelect.addEventListener('change', cargarCalificaciones);
    
    // Inicializar el sistema moderno de arrastrar y soltar
    setTimeout(initDragAndDrop, 500); // Pequeño retraso para asegurar que todo esté cargado
    
    // Configurar el evento para guardar observaciones
    document.getElementById('guardarObservacion').addEventListener('click', function() {
        const estudianteId = document.getElementById('estudiante-id').value;
        const observacion = document.getElementById('observacion').value;
        const situacion = document.getElementById('situacion').value;
        const año = document.getElementById('año').value;
        const idTrimestre = document.getElementById('id_trimestre').value;
        
        // Obtener el primer id_asignacion disponible
        const primeraCeldaNota = document.querySelector('.nota-cell');
        if (!primeraCeldaNota) {
            alert('Error: No se encontró ninguna asignación');
            return;
        }
        const idAsignacion = primeraCeldaNota.getAttribute('data-id-asignacion');
        
        if (!idAsignacion) {
            alert('Error: No se pudo obtener el ID de la asignación');
            return;
        }

        // Validar que tenemos todos los datos necesarios
        if (!estudianteId || !año || !idTrimestre || !idAsignacion) {
            alert('Error: Faltan datos necesarios para guardar la observación');
            return;
        }

        // Preparar los datos en el formato que espera el controlador
        const datos = {
            calificaciones: [{
                id_estudiante: parseInt(estudianteId),
                id_asignacion: parseInt(idAsignacion),
                id_trimestre: parseInt(idTrimestre),
                nota: 0,
                comportamiento: 0,
                asignaturas_reprobadas: 0,
                conclusion: situacion,
                grado: "{{ $aula->grado->nombre }} '{{ $aula->seccion->nombre }}'",
                fecha: new Date().toISOString().split('T')[0],
                año: parseInt(año),
                observacion: observacion
            }]
        };

        console.log('Enviando datos:', datos); // Para depuración

        // Enviar datos al servidor
        fetch(saveCalificacionesUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datos)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del servidor:', data); // Para depuración
            
            if (data.success) {
                // Actualizar la celda de situación final
                const tdSituacion = document.querySelector(`.situacion-final[data-id-estudiante="${estudianteId}"]`);
                if (tdSituacion) {
                    tdSituacion.textContent = situacion;
                    tdSituacion.setAttribute('data-observacion', observacion);
                    
                    // Limpiar clases de color
                    tdSituacion.classList.remove('text-success', 'text-danger', 'text-warning', 'text-info', 'text-secondary');
                    
                    // Aplicar clase según situación
                    if (situacion === 'P') {
                        tdSituacion.classList.add('text-success');
                    } else if (situacion === 'A') {
                        tdSituacion.classList.add('text-warning');
                    } else if (situacion === 'RET') {
                        tdSituacion.classList.add('text-secondary');
                    } else if (situacion === 'TRA') {
                        tdSituacion.classList.add('text-info');
                    } else {
                        tdSituacion.classList.add('text-danger');
                    }
                    
                    // Agregar o quitar clase de observación
                    if (observacion) {
                        tdSituacion.classList.add('has-observacion');
                    } else {
                        tdSituacion.classList.remove('has-observacion');
                    }
                }
                
                // Cerrar el modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('observacionModal'));
                modal.hide();
            } else {
                mostrarAlerta('Error al guardar la observación: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al guardar la observación: ' + error.message, 'danger');
        });
    });
});
</script>
@endpush

<style>
    /* Panel de observaciones */
    .observacion-panel {
        position: absolute;
        z-index: 1000;
        width: 300px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        font-size: 14px;
    }
    
    .observacion-header {
        padding: 10px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: bold;
        border-radius: 10px 10px 0 0;
    }
    
    .observacion-content {
        padding: 15px;
        max-height: 200px;
        overflow-y: auto;
    }
    
    .observacion-footer {
        padding: 10px;
        text-align: right;
        border-top: 1px solid #dee2e6;
        background-color: #ffffff; /*#f8f9fa color del header */
        border-radius: 0 0 10px 10px;
    }
    
    /* Indicador de arrastre */
    .drag-indicator {
        display: flex;
        justify-content: center;
        margin-top: 5px;
        cursor: grab;
        color: #6c757d;
        font-size: 14px;
        user-select: none;
    }
    
    .draggable-column {
        cursor: grab;
        position: relative;
        /* Eliminamos la transición para evitar el efecto de movimiento */
    }
    
    .draggable-column.being-dragged {
        opacity: 0.4;
        z-index: 1;
    }
    
    .draggable-column.potential-drop-target {
        border: 2px dashed #007bff;
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    /* Estilo para el clon que se arrastra */
    .column-clone {
        position: fixed;
        pointer-events: none;
        z-index: 1000;
        background-color: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        opacity: 0.9;
        border: 1px solid #007bff;
        transition: transform 0.05s linear; /* Movimiento más rápido y fluido */
    }
/* Estilos para los modos de lectura/edición */
.readonly-input {
    border: none !important;
    background-color: #f8f9fa !important;
    pointer-events: none;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    color: #495057 !important;
}

.draggable {
    cursor: move;
}

/* Estilos para texto vertical en encabezados */
.vertical-text {
    writing-mode: vertical-rl;
    text-orientation: mixed;
    transform: rotate(180deg);
    min-height: 120px;
    margin: auto;
    white-space: nowrap;
}

/* Estilos para celdas de notas */
.nota-input, .matricula-input, .comportamiento-input {
    width: 45px;
    text-align: center;
    display: inline-block;
    padding: 0%;
    background-color: transparent;
    border: 1px solid #ddd;
    transition: all 0.2s ease;
}

.nota-input:focus, .matricula-input:focus, .comportamiento-input:focus {
    background-color: #f0f8ff;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

/* Ocultar los botones de incremento/decremento (spin buttons) en inputs numéricos */
.nota-input::-webkit-outer-spin-button,
.nota-input::-webkit-inner-spin-button,
.comportamiento-input::-webkit-outer-spin-button,
.comportamiento-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    appearance: none;
    margin: 0;
}

/* Para Firefox */
.nota-input[type=number],
.comportamiento-input[type=number] {
    -moz-appearance: textfield;
    appearance: textfield;
}

/* Estilos para estado de situación final */
.text-success {
    font-weight: bold;
}

.text-danger {
    font-weight: bold;
}

.text-warning {
    font-weight: bold;
    color: #ffc107 !important;
}

.text-info {
    font-weight: bold;
    color: #17a2b8 !important;
}

.text-secondary {
    font-weight: bold;
    color: #6c757d !important;
}

/* Estilos para situación final con hover */
.situacion-final {
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease;
}

.situacion-final:hover {
    background-color: #f0f0f0;
    transform: scale(1.05);
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
}

/* Indicador de observación */
.has-observacion {
    position: relative;
}

.has-observacion::after {
    content: '•';
    position: absolute;
    top: -5px;
    right: 0;
    font-size: 16px;
    color: #007bff;
}

/* Padding para todas las celdas de la tabla */
#calificaciones-table td {
    padding: 2px 2px;
}

/* Ancho para columnas específicas */
#calificaciones-table td:nth-child(1), /* N° Orden */
#calificaciones-table td:nth-child(2) { /* N° Matrícula */
    width: 45px;
    padding: 2px;
}

#calificaciones-table td:nth-child(3), /* Condición */
#calificaciones-table td:nth-child(7), /* Comportamiento */
#calificaciones-table td:nth-child(8), /* N° asignaturas desaprobadas */
#calificaciones-table td:nth-child(9) { /* Situación Final */
    width: 45px;
    padding: 6px;
}

/* Estilos para los encabezados de la tabla */
#calificaciones-table thead th {
    background-color: #054f9f;
    color: white;
    border: 1px solid rgb(211, 237, 255);
}

/* Color específico para número de orden */
#calificaciones-table thead th:first-child {
    background-color: #03366c;
}

#calificaciones-table #asignaturas-header {
    background-color: #1067c4;
    border: 1px solid rgb(211, 237, 255);
}

/* Color para los encabezados de asignaturas generadas */
#calificaciones-table #materias-row th {
    background-color: #f8f8f8;
    color: #333;
    font-weight: bold;
    border: 1px solid #ddd;
}

/* Ajustar altura de las celdas de encabezado para los textos verticales */
#calificaciones-table th .vertical-text {
    min-height: 150px;
}

#calificaciones-table td:nth-child(3), #calificaciones-table td:nth-child(7), #calificaciones-table td:nth-child(8), #calificaciones-table td:nth-child(9) {
    width: 45px;
    padding: 6px;
}
/* Estilos para el selector de modo */
.mode-selector {
    margin-bottom: 1rem;
}

.mode-selector .btn {
    padding: 0.5rem 1.5rem;
}


/* Estilos para inputs */
.form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
#calificaciones-table td {
    padding: 2px 2px;
}

/* Mejorar visibilidad de las filas alternadas */
#calificaciones-table tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
}

#calificaciones-table tbody tr:hover {
    background-color: #f1f1f1;
}
</style>
@endsection
