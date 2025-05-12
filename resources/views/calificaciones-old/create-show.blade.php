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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos de PHP que necesitamos en JavaScript
    const aulaId = "{{ $aula->id_aula }}";
    const nivelId = "{{ $aula->nivel->id_nivel }}";
    const getCalificacionesUrl = "{{ route('calificaciones-old.get-calificaciones') }}";
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
    
    // Elementos para exportación
    const exportDropdownBtn = document.getElementById('exportDropdown');
    const exportExcelBtn = document.getElementById('export-excel-btn');
    const exportPdfBtn = document.getElementById('export-pdf-btn');
    
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
    
    // Función para mostrar las calificaciones en la tabla
    function mostrarCalificaciones() {
        // Actualizar el encabezado de asignaturas para abarcar todas las materias
        asignaturasHeader.colSpan = materias.length;
        
        // Aplicar el modo actual (lectura o edición)
        if (currentMode === 'read') {
            setReadOnlyMode(true);
        } else {
            setReadOnlyMode(false);
        }
        
        // Limpiar filas anteriores
        materiasRow.innerHTML = '';
        document.getElementById('letras-row').innerHTML = '<td colspan="4"></td><td colspan="3"></td>';
        calificacionesBody.innerHTML = '';
        
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
            
            // Agregar letras del abecedario en la fila de letras
            const letrasRow = document.getElementById('letras-row');
            const tdLetra = document.createElement('td');
            tdLetra.className = 'text-center';
            tdLetra.style.fontWeight = 'bold';
            tdLetra.style.backgroundColor = '#f8f9fa'; // Fondo gris claro para destacar
            
            // Obtener la letra correspondiente (a, b, c, ...)
            const letra = String.fromCharCode(97 + index); // 97 es el código ASCII para 'a'
            tdLetra.textContent = letra;
            
            // Insertar la letra antes del último td (que es el colspan="3")
            letrasRow.insertBefore(tdLetra, letrasRow.lastChild);
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
            
            // Determinar situación final (A = Aprobado, P = Pendiente)
            const situacion = asignaturasDesaprobadas > 3 ? 'R' : 'A';
            tdSituacion.textContent = situacion;
            
            // Colorear según situación
            if (situacion === 'A') {
                tdSituacion.classList.add('text-success');
            } else {
                tdSituacion.classList.add('text-danger');
            }
            
            tr.appendChild(tdSituacion);
            
            // Agregar fila a la tabla
            calificacionesBody.appendChild(tr);
        });
        
        // Configurar evento para recalcular asignaturas desaprobadas
        document.querySelectorAll('.nota-input').forEach(input => {
            input.addEventListener('change', actualizarDesaprobadas);
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
        const idEstudiante = event.target.closest('td').getAttribute('data-id-estudiante');
        const fila = document.querySelector(`tr[data-id-estudiante="${idEstudiante}"]`);
        const inputs = fila.querySelectorAll('.nota-input');
        
        let desaprobadas = 0;
        inputs.forEach(input => {
            if (input.value && parseFloat(input.value) < 11) {
                desaprobadas++;
            }
        });
        
        // Actualizar celda de asignaturas desaprobadas
        const tdDesaprobadas = fila.querySelector('.asignaturas-desaprobadas');
        tdDesaprobadas.textContent = desaprobadas;
        
        // Actualizar situación final
        const tdSituacion = fila.querySelector('.situacion-final');
        const situacion = desaprobadas > 0 ? 'R' : 'A';
        tdSituacion.textContent = situacion;
        
        // Actualizar color
        tdSituacion.classList.remove('text-success', 'text-danger');
        if (situacion === 'A') {
            tdSituacion.classList.add('text-success');
        } else {
            tdSituacion.classList.add('text-danger');
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
            const situacionFinal = fila.querySelector('.situacion-final').textContent;
            
            // Para cada materia de este estudiante
            notaCells.forEach(cell => {
                const idMateria = cell.getAttribute('data-id-materia');
                const idAsignacion = cell.getAttribute('data-id-asignacion');
                const notaInput = cell.querySelector('input');
                const nota = notaInput.value.trim();
                
                if (nota) { // Solo guardar si hay una nota
                    calificaciones.push({
                        id_estudiante: idEstudiante,
                        id_asignacion: idAsignacion, // Usar el id_asignacion correcto
                        id_trimestre: idTrimestre,
                        nota: nota,
                        comportamiento: comportamientoInput.value || 0,
                        asignaturas_reprobadas: asignaturasDesaprobadas,
                        conclusion: situacionFinal === 'A' ? 'Aprobado' : 'Reprobado',
                        grado: "{{ $aula->grado->nombre }} '{{ $aula->seccion->nombre }}'",
                        fecha: new Date().toISOString().split('T')[0],
                        año: año
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
                alert('Calificaciones guardadas correctamente');
                // Recargar datos para mostrar cambios
                cargarCalificaciones();
            } else {
                alert('Error al guardar calificaciones: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar calificaciones');
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
    
    // Eventos para cargar datos cuando cambien los filtros
    añoSelect.addEventListener('change', cargarCalificaciones);
    trimestreSelect.addEventListener('change', cargarCalificaciones);
    promocionSelect.addEventListener('change', cargarCalificaciones);
    
    // Inicializar el sistema moderno de arrastrar y soltar
    setTimeout(initDragAndDrop, 500); // Pequeño retraso para asegurar que todo esté cargado
});
</script>
@endpush

<style>
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
