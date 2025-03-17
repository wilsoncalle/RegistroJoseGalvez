@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-4">Dashboard</h1>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Bienvenido, {{ Auth::user()->nombre }}</h5>
                    <p class="card-text">Este es el sistema de gestión escolar. Utilice la barra de navegación para acceder a las diferentes funcionalidades.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Estudiantes</h6>
                            <h2 class="card-text">{{ $estudiantes_count }}</h2>
                        </div>
                        <i class="bi bi-mortarboard fs-1"></i>
                    </div>
                    <a href="{{ route('estudiantes.index') }}" class="text-white text-decoration-none small">Ver detalles <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Docentes</h6>
                            <h2 class="card-text">{{ $docentes_count }}</h2>
                        </div>
                        <i class="bi bi-person-badge fs-1"></i>
                    </div>
                    <a href="{{ route('docentes.index') }}" class="text-white text-decoration-none small">Ver detalles <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Materias</h6>
                            <h2 class="card-text">{{ $materias_count }}</h2>
                        </div>
                        <i class="bi bi-book fs-1"></i>
                    </div>
                    <a href="{{ route('materias.index') }}" class="text-white text-decoration-none small">Ver detalles <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Asistencia hoy</h6>
                            <h2 class="card-text">{{ $asistencia_hoy }}%</h2>
                        </div>
                        <i class="bi bi-calendar-check fs-1"></i>
                    </div>
                    <a href="{{ route('asistencia.index') }}" class="text-white text-decoration-none small">Ver detalles <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Próximos eventos</h5>
                </div>
                <div class="card-body">
                    @if (count($proximos_eventos) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($proximos_eventos as $evento)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $evento->titulo }}</strong>
                                    <p class="text-muted mb-0 small">{{ $evento->descripcion }}</p>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $evento->fecha->format('d/m/Y') }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No hay eventos próximos programados.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Incidentes recientes</h5>
                </div>
                <div class="card-body">
                    @if (count($incidentes_recientes) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($incidentes_recientes as $incidente)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $incidente->estudiante->nombre }}</strong>
                                    <span class="badge {{ $incidente->tipo == 'Disciplina' ? 'bg-danger' : ($incidente->tipo == 'Salud' ? 'bg-warning' : 'bg-secondary') }}">
                                        {{ $incidente->tipo }}
                                    </span>
                                </div>
                                <p class="text-muted mb-0 small">{{ \Illuminate\Support\Str::limit($incidente->descripcion, 100) }}</p>
                                <small class="text-muted">{{ $incidente->fecha->format('d/m/Y') }}</small>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No hay incidentes recientes registrados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection