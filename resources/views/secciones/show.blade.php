@extends('layouts.app')

@section('title', 'Ver Sección - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Detalles de la Sección</h1>
                <div>
                    <a href="{{ route('secciones.edit', $seccion) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil me-1"></i> Editar
                    </a>
                    <a href="{{ route('secciones.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title">Información General</h5>
                        <hr>
                        <dl class="row">
                            <dt class="col-sm-3">ID:</dt>
                            <dd class="col-sm-9">{{ $seccion->id_seccion }}</dd>

                            <dt class="col-sm-3">Nombre:</dt>
                            <dd class="col-sm-9">{{ $seccion->nombre }}</dd>

                            <dt class="col-sm-3">Grado:</dt>
                            <dd class="col-sm-9">
                                @if($seccion->grado)
                                    <a href="{{ route('grados.show', $seccion->grado) }}">
                                        {{ $seccion->grado->nombre }}
                                    </a>
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </dd>
                            <dt class="col-sm-3">Creado:</dt>
                            <dd class="col-sm-9">{{ $seccion->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-3">Actualizado:</dt>
                            <dd class="col-sm-9">{{ $seccion->updated_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>

                    <div class="mb-4">
                        <h5 class="card-title">Estudiantes Matriculados</h5>
                        <hr>
                        @if($seccion->estudiantes && $seccion->estudiantes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($seccion->estudiantes as $estudiante)
                                            <tr>
                                                <td>{{ $estudiante->id_estudiante }}</td>
                                                <td>{{ $estudiante->nombre }} {{ $estudiante->apellido }}</td>
                                                <td>
                                                    <a href="{{ route('estudiantes.show', $estudiante) }}" 
                                                       class="btn btn-sm btn-info text-white">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No hay estudiantes matriculados en esta sección.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
