@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Detalles del Año Académico</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <!-- Información básica del Año Académico -->
                <div class="col-md-6">
                    <p><strong>Año:</strong> {{ $anio->anio }}</p>
                    <p><strong>Fecha de Inicio:</strong> {{ \Carbon\Carbon::parse($anio->fecha_inicio)->format('d/m/Y') }}</p>
                    <p><strong>Fecha de Fin:</strong> {{ \Carbon\Carbon::parse($anio->fecha_fin)->format('d/m/Y') }}</p>
                    <p><strong>Descripción:</strong> {{ $anio->descripcion ?: 'Sin descripción' }}</p>
                </div>
                <!-- Estado y fechas de registro -->
                <div class="col-md-6">
                    <p>
                        <strong>Estado:</strong>
                        <span class="badge bg-{{ $anio->estado == 'Planificado' ? 'info' : ($anio->estado == 'En curso' ? 'success' : 'secondary') }}">
                            {{ $anio->estado }}
                        </span>
                    </p>
                    <dl class="row">
                        <dt class="col-sm-3">Creado:</dt>
                        <dd class="col-sm-9">{{ $anio->created_at->format('d/m/Y H:i') }}</dd>
                        <dt class="col-sm-3">Actualizado:</dt>
                        <dd class="col-sm-9">{{ $anio->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <hr>

            <h4>Trimestres:</h4>
            @if($anio->trimestres->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($anio->trimestres as $trimestre)
                                <tr>
                                    <td>{{ $trimestre->id_trimestre }}</td>
                                    <td>{{ $trimestre->nombre }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trimestre->fecha_inicio)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trimestre->fecha_fin)->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No se encontraron trimestres para este año académico.</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('anios.edit', $anio) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('anios.index') }}" class="btn btn-secondary">Volver</a>
                <form action="{{ route('anios.destroy', $anio) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este año académico?')">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
