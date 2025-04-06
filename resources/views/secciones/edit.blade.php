@extends('layouts.app')

@section('title', 'Editar Sección - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Editar Sección</h1>
        <a href="{{ route('secciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Secciones
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

            <form action="{{ route('secciones.update', $seccion) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos de la Sección</h5>
                        <hr>
                    </div>
                </div>

                <!-- Campo para seleccionar el Nivel -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nivel_id" class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select class="form-select" id="nivel_id" name="nivel_id" required>
                            <option value="">Seleccione un nivel...</option>
                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel->id_nivel }}"
                                    {{ old('nivel_id', $seccion->grado->id_nivel) == $nivel->id_nivel ? 'selected' : '' }}>
                                    {{ $nivel->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Campo para seleccionar el Grado y editar el Nombre de la Sección -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="grado_id" class="form-label">Grado <span class="text-danger">*</span></label>
                        <select class="form-select @error('grado_id') is-invalid @enderror" 
                                id="grado_id" name="grado_id" disabled required>
                            <option value="">Seleccione un grado...</option>
                        </select>
                        @error('grado_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre de la Sección <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre', $seccion->nombre) }}" disabled required placeholder="Ej: Sección A">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para filtrar grados -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nivelSelect = document.getElementById('nivel_id');
    const gradoSelect = document.getElementById('grado_id');
    const nombreInput = document.getElementById('nombre');
    
    const grados = @json($grados);

    function filtrarGrados(nivelSeleccionado, selectedGrade = null) {
        gradoSelect.innerHTML = '<option value="">Seleccione un grado...</option>';
        if (!isNaN(nivelSeleccionado)) {
            gradoSelect.disabled = false;
            nombreInput.disabled = false;
            grados.forEach(function(grado) {
                if (parseInt(grado.id_nivel) === parseInt(nivelSeleccionado)) {
                    const option = document.createElement('option');
                    option.value = grado.id_grado;
                    option.textContent = grado.nombre;
                    if(selectedGrade && parseInt(selectedGrade) === parseInt(grado.id_grado)){
                        option.selected = true;
                    }
                    gradoSelect.appendChild(option);
                }
            });
        } else {
            gradoSelect.disabled = true;
            nombreInput.disabled = true;
        }
    }
    
    nivelSelect.addEventListener('change', function () {
        filtrarGrados(this.value);
    });
    
    // Al cargar la página, si ya hay un nivel seleccionado, filtrar grados automáticamente
    const nivelActual = nivelSelect.value;
    const gradoActual = '{{ old('grado_id', $seccion->id_grado) }}';
    if(nivelActual){
        filtrarGrados(nivelActual, gradoActual);
    }
});
</script>
@endsection
