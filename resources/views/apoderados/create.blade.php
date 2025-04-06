@extends('layouts.app')

@section('title', 'Nuevo Apoderado - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Nuevo Apoderado</h1>
        <a href="{{ route('apoderados.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Apoderados
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('apoderados.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos Personales</h5>
                        <hr>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="nombre" class="form-label">Nombre<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="apellido" class="form-label">Apellido<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
                        @error('apellido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" class="form-control @error('dni') is-invalid @enderror" id="dni" name="dni" value="{{ old('dni') }}">
                        @error('dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="relacion" class="form-label">Relación con el Estudiante <span class="text-danger">*</span></label>
                        <select class="form-select @error('relacion') is-invalid @enderror" id="relacion" name="relacion" required>
                            <option value="">Seleccionar...</option>
                            <option value="Padre" {{ old('relacion') == 'Padre' ? 'selected' : '' }}>Padre</option>
                            <option value="Madre" {{ old('relacion') == 'Madre' ? 'selected' : '' }}>Madre</option>
                            <option value="Abuelo/a" {{ old('relacion') == 'Abuelo/a' ? 'selected' : '' }}>Abuelo/a</option>
                            <option value="Tío/a" {{ old('relacion') == 'Tío/a' ? 'selected' : '' }}>Tío/a</option>
                            <option value="Tutor Legal" {{ old('relacion') == 'Tutor Legal' ? 'selected' : '' }}>Tutor Legal</option>
                            <option value="Otro" {{ old('relacion') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('relacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Estudiantes Asociados</h5>
                        <hr>
                        <p class="text-muted small">Seleccione los estudiantes que estarán asociados a este apoderado</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="buscador_estudiante" placeholder="Buscar estudiante por nombre o DNI...">
                                            <button class="btn btn-primary me-2" type="button" id="btn_buscar">
                                                <i class="bi bi-search me-1"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                    @if($estudiantes->count() > 0)
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="5%"></th>
                                                        <th>Nombre</th>
                                                        <th>Apellido</th>
                                                        <th>DNI</th>
                                                        <th>Nivel</th>
                                                        <th>Aula</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tabla_estudiantes">
                                                    <!-- Mensaje inicial -->
                                                    <tr id="mensaje_no_busqueda" style="display: table-row;">
                                                        <td colspan="6" class="text-center">Por favor, busque un estudiante para seleccionar.</td>
                                                    </tr>
                                                    <!-- Filas de estudiantes ocultas inicialmente -->
                                                    @foreach($estudiantes as $estudiante)
                                                    <tr class="fila-estudiante" style="display: none;">
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input estudiante-checkbox" type="checkbox" 
                                                                    name="estudiantes[]" value="{{ $estudiante->id_estudiante }}" 
                                                                    id="estudiante{{ $estudiante->id_estudiante }}"
                                                                    {{ in_array($estudiante->id_estudiante, old('estudiantes', [])) ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td>{{ $estudiante->nombre }}</td>
                                                        <td>{{ $estudiante->apellido }}</td>
                                                        <td>{{ $estudiante->dni ?? 'No registrado' }}</td>
                                                        <td>{{ $estudiante->aula && $estudiante->aula->nivel ? $estudiante->aula->nivel->nombre : 'No definido' }}</td>
                                                        <td>
                                                            @if($estudiante->aula)
                                                                {{ $estudiante->aula->nombre_completo }}
                                                            @else
                                                                <span class="text-muted">No asignado</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            No hay estudiantes activos disponibles para asociar.
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Apoderado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscadorInput = document.getElementById('buscador_estudiante');
    const btnBuscar = document.getElementById('btn_buscar');
    const filasEstudiantes = document.querySelectorAll('.fila-estudiante');
    const mensajeNoBusqueda = document.getElementById('mensaje_no_busqueda');

    function buscarEstudiante() {
        const textoBusqueda = buscadorInput.value.toLowerCase().trim();

        if (textoBusqueda === '') {
            // Mostrar mensaje inicial y ocultar filas
            mensajeNoBusqueda.style.display = 'table-row';
            mensajeNoBusqueda.querySelector('td').textContent = 'Por favor, busque un estudiante para seleccionar.';
            filasEstudiantes.forEach(fila => {
                fila.style.display = 'none';
            });
        } else {
            // Ocultar mensaje por defecto
            mensajeNoBusqueda.style.display = 'none';
            let hayResultados = false;

            // Filtrar estudiantes
            filasEstudiantes.forEach(fila => {
                const nombre = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const apellido = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const dni = fila.querySelector('td:nth-child(4)').textContent.toLowerCase(); // Ajustado a la columna correcta (DNI es la 4ª)
                
                if (nombre.includes(textoBusqueda) || dni.includes(textoBusqueda) || apellido.includes(textoBusqueda)) {
                    fila.style.display = '';
                    hayResultados = true;
                } else {
                    fila.style.display = 'none';
                }
            });

            // Mostrar mensaje si no hay resultados
            if (!hayResultados) {
                mensajeNoBusqueda.style.display = 'table-row';
                mensajeNoBusqueda.querySelector('td').textContent = 'No se encontraron estudiantes que coincidan con la búsqueda.';
            }
        }
    }

    // Eventos
    btnBuscar.addEventListener('click', buscarEstudiante);
    buscadorInput.addEventListener('input', buscarEstudiante); // Búsqueda en tiempo real

    // Ejecutar al cargar la página
    buscarEstudiante();
});
</script>
@endpush
@endsection