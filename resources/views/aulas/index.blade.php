@extends('layouts.app')

@section('title', 'Aulas - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Aulas</h1>
        <a href="{{ route('aulas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nueva Aula
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('aulas.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="busqueda" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="busqueda" name="busqueda" 
                           placeholder="Nombre de grado o sección" value="{{ request('busqueda') }}">
                </div>
                <div class="col-md-4">
                    <label for="nivel" class="form-label">Nivel</label>
                    <select name="nivel" class="form-select">
                        <option value="">Todos los niveles</option>
                        @foreach($niveles as $nivel)
                            <option value="{{ $nivel->id_nivel }}" {{ ($filtroNivel ?? '') == $nivel->id_nivel ? 'selected' : '' }}>
                                {{ $nivel->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                    <a href="{{ route('aulas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nivel</th>
                            <th>Grado</th>
                            <th>Sección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aulas as $aula)
                            <tr>
                                <td>{{ $aula->id_aula }}</td>
                                <td>{{ $aula->nivel->nombre }}</td>
                                <td>{{ $aula->grado->nombre }}</td>
                                <td>{{ $aula->seccion->nombre }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('aulas.show', $aula) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('aulas.edit', $aula) }}" class="btn btn-sm btn-warning text-white">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $aula->id_aula }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <div class="modal fade" id="deleteModal{{ $aula->id_aula }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Está seguro de que desea eliminar el aula <strong>{{ $aula->grado->nombre }} - {{ $aula->seccion->nombre }}</strong>?</p>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted mb-0">No se encontraron aulas con los criterios especificados.</p>
                                    <a href="{{ route('aulas.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                        <i class="bi bi-arrow-repeat me-1"></i> Mostrar todas las aulas
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    {{ __('Mostrando') }} 
                    {{ $aulas->firstItem() }} - 
                    {{ $aulas->lastItem() }} 
                    {{ __('de') }} 
                    {{ $aulas->total() }} {{ __('resultados') }}
                </div>
                <div>
                    {{ $aulas->appends(request()->query())->links('pagination::custom-bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
}

.pagination .page-item {
    margin: 0 2px;
}

.pagination .page-item .page-link {
    color: #333;
    background-color: #fff;
    border: 1px solid #dee2e6;
    padding: 0.375rem 0.75rem;
    line-height: 1.5;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}
</style>
@endpush