@extends('layouts.app')

@section('title', 'Editar Aula - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Editar Aula</h1>
        <a href="{{ route('aulas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Aulas
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

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('aulas.update', $aula->id_aula) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos del Aula</h5>
                        <hr>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="id_nivel" class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_nivel" name="id_nivel" required>
                            <option value="">Seleccionar nivel...</option>
                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel->id_nivel }}" 
                                    {{ old('id_nivel', $aula->id_nivel) == $nivel->id_nivel ? 'selected' : '' }}>
                                    {{ $nivel->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="id_grado" class="form-label">Grado <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_grado" name="id_grado" required disabled>
                            <option value="">Seleccionar grado...</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="id_seccion" class="form-label">Sección <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_seccion" name="id_seccion" required disabled>
                            <option value="">Seleccionar sección...</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Restaurar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Actualizar Aula
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const nivelSelect = document.getElementById('id_nivel');
    const gradoSelect = document.getElementById('id_grado');
    const seccionSelect = document.getElementById('id_seccion');

    function cargarGrados(id_nivel, selectedGrado) {
        gradoSelect.innerHTML = '<option value="">Seleccionar grado...</option>';
        seccionSelect.innerHTML = '<option value="">Seleccionar sección...</option>';
        seccionSelect.disabled = true;

        if (id_nivel) {
            fetch(`{{ url('aulas/getGrados') }}/${id_nivel}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(grado => {
                            gradoSelect.innerHTML += `<option value="${grado.id_grado}" ${selectedGrado == grado.id_grado ? 'selected' : ''}>${grado.nombre}</option>`;
                        });
                        gradoSelect.disabled = false;
                    } else {
                        gradoSelect.disabled = true;
                    }
                })
                .catch(error => console.error('Error al obtener grados:', error));
        } else {
            gradoSelect.disabled = true;
        }
    }

    function cargarSecciones(id_grado, selectedSeccion) {
        seccionSelect.innerHTML = '<option value="">Seleccionar sección...</option>';
        if (id_grado) {
            fetch(`{{ url('aulas/getSecciones') }}/${id_grado}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(seccion => {
                            seccionSelect.innerHTML += `<option value="${seccion.id_seccion}" ${selectedSeccion == seccion.id_seccion ? 'selected' : ''}>${seccion.nombre}</option>`;
                        });
                        seccionSelect.disabled = false;
                    } else {
                        seccionSelect.disabled = true;
                    }
                })
                .catch(error => console.error('Error al obtener secciones:', error));
        } else {
            seccionSelect.disabled = true;
        }
    }

    nivelSelect.addEventListener('change', function() {
        cargarGrados(this.value, null);
    });

    gradoSelect.addEventListener('change', function() {
        cargarSecciones(this.value, null);
    });

    // Cargar grados y secciones en la edición
    const idNivelActual = nivelSelect.value;
    const idGradoActual = '{{ old('id_grado', $aula->id_grado) }}';
    const idSeccionActual = '{{ old('id_seccion', $aula->id_seccion) }}';
    
    if (idNivelActual) {
        cargarGrados(idNivelActual, idGradoActual);
        if (idGradoActual) {
            cargarSecciones(idGradoActual, idSeccionActual);
        }
    }
});
</script>
@endsection
