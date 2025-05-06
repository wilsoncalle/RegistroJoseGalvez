@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Encabezado y botón para volver -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Calificaciones de Egresados - Nivel {{ $nivel }}</h1>
                <a href="{{ route('calificaciones-old.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Niveles
                </a>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-body">
            <!-- Filtros: Grado y Año Académico -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="gradeFilter">Filtrar por Grado:</label>
                    <select id="gradeFilter" class="form-select">
                        <option value="">Todos los Grados</option>
                        @foreach ($grados as $grado)
                            <option value="{{ $grado->id_grado }}">{{ $grado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="yearFilter">Año Académico:</label>
                    <select id="yearFilter" class="form-select">
                        <option value="">Todos los Años</option>
                        @php
                            $currentYear = date('Y');
                            for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                                echo "<option value=\"$year\">$year</option>";
                            }
                        @endphp
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('calificaciones-old.index-nivel', ['nivel' => $nivel]) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('error') }}
        </div>
    @endif
    
    <!-- Contenedor de aulas -->
    <div class="row" id="aulasContainer">
        @foreach ($aulas as $aula)
            <div class="col-md-4 mb-4 aula-card" data-grade="{{ $aula->grado->id_grado }}">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3">
                            {{ $aula->grado->nombre }} "{{ $aula->seccion->nombre }}"
                        </h3>
                        <p class="card-text text-muted">
                            Gestionar calificaciones de estudiantes egresados
                        </p>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('calificaciones-old.create-show', ['nivel' => $aula->nivel->nombre, 'aulaId' => $aula->id_aula]) }}" 
                               class="btn btn-primary">
                                Gestionar Calificaciones
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Estilos para hover -->
<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const gradeFilter = document.getElementById('gradeFilter');
    const aulaCards = document.querySelectorAll('.aula-card');

    // Filtro por grado
    gradeFilter.addEventListener('change', function() {
        const selectedGrade = this.value;
        
        aulaCards.forEach(card => {
            const cardGrade = card.getAttribute('data-grade');
            
            if (!selectedGrade || cardGrade === selectedGrade) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
