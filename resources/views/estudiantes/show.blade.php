@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Detalles del Estudiante</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $estudiante->nombre }}</p>
                    <p><strong>Apellido:</strong> {{ $estudiante->apellido}}</p>
                    <p><strong>DNI:</strong> {{ $estudiante->dni ?: 'No registrado' }}</p>
                    <p><strong>Fecha de Nacimiento:</strong> 
                        {{ $estudiante->fecha_nacimiento ? \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}
                    </p>
                    <p><strong>Teléfono:</strong> {{ $estudiante->telefono ?: 'No registrado' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Aula:</strong> 
                        {{ $nombreCompletoAula ?: 'No asignada' }}
                    </p>
                    <p><strong>Fecha de Ingreso:</strong> 
                        {{ $estudiante->fecha_ingreso ? \Carbon\Carbon::parse($estudiante->fecha_ingreso)->format('d/m/Y') : 'No registrada' }}
                    </p>
                    <p><strong>Estado:</strong> 
                        <span class="badge bg-{{ $estudiante->estado == 'Activo' ? 'success' : ($estudiante->estado == 'Retirado' ? 'warning' : 'secondary') }}">
                            {{ $estudiante->estado }}
                        </span>
                    </p>
                    <dl class="row">
                        <dt class="col-sm-3">Creado:</dt>
                        <dd class="col-sm-9">{{ $estudiante->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-3">Actualizado:</dt>
                        <dd class="col-sm-9">{{ $estudiante->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <hr>

            <h4>Apoderados:</h4>
            @if($estudiante->apoderados->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>DNI</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estudiante->apoderados as $apoderado)
                                <tr>
                                    <td>{{ $apoderado->nombre }}</td>
                                    <td>{{ $apoderado->apellido }}</td>
                                    <td>{{ $apoderado->dni ?: 'No registrado' }}</td>
                                    <td>{{ $apoderado->telefono ?: 'No registrado' }}</td>
                                    <td>{{ $apoderado->email ?: 'No registrado' }}</td>
                                    <td>
                                        <a href="{{ route('apoderados.show', $apoderado) }}" 
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
                <p>No hay apoderados registrados para este estudiante.</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('estudiantes.edit', $estudiante) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">Volver</a>
                
                <form action="{{ route('estudiantes.destroy', $estudiante) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este estudiante?')">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
