@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Detalles del Trimestre</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $trimestre->nombre }}</p>
                    <p><strong>Año Académico:</strong>
                        @if($trimestre->anioAcademico)
                            {{ $trimestre->anioAcademico->anio }}
                        @else
                            <span class="text-muted">No definido</span>
                        @endif
                    </p>
                    <p><strong>Fecha de Inicio:</strong>
                        {{ $trimestre->fecha_inicio ? \Carbon\Carbon::parse($trimestre->fecha_inicio)->format('d/m/Y') : 'No registrada' }}
                    </p>
                    <p><strong>Fecha de Fin:</strong>
                        {{ $trimestre->fecha_fin ? \Carbon\Carbon::parse($trimestre->fecha_fin)->format('d/m/Y') : 'No registrada' }}
                    </p>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Creado:</dt>
                        <dd class="col-sm-8">{{ $trimestre->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Actualizado:</dt>
                        <dd class="col-sm-8">{{ $trimestre->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <hr>

            <div class="mt-4">
                <a href="{{ route('trimestres.edit', $trimestre) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('trimestres.index') }}" class="btn btn-secondary">Volver</a>
                
                <form action="{{ route('trimestres.destroy', $trimestre) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este trimestre?')">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
