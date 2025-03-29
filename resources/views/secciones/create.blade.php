@extends('layouts.app')

@section('title', 'Crear Sección - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Crear Sección</h1>
        <a href="{{ route('secciones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Secciones
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('secciones.store') }}" method="POST">
                @csrf
                
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
                                <option value="{{ $nivel->id_nivel }}">{{ $nivel->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Campo para seleccionar el Grado (inicialmente deshabilitado) -->
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

                    <!-- Campo para el nombre de la sección (inicialmente deshabilitado) -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre de la Sección <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre') }}" disabled required placeholder="Ej: Sección A">
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
                        <i class="bi bi-save me-1"></i> Guardar Sección
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
    
    // Verifica que la lista de grados se está cargando
    const grados = @json($grados);
    console.log("Grados:", grados);

    nivelSelect.addEventListener('change', function () {
        const nivelSeleccionado = parseInt(this.value);
        console.log("Nivel seleccionado:", nivelSeleccionado);

        // Reinicia las opciones del select de grados
        gradoSelect.innerHTML = '<option value="">Seleccione un grado...</option>';

        if (!isNaN(nivelSeleccionado)) {
            gradoSelect.disabled = false;
            nombreInput.disabled = false;
            
            // Recorre los grados y agrega los que coincidan con el nivel seleccionado
            grados.forEach(function(grado) {
                console.log("Revisando grado:", grado);
                // Ajusta el nombre de la propiedad si es necesario (por ejemplo, si se llama 'nivel_id' en lugar de 'id_nivel')
                if (parseInt(grado.id_nivel) === nivelSeleccionado) {
                    const option = document.createElement('option');
                    option.value = grado.id_grado;
                    option.textContent = grado.nombre;
                    gradoSelect.appendChild(option);
                }
            });
        } else {
            gradoSelect.disabled = true;
            nombreInput.disabled = true;
        }
    });
});
</script>
@endsection
