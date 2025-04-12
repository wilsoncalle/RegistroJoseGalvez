@extends('layouts.app')

@section('title', 'Materias - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Materias</h1>
        <div class="d-flex gap-2">
        <!-- Dropdown Exportar -->
        <div class="dropdown">
            <button class="btn btn-warning dropdown-toggle text-white" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-download me-1"></i> Exportar
            </button>
            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                <li>
                    <a class="dropdown-item text-success" href="{{ route('materias.export', request()->query()) }}">
                        <i class="bi bi-file-earmark-excel me-1"></i> Exportar a Excel
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('materias.pdf', request()->query()) }}">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Exportar a PDF
                    </a>
                </li>
            </ul>
        </div>

        <!-- Estilos personalizados -->
        <style>
            /* Estilo personalizado para PDF */
            .dropdown-item.text-danger:active,
            .dropdown-item.text-danger:focus,
            .dropdown-item.text-danger:hover {
                background-color: #dc3545 !important;
                color: #fff !important;
            }

            /* Estilo personalizado para Excel */
            .dropdown-item.text-success:active,
            .dropdown-item.text-success:focus,
            .dropdown-item.text-success:hover {
                background-color: #198754 !important;
                color: #fff !important;
            }
        </style>

        <!-- Botón de Nueva Materia -->
        <a href="{{ route('materias.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nueva Materia
        </a>
    </div>

    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('materias.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="busqueda" class="form-label">Buscar por nombre</label>
                    <input type="text" class="form-control" id="busqueda" name="busqueda" 
                           placeholder="Nombre de la materia" value="{{ $busqueda }}">
                </div>
                <div class="col-md-4">
                    <label for="nivel" class="form-label">Filtrar por nivel</label>
                    <select class="form-select" id="nivel" name="nivel">
                        <option value="">Todos los niveles</option>
                        @foreach($niveles as $nivel)
                            <option value="{{ $nivel->id_nivel }}" {{ $filtroNivel == $nivel->id_nivel ? 'selected' : '' }}>
                                {{ $nivel->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                    <a href="{{ route('materias.index') }}" class="btn btn-secondary">
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
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Nivel</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                         @php $counter = ($materias->currentPage() - 1) * $materias->perPage() + 1; @endphp
                        @forelse($materias as $materia)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>{{ $materia->nombre }}</td>
                                <td>
                                    @if($materia->nivel)
                                        {{ $materia->nivel->nombre }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('materias.show', $materia) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('materias.edit', $materia) }}" class="btn btn-sm btn-warning text-white">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $materia->id_materia }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal de confirmación de eliminación -->
                                    <div class="modal fade" id="deleteModal{{ $materia->id_materia }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Está seguro de que desea eliminar la materia <strong>{{ $materia->nombre }}</strong>?</p>
                                                    <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('materias.destroy', $materia) }}" method="POST">
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
                                    <p class="text-muted mb-0">No se encontraron materias con los criterios especificados.</p>
                                    <a href="{{ route('materias.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                        <i class="bi bi-arrow-repeat me-1"></i> Mostrar todas las materias
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
                    {{ $materias->firstItem() }} - 
                    {{ $materias->lastItem() }} 
                    {{ __('de') }} 
                    {{ $materias->total() }} {{ __('resultados') }}
                </div>
                <div>
                    {{ $materias->appends(request()->query())->links('pagination::custom-bootstrap-5') }}
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