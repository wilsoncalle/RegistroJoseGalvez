@extends('layouts.app')

@section('title', 'Nueva Asignación - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Nueva Asignación</h1>
        <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Asignaciones
        </a>
    </div>

    <div class="card">
        <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
            <form action="{{ route('asignaciones.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos de Asignación</h5>
                        <hr>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Campo Nivel -->
                    <div class="col-md-3">
                        <label for="nivel" class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select name="nivel" id="nivel" class="form-select" required>
                            <option value="">Seleccione un nivel</option>
                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel->id_nivel }}">{{ $nivel->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Campo Docente -->
                    <div class="col-md-4">
                        <label for="id_docente" class="form-label">Docente <span class="text-danger">*</span></label>
                        <select name="id_docente" id="id_docente" class="form-select" required disabled>
                            <option value="">Seleccione un nivel primero</option>
                        </select>
                    </div>
                    <!-- Campo Materia -->
                    <div class="col-md-3">
                        <label for="id_materia" class="form-label">Materia <span class="text-danger">*</span></label>
                        <select name="id_materia" id="id_materia" class="form-select" required disabled>
                            <option value="">Seleccione un nivel primero</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Campo Aula -->
                    <div class="col-md-4">
                        <label for="id_aula" class="form-label">Aula <span class="text-danger">*</span></label>
                        <select name="id_aula" id="id_aula" class="form-select" required disabled>
                            <option value="">Primero seleccione un nivel</option>
                        </select>
                    </div>
                    <!-- Campo Año Académico -->
                    <div class="col-md-3">
                        <label for="id_anio" class="form-label">Año Académico <span class="text-danger">*</span></label>
                        <select name="id_anio" id="id_anio" class="form-select" required>
                            <option value="">Seleccione un año académico</option>
                            @foreach($anios as $anio)
                                <option value="{{ $anio->id_anio }}" {{ old('id_anio') == $anio->id_anio ? 'selected' : '' }}>
                                    {{ $anio->anio }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @push('scripts')
                <script>
                    // URLs base para cargar aulas, materias y docentes según el nivel seleccionado
                    const urlAulas = "{{ url('/aulas/nivel') }}";
                    const urlMaterias = "{{ url('/materias/nivel') }}";
                    const urlDocentes = "{{ url('/docentes/nivel') }}";

                    document.addEventListener('DOMContentLoaded', function() {
                        const nivelSelect = document.getElementById('nivel');
                        const docenteSelect = document.getElementById('id_docente');
                        const materiaSelect = document.getElementById('id_materia');
                        const aulaSelect = document.getElementById('id_aula');

                        // Al cambiar el nivel, se actualizan docentes, aulas y materias
                        nivelSelect.addEventListener('change', function() {
                            const nivelId = this.value;

                            // Cargar docentes
                            docenteSelect.innerHTML = '<option value="">Cargando docentes...</option>';
                            docenteSelect.disabled = true;
                            if (nivelId) {
                                fetch(`${urlDocentes}/${nivelId}`)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Error en la red');
                                        }
                                        return response.json();
                                    })
                                    .then(docentes => {
                                        docenteSelect.innerHTML = '';
                                        if(docentes.length > 0) {
                                            const defaultOption = document.createElement('option');
                                            defaultOption.value = '';
                                            defaultOption.textContent = 'Seleccione un docente';
                                            docenteSelect.appendChild(defaultOption);
                                            docentes.forEach(docente => {
                                                const option = document.createElement('option');
                                                option.value = docente.id_docente;
                                                option.textContent = docente.nombre_completo;
                                                docenteSelect.appendChild(option);
                                            });
                                            docenteSelect.disabled = false;
                                        } else {
                                            docenteSelect.innerHTML = '<option value="">No hay docentes disponibles para este nivel</option>';
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        docenteSelect.innerHTML = '<option value="">Error al cargar docentes</option>';
                                    });
                            } else {
                                docenteSelect.innerHTML = '<option value="">Seleccione un nivel primero</option>';
                                docenteSelect.disabled = true;
                            }

                            // Cargar aulas (igual que antes)
                            aulaSelect.innerHTML = '<option value="">Cargando aulas...</option>';
                            aulaSelect.disabled = true;
                            if (nivelId) {
                                fetch(`${urlAulas}/${nivelId}`)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Error en la red');
                                        }
                                        return response.json();
                                    })
                                    .then(aulas => {
                                        aulaSelect.innerHTML = '';
                                        if(aulas.length > 0) {
                                            const defaultOption = document.createElement('option');
                                            defaultOption.value = '';
                                            defaultOption.textContent = 'Seleccione un aula';
                                            aulaSelect.appendChild(defaultOption);
                                            aulas.forEach(aula => {
                                                const option = document.createElement('option');
                                                option.value = aula.id;
                                                option.textContent = aula.nombre_completo;
                                                aulaSelect.appendChild(option);
                                            });
                                            aulaSelect.disabled = false;
                                        } else {
                                            aulaSelect.innerHTML = '<option value="">No hay aulas disponibles para este nivel</option>';
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        aulaSelect.innerHTML = '<option value="">Error al cargar aulas</option>';
                                    });
                            } else {
                                aulaSelect.innerHTML = '<option value="">Primero seleccione un nivel</option>';
                                aulaSelect.disabled = true;
                            }
                            
                            // Cargar materias (igual que antes)
                            materiaSelect.innerHTML = '<option value="">Cargando materias...</option>';
                            materiaSelect.disabled = true;
                            if (nivelId) {
                                fetch(`${urlMaterias}/${nivelId}`)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Error en la red');
                                        }
                                        return response.json();
                                    })
                                    .then(materias => {
                                        materiaSelect.innerHTML = '';
                                        if(materias.length > 0) {
                                            const defaultOption = document.createElement('option');
                                            defaultOption.value = '';
                                            defaultOption.textContent = 'Seleccione una materia';
                                            materiaSelect.appendChild(defaultOption);
                                            materias.forEach(materia => {
                                                const option = document.createElement('option');
                                                option.value = materia.id_materia;
                                                option.textContent = materia.nombre;
                                                materiaSelect.appendChild(option);
                                            });
                                            materiaSelect.disabled = false;
                                        } else {
                                            materiaSelect.innerHTML = '<option value="">No hay materias disponibles para este nivel</option>';
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        materiaSelect.innerHTML = '<option value="">Error al cargar materias</option>';
                                    });
                            } else {
                                materiaSelect.innerHTML = '<option value="">Seleccione un nivel primero</option>';
                                materiaSelect.disabled = true;
                            }
                        });
                    });
                </script>
                @endpush

                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Asignación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
