@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Encabezado y botón para volver -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Asistencia - Nivel {{ $nivel }}</h1>
                <a href="{{ route('asistencias.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Niveles
                </a>
            </div>
        </div>
    </div>
    <div class="card mb-4"">
        <div class="card-body">
    <!-- Filtros: Grado y Sección -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="gradeFilter">Filtrar por Grado:</label>
                    <select id="gradeFilter" class="form-select">
                        <option value="">Selecciona un Grado</option>
                        @foreach ($grados as $grado)
                            <option value="{{ $grado->id_grado }}">{{ $grado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="sectionFilter">Filtrar por Sección:</label>
                    <select id="sectionFilter" class="form-select" disabled>
                        <option value="">Selecciona una Sección</option>
                        <!-- Se llenarán vía JavaScript -->
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('asistencias.index-niveles', ['nivel' => $nivel]) }}" class="btn btn-secondary">
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
    <!-- Contenedor de aulas -->
    <div class="row" id="aulasContainer">
        @foreach ($aulas as $aula)
            <div class="col-md-4 mb-4 aula-card" data-grade="{{ $aula->grado->id_grado }}" data-section="{{ $aula->seccion->id_seccion }}">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body">
                        <h3 class="card-title h5 mb-3">
                            {{ $aula->grado->nombre }} "{{ $aula->seccion->nombre }}"
                        </h3>
                        <p class="card-text text-muted">
                            Gestionar asistencias del aula
                        </p>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('asistencias.show', ['nivel' => $aula->nivel->nombre, 'aulaId' => $aula->id_aula]) }}" 
                               class="btn btn-success">
                                Ver Asistencias
                            </a>
                            <a href="{{ route('asistencias.create', ['id_aula' => $aula->id_aula]) }}" 
                               class="btn btn-primary">
                                Tomar Asistencia
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
    const sectionFilter = document.getElementById('sectionFilter');
    const aulaCards = document.querySelectorAll('.aula-card');

    // Construir un mapa de grade → secciones a partir de las aulas
    const gradeSectionsMap = {};
    aulaCards.forEach(card => {
        const grade = card.getAttribute('data-grade');
        const section = card.getAttribute('data-section');
        // Se extrae el nombre de la sección a partir del título (suponiendo formato: "Grado" "Sección")
        const cardTitle = card.querySelector('.card-title').textContent;
        const sectionName = cardTitle.split('"')[1].trim();
        
        if (!gradeSectionsMap[grade]) {
            gradeSectionsMap[grade] = [];
        }
        // Evitar duplicados
        if (!gradeSectionsMap[grade].some(sec => sec.id === section)) {
            gradeSectionsMap[grade].push({id: section, name: sectionName});
        }
    });

    // Evento: Cambio en el filtro de grado
    gradeFilter.addEventListener('change', function () {
        const selectedGrade = this.value;
        // Reiniciar el filtro de sección
        sectionFilter.innerHTML = '<option value="">Selecciona una Sección</option>';
        sectionFilter.disabled = !selectedGrade;

        // Filtrar las tarjetas de aula según el grado seleccionado
        aulaCards.forEach(card => {
            if (!selectedGrade || card.getAttribute('data-grade') === selectedGrade) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        // Si se seleccionó un grado, poblar el filtro de sección
        if (selectedGrade && gradeSectionsMap[selectedGrade]) {
            gradeSectionsMap[selectedGrade].forEach(sec => {
                const option = document.createElement('option');
                option.value = sec.id;
                option.textContent = sec.name;
                sectionFilter.appendChild(option);
            });
        }
    });

    // Evento: Cambio en el filtro de sección
    sectionFilter.addEventListener('change', function () {
        const selectedSection = this.value;
        // Filtrar tarjetas según grado y sección (si la sección está seleccionada)
        aulaCards.forEach(card => {
            if (selectedSection === "" || card.getAttribute('data-section') === selectedSection) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
