@extends('layouts.app')

@section('title', 'Editar Apoderado - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Editar Apoderado</h1>
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

            <form action="{{ route('apoderados.update', $apoderado) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Datos Personales -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos Personales</h5>
                        <hr>
                    </div>
                </div>
                
                <div class="row mb-3">
                <div class="col-md-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" 
                               value="{{ old('nombre', $apoderado->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('apellido') is-invalid @enderror" 
                               id="apellido" name="apellido" 
                               value="{{ old('apellido', $apoderado->apellido) }}" required>
                        @error('apellido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="dni" class="form-label">DNI</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('dni') is-invalid @enderror" 
                                   id="dni" name="dni" value="{{ old('dni', $apoderado->dni) }}"
                                   maxlength="8" pattern="[0-9]{8}" title="El DNI debe tener 8 dígitos numéricos">
                            <button class="btn btn-success" type="button" id="btn_buscar_dni">
                                <i class="bi bi-check"></i>
                            </button>
                        </div>
                        <div class="form-text" id="dni_mensaje"></div>
                        @error('dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                               id="telefono" name="telefono" 
                               value="{{ old('telefono', $apoderado->telefono) }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3 mt-3">
                        <label for="relacion" class="form-label">Relación con el Estudiante <span class="text-danger">*</span></label>
                        <select class="form-select @error('relacion') is-invalid @enderror" id="relacion" name="relacion" required>
                            <option value="">Seleccionar...</option>
                            <option value="Padre" {{ old('relacion', $apoderado->relacion) == 'Padre' ? 'selected' : '' }}>Padre</option>
                            <option value="Madre" {{ old('relacion', $apoderado->relacion) == 'Madre' ? 'selected' : '' }}>Madre</option>
                            <option value="Abuelo/a" {{ old('relacion', $apoderado->relacion) == 'Abuelo/a' ? 'selected' : '' }}>Abuelo/a</option>
                            <option value="Tío/a" {{ old('relacion', $apoderado->relacion) == 'Tío/a' ? 'selected' : '' }}>Tío/a</option>
                            <option value="Tutor Legal" {{ old('relacion', $apoderado->relacion) == 'Tutor Legal' ? 'selected' : '' }}>Tutor Legal</option>
                            <option value="Otro" {{ old('relacion', $apoderado->relacion) == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('relacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Estudiantes Asociados -->
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
                                            <input type="text" class="form-control" id="buscador_estudiante" 
                                                   placeholder="Buscar estudiante por nombre o DNI...">
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
                                                    <tr class="fila-estudiante" style="{{ in_array($estudiante->id_estudiante, old('estudiantes', $apoderado->estudiantes->pluck('id_estudiante')->toArray())) ? '' : 'display: none;' }}">

                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input estudiante-checkbox" type="checkbox" 
                                                                       name="estudiantes[]" value="{{ $estudiante->id_estudiante }}" 
                                                                       id="estudiante{{ $estudiante->id_estudiante }}"
                                                                       {{ in_array($estudiante->id_estudiante, old('estudiantes', $apoderado->estudiantes->pluck('id_estudiante')->toArray())) ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td>{{ $estudiante->nombre }}</td>
                                                        <td>{{ $estudiante->apellido }}</td>
                                                        <td>{{ $estudiante->dni ?? 'No registrado' }}</td>
                                                        <td>
                                                            @if($estudiante->aula && $estudiante->aula->nivel)
                                                                {{ $estudiante->aula->nivel->nombre }}
                                                            @else
                                                                <span class="text-muted">No definido</span>
                                                            @endif
                                                        </td>
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
                
                <!-- Botones -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Actualizar Apoderado
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
            let algunaVisible = false;
            filasEstudiantes.forEach(fila => {
                const checkbox = fila.querySelector('.estudiante-checkbox');
                if (checkbox.checked) {
                    fila.style.display = '';
                    algunaVisible = true;
                } else {
                    fila.style.display = 'none';
                }
            });
            // Si ninguna fila asignada se muestra, mostramos el mensaje inicial.
            mensajeNoBusqueda.style.display = algunaVisible ? 'none' : 'table-row';
            if (!algunaVisible) {
                mensajeNoBusqueda.querySelector('td').textContent = 'Por favor, busque un estudiante para seleccionar.';
            }
        } else {
            mensajeNoBusqueda.style.display = 'none';
            let hayResultados = false;
            filasEstudiantes.forEach(fila => {
                const nombre = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const apellido = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const dni = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                if (nombre.includes(textoBusqueda) || apellido.includes(textoBusqueda) || dni.includes(textoBusqueda)) {
                    fila.style.display = '';
                    hayResultados = true;
                } else {
                    fila.style.display = 'none';
                }
            });
            if (!hayResultados) {
                mensajeNoBusqueda.style.display = 'table-row';
                mensajeNoBusqueda.querySelector('td').textContent = 'No se encontraron estudiantes que coincidieron con la búsqueda.';
            }
        }
    }

    btnBuscar.addEventListener('click', buscarEstudiante);
    buscadorInput.addEventListener('input', buscarEstudiante);
    buscadorInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') buscarEstudiante();
    });

    // Ejecutar al cargar la página para mostrar los estudiantes asignados
    buscarEstudiante();
});
// Lógica para consultar DNI y autocompletar datos
document.addEventListener('DOMContentLoaded', function() {
    const btnBuscarDni = document.getElementById('btn_buscar_dni');
    const dniInput = document.getElementById('dni');
    const nombreInput = document.getElementById('nombre');
    const apellidoInput = document.getElementById('apellido');
    const dniMensaje = document.getElementById('dni_mensaje');
    
    btnBuscarDni.addEventListener('click', function() {
        const dni = dniInput.value.trim();
        
        if (!/^\d{8}$/.test(dni)) {
            dniMensaje.textContent = 'El DNI debe tener 8 dígitos numéricos';
            dniMensaje.classList.add('text-danger');
            return;
        }
        
        dniMensaje.textContent = 'Consultando DNI...';
        dniMensaje.classList.remove('text-danger', 'text-success');
        dniMensaje.classList.add('text-info');
        
        fetch('{{ route("apoderados.consultar-dni") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ dni: dni })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const persona = data.data;
                
                if (persona.nombres) {
                    nombreInput.value = persona.nombres;
                }
                
                if (persona.apellido_paterno && persona.apellido_materno) {
                    apellidoInput.value = `${persona.apellido_paterno} ${persona.apellido_materno}`;
                } else if (persona.apellidos) {
                    apellidoInput.value = persona.apellidos;
                }
                
                dniMensaje.textContent = 'Datos actualizados desde DNI';
                dniMensaje.classList.add('text-success');
            } else {
                dniMensaje.textContent = data.message || 'No se encontraron datos';
                dniMensaje.classList.add('text-danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            dniMensaje.textContent = 'Error en la consulta';
            dniMensaje.classList.add('text-danger');
        });
    });
    
    dniInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
    });
});

</script>
@endpush
@endsection
