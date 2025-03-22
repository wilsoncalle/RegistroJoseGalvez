@extends('layouts.app')

@section('title', 'Detalles del Aula - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Detalles del Aula</h1>
                <div>
                    <a href="{{ route('aulas.edit', $aula) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil me-1"></i> Editar
                    </a>
                    <a href="{{ route('aulas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nivel:</div>
                        <div class="col-md-8">{{ $aula->nivel->nombre ?? 'No especificado' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Grado:</div>
                        <div class="col-md-8">{{ $aula->grado->nombre ?? 'No especificado' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Sección:</div>
                        <div class="col-md-8">{{ $aula->seccion->nombre ?? 'No especificado' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Fecha de Creación:</div>
                        <div class="col-md-8">{{ $aula->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Última Actualización:</div>
                        <div class="col-md-8">{{ $aula->updated_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash me-1"></i> Eliminar Aula
                </button>
            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmar eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Está seguro de que desea eliminar el aula <strong>{{ $aula->id }}</strong>?</p>
                            <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form action="{{ route('aulas.destroy', $aula) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
