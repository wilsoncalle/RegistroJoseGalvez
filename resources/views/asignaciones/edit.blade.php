@extends('layouts.app')

@section('title', 'Editar Asignación - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Editar Asignación</h1>
        <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Asignaciones
        </a>
    </div>

    <div class="card">
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

            <form action="{{ route('asignaciones.update', ['asignacione' => $asignacion->id_asignacion]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos de Asignación</h5>
                        <hr>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Campo Docente -->
                    <div class="col-md-4">
                        <label for="id_docente" class="form-label">Docente <span class="text-danger">*</span></label>
                        <select name="id_docente" id="id_docente" class="form-select" required>
                            <option value="">Seleccione un docente</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id_docente }}"
                                    data-materia="{{ $docente->id_materia ?? '' }}"
                                    data-nivel="{{ $docente->id_nivel ?? '' }}"
                                    {{ $asignacion->id_docente == $docente->id_docente ? 'selected' : '' }}>
                                    {{ $docente->nombre }} {{ $docente->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Campo Nivel -->
                    <div class="col-md-3">
                        <label for="nivel" class="form-label">Nivel <span class="text-danger">*</span></label>
                        @php
                            $nivelSeleccionado = $asignacion->aula ? $asignacion->aula->id_nivel : null;
                        @endphp
                        <select name="nivel" id="nivel" class="form-select" required>
                            <option value="">Seleccione un nivel</option>
                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel->id_nivel }}" {{ $nivelSeleccionado == $nivel->id_nivel ? 'selected' : '' }}>
                                    {{ $nivel->nombre }}
                                </option>
                            @endforeach
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
                            <option value="">Seleccione un nivel primero</option>
                        </select>
                    </div>
                    <!-- Campo Año Académico -->
                    <div class="col-md-3">
                        <label for="id_anio" class="form-label">Año Académico <span class="text-danger">*</span></label>
                        <select name="id_anio" id="id_anio" class="form-select" required>
                            <option value="">Seleccione un año académico</option>
                            @foreach($anios as $anio)
                                <option value="{{ $anio->id_anio }}" {{ $asignacion->id_anio == $anio->id_anio ? 'selected' : '' }}>
                                    {{ $anio->anio }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Actualizar Asignación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // URLs base para cargar aulas y materias según el nivel seleccionado
    const urlAulas = "{{ url('/aulas/nivel') }}";
    const urlMaterias = "{{ url('/materias/nivel') }}";

    document.addEventListener('DOMContentLoaded', function() {
        const docenteSelect = document.getElementById('id_docente');
        const nivelSelect = document.getElementById('nivel');
        const materiaSelect = document.getElementById('id_materia');
        const aulaSelect = document.getElementById('id_aula');

        // Valores previos de la asignación
        const asignacionMateriaId = "{{ old('id_materia', $asignacion->id_materia) }}";
        const asignacionAulaId = "{{ old('id_aula', $asignacion->id_aula) }}";

        // Al seleccionar un docente se asigna automáticamente su nivel y se recargan aulas y materias
        docenteSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const docenteNivel = selectedOption.getAttribute('data-nivel');
            if (docenteNivel) {
                nivelSelect.value = docenteNivel;
                nivelSelect.disabled = true;
            } else {
                nivelSelect.value = '';
                nivelSelect.disabled = false;
            }
            nivelSelect.dispatchEvent(new Event('change'));
        });

        function cargarAulas(nivelId, selectedAula = '') {
            aulaSelect.innerHTML = '<option value="">Cargando aulas...</option>';
            aulaSelect.disabled = true;
            if (nivelId) {
                fetch(`${urlAulas}/${nivelId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Error en la red');
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
                                if(aula.id == selectedAula) {
                                    option.selected = true;
                                }
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
        }

        function cargarMaterias(nivelId, selectedMateria = '') {
            materiaSelect.innerHTML = '<option value="">Cargando materias...</option>';
            materiaSelect.disabled = true;
            if (nivelId) {
                fetch(`${urlMaterias}/${nivelId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Error en la red');
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
                                if(materia.id_materia == selectedMateria) {
                                    option.selected = true;
                                }
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
        }

        // Si ya hay un nivel seleccionado, carga automáticamente las aulas y materias correspondientes
        if(nivelSelect.value) {
            cargarAulas(nivelSelect.value, asignacionAulaId);
            cargarMaterias(nivelSelect.value, asignacionMateriaId);
        }

        // Al cambiar el nivel (por si se decide modificarlo manualmente) se recargan aulas y materias
        nivelSelect.addEventListener('change', function() {
            cargarAulas(this.value);
            cargarMaterias(this.value);
        });
    });
</script>
@endpush
