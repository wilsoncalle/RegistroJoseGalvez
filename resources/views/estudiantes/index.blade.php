@extends('layouts.app')

@section('title', 'Estudiantes - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Estudiantes</h1>
        <div class="d-flex gap-2">
            <!-- Dropdown Exportar -->
            <div class="dropdown">
                <button class="btn btn-warning dropdown-toggle text-white" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-download me-1"></i> Exportar
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('estudiantes.exportPdf', request()->query()) }}">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Exportar a PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-success" href="{{ route('estudiantes.exportExcel', request()->query()) }}">
                            <i class="bi bi-file-earmark-excel me-1"></i> Exportar a Excel
                        </a>
                    </li>
                </ul>
            </div>
            <style>
                /* Estilo personalizado para PDF */
                .dropdown-item.text-danger:active,
                .dropdown-item.text-danger:focus,
                .dropdown-item.text-danger:hover {
                    background-color: #dc3545 !important; /* rojo Bootstrap */
                    color: #fff !important;
                }

                /* Estilo personalizado para Excel */
                .dropdown-item.text-success:active,
                .dropdown-item.text-success:focus,
                .dropdown-item.text-success:hover {
                    background-color: #198754 !important; /* verde Bootstrap */
                    color: #fff !important;
                }
            </style>
            <a href="{{ route('estudiantes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Estudiante
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('estudiantes.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="busqueda" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="busqueda" name="busqueda" placeholder="Nombre o DNI" value="{{ $busqueda }}">
                </div>
                <div class="col-md-3">
                    <label for="nivel" class="form-label">Nivel</label>
                    <select class="form-select" id="nivel" name="nivel">
                        <option value="">Todos los niveles</option>
                        @foreach($niveles as $nivel)
                            <option value="{{ $nivel->id_nivel }}" {{ $filtroNivel == $nivel->id_nivel ? 'selected' : '' }}>
                                {{ $nivel->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="aula" class="form-label">Aula</label>
                    <select class="form-select" id="aula" name="aula" disabled>
                        <option value="">Todas las aulas</option>
                        @foreach($aulas as $aula)
                            <option value="{{ $aula->id_aula }}" {{ $filtroAula == $aula->id_aula ? 'selected' : '' }}>
                                {{ $aula->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Todos</option>
                        <option value="Activo" {{ $filtroEstado == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Retirado" {{ $filtroEstado == 'Retirado' ? 'selected' : '' }}>Retirado</option>
                        <option value="Egresado" {{ $filtroEstado == 'Egresado' ? 'selected' : '' }}>Egresado</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end gap-2">
                    <!-- Botón Buscar -->
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>

                    <!-- Botón Limpiar -->
                    <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">
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
                            <th>Apellido</th>
                            <th>DNI</th>
                            <th>Nivel</th>
                            <th>Aula</th>
                            <th>Apoderado</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = ($estudiantes->currentPage() - 1) * $estudiantes->perPage() + 1; @endphp
                        @forelse($estudiantes as $estudiante)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>{{ $estudiante->nombre }}</td>
                                <td>{{ $estudiante->apellido }}</td>
                                <td>{{ $estudiante->dni ?: 'No registrado'}}</td>
                                <td>{{ $estudiante->aula && $estudiante->aula->nivel ? $estudiante->aula->nivel->nombre : 'No definido' }}</td>
                                <td>
                                    @if($estudiante->aula)
                                        {{ $estudiante->aula->nombre_completo }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($estudiante->apoderados->isNotEmpty())
                                        {{ $estudiante->apoderados->first()->nombre }}
                                        <small class="text-muted">({{ $estudiante->apoderados->first()->relacion }})</small>
                                    @else
                                        <span class="text-danger">Sin apoderado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($estudiante->estado == 'Activo')
                                        <span class="badge bg-success">Activo</span>
                                    @elseif($estudiante->estado == 'Retirado')
                                        <span class="badge bg-danger">Retirado</span>
                                    @else
                                        <span class="badge bg-secondary">Egresado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('estudiantes.show', $estudiante) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('estudiantes.edit', $estudiante) }}" class="btn btn-sm btn-warning text-white">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $estudiante->id_estudiante }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal de confirmación de eliminación -->
                                    <div class="modal fade" id="deleteModal{{ $estudiante->id_estudiante }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Está seguro de que desea eliminar al estudiante <strong>{{ $estudiante->nombre }} {{ $estudiante->apellido }}</strong>?</p>
                                                    <p class="text-danger"><small>Esta acción no se puede deshacer. Si el estudiante tiene registros académicos, será marcado como "Retirado" en lugar de ser eliminado.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('estudiantes.destroy', $estudiante) }}" method="POST">
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
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-muted mb-0">No se encontraron estudiantes con los criterios especificados.</p>
                                    <a href="{{ route('estudiantes.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                        <i class="bi bi-arrow-repeat me-1"></i> Mostrar todos los estudiantes
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
                    {{ $estudiantes->firstItem() }} - 
                    {{ $estudiantes->lastItem() }} 
                    {{ __('de') }} 
                    {{ $estudiantes->total() }} {{ __('resultados') }}
                </div>
                <div>
                    {{ $estudiantes->appends(request()->query())->links('pagination::custom-bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const nivelSelect = document.getElementById('nivel');
    const aulaSelect = document.getElementById('aula');
    const urlAulas = "{{ url('get-aulas-por-nivel') }}";

    function cargarAulas(nivelId) {
        if (nivelId) {
            fetch(`${urlAulas}/${nivelId}`)
                .then(response => response.json())
                .then(data => {
                    aulaSelect.innerHTML = '<option value="">Selecciona un aula</option>';
                    data.forEach(aula => {
                        const option = document.createElement('option');
                        option.value = aula.id;
                        option.textContent = aula.nombre_completo;
                        aulaSelect.appendChild(option);
                    });
                    aulaSelect.disabled = false; // Habilita el select de aulas
                })
                .catch(error => {
                    console.error('Error al cargar aulas:', error);
                    aulaSelect.innerHTML = '<option value="">Error al cargar aulas</option>';
                    aulaSelect.disabled = true;
                });
        } else {
            aulaSelect.innerHTML = `<option value="">Todas las aulas</option>
                @foreach($aulas as $aula)
                    <option value="{{ $aula->id_aula }}" {{ $filtroAula == $aula->id_aula ? 'selected' : '' }}>
                        {{ $aula->nombre_completo }}
                    </option>
                @endforeach`;
            aulaSelect.disabled = true; // Deshabilita el select si no se selecciona un nivel
        }
    }

    // Ejecutar la función en caso de que haya un nivel preseleccionado
    cargarAulas(nivelSelect.value);

    // Escuchar el evento de cambio en el nivel
    nivelSelect.addEventListener('change', function() {
        cargarAulas(this.value);
    });
});
</script>
@endpush

@endsection
