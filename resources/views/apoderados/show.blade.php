@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Detalles del Apoderado</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $apoderado->nombre }}</p>
                    <p><strong>Apellido:</strong> {{ $apoderado->apellido }}</p>
                    <p><strong>DNI:</strong> {{ $apoderado->dni ?: 'No registrado' }}</p>
                    <p><strong>Relación:</strong> {{ $apoderado->relacion ?: 'No registrado' }}</p>
                    <p><strong>Teléfono:</strong> {{ $apoderado->telefono ?: 'No registrado' }}</p>
                </div>
            </div>

            <hr>

            <h4>Estudiantes:</h4>
            @if($apoderado->estudiantes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>DNI</th>
                                <th>Teléfono</th>
                                <th>Aula</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($apoderado->estudiantes as $estudiante)
                                <tr>
                                    <td>{{ $estudiante->nombre }}</td>
                                    <td>{{ $estudiante->dni ?: 'No registrado' }}</td>
                                    <td>{{ $estudiante->telefono ?: 'No registrado' }}</td>
                                    <td>
                                        @if($estudiante->aula)
                                            {{ $estudiante->aula->nombre }}
                                        @else
                                            No asignada
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $estudiante->estado == 'Activo' ? 'success' : ($estudiante->estado == 'Retirado' ? 'warning' : 'secondary') }}">
                                            {{ $estudiante->estado }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('estudiantes.show', $estudiante) }}" 
                                        class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No hay estudiantes registrados para este apoderado.</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('apoderados.edit', $apoderado) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('apoderados.index') }}" class="btn btn-secondary">Volver</a>
                <form action="{{ route('apoderados.destroy', $apoderado) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este apoderado?')">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
