@extends('layouts.app')

@section('title', 'Importar Estudiantes - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Importar Estudiantes</h1>
        <div>
            <a href="{{ route('estudiantes.create') }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-person-plus me-1"></i> Crear Individual
            </a>
            <a href="{{ route('estudiantes.bulk-delete-form') }}" class="btn btn-outline-danger me-2">
                <i class="bi bi-trash me-1"></i> Eliminar Importación Errónea
            </a>
            <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver a Estudiantes
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-excel me-2"></i>Importar desde Excel/CSV</h5>
                </div>
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

                    <form action="{{ route('estudiantes.importar') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <input type="hidden" name="confirm_import" id="confirm_import" value="0">
                        <div class="mb-3">
                            <label for="file" class="form-label">Archivo Excel/CSV</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".csv, .xls, .xlsx" required>
                            <div class="form-text">Seleccione un archivo Excel o CSV con los datos de los estudiantes.</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nivel" class="form-label">Nivel</label>
                                <select class="form-select" id="nivel" name="nivel">
                                    <option value="">Seleccione un nivel</option>
                                    @foreach($niveles as $nivel)
                                        <option value="{{ $nivel->id_nivel }}">{{ $nivel->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="id_aula" class="form-label">Aula (opcional)</label>
                                <select class="form-select" id="id_aula" name="id_aula" disabled>
                                    <option value="">Primero seleccione un nivel</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="{{ date('Y-m-d') }}">
                            <div class="form-text">Formato día/mes/año. Se utilizará para todos los estudiantes importados.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" id="btnPreview" class="btn btn-info text-white flex-grow-1">
                                <i class="bi bi-eye me-1"></i> Vista Previa
                            </button>
                            <button type="button" id="btnImport" class="btn btn-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#confirmModal">
                                <i class="bi bi-upload me-1"></i> Importar Estudiantes
                            </button>
                        </div>
                        
                        <!-- Sección de vista previa -->
                        <div id="previewSection" class="mt-4" style="display: none;">
                            <h5 class="border-bottom pb-2">Vista Previa de Datos a Importar</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>DNI</th>
                                            <th>Fecha Nac.</th>
                                            <th>Teléfono</th>
                                            <th>Aula</th>
                                        </tr>
                                    </thead>
                                    <tbody id="previewData">
                                        <!-- Aquí se cargarán los datos de la vista previa -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="alert alert-info mt-2">
                                <i class="bi bi-info-circle"></i> Se muestran los primeros 10 registros del archivo.
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Formato del Archivo</h5>
                </div>
                <div class="card-body">
                    <p>El archivo debe tener las siguientes columnas (en este orden):</p>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Columna</th>
                                <th>Descripción</th>
                                <th>Requerido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nombre</td>
                                <td>Nombre del estudiante</td>
                                <td class="text-center"><span class="badge bg-success">Sí</span></td>
                            </tr>
                            <tr>
                                <td>Apellido</td>
                                <td>Apellido del estudiante</td>
                                <td class="text-center"><span class="badge bg-success">Sí</span></td>
                            </tr>
                            <tr>
                                <td>DNI</td>
                                <td>Número de DNI (8 dígitos)</td>
                                <td class="text-center"><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td>Fecha Nacimiento</td>
                                <td>En formato DD/MM/YYYY (12/12/2012)</td>
                                <td class="text-center"><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td>Teléfono</td>
                                <td>Número de teléfono del estudiante</td>
                                <td class="text-center"><span class="badge bg-secondary">No</span></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-3">
                        <a href="javascript:void(0)" id="btn-descargar-plantilla-simple" class="btn btn-outline-primary">
                            <i class="bi bi-download me-1"></i> Descargar Plantilla
                        </a>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                document.getElementById('btn-descargar-plantilla-simple').addEventListener('click', function() {
                                    const fechaIngreso = document.getElementById('fecha_ingreso').value;
                                    const nivelId = document.getElementById('nivel').value;
                                    const aulaId = document.getElementById('id_aula').value;
                                    
                                    // Construir la URL con los parámetros
                                    const url = `{{ route('estudiantes.descargar-plantilla') }}?fecha_ingreso=${fechaIngreso || ''}&nivel=${nivelId || ''}&aula=${aulaId || ''}`;
                                    
                                    // Redirigir a la URL de descarga
                                    window.location.href = url;
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Ayuda</h5>
                </div>
                <div class="card-body">
                    <h6 class="card-subtitle mb-3">Instrucciones:</h6>
                    <ol class="ps-3">
                        <li class="mb-2">Descargue la plantilla para asegurar que su archivo tenga el formato correcto.</li>
                        <li class="mb-2">Complete los datos de los estudiantes en la plantilla.</li>
                        <li class="mb-2">Seleccione el archivo completado para importar.</li>
                        <li class="mb-2">Opcionalmente, seleccione un aula para todos los estudiantes importados.</li>
                        <li class="mb-2">Haga clic en "Importar Estudiantes" para comenzar el proceso.</li>
                    </ol>
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> El sistema validará los datos antes de importarlos. Si hay errores, se mostrarán y ningún estudiante será importado.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lógica para cargar aulas según nivel
        const urlAulas = "{{ url('/aulas/nivel') }}";
        const nivelSelect = document.getElementById('nivel');
        const aulaSelect = document.getElementById('id_aula');
        const btnPreview = document.getElementById('btnPreview');
        const btnImport = document.getElementById('btnImport');
        const previewSection = document.getElementById('previewSection');
        const previewData = document.getElementById('previewData');
        const importForm = document.getElementById('importForm');
        const fileInput = document.getElementById('file');
        const confirmImport = document.getElementById('confirm_import');
        
        // Lógica para vista previa
        btnPreview.addEventListener('click', function() {
            // Validar que se haya seleccionado un archivo
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Por favor, seleccione un archivo para importar');
                return;
            }
            
            const formData = new FormData(importForm);
            
            // Mostrar indicador de carga
            previewData.innerHTML = '<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><p class="mt-2">Procesando archivo, por favor espere...</p></td></tr>';
            previewSection.style.display = 'block';
            
            // Realizar la solicitud para la vista previa
            fetch('{{ route("estudiantes.preview") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Error al procesar el archivo');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Limpiar tabla
                    previewData.innerHTML = '';
                    
                    // Mostrar datos en la tabla
                    if (!data.data || data.data.length === 0) {
                        previewData.innerHTML = '<tr><td colspan="7" class="text-center">No se encontraron datos en el archivo</td></tr>';
                    } else {
                        // Mostrar hasta 10 registros
                        const records = data.data.slice(0, 10);
                        records.forEach((record, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${record.nombre || '-'}</td>
                                <td>${record.apellido || '-'}</td>
                                <td>${record.dni || '-'}</td>
                                <td>${record.fecha_nacimiento || '-'}</td>
                                <td>${record.telefono || '-'}</td>
                                <td>${data.aula || 'Por asignar'}</td>
                            `;
                            previewData.appendChild(row);
                        });
                        
                        // Mostrar estadísticas
                        const statsRow = document.createElement('tr');
                        statsRow.className = 'table-info';
                        statsRow.innerHTML = `
                            <td colspan="7" class="text-end">
                                <strong>Total de registros: </strong> ${data.data.length} 
                                ${data.data.length > 10 ? '(mostrando solo los primeros 10)' : ''}
                            </td>
                        `;
                        previewData.appendChild(statsRow);
                        
                        // Actualizar texto del botón de vista previa
                        btnPreview.innerHTML = '<i class="bi bi-eye me-1"></i> <span class="text-white">Actualizar Vista Previa</span>';
                        confirmImport.value = '1';
                        
                        // Actualizar información para el modal de confirmación
                        document.getElementById('fileName').textContent = fileInput.files[0].name;
                        document.getElementById('recordCount').textContent = data.data.length;
                        document.getElementById('targetClassroom').textContent = data.aula || 'No seleccionada';
                    }
                } else {
                    // Mostrar error con más detalles
                    const errorMessage = data.message || 'Error desconocido al procesar el archivo';
                    previewData.innerHTML = `<tr><td colspan="7" class="text-center text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${errorMessage}
                    </td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                previewData.innerHTML = `<tr><td colspan="7" class="text-center text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Error al procesar el archivo: ${error.message}
                    <br>
                    <small class="text-muted">Por favor, verifique que el archivo tenga el formato correcto y vuelva a intentarlo.</small>
                </td></tr>`;
            });
        });
        
        // Cuando se cambia el archivo, resetear la vista previa
        fileInput.addEventListener('change', function() {
            previewSection.style.display = 'none';
            btnPreview.innerHTML = '<i class="bi bi-eye me-1"></i> <span class="text-white">Vista Previa</span>';
            confirmImport.value = '0';
        });
        
        // Configurar el botón de confirmación del modal para enviar el formulario
        document.getElementById('btnConfirmImport').addEventListener('click', function() {
            confirmImport.value = '1';
            importForm.submit();
        });
        
        // Lógica para cargar aulas según nivel
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
            
            // Resetear vista previa si cambió el nivel
            previewSection.style.display = 'none';
            btnPreview.innerHTML = '<i class="bi bi-eye me-1"></i> <span class="text-white">Vista Previa</span>';
            confirmImport.value = '0';
        });
        
        // Si cambia el aula, resetear la vista previa
        aulaSelect.addEventListener('change', function() {
            previewSection.style.display = 'none';
            btnPreview.innerHTML = '<i class="bi bi-eye me-1"></i> <span class="text-white">Vista Previa</span>';
            confirmImport.value = '0';
        });
        
        // Formatear la visualización de la fecha
        const fechaIngreso = document.getElementById('fecha_ingreso');
        fechaIngreso.addEventListener('change', function() {
            // Usar este enfoque para evitar problemas de zona horaria
            const [anio, mes, dia] = this.value.split('-');
            document.querySelector('.form-text').innerHTML = `Fecha seleccionada: ${dia}/${mes}/${anio}`;
            
            // Resetear vista previa si cambió la fecha
            previewSection.style.display = 'none';
            btnPreview.innerHTML = '<i class="bi bi-eye me-1"></i> <span class="text-white">Vista Previa</span>';
            confirmImport.value = '0';
        });
    });
</script>
@endpush

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="confirmModalLabel">Confirmar Importación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <span id="modalMessage">¿Está seguro que desea importar estos datos?</span>
                </div>
                <p>Esta acción importará todos los registros del archivo y no se puede deshacer.</p>
                <div id="previewSummary">
                    <p><strong>Archivo:</strong> <span id="fileName">No seleccionado</span></p>
                    <p><strong>Total de registros:</strong> <span id="recordCount">0</span></p>
                    <p><strong>Aula de destino:</strong> <span id="targetClassroom">No seleccionada</span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnConfirmImport" class="btn btn-primary text-white">Confirmar Importación</button>
            </div>
        </div>
    </div>
</div>

@endsection