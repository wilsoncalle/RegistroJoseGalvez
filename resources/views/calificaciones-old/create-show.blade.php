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
        
        // Limpiar filas anteriores
        materiasRow.innerHTML = '';
        calificacionesBody.innerHTML = '';
        
        // Agregar columnas de materias rotadas
        materias.forEach(materia => {
            const th = document.createElement('th');
            th.className = 'text-center materia-column';
            th.setAttribute('data-id-materia', materia.id_materia);
            th.setAttribute('data-id-asignacion', materia.id_asignacion);
            
            const divContainer = document.createElement('div');
            divContainer.className = 'vertical-text';
            divContainer.textContent = materia.nombre;
            
            th.appendChild(divContainer);
            materiasRow.appendChild(th);
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
        const situacion = desaprobadas > 0 ? 'P' : 'A';
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
                        conclusion: situacionFinal === 'A' ? 'Aprobado' : 'Pendiente',
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
});
</script>
@endpush

<style>
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
