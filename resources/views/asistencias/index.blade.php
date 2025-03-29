@extends('layouts.app')

@section('title', 'Registro de Asistencias - Sistema de Gestión Escolar')
@section('content')
<div class="container">
<div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Registro de Asistencia</h1>
    </div>

    <div class="row">
        @foreach ($niveles as $nivel)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body text-center">
                        <h3 class="card-title h4 mb-3">{{ $nivel->nombre }}</h3>
                        <p class="card-text text-muted">
                            Gestionar asistencias del nivel {{ $nivel->nombre }}
                        </p>
                        <a href="{{ route('asistencias.nivel', $nivel->nombre) }}" 
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
