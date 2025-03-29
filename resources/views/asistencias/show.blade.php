@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Control de Asistencia - {{ $aula->nivel->nombre }} {{ $aula->grado->nombre }} "{{ $aula->seccion->nombre }}"</h2>
                <a href="{{ route('asistencias.index-niveles', $aula->nivel->nombre) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Secciones
                </a>
            </div>
        </div>
    </div>

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

    <div class="row mb-4">
        <div class="col-md-3">
            <label for="materia" class="form-label">Materia</label>
            <select id="materia" class="form-select" required>
                <option value="">Seleccionar Materia</option>
                @foreach($materias as $materia)
                    <option value="{{ $materia->id_materia }}">{{ $materia->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="docente" class="form-label">Docente</label>
            <select id="docente" class="form-select" disabled required>
                <option value="">Primero seleccione una materia</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="mes" class="form-label">Mes</label>
            <select id="mes" class="form-select" required>
                @foreach(range(1,12) as $month)
                    <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $month)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="año" class="form-label">Año</label>
            <select id="año" class="form-select" required>
                @foreach(range(date('Y') - 2, date('Y') + 1) as $year)
                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Mensaje de carga -->
    <div id="loading-message" class="alert alert-info">
        Seleccione los filtros de asistencia para cargar los datos
    </div>

    <!-- Contenedor de la tabla -->
    <div id="attendance-container" class="d-none">
        <div id="edit-mode-controls" class="mb-3 d-none">
            <button id="save-attendance-btn" class="btn btn-primary">Guardar Cambios</button>
            <div class="mt-2 alert alert-warning">
                <i class="bi bi-info-circle"></i> Haga clic en los estados de asistencia para modificarlos
            </div>
        </div>
        <table class="table table-bordered m-0" id="attendance-table">
            <thead>
                <!-- Se genera dinámicamente en JS -->
            </thead>
            <tbody id="attendance-body">
                <!-- Se llenan los datos de asistencia -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para selección de estado de asistencia -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceModalLabel">Modificar Asistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="student-id">
                <input type="hidden" id="attendance-date">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-success attendance-option" data-value="P">Presente</button>
                    <button class="btn btn-warning attendance-option" data-value="T">Tardanza</button>
                    <button class="btn btn-danger attendance-option" data-value="F">Falta</button>
                    <button class="btn btn-info attendance-option" data-value="J">Justificado</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Opciones rápidas de asistencia (visible solo en modo edición) -->
<div id="quick-attendance-options" class="position-fixed d-none" style="background: white; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); padding: 5px;">
    <div class="d-flex">
        <button class="btn btn-sm btn-success quick-option mx-1" data-value="P">P</button>
        <button class="btn btn-sm btn-warning quick-option mx-1" data-value="T">T</button>
        <button class="btn btn-sm btn-danger quick-option mx-1" data-value="F">F</button>
        <button class="btn btn-sm btn-info quick-option mx-1" data-value="J">J</button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos de PHP que necesitamos en JavaScript
    const aulaId = @json($aula->id_aula);
    const updateAttendanceUrl = @json(route('asistencias.update-attendance'));
    const getDetailsUrl = @json(route('asistencias.get-details'));
    const getDocentesUrl = @json(route('asistencias.docentes', ['materiaId' => ':materiaId', 'aulaId' => $aula->id_aula]));
    
    const materiaSelect = document.getElementById('materia');
    const docenteSelect = document.getElementById('docente');
    const mesSelect = document.getElementById('mes');
    const añoSelect = document.getElementById('año');
    const attendanceContainer = document.getElementById('attendance-container');
    const attendanceBody = document.getElementById('attendance-body');
    const loadingMessage = document.getElementById('loading-message');
    const modeRead = document.getElementById('modeRead');
    const modeEdit = document.getElementById('modeEdit');
    const editModeControls = document.getElementById('edit-mode-controls');
    const saveAttendanceBtn = document.getElementById('save-attendance-btn');
    
    // Variables para el modo edición
    let currentMode = 'read';
    let attendanceData = null;
    let modifiedAttendance = {};
    
    // Inicializar el modal
    const attendanceModal = new bootstrap.Modal(document.getElementById('attendanceModal'));
    const quickOptionsPanel = document.getElementById('quick-attendance-options');
    
    // Event listeners para cambio de modo
    modeRead.addEventListener('change', function() {
        if (this.checked) {
            currentMode = 'read';
            editModeControls.classList.add('d-none');
            if (attendanceData) {
                renderAttendanceTable(attendanceData);
            }
        }
    });
    
    modeEdit.addEventListener('change', function() {
        if (this.checked) {
            currentMode = 'edit';
            editModeControls.classList.remove('d-none');
            if (attendanceData) {
                renderAttendanceTable(attendanceData);
            }
        }
    });
    
    // Event listener para guardar cambios
    saveAttendanceBtn.addEventListener('click', function() {
        saveAttendanceChanges();
    });
    
    // Event listeners para opciones de asistencia en el modal y panel rápido
    document.querySelectorAll('.attendance-option, .quick-option').forEach(button => {
        button.addEventListener('click', function() {
            const studentId = document.getElementById('student-id').value;
            const date = document.getElementById('attendance-date').value;
            const value = this.getAttribute('data-value');
            
            // Guardar el cambio en el objeto de modificaciones
            if (!modifiedAttendance[studentId]) {
                modifiedAttendance[studentId] = {};
            }
            modifiedAttendance[studentId][date] = value;
            
            // Actualizar la celda en la tabla
            const cell = document.querySelector(`[data-student="${studentId}"][data-date="${date}"]`);
            if (cell) {
                updateAttendanceCell(cell, value);
            }
            
            attendanceModal.hide();
            quickOptionsPanel.classList.add('d-none');
        });
    });

    materiaSelect.addEventListener('change', function() {
        const materiaId = this.value;
        docenteSelect.disabled = true;
        docenteSelect.innerHTML = '<option value="">Cargando...</option>';

        if (!materiaId) {
            docenteSelect.innerHTML = '<option value="">Primero seleccione una materia</option>';
            return;
        }

        const url = getDocentesUrl.replace(':materiaId', materiaId);
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                docenteSelect.innerHTML = '<option value="">Seleccionar Docente</option>';
                data.forEach(docente => {
                    const option = document.createElement('option');
                    option.value = docente.id_docente;
                    option.textContent = `${docente.nombre} ${docente.apellido}`;
                    docenteSelect.appendChild(option);
                });
                docenteSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                docenteSelect.innerHTML = '<option value="">Error al cargar docentes</option>';
            });
    });

    [docenteSelect, mesSelect, añoSelect].forEach(select => {
        select.addEventListener('change', loadAttendanceDetails);
    });

    function loadAttendanceDetails() {
        if (materiaSelect.value && docenteSelect.value && mesSelect.value && añoSelect.value) {
            loadingMessage.classList.remove('d-none');
            attendanceContainer.classList.add('d-none');
            modifiedAttendance = {}; // Reiniciar modificaciones al cargar nuevos datos

            const data = {
                id_aula: aulaId,
                id_materia: materiaSelect.value,
                id_docente: docenteSelect.value,
                mes: mesSelect.value,
                año: añoSelect.value
            };

            fetch(getDetailsUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                attendanceData = data; // Guardar los datos para uso posterior
                renderAttendanceTable(data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los detalles de asistencia');
                loadingMessage.classList.add('d-none');
            });
        }
    }

    function renderAttendanceTable(data) {
        const selectedYear = parseInt(document.getElementById('año').value);
        const selectedMonth = parseInt(document.getElementById('mes').value);
        const totalDays = data.total_days;

        // Obtener días hábiles (lunes a viernes)
        const schoolDays = [];
        for (let day = 1; day <= totalDays; day++) {
            const date = new Date(selectedYear, selectedMonth - 1, day);
            const dayOfWeek = date.getDay();
            if (dayOfWeek >= 1 && dayOfWeek <= 5) {
                let initial = '';
                switch(dayOfWeek) {
                    case 1: initial = 'L'; break;
                    case 2: initial = 'M'; break;
                    case 3: initial = 'M'; break;
                    case 4: initial = 'J'; break;
                    case 5: initial = 'V'; break;
                }
                schoolDays.push({ day, initial, dayOfWeek });
            }
        }

        // Agrupar los días en semanas
        const weeks = [];
        let currentWeek = [];
        schoolDays.forEach(sd => {
            if (sd.dayOfWeek === 1 && currentWeek.length > 0) {
                weeks.push(currentWeek);
                currentWeek = [];
            }
            currentWeek.push(sd);
        });
        if (currentWeek.length > 0) {
            weeks.push(currentWeek);
        }

        // Construir la cabecera de la tabla (3 filas)
        const table = document.getElementById('attendance-table');
        const thead = table.querySelector('thead');
        thead.innerHTML = '';

        // Primera fila: "Apellidos y Nombres", semanas y Totales
        const weekRow = document.createElement('tr');
        const nameHeader = document.createElement('th');
        nameHeader.textContent = 'Apellidos y Nombres';
        nameHeader.rowSpan = 3;
        nameHeader.classList.add('th-blue'); // Encabezado de estudiantes azul
        weekRow.appendChild(nameHeader);

        weeks.forEach((week, index) => {
            const weekHeader = document.createElement('th');
            weekHeader.textContent = `Semana ${index + 1}`;
            weekHeader.colSpan = week.length;
            weekHeader.classList.add('th-black'); // Encabezado de semanas negro
            weekRow.appendChild(weekHeader);
        });
        // Agregar la cabecera de Totales con rowSpan de 3
        const totalsHeader = document.createElement('th');
        totalsHeader.textContent = 'Totales';
        totalsHeader.rowSpan = 3;
        totalsHeader.classList.add('th-celeste');
        weekRow.appendChild(totalsHeader);

        // Segunda fila: Iniciales de los días (usando las semanas)
        const dayInitialsRow = document.createElement('tr');
        weeks.forEach(week => {
            week.forEach(sd => {
                const initialCell = document.createElement('th');
                initialCell.textContent = sd.initial;
                initialCell.classList.add('th-grey');
                dayInitialsRow.appendChild(initialCell);
            });
        });

        // Tercera fila: Números de días y (sin celda para Totales, ya que está en la primera fila)
        const dateRow = document.createElement('tr');
        weeks.forEach(week => {
            week.forEach(sd => {
                const dateHeader = document.createElement('th');
                dateHeader.textContent = sd.day;
                dateHeader.classList.add('th-grey');
                dateRow.appendChild(dateHeader);
            });
        });

        thead.appendChild(weekRow);
        thead.appendChild(dayInitialsRow);
        thead.appendChild(dateRow);

        // Construir el cuerpo de la tabla con los datos de asistencia
        const tbody = document.getElementById('attendance-body');
        tbody.innerHTML = '';

        // Generar filas para cada estudiante
        Object.entries(data.attendance_details).forEach(([studentId, studentData]) => {
            const row = document.createElement('tr');
            
            // Celda de nombre de estudiante
            const nameCell = document.createElement('td');
            nameCell.textContent = studentData.nombre;
            nameCell.classList.add('student-name');
            row.appendChild(nameCell);

            // Celdas para cada día escolar
            schoolDays.forEach(sd => {
                const cell = document.createElement('td');
                const day = sd.day;
                const status = studentData.daily_attendance[day];
                
                // Verificar si hay modificaciones para esta celda
                if (modifiedAttendance[studentId] && modifiedAttendance[studentId][day]) {
                    updateAttendanceCell(cell, modifiedAttendance[studentId][day]);
                } else {
                    updateAttendanceCell(cell, status);
                }
                
                // Agregar atributos para identificar la celda
                cell.setAttribute('data-student', studentId);
                cell.setAttribute('data-date', day);
                
                // En modo edición, hacer las celdas clickeables
                if (currentMode === 'edit') {
                    cell.classList.add('editable-cell');
                    cell.addEventListener('click', function() {
                        if (status || modifiedAttendance[studentId]?.[day]) {
                            handleAttendanceCellClick(cell);
                        }
                    });
                }
                
                row.appendChild(cell);
            });

            // Agregar la celda de Totales para cada estudiante
            const totalsCell = document.createElement('td');
            const stats = studentData.monthly_stats;
            totalsCell.textContent = `P:${stats.P} T:${stats.T} F:${stats.F} J:${stats.J}`;
            row.appendChild(totalsCell);

            tbody.appendChild(row);
        });

        // Mostrar la tabla y ocultar mensaje de carga
        loadingMessage.classList.add('d-none');
        attendanceContainer.classList.remove('d-none');
        
        // Mostrar/ocultar controles de edición según el modo
        if (currentMode === 'edit') {
            editModeControls.classList.remove('d-none');
        } else {
            editModeControls.classList.add('d-none');
        }
    }
    
    function updateAttendanceCell(cell, status) {
        cell.textContent = status || '';
        cell.className = ''; // Limpiar clases existentes
        
        if (status) {
            switch(status) {
                case 'P':
                    cell.classList.add('attendance-P');
                    break;
                case 'T':
                    cell.classList.add('attendance-T');
                    break;
                case 'F':
                    cell.classList.add('attendance-F');
                    break;
                case 'J':
                    cell.classList.add('attendance-J');
                    break;
            }
        }
        
        if (currentMode === 'edit') {
            cell.classList.add('editable-cell');
        }
    }
    
    function handleAttendanceCellClick(cell) {
        if (currentMode === 'edit') {
            const studentId = cell.getAttribute('data-student');
            const date = cell.getAttribute('data-date');
            
            document.getElementById('student-id').value = studentId;
            document.getElementById('attendance-date').value = date;
            
            // Mostrar opciones rápidas en lugar del modal
            const rect = cell.getBoundingClientRect();
            quickOptionsPanel.style.top = `${rect.bottom + window.scrollY + 5}px`;
            quickOptionsPanel.style.left = `${rect.left + window.scrollX}px`;
            quickOptionsPanel.classList.remove('d-none');
            
            // Cerrar el panel al hacer clic fuera
            const closeQuickPanel = function(e) {
                if (!quickOptionsPanel.contains(e.target) && e.target !== cell) {
                    quickOptionsPanel.classList.add('d-none');
                    document.removeEventListener('click', closeQuickPanel);
                }
            };
            
            // Pequeño retraso para evitar que se cierre inmediatamente
            setTimeout(() => {
                document.addEventListener('click', closeQuickPanel);
            }, 100);
        }
    }
    
    function saveAttendanceChanges() {
        if (Object.keys(modifiedAttendance).length === 0) {
            alert('No hay cambios para guardar');
            return;
        }
        
        const data = {
            id_aula: aulaId,
            id_materia: materiaSelect.value,
            id_docente: docenteSelect.value,
            mes: mesSelect.value,
            año: añoSelect.value,
            modificaciones: modifiedAttendance
        };
        
        saveAttendanceBtn.disabled = true;
        saveAttendanceBtn.textContent = 'Guardando...';
        
        fetch(updateAttendanceUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cambios guardados correctamente');
                modifiedAttendance = {}; // Reiniciar modificaciones
                loadAttendanceDetails(); // Recargar datos
            } else {
                alert('Error al guardar los cambios: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar los cambios');
        })
        .finally(() => {
            saveAttendanceBtn.disabled = false;
            saveAttendanceBtn.textContent = 'Guardar Cambios';
        });
    }
});
</script>
@endpush

<style>
/* Estilos para la tabla de asistencia */
#attendance-table {
    border-collapse: collapse;
    border-radius: 10px; /* Ajusta el valor según el radio deseado */
    overflow: hidden; /* Evita que el contenido se desborde fuera de las esquinas redondeadas */
    width: 100%;
}

#attendance-table td, 
#attendance-table th {
    padding: 8px;
    text-align: center;
}

/* Encabezados de estudiantes: fondo azul */
.th-blue {
    background-color: #007bff !important; /* Azul */
    color: #fff !important;
    font-weight: bold;
}

/* Encabezados de semanas: fondo negro */
.th-black {
    background-color: #000 !important;
    color: #fff !important;
    font-weight: bold;
}

/* Encabezados de días: fondo gris */
.th-grey {
    background-color: #808080 !important;
    color: #fff !important;
    font-weight: bold;
}

/* Estilo para la cabecera de Totales */
.th-celeste {
    background-color: #17a2b8 !important;
    color: #fff !important;
    font-weight: bold;
}

/* Alinear a la izquierda la primera columna */
#attendance-table td:first-child,
#attendance-table th:first-child {
    text-align: left;
}

/* Estilos para estados de asistencia */
.attendance-P { 
    background-color: #28a745 !important; 
    color: rgb(242, 249, 244) !important; 
    font-weight: bold;
}
.attendance-T { 
    background-color: #ffc107 !important; 
    color: rgb(248, 246, 241) !important; 
    font-weight: bold;
}
.attendance-F { 
    background-color: #dc3545 !important; 
    color: rgb(249, 244, 244) !important; 
    font-weight: bold;
}
.attendance-J { 
    background-color: #17a2b8 !important; 
    color: rgb(248, 248, 248) !important; 
    font-weight: bold;
}

.student-name {
    text-align: left;
    font-weight: 500;
    background-color: #f8f9fa;
}

/* Estilos para el modo edición */
.editable-cell {
    cursor: pointer;
    transition: all 0.2s;
}

.editable-cell:hover {
    transform: scale(1.1);
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
}

/* Estilos para el selector de modo */
.mode-selector {
    margin-bottom: 1rem;
}

.mode-selector .btn {
    padding: 0.5rem 1.5rem;
}

/* Estilos para el modal y opciones rápidas */
.attendance-option, .quick-option {
    font-weight: bold;
}

.btn-success.attendance-option, .btn-success.quick-option {
    color: white;
}

.btn-warning.attendance-option, .btn-warning.quick-option {
    color: white;
}

.btn-danger.attendance-option, .btn-danger.quick-option {
    color: white;
}

.btn-info.attendance-option, .btn-info.quick-option {
    color: white;
}
</style>
@endsection
