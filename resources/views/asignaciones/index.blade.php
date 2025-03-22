@extends('layouts.app')

@section('title', 'Asignaciones - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Asignaciones</h1>
        <a href="{{ route('asignaciones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nueva Asignación
        </a>
    </div>

    <!-- Filtros de búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('asignaciones.index') }}" method="GET" class="row g-3 align-items-end">
                <!-- Búsqueda (por docente o materia) -->
                <div class="col-md-3">
                    <label for="busqueda" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="busqueda" name="busqueda" placeholder="Docente o materia" value="{{ request('busqueda') }}">
                </div>
                <!-- Filtro por Nivel -->
                <div class="col-md-2">
                    <label for="nivel" class="form-label">Nivel</label>
                    <select class="form-select" id="nivel" name="nivel">
                        <option value="">Todos los niveles</option>
                        @foreach($niveles as $nivel)
                            <option value="{{ $nivel->id_nivel }}" {{ (request('nivel') == $nivel->id_nivel) ? 'selected' : '' }}>
                                {{ $nivel->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Filtro por Aula -->
                <div class="col-md-4">
                    <label for="aula" class="form-label">Aula</label>
                    <select class="form-select" id="aula" name="aula" {{ empty(request('nivel')) ? 'disabled' : '' }}>
                        <option value="">Todas las aulas</option>
                        @foreach($aulas as $aula)
                            <option value="{{ $aula->id_aula }}" {{ (request('aula') == $aula->id_aula) ? 'selected' : '' }}>
                                {{ $aula->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Filtro por Año Académico (opcional) -->
                <div class="col-md-2">
                    <label for="anio" class="form-label">Año Académico</label>
                    <select class="form-select" id="anio" name="anio">
                        <option value="">Todos los años</option>
                        @foreach($anios as $anio)
                            <option value="{{ $anio->id_anio }}" {{ $filtroAnio == $anio->id_anio ? 'selected' : '' }}>
                                {{ $anio->anio }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Botones de acción -->
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                    <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-repeat me-1"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Mensajes de sesión -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <!-- Tabla de asignaciones -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Docente</th>
                            <th>Materia</th>
                            <th>Nivel</th>
                            <th>Aula</th>
                            <th>Año Académico</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asignaciones as $asignacion)
                            <tr>
                                <td>{{ $asignacion->id_asignacion }}</td>
                                <td>
                                    {{ $asignacion->docente->nombre ?? 'No definido' }} 
                                    {{ $asignacion->docente->apellido ?? '' }}
                                </td>

                                <td>{{ $asignacion->materia->nombre ?? 'No definido' }}</td>
                                <td>
                                    {{ $asignacion->aula && $asignacion->aula->nivel ? $asignacion->aula->nivel->nombre : 'No definido' }}
                                </td>
                                <td>
                                    @if($asignacion->aula)
                                        {{ $asignacion->aula->nombre_completo }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td>{{ $asignacion->anioAcademico->anio ?? 'No definido' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('asignaciones.show', $asignacion) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('asignaciones.edit', $asignacion) }}" class="btn btn-sm btn-warning text-white">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $asignacion->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <!-- Modal de confirmación de eliminación -->
                                    <div class="modal fade" id="deleteModal{{ $asignacion->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Está seguro de que desea eliminar la asignación del docente <strong>{{ $asignacion->docente->nombre ?? '' }}</strong>?</p>
                                                    <p class="text-danger">
                                                        <small>Esta acción no se puede deshacer.</small>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('asignaciones.destroy', $asignacion) }}" method="POST">
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
                                    <p class="text-muted mb-0">No se encontraron asignaciones con los criterios especificados.</p>
                                    <a href="{{ route('asignaciones.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                        <i class="bi bi-arrow-repeat me-1"></i> Mostrar todas las asignaciones
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $asignaciones->appends(request()->query())->links() }}
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
