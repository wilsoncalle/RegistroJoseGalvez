@extends('layouts.app')

@section('title', 'Nuevo Estudiante - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Nuevo Estudiante</h1>
        <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Estudiantes
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

            <form action="{{ route('estudiantes.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos Personales</h5>
                        <hr>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos Académicos</h5>
                        <hr>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_aula" class="form-label">Aula</label>
                        <select class="form-select" id="id_aula" name="id_aula">
                            <option value="">Seleccionar aula...</option>
                            @foreach($aulas as $aula)
                                <option value="{{ $aula->id_aula }}" {{ old('id_aula') == $aula->id_aula ? 'selected' : '' }}>
                                    {{ $aula->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Retirado" {{ old('estado') == 'Retirado' ? 'selected' : '' }}>Retirado</option>
                            <option value="Egresado" {{ old('estado') == 'Egresado' ? 'selected' : '' }}>Egresado</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5>Información del Apoderado Principal</h5>
                        <hr>
                        <p class="text-muted small">Seleccione los apoderados que estarán asociados a este estudiante</p>
                    </div>
                </div>
                <div class="col-md-12"> 
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="buscador_apoderado" placeholder="Buscar apoderado por nombre o DNI...">
                                        <button class="btn btn-primary me-2" type="button" id="btn_buscar">
                                            <i class="bi bi-search me-1"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                                @if($apoderados->count() > 0)
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="5%"></th>
                                                    <th>Apoderado</th>
                                                    <th>DNI</th>
                                                    <th>Teléfono</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tabla_apoderados">
                                                @foreach($apoderados as $apoderado)
                                                <tr class="fila-apoderado">
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input apoderado-checkbox" type="checkbox" 
                                                                name="apoderados[]" value="{{ $apoderado->id_apoderado }}" 
                                                                id="apoderado{{ $apoderado->id_apoderado }}"
                                                                {{ in_array($apoderado->id_apoderado, old('apoderados', [])) ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>{{ $apoderado->nombre }}</td>
                                                    <td>{{ $apoderado->dni ?? 'No registrado' }}</td>
                                                    <td>{{ $apoderado->telefono ?? 'No registrado' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        No hay apoderados activos disponibles para asociar.
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const buscadorInput = document.getElementById('buscador_apoderado');
                    const btnBuscar = document.getElementById('btn_buscar');
                    const filasApoderados = document.querySelectorAll('.fila-apoderado');
                    
                    function buscarApoderado() {
                        const textoBusqueda = buscadorInput.value.toLowerCase().trim();
                        
                        if (textoBusqueda === '') {
                            filasApoderados.forEach(fila => fila.style.display = '');
                            return;
                        }
                        
                        filasApoderados.forEach(fila => {
                            const nombre = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                            const dni = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                            
                            fila.style.display = (nombre.includes(textoBusqueda) || dni.includes(textoBusqueda)) ? '' : 'none';
                        });
                    }
                    
                    btnBuscar.addEventListener('click', buscarApoderado);
                    buscadorInput.addEventListener('keyup', function(e) {
                        if (e.key === 'Enter') {
                            buscarApoderado();
                        }
                    });
                });
                </script>

                @push('scripts')
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const seleccionarTodos = document.getElementById('seleccionar_todos');
                    const checkboxes = document.querySelectorAll('.apoderado-checkbox');
                    
                    if(seleccionarTodos) {
                        seleccionarTodos.addEventListener('change', function() {
                            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                        });
                    }
                });
                </script>
                @endpush

                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Estudiante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
