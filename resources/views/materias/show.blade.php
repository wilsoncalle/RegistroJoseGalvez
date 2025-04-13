@extends('layouts.app')

@section('title', 'Detalles de la Materia - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Detalles de la Materia</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $materia->nombre }}</p>
                    <p><strong>Nivel:</strong>
                        @if($materia->nivel)
                            {{ $materia->nivel->nombre }}
                        @else
                            No asignado
                        @endif
                    </p>
                    <dt class="col-sm-3">Creado:</dt>
                    <dd class="col-sm-9">
                        {{ $materia->created_at ? $materia->created_at->format('d/m/Y H:i') : 'No disponible' }}
                    </dd>

                    <dt class="col-sm-3">Actualizado:</dt>
                    <dd class="col-sm-9">
                        {{ $materia->updated_at ? $materia->updated_at->format('d/m/Y H:i') : 'No disponible' }}
                    </dd>

                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('materias.edit', $materia) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
                <a href="{{ route('materias.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
                
                <form action="{{ route('materias.destroy', $materia) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" 
                        onclick="return confirm('¿Está seguro que desea eliminar esta materia?')">
                        <i class="bi bi-trash me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection