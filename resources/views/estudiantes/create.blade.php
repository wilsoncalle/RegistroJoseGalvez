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
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="dni" class="form-label">DNI</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni') }}" maxlength="8" pattern="[0-9]{8}" title="El DNI debe tener 8 dígitos numéricos">
                            <button class="btn btn-success" type="button" id="btn_buscar_dni">
                                <i class="bi bi-check"></i> 
                            </button>
                        </div>
                        <div class="form-text" id="dni_mensaje"></div>
                    </div>
                    <div class="col-md-2">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}">
                    </div>
                </div>

                <!-- Datos Académicos -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos Académicos</h5>
                        <hr>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="nivel" class="form-label">Nivel</label>
                        <select name="nivel" id="nivel" class="form-control" required>
                            <option value="">Seleccione un nivel</option>
                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel->id_nivel }}">{{ $nivel->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="id_aula" class="form-label">Aula</label>
                        <select name="id_aula" id="id_aula" class="form-control" required disabled>
                            <option value="">Primero seleccione un nivel</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Retirado" {{ old('estado') == 'Retirado' ? 'selected' : '' }}>Retirado</option>
                            <option value="Egresado" {{ old('estado') == 'Egresado' ? 'selected' : '' }}>Egresado</option>
                        </select>
                    </div>
                </div>

                <!-- Información del Apoderado Principal -->
                <div class="row mb-3">
                    <div class="col-md-12">
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
                                                    <th>Nombre</th>
                                                    <th>Apellido</th>
                                                    <th>DNI</th>
                                                    <th>Teléfono</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tabla_apoderados">
                                                <!-- Mensaje inicial -->
                                                <tr id="mensaje_no_busqueda" style="display: table-row;">
                                                    <td colspan="4" class="text-center">Por favor, busque un apoderado para seleccionar.</td>
                                                </tr>
                                                <!-- Filas de apoderados ocultas inicialmente -->
                                                @foreach($apoderados as $apoderado)
                                                <tr class="fila-apoderado" style="display: none;">
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input apoderado-checkbox" type="checkbox" 
                                                                name="apoderados[]" value="{{ $apoderado->id_apoderado }}" 
                                                                id="apoderado{{ $apoderado->id_apoderado }}"
                                                                {{ in_array($apoderado->id_apoderado, old('apoderados', [])) ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>{{ $apoderado->nombre }}</td>
                                                    <td>{{ $apoderado->apellido }}</td>
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

                <!-- Botones de acción -->
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

@push('scripts')
<script>
// Lógica para consultar DNI y autocompletar datos
document.addEventListener('DOMContentLoaded', function() {
    const btnBuscarDni = document.getElementById('btn_buscar_dni');
    const dniInput = document.getElementById('dni');
    const nombreInput = document.getElementById('nombre');
    const apellidoInput = document.getElementById('apellido');
    const dniMensaje = document.getElementById('dni_mensaje');
    
    btnBuscarDni.addEventListener('click', function() {
        const dni = dniInput.value.trim();
        
        // Validar que el DNI tenga 8 dígitos
        if (!/^\d{8}$/.test(dni)) {
            dniMensaje.textContent = 'El DNI debe tener 8 dígitos numéricos';
            dniMensaje.classList.add('text-danger');
            return;
        }
        
        // Mostrar indicador de carga
        dniMensaje.textContent = 'Consultando DNI...';
        dniMensaje.classList.remove('text-danger', 'text-success');
        dniMensaje.classList.add('text-info');
        
        // Realizar la consulta AJAX
        fetch('{{ route("estudiantes.consultar-dni") }}', {
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
                // Autocompletar los campos con la información obtenida
                const persona = data.data;
                
                if (persona.nombres) {
                    nombreInput.value = persona.nombres;
                }
                
                // Combinar apellido paterno y materno para el campo apellido
                if (persona.apellido_paterno && persona.apellido_materno) {
                    apellidoInput.value = persona.apellido_paterno + ' ' + persona.apellido_materno;
                } else if (persona.apellido_paterno) {
                    apellidoInput.value = persona.apellido_paterno;
                } else if (persona.apellidos) {
                    apellidoInput.value = persona.apellidos;
                }
                
                dniMensaje.textContent = 'Datos cargados correctamente';
                dniMensaje.classList.remove('text-danger', 'text-info');
                dniMensaje.classList.add('text-success');
            } else {
                dniMensaje.textContent = data.message || 'No se encontraron datos para este DNI';
                dniMensaje.classList.remove('text-success', 'text-info');
                dniMensaje.classList.add('text-danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            dniMensaje.textContent = 'Error al consultar el DNI. Intente nuevamente.';
            dniMensaje.classList.remove('text-success', 'text-info');
            dniMensaje.classList.add('text-danger');
        });
    });
    
    // Evento para validar que solo se ingresen números en el campo DNI
    dniInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
    });
    
    // Lógica de búsqueda de apoderados
    const buscadorInput = document.getElementById('buscador_apoderado');
    const btnBuscar = document.getElementById('btn_buscar');
    const filasApoderados = document.querySelectorAll('.fila-apoderado');
    const mensajeNoBusqueda = document.getElementById('mensaje_no_busqueda');

    function buscarApoderado() {
        const textoBusqueda = buscadorInput.value.toLowerCase().trim();

        if (textoBusqueda === '') {
            // Mostrar mensaje inicial y ocultar filas
            mensajeNoBusqueda.style.display = 'table-row';
            mensajeNoBusqueda.querySelector('td').textContent = 'Por favor, busque un apoderado para seleccionar.';
            filasApoderados.forEach(fila => fila.style.display = 'none');
        } else {
            // Ocultar mensaje por defecto
            mensajeNoBusqueda.style.display = 'none';
            let hayResultados = false;

            // Filtrar apoderados
            filasApoderados.forEach(fila => {
                const nombre = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const apellido = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const dni = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (nombre.includes(textoBusqueda) || apellido.includes(textoBusqueda) || dni.includes(textoBusqueda)) {
                    fila.style.display = '';
                    hayResultados = true;
                } else {
                    fila.style.display = 'none';
                }
            });

            // Mostrar mensaje si no hay resultados
            if (!hayResultados) {
                mensajeNoBusqueda.style.display = 'table-row';
                mensajeNoBusqueda.querySelector('td').textContent = 'No se encontraron apoderados que coincidan con la búsqueda.';
            }
        }
    }

    // Eventos
    btnBuscar.addEventListener('click', buscarApoderado);
    buscadorInput.addEventListener('input', buscarApoderado); // Búsqueda en tiempo real
    buscadorInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') buscarApoderado();
    });

    // Ejecutar al cargar la página
    buscarApoderado();
});

// Lógica para cargar aulas según nivel
const urlAulas = "{{ url('/aulas/nivel') }}";
document.addEventListener('DOMContentLoaded', function() {
    const nivelSelect = document.getElementById('nivel');
    const aulaSelect = document.getElementById('id_aula');
    
    nivelSelect.addEventListener('change', function() {
        const nivelId = this.value;
        
        aulaSelect.innerHTML = '<option value="">Cargando aulas...</option>';
        
        if (nivelId) {
            aulaSelect.disabled = false;
            fetch(`${urlAulas}/${nivelId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la red');
                    return response.json();
                })
                .then(aulas => {
                    aulaSelect.innerHTML = '';
                    if (aulas.length > 0) {
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Seleccione un aula';
                        aulaSelect.appendChild(defaultOption);
                        aulas.forEach(aula => {
                            const option = document.createElement('option');
                            option.value = aula.id;
                            option.textContent = aula.nombre_completo;
                            aulaSelect.appendChild(option);
                        });
                    } else {
                        aulaSelect.innerHTML = '<option value="">No hay aulas disponibles para este nivel</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    aulaSelect.innerHTML = '<option value="">Error al cargar aulas</option>';
                });
        } else {
            aulaSelect.disabled = true;
            aulaSelect.innerHTML = '<option value="">Primero seleccione un nivel</option>';
        }
    });
});
</script>
@endpush
@endsection