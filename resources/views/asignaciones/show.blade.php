@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Detalles de la Asignación</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Docente:</strong> {{ $asignacion->docente->nombre }} {{ $asignacion->docente->apellido }}</p>
                    <p><strong>DNI Docente:</strong> {{ $asignacion->docente->dni ?: 'No registrado' }}</p>
                    <p><strong>Materia:</strong> {{ $asignacion->materia->nombre }}</p>
                    <p><strong>Año Académico:</strong> {{ $asignacion->anioAcademico->anio }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Nivel:</strong> {{ $asignacion->aula->nivel->nombre }}</p>
                    <p><strong>Aula:</strong> {{ $asignacion->aula->nombre_completo }}</p>
                    <dl class="row">
                        <dt class="col-sm-3">Creado:</dt>
                        <dd class="col-sm-9">{{ $asignacion->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-3">Actualizado:</dt>
                        <dd class="col-sm-9">{{ $asignacion->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <hr>

            <div class="mt-4">
                <a href="{{ route('asignaciones.show', ['asignacione' => $asignacion->id_asignacion]) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary">Volver</a>
                
                <form action="{{ route('asignaciones.destroy', $asignacion) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar esta asignación?')">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection