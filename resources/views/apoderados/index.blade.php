@extends('layouts.app') 

@section('title', 'Gestión de Apoderados - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Apoderados</h1>
        <div>
            <a href="{{ route('apoderados.export', request()->query()) }}" class="btn btn-success me-2">
                <i class="bi bi-file-earmark-excel me-1"></i> Exportar a Excel
            </a>
            <a href="{{ route('apoderados.pdf', request()->query()) }}" class="btn btn-danger me-2">
                <i class="bi bi-file-earmark-pdf me-1"></i> Exportar a PDF
            </a>
            <a href="{{ route('apoderados.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Apoderado
            </a>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('apoderados.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="busqueda" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="busqueda" name="busqueda" 
                           placeholder="Nombre, DNI o teléfono" value="{{ $busqueda ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="relacion" class="form-label">Relación</label>
                    <select class="form-select" id="relacion" name="relacion">
                        <option value="">Todas las relaciones</option>
                        @foreach($relaciones as $relacion)
                            <option value="{{ $relacion }}" {{ ($filtroRelacion ?? '') == $relacion ? 'selected' : '' }}>
                                {{ $relacion }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                    <a href="{{ route('apoderados.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>
    <!-- Alertas de sesión -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Listado de apoderados -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>DNI</th>
                            <th>Relación</th>
                            <th>Teléfono</th>
                            <th>Estudiantes</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = ($apoderados->currentPage() - 1) * $apoderados->perPage() + 1; @endphp
                        @forelse($apoderados as $apoderado)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>{{ $apoderado->nombre }}</td>
                                <td>{{ $apoderado->apellido }}</td>
                                <td>{{ $apoderado->dni ?? 'No registrado' }}</td>
                                <td>{{ $apoderado->relacion }}</td>
                                <td>{{ $apoderado->telefono }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $apoderado->estudiantes->count() }} estudiante(s)
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('apoderados.show', $apoderado) }}" class="btn btn-info" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('apoderados.edit', $apoderado) }}" class="btn btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $apoderado->id_apoderado }}" 
                                                title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal de confirmación de eliminación -->
                                    <div class="modal fade" id="deleteModal{{ $apoderado->id_apoderado }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $apoderado->id_apoderado }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $apoderado->id_apoderado }}">Confirmar eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Está seguro que desea eliminar al apoderado <strong>{{ $apoderado->nombre }} {{ $apoderado->apellido }}</strong>?</p>
                                                    @if($apoderado->estudiantes->count() > 0)
                                                        <div class="alert alert-warning">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            Este apoderado tiene {{ $apoderado->estudiantes->count() }} estudiante(s) asociado(s).
                                                            No podrá ser eliminado hasta que se eliminen estas asociaciones.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('apoderados.destroy', $apoderado) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" 
                                                            {{ $apoderado->estudiantes->count() > 0 ? 'disabled' : '' }}>
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                       @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">No se encontraron apoderados con los criterios especificados.</p>
                                    <a href="{{ route('apoderados.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                        <i class="bi bi-arrow-repeat me-1"></i> Mostrar todos los apoderados
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
                    {{ $apoderados->firstItem() }} - 
                    {{ $apoderados->lastItem() }} 
                    {{ __('de') }} 
                    {{ $apoderados->total() }} {{ __('resultados') }}
                </div>
                <div>
                    {{ $apoderados->appends(request()->query())->links('pagination::custom-bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
