@extends('layouts.app')

@section('title', 'Nueva Aula - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Nueva Aula</h1>
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

            @php
                // Si existe un error en la sesión, se ignora el valor anterior para id_nivel
                $selectedNivel = session('error') ? '' : old('id_nivel');
            @endphp

            <form action="{{ route('aulas.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos del Aula</h5>
                        <hr>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Selección de Nivel -->
                    <div class="col-md-4">
                        <label for="id_nivel" class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_nivel" name="id_nivel" required>
                            <option value="">Seleccionar nivel...</option>
                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel->id_nivel }}" 
                                    {{ $selectedNivel == $nivel->id_nivel ? 'selected' : '' }}>
                                    {{ $nivel->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Selección de Grado: inicialmente deshabilitado -->
                    <div class="col-md-4">
                        <label for="id_grado" class="form-label">Grado <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_grado" name="id_grado" required disabled>
                            <option value="">Seleccionar grado...</option>
                        </select>
                    </div>
                    
                    <!-- Selección de Sección: inicialmente deshabilitado -->
                    <div class="col-md-4">
                        <label for="id_seccion" class="form-label">Sección <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_seccion" name="id_seccion" required disabled>
                            <option value="">Seleccionar sección...</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Aula
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

    // Cuando se selecciona un nivel, obtener los grados correspondientes
    nivelSelect.addEventListener('change', function() {
        const id_nivel = this.value;
        gradoSelect.innerHTML = '<option value="">Seleccionar grado...</option>';
        seccionSelect.innerHTML = '<option value="">Seleccionar sección...</option>';
        seccionSelect.disabled = true;

        if(id_nivel) {
            fetch(`{{ url('aulas/getGrados') }}/${id_nivel}`)
                .then(response => response.json())
                .then(data => {
                    if(data.length > 0){
                        data.forEach(grado => {
                            gradoSelect.innerHTML += `<option value="${grado.id_grado}">${grado.nombre}</option>`;
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
    });

    // Cuando se selecciona un grado, obtener las secciones correspondientes
    gradoSelect.addEventListener('change', function() {
        const id_grado = this.value;
        seccionSelect.innerHTML = '<option value="">Seleccionar sección...</option>';

        if(id_grado) {
            fetch(`{{ url('aulas/getSecciones') }}/${id_grado}`)
                .then(response => response.json())
                .then(data => {
                    if(data.length > 0){
                        data.forEach(seccion => {
                            seccionSelect.innerHTML += `<option value="${seccion.id_seccion}">${seccion.nombre}</option>`;
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
    });
});
</script>
@endsection
