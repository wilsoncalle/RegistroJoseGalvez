@extends('layouts.app')

@section('title', 'Registro de Calificaiones Viejas - Sistema de Gestión Escolar')
@section('content')
<div class="container">
<div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Calificaciones para Estudiantes Egresados</h1>
    </div>

    <div class="row">
        @foreach ($niveles as $nivel)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body text-center">
                        <h3 class="card-title h4 mb-3">{{ $nivel->nombre }}</h3>
                        <p class="card-text text-muted">
                            Gestionar calificaciones de estudiantes egresados
                        </p>
                        <a href="{{ route('calificaciones-old.index-nivel', $nivel->nombre) }}" 
                            class="btn btn-primary">
                            Ingresar
                        </a>
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
