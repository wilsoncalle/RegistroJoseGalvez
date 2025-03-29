@extends('layouts.app')

@section('content')
<div class="container py-4">
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

    <div class="row">
    @foreach ($aulas as $aula)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <h3 class="card-title h5 mb-3">
                        {{ $aula->grado->nombre }} "{{ $aula->seccion->nombre }}"
                    </h3>
                    <p class="card-text text-muted">
                        Gestionar asistencias del aula
                    </p>
                    <!-- Contenedor flexbox para separar los botones -->
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
