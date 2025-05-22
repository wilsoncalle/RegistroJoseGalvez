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
                    <div class="col-md-2">
                        <label for="nivel" class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select name="nivel" id="nivel" class="form-select" required>
                            <option value="">Seleccionar nivel</option>
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
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" id="agregar-materia" class="btn btn-success mb-2" disabled>
                            <i class="bi bi-plus-circle me-1"></i> Agregar Materia
                        </button>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Campo Aula -->
                    <div class="col-md-4">
                        <label for="id_aula" class="form-label">Aula <span class="text-danger">*</span></label>
                        <select name="aulas[]" id="id_aula" class="form-select" required disabled>
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
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" id="agregar-aula" class="btn btn-success mb-2" disabled>
                            <i class="bi bi-plus-circle me-1"></i> Agregar Aula
                        </button>
                    </div>
                </div>
                
                <!-- Contenedor para aulas adicionales -->
                <div id="aulas-adicionales"></div>

                <!-- Contenedor para materias adicionales -->
                <div id="materias-adicionales"></div>

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
                        const agregarAulaBtn = document.getElementById('agregar-aula');
                        const agregarMateriaBtn = document.getElementById('agregar-materia');
                        const aulasAdicionalesContainer = document.getElementById('aulas-adicionales');
                        const materiasAdicionalesContainer = document.getElementById('materias-adicionales');
                        let contadorAulas = 1;
                        let contadorMaterias = 1;

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
                                            agregarAulaBtn.disabled = false;
                                        } else {
                                            aulaSelect.innerHTML = '<option value="">No hay aulas disponibles para este nivel</option>';
                                            agregarAulaBtn.disabled = true;
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        aulaSelect.innerHTML = '<option value="">Error al cargar aulas</option>';
                                    });
                            } else {
                                aulaSelect.innerHTML = '<option value="">Primero seleccione un nivel</option>';
                                aulaSelect.disabled = true;
                                agregarAulaBtn.disabled = true;
                                // Limpiar aulas adicionales cuando se cambia el nivel
                                aulasAdicionalesContainer.innerHTML = '';
                                contadorAulas = 1;
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
                                            agregarMateriaBtn.disabled = false;
                                        } else {
                                            materiaSelect.innerHTML = '<option value="">No hay materias disponibles para este nivel</option>';
                                            agregarMateriaBtn.disabled = true;
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        materiaSelect.innerHTML = '<option value="">Error al cargar materias</option>';
                                    });
                            } else {
                                materiaSelect.innerHTML = '<option value="">Seleccione un nivel primero</option>';
                                materiaSelect.disabled = true;
                                agregarMateriaBtn.disabled = true;
                                // Limpiar materias adicionales cuando se cambia el nivel
                                materiasAdicionalesContainer.innerHTML = '';
                                contadorMaterias = 1;
                            }
                        });

                        // Función para agregar una nueva aula
                        agregarAulaBtn.addEventListener('click', function() {
                            const nivelId = nivelSelect.value;
                            if (!nivelId) return;

                            contadorAulas++;
                            const aulaId = `id_aula_${contadorAulas}`;
                            const divRow = document.createElement('div');
                            divRow.className = 'row mb-3';
                            divRow.id = `aula-row-${contadorAulas}`;
                            
                            divRow.innerHTML = `
                                <div class="col-md-4">
                                    <label for="${aulaId}" class="form-label">Aula ${contadorAulas} <span class="text-danger">*</span></label>
                                    <select name="aulas[]" id="${aulaId}" class="form-select" required>
                                        <option value="">Cargando aulas...</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger mb-2 eliminar-aula" data-id="${contadorAulas}">
                                        <i class="bi bi-trash me-1"></i> Eliminar
                                    </button>
                                </div>
                            `;
                            
                            aulasAdicionalesContainer.appendChild(divRow);
                            
                            const nuevoAulaSelect = document.getElementById(aulaId);
                            
                            // Cargar las aulas para el nuevo select
                            fetch(`${urlAulas}/${nivelId}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Error en la red');
                                    }
                                    return response.json();
                                })
                                .then(aulas => {
                                    nuevoAulaSelect.innerHTML = '';
                                    if(aulas.length > 0) {
                                        const defaultOption = document.createElement('option');
                                        defaultOption.value = '';
                                        defaultOption.textContent = 'Seleccione un aula';
                                        nuevoAulaSelect.appendChild(defaultOption);
                                        aulas.forEach(aula => {
                                            const option = document.createElement('option');
                                            option.value = aula.id;
                                            option.textContent = aula.nombre_completo;
                                            nuevoAulaSelect.appendChild(option);
                                        });
                                    } else {
                                        nuevoAulaSelect.innerHTML = '<option value="">No hay aulas disponibles para este nivel</option>';
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    nuevoAulaSelect.innerHTML = '<option value="">Error al cargar aulas</option>';
                                });
                            
                            // Agregar evento para eliminar el aula
                            divRow.querySelector('.eliminar-aula').addEventListener('click', function() {
                                const aulaId = this.getAttribute('data-id');
                                const aulaRow = document.getElementById(`aula-row-${aulaId}`);
                                aulaRow.remove();
                            });
                        });

                        // Función para agregar una nueva materia
                        agregarMateriaBtn.addEventListener('click', function() {
                            const nivelId = nivelSelect.value;
                            if (!nivelId) return;

                            contadorMaterias++;
                            const materiaId = `id_materia_${contadorMaterias}`;
                            const divRow = document.createElement('div');
                            divRow.className = 'row mb-3';
                            divRow.id = `materia-row-${contadorMaterias}`;
                            
                            divRow.innerHTML = `
                                <div class="col-md-3">
                                    <label for="${materiaId}" class="form-label">Materia ${contadorMaterias} <span class="text-danger">*</span></label>
                                    <select name="materias_adicionales[]" id="${materiaId}" class="form-select" required>
                                        <option value="">Cargando materias...</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger mb-2 eliminar-materia" data-id="${contadorMaterias}">
                                        <i class="bi bi-trash me-1"></i> Eliminar
                                    </button>
                                </div>
                            `;
                            
                            materiasAdicionalesContainer.appendChild(divRow);
                            
                            const nuevaMateriaSelect = document.getElementById(materiaId);
                            
                            // Cargar las materias para el nuevo select
                            fetch(`${urlMaterias}/${nivelId}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Error en la red');
                                    }
                                    return response.json();
                                })
                                .then(materias => {
                                    nuevaMateriaSelect.innerHTML = '';
                                    if(materias.length > 0) {
                                        const defaultOption = document.createElement('option');
                                        defaultOption.value = '';
                                        defaultOption.textContent = 'Seleccione una materia';
                                        nuevaMateriaSelect.appendChild(defaultOption);
                                        materias.forEach(materia => {
                                            const option = document.createElement('option');
                                            option.value = materia.id_materia;
                                            option.textContent = materia.nombre;
                                            nuevaMateriaSelect.appendChild(option);
                                        });
                                    } else {
                                        nuevaMateriaSelect.innerHTML = '<option value="">No hay materias disponibles para este nivel</option>';
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    nuevaMateriaSelect.innerHTML = '<option value="">Error al cargar materias</option>';
                                });
                            
                            // Agregar evento para eliminar la materia
                            divRow.querySelector('.eliminar-materia').addEventListener('click', function() {
                                const materiaId = this.getAttribute('data-id');
                                const materiaRow = document.getElementById(`materia-row-${materiaId}`);
                                materiaRow.remove();
                            });
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
