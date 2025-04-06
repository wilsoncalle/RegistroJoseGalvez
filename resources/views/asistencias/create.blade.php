@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('asistencias.nivel', $aula->nivel->nombre) }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                    <h2 class="mb-0">Tomar Asistencia - {{ $aula->nivel->nombre }} {{ $aula->grado->nombre }} "{{ $aula->seccion->nombre }}"</h2>
                </div>
                <a href="{{ route('asistencias.index-niveles', $aula->nivel->nombre) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Secciones
                </a>
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

    <form action="{{ route('asistencias.store') }}" method="POST" id="asistenciaForm">
        @csrf
        <input type="hidden" name="id_aula" value="{{ $aula->id_aula }}">
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" 
                           value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="id_materia">Materia</label>
                    <select class="form-control" id="id_materia" name="id_materia" required>
                        <option value="">Seleccione una materia</option>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->id_materia }}">
                                {{ $materia->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="id_docente">Docente</label>
                    <select class="form-control" id="id_docente" name="id_docente" required disabled>
                        <option value="">Seleccione una materia primero</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <!-- Columna para el número de orden con título en vertical -->
                                <th style="width: 80px;">N° Orden</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estudiantes as $estudiante)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $estudiante->nombre }}</td>
                                    <td>{{ $estudiante->apellido }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <input type="radio" class="btn-check" name="asistencias[{{ $estudiante->id_estudiante }}]" 
                                                   id="presente_{{ $estudiante->id_estudiante }}" value="P" checked>
                                            <label class="btn btn-outline-success" for="presente_{{ $estudiante->id_estudiante }}">
                                                P
                                            </label>

                                            <input type="radio" class="btn-check" name="asistencias[{{ $estudiante->id_estudiante }}]" 
                                                   id="tardanza_{{ $estudiante->id_estudiante }}" value="T">
                                            <label class="btn btn-outline-warning" for="tardanza_{{ $estudiante->id_estudiante }}">
                                                T
                                            </label>

                                            <input type="radio" class="btn-check" name="asistencias[{{ $estudiante->id_estudiante }}]" 
                                                   id="falta_{{ $estudiante->id_estudiante }}" value="F">
                                            <label class="btn btn-outline-danger" for="falta_{{ $estudiante->id_estudiante }}">
                                                F
                                            </label>

                                            <input type="radio" class="btn-check" name="asistencias[{{ $estudiante->id_estudiante }}]" 
                                                   id="justificado_{{ $estudiante->id_estudiante }}" value="J">
                                            <label class="btn btn-outline-info" for="justificado_{{ $estudiante->id_estudiante }}">
                                                J
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <a href="{{ route('asistencias.nivel', $aula->nivel->nombre) }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    Guardar Asistencias
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const materiaSelect = document.getElementById('id_materia');
    const docenteSelect = document.getElementById('id_docente');
    const aulaId = document.querySelector('input[name="id_aula"]').value;

    materiaSelect.addEventListener('change', function() {
        const materiaId = this.value;
        console.log('Materia seleccionada:', materiaId);
        console.log('Aula ID:', aulaId);
        
        if (materiaId && aulaId) {
            fetch(`/asistencias/docentes/${materiaId}/${aulaId}`)
                .then(response => {
                    console.log('Respuesta del servidor:', response);
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Docentes recibidos:', data);
                    
                    docenteSelect.innerHTML = '<option value="">Seleccione un docente</option>';
                    
                    data.forEach(docente => {
                        const option = document.createElement('option');
                        option.value = docente.id_docente;
                        option.textContent = `${docente.nombre} ${docente.apellido || ''}`.trim();
                        docenteSelect.appendChild(option);
                    });
                    
                    docenteSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error completo:', error);
                    docenteSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
                    docenteSelect.disabled = true;
                });
        } else {
            docenteSelect.innerHTML = '<option value="">Seleccione una materia primero</option>';
            docenteSelect.disabled = true;
        }
    });
});
</script>
@endpush

<style>
.vertical-header {
    writing-mode: vertical-rl;
    text-orientation: mixed;
    /* Opcional: centrar el texto verticalmente y ajustar márgenes */
    vertical-align: middle;
    padding: 5px;
}

.btn-check {
    position: absolute;
    clip: rect(0,0,0,0);
    pointer-events: none;
}

.btn-check:checked + .btn {
    color: #fff;
}

.btn-check:checked + .btn-outline-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-check:checked + .btn-outline-warning {
    background-color: #ffc107;
    border-color: #ffc107;
}

.btn-check:checked + .btn-outline-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-check:checked + .btn-outline-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
}
</style>
@endsection
