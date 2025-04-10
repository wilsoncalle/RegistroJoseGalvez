@extends('layouts.app')

@section('title', 'Nuevo Docente - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Nuevo Docente</h1>
        <a href="{{ route('docentes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Docentes
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

            <form action="{{ route('docentes.store') }}" method="POST">
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
                        <label for="nombre" class="form-label">Nombres <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="apellido" class="form-label">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="dni" class="form-label">DNI</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni') }}" maxlength="8" pattern="[0-9]{8}" title="El DNI debe tener 8 dígitos numéricos">
                            <button class="btn btn-success" type="button" id="btn_buscar_dni">
                                <i class="bi bi-check"></i>
                            </button>
                        </div>
                        <div class="form-text" id="dni_mensaje"></div>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-5">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}">
                    </div>
                </div>

                <!-- Datos Profesionales -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos Profesionales</h5>
                        <hr>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="fecha_contratacion" class="form-label">Fecha de Contratación</label>
                        <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" value="{{ old('fecha_contratacion') }}">
                    </div>
                    <div class="col-md-3">
                        <!-- Select para Nivel -->
                        <label for="id_nivel" class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_nivel" name="id_nivel" required>
                            <option value="">Seleccione un nivel</option>
                            @foreach ($niveles as $nivel)
                                <option value="{{ $nivel->id_nivel }}" {{ old('id_nivel') == $nivel->id_nivel ? 'selected' : '' }}>
                                    {{ $nivel->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Docente
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
        fetch('{{ route("docentes.consultar-dni") }}', {
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
});
</script>
@endpush

@endsection