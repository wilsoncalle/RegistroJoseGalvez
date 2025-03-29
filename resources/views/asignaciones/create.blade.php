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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
    <!-- Campo Docente -->
    <div class="col-md-4">
        <label for="id_docente" class="form-label">Docente <span class="text-danger">*</span></label>
        <select name="id_docente" id="id_docente" class="form-select" required>
            <option value="">Seleccione un docente</option>
            @foreach($docentes as $docente)
                <option value="{{ $docente->id_docente }}"
                    data-materia="{{ $docente->id_materia ? $docente->id_materia : '' }}"
                    {{ old('id_docente') == $docente->id_docente ? 'selected' : '' }}>
                    {{ $docente->nombre }} {{ $docente->apellido }}
                </option>
            @endforeach
        </select>
    </div>
    <!-- Campo Materia -->
    <div class="col-md-3">
        <label for="id_materia" class="form-label">Materia <span class="text-danger">*</span></label>
        <!-- Campo Materia visible y deshabilitado para el usuario -->
<select id="id_materia_disabled" class="form-select" disabled>
    <option value="">Seleccione una materia</option>
    @foreach($materias as $materia)
        <option value="{{ $materia->id_materia }}">{{ $materia->nombre }}</option>
    @endforeach
</select>

<!-- Campo Materia oculto que se enviará en el formulario -->
<input type="hidden" name="id_materia" id="id_materia">

    </div>
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
</div>


                <div class="row mb-3">
                    <!-- Campo Año Académico -->
                    <div class="col-md-4">
                        <label for="id_aula" class="form-label">Aula <span class="text-danger">*</span></label>
                        <select name="id_aula" id="id_aula" class="form-select" required disabled>
                            <option value="">Primero seleccione un nivel</option>
                        </select>
                    </div>
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
    // Genera la URL base desde Laravel
    const urlAulas = "{{ url('/aulas/nivel') }}";
    
    document.addEventListener('DOMContentLoaded', function() {
        const nivelSelect = document.getElementById('nivel');
        const aulaSelect = document.getElementById('id_aula');
        
        nivelSelect.addEventListener('change', function() {
            const nivelId = this.value;
            
            // Mostrar mensaje mientras se cargan las aulas
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
                        // Limpiar el select antes de agregar nuevas opciones
                        aulaSelect.innerHTML = '';
                        
                        if(aulas.length > 0) {
                            // Agregar opción por defecto
                            const defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.textContent = 'Seleccione un aula';
                            aulaSelect.appendChild(defaultOption);
                            
                            // Agregar cada aula
                            aulas.forEach(aula => {
                                const option = document.createElement('option');
                                option.value = aula.id;
                                option.textContent = aula.nombre_completo;
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
                // Si no se ha seleccionado un nivel, se deshabilita el select de aulas
                aulaSelect.disabled = true;
                aulaSelect.innerHTML = '<option value="">Primero seleccione un nivel</option>';
            }
        });
    });
</script>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const docenteSelect = document.getElementById('id_docente');
        const materiaSelect = document.getElementById('id_materia');

        docenteSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const materiaId = selectedOption.getAttribute('data-materia');

    if (materiaId) {
        document.getElementById('id_materia').value = materiaId;
        // También se puede actualizar el select visible si es necesario
        document.getElementById('id_materia_disabled').value = materiaId;
    } else {
        document.getElementById('id_materia').value = "";
        document.getElementById('id_materia_disabled').value = "";
    }
});


        // Opcional: Si hay un docente seleccionado al cargar la página, se dispara el cambio
        if (docenteSelect.value) {
            const event = new Event('change');
            docenteSelect.dispatchEvent(event);
        }
    });
</script>
@endpush

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
