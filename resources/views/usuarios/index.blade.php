@extends('layouts.app') 

@section('title', 'Gestión de Usuarios - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Usuarios</h1>
        <div>
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Usuario
            </a>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('usuarios.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label for="busqueda" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="busqueda" name="busqueda" 
                           placeholder="Nombre o correo electrónico" value="{{ $busqueda ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ ($filtroEstado === '1') ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ ($filtroEstado === '0') ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Alertas de sesión -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Listado de usuarios -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Fecha de Registro</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = ($usuarios->currentPage() - 1) * $usuarios->perPage() + 1; @endphp
                        @forelse($usuarios as $usuario)
                            <tr class="{{ $usuarioActual->id_usuario === $usuario->id_usuario ? '' : 'text-muted opacity-75' }}">
                                <td>{{ $counter++ }}</td>
                                <td>{{ $usuario->nombre }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->fecha_registro->format('d/m/Y') }}</td>
                                <td>
                                    @if($usuario->activo)
                                        <span class="badge {{ $usuarioActual->id_usuario === $usuario->id_usuario ? 'bg-success' : 'bg-success opacity-75' }}">Activo</span>
                                    @else
                                        <span class="badge {{ $usuarioActual->id_usuario === $usuario->id_usuario ? 'bg-danger' : 'bg-danger opacity-75' }}">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($usuarioActual->id_usuario === $usuario->id_usuario)
                                            <button type="button" class="btn btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $usuario->id_usuario }}" 
                                                    title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">No disponible</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Modal de confirmación de eliminación -->
                                    <div class="modal fade" id="deleteModal{{ $usuario->id_usuario }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $usuario->id_usuario }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $usuario->id_usuario }}">Confirmar eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Está seguro que desea eliminar su cuenta de usuario <strong>{{ $usuario->nombre }}</strong>?</p>
                                                    <div class="alert alert-danger">
                                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                        <strong>Advertencia:</strong> Esta acción es irreversible. Una vez eliminada la cuenta, todos los datos asociados se perderán permanentemente.
                                                    </div>
                                                    
                                                    <div class="mt-3">
                                                        <label for="password_confirmation" class="form-label">Confirme su contraseña para continuar:</label>
                                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" id="modal_password_confirmation" name="password_confirmation">
                                                        <button type="submit" class="btn btn-danger" id="confirm_delete_btn">Eliminar mi cuenta</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                       @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">No se encontraron usuarios con los criterios especificados.</p>
                                    <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                        <i class="bi bi-arrow-repeat me-1"></i> Mostrar todos los usuarios
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
                    {{ $usuarios->firstItem() }} - 
                    {{ $usuarios->lastItem() }} 
                    {{ __('de') }} 
                    {{ $usuarios->total() }} {{ __('resultados') }}
                </div>
                <div>
                    {{ $usuarios->appends(request()->query())->links('pagination::custom-bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Transferir la contraseña del campo visible al campo oculto en el formulario de eliminación
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener todos los modales de eliminación
        const deleteModals = document.querySelectorAll('[id^=\'deleteModal\']');
        
        deleteModals.forEach(modal => {
            const passwordInput = modal.querySelector('#password_confirmation');
            const hiddenPasswordInput = modal.querySelector('#modal_password_confirmation');
            const submitButton = modal.querySelector('#confirm_delete_btn');
            
            if (passwordInput && hiddenPasswordInput && submitButton) {
                submitButton.addEventListener('click', function(e) {
                    // Prevenir el envío del formulario si no hay contraseña
                    if (!passwordInput.value.trim()) {
                        e.preventDefault();
                        alert('Por favor, ingrese su contraseña para confirmar la eliminación de su cuenta.');
                        return false;
                    }
                    
                    // Transferir el valor al campo oculto
                    hiddenPasswordInput.value = passwordInput.value;
                });
            }
        });
    });
</script>
@endsection
