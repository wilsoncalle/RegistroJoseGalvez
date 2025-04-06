@extends('layouts.app')

@section('title', 'Detalles del Docente - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Detalles del Docente</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $docente->nombre }} {{ $docente->apellido }}</p>
                    <p><strong>DNI:</strong> {{ $docente->dni ?: 'No registrado' }}</p>
                    <p><strong>Fecha de Nacimiento:</strong> 
                        {{ $docente->fecha_nacimiento ? \Carbon\Carbon::parse($docente->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}
                    </p>
                    <p><strong>Teléfono:</strong> {{ $docente->telefono ?: 'No registrado' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Email:</strong> {{ $docente->email ?: 'No registrado' }}</p>
                    <p><strong>Fecha de Contratación:</strong> 
                        {{ $docente->fecha_contratacion ? \Carbon\Carbon::parse($docente->fecha_contratacion)->format('d/m/Y') : 'No registrada' }}
                    </p>
                    <p><strong>Nivel:</strong>
                        @if($docente->nivel)
                            {{ $docente->nivel->nombre }}
                        @else
                            No asignado
                        @endif
                    </p>
                    <p><strong>Materias Asignadas:</strong>
                        @if($docente->asignaciones->count() > 0)
                            <ul>
                                @foreach($docente->asignaciones as $asignacion)
                                    <li>{{ $asignacion->materia->nombre ?? 'Materia no disponible' }}</li>
                                @endforeach
                            </ul>
                        @else
                            No tiene materias asignadas
                        @endif
                    </p>
                    <p><strong>Dirección:</strong> {{ $docente->direccion ?: 'No registrada' }}</p>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('docentes.edit', $docente) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('docentes.index') }}" class="btn btn-secondary">Volver</a>
                
                <form action="{{ route('docentes.destroy', $docente) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este docente?')">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection