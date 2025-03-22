@extends('layouts.app')

@section('title', 'Años Académicos - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Años Académicos</h1>
        <a href="{{ route('anios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Año Académico
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('anios.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="busqueda" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="busqueda" name="busqueda" placeholder="Buscar por año" value="{{ $busqueda }}">
                </div>
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Todos los estados</option>
                        <option value="Planificado" {{ $filtroEstado == 'Planificado' ? 'selected' : '' }}>Planificado</option>
                        <option value="En curso" {{ $filtroEstado == 'En curso' ? 'selected' : '' }}>En curso</option>
                        <option value="Finalizado" {{ $filtroEstado == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                    <a href="{{ route('anios.index') }}" class="btn btn-secondary">
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
                            <th>Año</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anios as $anio)
                            <tr>
                                <td>{{ $anio->id_anio }}</td>
                                <td>{{ $anio->anio }}</td>
                                <td>{{ \Carbon\Carbon::parse($anio->fecha_inicio)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($anio->fecha_fin)->format('d/m/Y') }}</td>
                                <td>
                                    @if($anio->estado == 'Planificado')
                                        <span class="badge bg-info">Planificado</span>
                                    @elseif($anio->estado == 'En curso')
                                        <span class="badge bg-success">En curso</span>
                                    @else
                                        <span class="badge bg-secondary">Finalizado</span>
                                    @endif
                                </td>
                                <td>{{ $anio->descripcion }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('anios.show', $anio) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('anios.edit', $anio) }}" class="btn btn-sm btn-warning text-white">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $anio->id_anio }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal de confirmación de eliminación -->
                                    <div class="modal fade" id="deleteModal{{ $anio->id_anio }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Está seguro de que desea eliminar el año académico <strong>{{ $anio->anio }}</strong>?</p>
                                                    <p class="text-danger"><small>Si el año académico tiene registros asociados, no se podrá eliminar.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('anios.destroy', $anio) }}" method="POST">
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
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted mb-0">No se encontraron años académicos con los criterios especificados.</p>
                                    <a href="{{ route('anios.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                        <i class="bi bi-arrow-repeat me-1"></i> Mostrar todos los años académicos
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $anios->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
