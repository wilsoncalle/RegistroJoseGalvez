@extends('layouts.app')

@section('title', 'Editar Materia - Sistema de Gestión Escolar')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Editar Materia: {{ $materia->nombre }}</h1>
        <a href="{{ route('materias.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Materias
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

            <form action="{{ route('materias.update', $materia) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Datos de la Materia</h5>
                        <hr>
                    </div>
                </div>

                <div class="row mb-3">
                <div class="col-md-6">
                        <label for="id_nivel" class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_nivel" name="id_nivel" required>
                            <option value="">Seleccionar nivel...</option>
                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel->id_nivel }}" 
                                    {{ old('id_nivel', $materia->id_nivel) == $nivel->id_nivel ? 'selected' : '' }}>
                                    {{ $nivel->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre de la Materia <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                            value="{{ old('nombre', $materia->nombre) }}" 
                            required 
                            placeholder="Ej: Matemáticas Avanzadas">
                    </div>
                    
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Restablecer
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Actualizar Materia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection