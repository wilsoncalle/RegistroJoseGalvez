@extends('layouts.app')

@section('title', 'Registro Masivo de Estudiantes - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Registro Masivo de Estudiantes</h1>
        <div>
            <a href="{{ route('estudiantes.create') }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-person-plus me-1"></i> Crear Individual
            </a>
            <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a Estudiantes
            </a>
        </div>
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

            <form action="{{ route('estudiantes.store-bulk') }}" method="POST" id="bulkCreateForm">
                @csrf
                
                <!-- Datos Académicos Comunes -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos Académicos Comunes</h5>
                        <hr>
                        <p class="text-muted small">Los siguientes datos serán aplicados a todos los estudiantes creados en este formulario</p>
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
                    <div class="col-md-3">
                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Activo" selected>Activo</option>
                            <option value="Retirado">Retirado</option>
                            <option value="Egresado">Egresado</option>
                        </select>
                    </div>
                </div>

                <!-- Botón para descargar plantilla -->
              <!--  <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" id="btn-descargar-plantilla" class="btn btn-outline-success">
                            <i class="bi bi-download me-1"></i> Descargar Plantilla
                        </button>
                    </div>
                </div> -->

                <!-- Estudiantes Dinámicos -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos de Estudiantes</h5>
                        <hr>
                    </div>
                </div>

                <div id="estudiantes-container">
                    <!-- Aquí se insertarán los bloques de estudiantes dinámicamente -->
                    <div class="estudiante-block card mb-3" data-index="0">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Estudiante #1</h6>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-estudiante" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="estudiantes[0][nombre]" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="estudiantes[0][nombre]" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="estudiantes[0][apellido]" class="form-label">Apellido <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="estudiantes[0][apellido]" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="estudiantes[0][dni]" class="form-label">DNI</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control dni-input" name="estudiantes[0][dni]" maxlength="8" pattern="[0-9]{8}" title="El DNI debe tener 8 dígitos numéricos">
                                        <button class="btn btn-success btn-buscar-dni" type="button" data-index="0">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </div>
                                    <div class="form-text dni-mensaje" id="dni_mensaje_0"></div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="estudiantes[0][fecha_nacimiento]" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" name="estudiantes[0][fecha_nacimiento]">
                                </div>
                                <div class="col-md-4">
                                    <label for="estudiantes[0][telefono]" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" name="estudiantes[0][telefono]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Apoderados</label>
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-outline-primary btn-sm btn-select-apoderado" data-index="0">
                                            <i class="bi bi-person-plus-fill me-1"></i> Seleccionar Apoderados
                                        </button>
                                    </div>
                                    <div class="apoderados-seleccionados mt-2" id="apoderados_seleccionados_0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <button type="button" id="btn-add-estudiante" class="btn btn-outline-success w-100">
                            <i class="bi bi-plus-circle me-1"></i> Agregar otro estudiante
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Estudiantes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para seleccionar apoderados -->
<div class="modal fade" id="apoderadosModal" tabindex="-1" aria-labelledby="apoderadosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="apoderadosModalLabel">Seleccionar Apoderados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="buscar_apoderado_modal" placeholder="Buscar apoderado por nombre, apellido o DNI">
                        <button class="btn btn-primary" type="button" id="btn_buscar_apoderado_modal">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th width="5%">Sel.</th>
                                <th width="35%">Nombre</th>
                                <th width="35%">Apellido</th>
                                <th width="25%">DNI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="mensaje_no_busqueda_modal">
                                <td colspan="4" class="text-center">Por favor, busque un apoderado para seleccionar.</td>
                            </tr>
                            @foreach($apoderados as $apoderado)
                                <tr class="fila-apoderado-modal" style="display: none;">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input check-apoderado-modal" type="checkbox" value="{{ $apoderado->id_apoderado }}" data-nombre="{{ $apoderado->nombre }}" data-apellido="{{ $apoderado->apellido }}">
                                        </div>
                                    </td>
                                    <td>{{ $apoderado->nombre }}</td>
                                    <td>{{ $apoderado->apellido }}</td>
                                    <td>{{ $apoderado->dni }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_confirmar_apoderados">Confirmar Selección</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables globales
        let currentEstudianteCount = 1;
        let currentApoderadoIndex = 0;
        const apoderadosSeleccionados = {};

        // Inicializar eventos de DNI
        initDniEvents();

        // Evento para agregar nuevo estudiante
        document.getElementById('btn-add-estudiante').addEventListener('click', function() {
            addEstudianteBlock();
        });

        // Inicializar eventos para seleccionar apoderados
        initApoderadosEvents();

        // Función para agregar un nuevo bloque de estudiante
        function addEstudianteBlock() {
            const container = document.getElementById('estudiantes-container');
            const index = currentEstudianteCount;
            
            currentEstudianteCount++;
            
            const html = `
                <div class="estudiante-block card mb-3" data-index="${index}">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Estudiante #${currentEstudianteCount}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-estudiante">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="estudiantes[${index}][nombre]" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Apellido <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="estudiantes[${index}][apellido]" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">DNI</label>
                                <div class="input-group">
                                    <input type="text" class="form-control dni-input" name="estudiantes[${index}][dni]" maxlength="8" pattern="[0-9]{8}" title="El DNI debe tener 8 dígitos numéricos">
                                    <button class="btn btn-success btn-buscar-dni" type="button" data-index="${index}">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </div>
                                <div class="form-text dni-mensaje" id="dni_mensaje_${index}"></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="estudiantes[${index}][fecha_nacimiento]">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="estudiantes[${index}][telefono]">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Apoderados</label>
                                <div class="d-grid">
                                    <button type="button" class="btn btn-outline-primary btn-sm btn-select-apoderado" data-index="${index}">
                                        <i class="bi bi-person-plus-fill me-1"></i> Seleccionar Apoderados
                                    </button>
                                </div>
                                <div class="apoderados-seleccionados mt-2" id="apoderados_seleccionados_${index}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Agregar el nuevo bloque al contenedor
            container.insertAdjacentHTML('beforeend', html);
            
            // Habilitar el botón de eliminar en el primer estudiante si hay más de uno
            if (currentEstudianteCount > 1) {
                const primerBotonEliminar = document.querySelector('.estudiante-block[data-index="0"] .btn-eliminar-estudiante');
                primerBotonEliminar.disabled = false;
            }
            
            // Inicializar eventos para el nuevo bloque
            initDeleteButtonEvents();
            initDniEvents();
            initApoderadosEvents();
        }

        // Evento para eliminar estudiante
        function initDeleteButtonEvents() {
            document.querySelectorAll('.btn-eliminar-estudiante').forEach(button => {
                button.removeEventListener('click', handleDeleteEstudiante);
                button.addEventListener('click', handleDeleteEstudiante);
            });
        }

        function handleDeleteEstudiante() {
            const block = this.closest('.estudiante-block');
            block.remove();
            currentEstudianteCount--;
            
            // Actualizar numeración de estudiantes
            document.querySelectorAll('.estudiante-block').forEach((block, index) => {
                block.querySelector('h6').textContent = `Estudiante #${index + 1}`;
            });
            
            // Desactivar el botón eliminar si solo queda un estudiante
            if (currentEstudianteCount === 1) {
                const primerBotonEliminar = document.querySelector('.estudiante-block .btn-eliminar-estudiante');
                if (primerBotonEliminar) {
                    primerBotonEliminar.disabled = true;
                }
            }
        }

        // Eventos para búsqueda de DNI
        function initDniEvents() {
            document.querySelectorAll('.btn-buscar-dni').forEach(button => {
                button.removeEventListener('click', handleBuscarDni);
                button.addEventListener('click', handleBuscarDni);
            });
        }

        function handleBuscarDni() {
            const index = this.getAttribute('data-index');
            const dniInput = document.querySelector(`input[name="estudiantes[${index}][dni]"]`);
            const nombreInput = document.querySelector(`input[name="estudiantes[${index}][nombre]"]`);
            const apellidoInput = document.querySelector(`input[name="estudiantes[${index}][apellido]"]`);
            const mensajeElement = document.getElementById(`dni_mensaje_${index}`);

            const dni = dniInput.value.trim();
            
            if (!dni || dni.length !== 8 || !/^\d+$/.test(dni)) {
                mensajeElement.innerHTML = '<span class="text-danger">El DNI debe tener 8 dígitos numéricos</span>';
                return;
            }

            mensajeElement.innerHTML = '<span class="text-info">Consultando...</span>';
            
            // Realizar la consulta al DNI
            fetch(`/api/consultar-dni?dni=${dni}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nombreInput.value = data.data.nombres;
                        apellidoInput.value = `${data.data.apellido_paterno} ${data.data.apellido_materno}`.trim();
                        mensajeElement.innerHTML = '<span class="text-success">Datos cargados correctamente</span>';
                    } else {
                        mensajeElement.innerHTML = `<span class="text-danger">${data.message}</span>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mensajeElement.innerHTML = '<span class="text-danger">Error al consultar el DNI</span>';
                });
        }

        // Eventos para seleccionar apoderados
        function initApoderadosEvents() {
            const apoderadosModal = new bootstrap.Modal(document.getElementById('apoderadosModal'));
            
            document.querySelectorAll('.btn-select-apoderado').forEach(button => {
                button.removeEventListener('click', handleOpenApoderadosModal);
                button.addEventListener('click', handleOpenApoderadosModal);
            });

            document.getElementById('btn_confirmar_apoderados').addEventListener('click', function() {
                const selectedApoderados = [];
                
                document.querySelectorAll('.check-apoderado-modal:checked').forEach(checkbox => {
                    selectedApoderados.push({
                        id: checkbox.value,
                        nombre: checkbox.getAttribute('data-nombre'),
                        apellido: checkbox.getAttribute('data-apellido')
                    });
                });
                
                if (selectedApoderados.length > 0) {
                    apoderadosSeleccionados[currentApoderadoIndex] = selectedApoderados;
                    updateApoderadosDisplay(currentApoderadoIndex);
                }
                
                apoderadosModal.hide();
            });

            // Búsqueda de apoderados en modal
            document.getElementById('btn_buscar_apoderado_modal').addEventListener('click', buscarApoderadoModal);
            document.getElementById('buscar_apoderado_modal').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') buscarApoderadoModal();
            });
        }

        function handleOpenApoderadosModal() {
            currentApoderadoIndex = this.getAttribute('data-index');
            
            // Limpiar selecciones previas
            document.querySelectorAll('.check-apoderado-modal').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Marcar apoderados ya seleccionados para este estudiante
            if (apoderadosSeleccionados[currentApoderadoIndex]) {
                apoderadosSeleccionados[currentApoderadoIndex].forEach(apoderado => {
                    const checkbox = document.querySelector(`.check-apoderado-modal[value="${apoderado.id}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }
            
            // Mostrar modal
            const apoderadosModal = new bootstrap.Modal(document.getElementById('apoderadosModal'));
            apoderadosModal.show();
        }

        function updateApoderadosDisplay(index) {
            const container = document.getElementById(`apoderados_seleccionados_${index}`);
            if (!container) return;
            
            container.innerHTML = '';
            
            if (apoderadosSeleccionados[index] && apoderadosSeleccionados[index].length > 0) {
                apoderadosSeleccionados[index].forEach(apoderado => {
                    const badge = document.createElement('div');
                    badge.className = 'badge bg-info text-dark mb-1 me-1';
                    badge.textContent = `${apoderado.nombre} ${apoderado.apellido}`;
                    
                    // Input oculto para enviar al servidor
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `estudiantes[${index}][apoderados][]`;
                    input.value = apoderado.id;
                    
                    container.appendChild(badge);
                    container.appendChild(input);
                });
            } else {
                container.innerHTML = '<small class="text-muted">No hay apoderados seleccionados</small>';
            }
        }

        function buscarApoderadoModal() {
            const textoBusqueda = document.getElementById('buscar_apoderado_modal').value.toLowerCase().trim();
            const filasApoderados = document.querySelectorAll('.fila-apoderado-modal');
            const mensajeNoBusqueda = document.getElementById('mensaje_no_busqueda_modal');

            if (textoBusqueda === '') {
                mensajeNoBusqueda.style.display = 'table-row';
                mensajeNoBusqueda.querySelector('td').textContent = 'Por favor, busque un apoderado para seleccionar.';
                filasApoderados.forEach(fila => fila.style.display = 'none');
            } else {
                mensajeNoBusqueda.style.display = 'none';
                let hayResultados = false;

                filasApoderados.forEach(fila => {
                    const nombre = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const apellido = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const dni = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    
                    if (nombre.includes(textoBusqueda) || apellido.includes(textoBusqueda) || dni.includes(textoBusqueda)) {
                        fila.style.display = 'table-row';
                        hayResultados = true;
                    } else {
                        fila.style.display = 'none';
                    }
                });

                if (!hayResultados) {
                    mensajeNoBusqueda.style.display = 'table-row';
                    mensajeNoBusqueda.querySelector('td').textContent = 'No se encontraron apoderados que coincidan con la búsqueda.';
                }
            }
        }

        // Lógica para cargar aulas según nivel
        const urlAulas = "{{ url('/aulas/nivel') }}";
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

        // Evento para descargar plantilla
        document.getElementById('btn-descargar-plantilla').addEventListener('click', function() {
            const nivelId = document.getElementById('nivel').value;
            const aulaId = document.getElementById('id_aula').value;
            const fechaIngreso = document.getElementById('fecha_ingreso').value;

            if (!nivelId || !aulaId) {
                alert('Por favor, seleccione un nivel y un aula antes de descargar la plantilla.');
                return;
            }

            // Construir la URL con los parámetros
            const url = `{{ route('estudiantes.descargar-plantilla') }}?aula=${aulaId}&fecha_ingreso=${fechaIngreso || ''}`;
            
            // Redirigir a la URL de descarga
            window.location.href = url;
        });
    });
</script>
@endpush
