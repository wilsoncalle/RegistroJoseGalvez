@extends('layouts.app')

@section('title', 'Editar Docente - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Editar Docente: {{ $docente->nombre }} {{ $docente->apellido }}</h1>
        <a href="{{ route('docentes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Docentes
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

            <form action="{{ route('docentes.update', $docente) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos Personales</h5>
                        <hr>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="nombre" class="form-label">Nombres <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                            value="{{ old('nombre', $docente->nombre) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="apellido" class="form-label">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="apellido" name="apellido" 
                            value="{{ old('apellido', $docente->apellido) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="dni" name="dni" 
                            value="{{ old('dni', $docente->dni) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                            value="{{ old('fecha_nacimiento', $docente->fecha_nacimiento) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" 
                            value="{{ old('telefono', $docente->telefono) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                            value="{{ old('email', $docente->email) }}">
                    </div>
                    <div class="col-md-5">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" 
                            value="{{ old('direccion', $docente->direccion) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos Profesionales</h5>
                        <hr>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="fecha_contratacion" class="form-label">Fecha de Contratación</label>
                        <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" 
                            value="{{ old('fecha_contratacion', $docente->fecha_contratacion) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="nivel" class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select class="form-select" id="nivel" name="nivel">
                            <option value="">Seleccione un nivel</option>
                            @foreach ($niveles as $nivel)
                                <option value="{{ $nivel->id_nivel }}" 
                                    {{ old('nivel', $docente->materia->nivel->id_nivel) == $nivel->id_nivel ? 'selected' : '' }}>
                                    {{ $nivel->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="id_materia" class="form-label">Materia <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_materia" name="id_materia">
                            <option value="">Seleccione una materia</option>
                            @foreach ($materias as $materia)
                                <option value="{{ $materia->id_materia }}" 
                                    {{ old('id_materia', $docente->id_materia) == $materia->id_materia ? 'selected' : '' }}>
                                    {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Restablecer
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Actualizar Docente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Cargar las materias según el nivel seleccionado
document.getElementById('nivel').addEventListener('change', function() {
    const nivelId = this.value;
    const materiaSelect = document.getElementById('id_materia');

    // Reiniciar el select de materias con el placeholder
    materiaSelect.innerHTML = '<option value="">Seleccione una materia</option>';

    if (nivelId === '') {
        materiaSelect.disabled = true;
        return;
    }

    // Habilitar el select de materias
    materiaSelect.disabled = false;

    // Realizar la petición AJAX para obtener las materias según el nivel
    fetch("{{ route('materias.pornivel') }}?id_nivel=" + nivelId)
        .then(response => response.json())
        .then(data => {
            data.forEach(materia => {
                let option = document.createElement('option');
                option.value = materia.id_materia;
                option.text = materia.nombre;
                materiaSelect.appendChild(option);
            });

            // Verificar si el nivel seleccionado coincide con el nivel de la materia del docente
            const docenteNivel = "{{ $docente->materia ? $docente->materia->nivel->id_nivel : '' }}";
            if(nivelId === docenteNivel) {
                // Seleccionar la materia previamente asignada si el nivel coincide
                document.getElementById('id_materia').value = "{{ old('id_materia', $docente->id_materia) }}";
            } else {
                // Dejar el placeholder seleccionado
                document.getElementById('id_materia').selectedIndex = 0;
            }
        })
        .catch(error => console.error('Error:', error));
});
</script>
@endsection
