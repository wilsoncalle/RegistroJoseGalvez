@extends('layouts.app')

@section('title', 'Secciones - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Secciones</h1>
        <a href="{{ route('secciones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nueva Sección
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('secciones.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="busqueda" class="form-label">Buscar por nombre</label>
                    <input type="text" class="form-control" id="busqueda" name="busqueda" 
                           placeholder="Nombre de la sección" value="{{ $busqueda ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label for="grado_id" class="form-label">Filtrar por grado</label>
                    <select class="form-select" id="grado_id" name="grado_id">
                        <option value="">Todos los grados</option>
                        @foreach($grados as $grado)
                            <option value="{{ $grado->id_grado }}" {{ ($filtroGrado ?? '') == $grado->id_grado ? 'selected' : '' }}>
                                {{ $grado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                    <a href="{{ route('secciones.index') }}" class="btn btn-secondary">
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
                            <th>Nombre</th>
                            <th>Grado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($secciones as $seccion)
                            <tr>
                                <td>{{ $seccion->id_seccion }}</td>
                                <td>{{ $seccion->nombre }}</td>
                                <td>
                                    @if($seccion->grado)
                                        {{ $seccion->grado->nombre }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('secciones.show', $seccion) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('secciones.edit', $seccion) }}" class="btn btn-sm btn-warning text-white">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $seccion->id_seccion }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal de confirmación de eliminación -->
                                    <div class="modal fade" id="deleteModal{{ $seccion->id_seccion }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Está seguro de que desea eliminar la sección <strong>{{ $seccion->nombre }}</strong>?</p>
                                                    <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('secciones.destroy', $seccion) }}" method="POST">
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
                                <td colspan="4" class="text-center py-4">
                                    <p class="text-muted mb-0">No se encontraron secciones con los criterios especificados.</p>
                                    <a href="{{ route('secciones.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                        <i class="bi bi-arrow-repeat me-1"></i> Mostrar todas las secciones
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
                    {{ $secciones->firstItem() }} - 
                    {{ $secciones->lastItem() }} 
                    {{ __('de') }} 
                    {{ $secciones->total() }} {{ __('resultados') }}
                </div>
                <div>
                    {{ $secciones->appends(request()->query())->links('pagination::custom-bootstrap-5') }}
                </div>
            </div>

        @push('styles')
        <style>
            /* Optional: Additional styling to remove any remaining unwanted text */
            .pagination small,
            .pagination .text-muted {
                display: none !important;
            }
        </style>
        @endpush
        </div>
    </div>
</div>

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
@endsection