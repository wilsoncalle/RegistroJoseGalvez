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
                                <option value="{{ $docente->id_docente }}" {{ $asignacion->id_docente == $docente->id_docente ? 'selected' : '' }}>
                                    {{ $docente->nombre }} {{ $docente->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Campo Materia -->
                    <div class="col-md-3">
                        <label for="id_materia" class="form-label">Materia <span class="text-danger">*</span></label>
                        <select name="id_materia" id="id_materia" class="form-select" required>
                            <option value="">Seleccione una materia</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id_materia }}" {{ $asignacion->id_materia == $materia->id_materia ? 'selected' : '' }}>
                                    {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Campo Nivel -->
                    <div class="col-md-3">
                        <label for="nivel" class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select name="nivel" id="nivel" class="form-select" required>
                            <option value="">Seleccione un nivel</option>
                            @foreach($niveles as $nivel)
                                @php
                                    $nivelSeleccionado = $asignacion->aula ? $asignacion->aula->id_nivel : null;
                                @endphp
                                <option value="{{ $nivel->id_nivel }}" {{ $nivelSeleccionado == $nivel->id_nivel ? 'selected' : '' }}>
                                    {{ $nivel->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Campo Aula -->
                    <div class="col-md-4">
                        <label for="id_aula" class="form-label">Aula <span class="text-danger">*</span></label>
                        <select name="id_aula" id="id_aula" class="form-select" required>
                            <option value="">Seleccione un aula</option>
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

@push('scripts')
                <script>
                    // URL base para obtener aulas según el nivel
                    const urlAulas = "{{ url('/aulas/nivel') }}";

                    document.addEventListener('DOMContentLoaded', function() {
                        const nivelSelect = document.getElementById('nivel');
                        const aulaSelect = document.getElementById('id_aula');
                        // Obtener el aula seleccionada previamente
                        const asignacionAulaId = "{{ old('id_aula', $asignacion->id_aula) }}";

                        function cargarAulas(nivelId, selectedAula = '') {
                            aulaSelect.innerHTML = '<option value="">Cargando aulas...</option>';
                            
                            if (nivelId) {
                                aulaSelect.disabled = false;
                                
                                fetch(`${urlAulas}/${nivelId}`)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Error en la red');
                                        }
                                        return response.json();
                                    })
                                    .then(aulas => {
                                        aulaSelect.innerHTML = '';
                                        
                                        if (aulas.length > 0) {
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
                                        } else {
                                            aulaSelect.innerHTML = '<option value="">No hay aulas disponibles para este nivel</option>';
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        aulaSelect.innerHTML = '<option value="">Error al cargar aulas</option>';
                                    });
                            } else {
                                aulaSelect.disabled = true;
                                aulaSelect.innerHTML = '<option value="">Primero seleccione un nivel</option>';
                            }
                        }

                        // Cargar aulas al iniciar si ya hay un nivel seleccionado
                        if (nivelSelect.value) {
                            cargarAulas(nivelSelect.value, asignacionAulaId);
                        }

                        nivelSelect.addEventListener('change', function() {
                            cargarAulas(this.value);
                        });
                    });
                </script>
                @endpush
@endsection
