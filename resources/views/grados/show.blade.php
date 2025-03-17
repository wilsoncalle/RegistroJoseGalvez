@extends('layouts.app')

@section('title', 'Ver Grado - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Detalles del Grado</h1>
                <div>
                    <a href="{{ route('grados.edit', $grado) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil me-1"></i> Editar
                    </a>
                    <a href="{{ route('grados.index') }}" class="btn btn-secondary">
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
                            <dd class="col-sm-9">{{ $grado->id_grado }}</dd>

                            <dt class="col-sm-3">Nombre:</dt>
                            <dd class="col-sm-9">{{ $grado->nombre }}</dd>

                            <dt class="col-sm-3">Nivel:</dt>
                            <dd class="col-sm-9">
                                @if($grado->nivel)
                                    {{ $grado->nivel->nombre }}
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </dd>
                            <dt class="col-sm-3">Creado:</dt>
                            <dd class="col-sm-9">{{ $grado->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-3">Actualizado:</dt>
                            <dd class="col-sm-9">{{ $grado->updated_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>

                    <div class="mb-4">
                        <h5 class="card-title">Secciones Asociadas</h5>
                        <hr>
                        @if($grado->secciones && $grado->secciones->count() > 0)
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
                                        @foreach($grado->secciones as $seccion)
                                            <tr>
                                                <td>{{ $seccion->id_seccion }}</td>
                                                <td>{{ $seccion->nombre }}</td>
                                                <td>
                                                    <a href="{{ route('secciones.show', $seccion) }}" 
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
                            <p class="text-muted">No hay secciones asociadas a este grado.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
