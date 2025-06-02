@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Configuración del Sistema</h1>
    
    <!-- Mensajes de alerta -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0"><i class="bi bi-database me-2"></i>Respaldo y Restauración de Datos</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Realice copias de seguridad de su base de datos, restaure desde un respaldo previo o importe datos.</p>
                    
                    <div class="row mt-4 row-eq-height">
                        <!-- Sección de Respaldo Manual -->
                        <div class="col-md-4 d-flex">
                            <div class="backup-section p-3 border rounded d-flex flex-column w-100" style="min-height: 280px;">
                                <h5 class="mb-3"><i class="bi bi-download me-2"></i>Crear Respaldo</h5>
                                <p class="flex-grow-1">Genere una copia de seguridad completa de la base de datos actual.</p>
                                <form action="{{ route('backup.create') }}" method="POST" class="mt-auto">
                                    @csrf
                                    <!--<div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="include_media" id="include_media">
                                        <label class="form-check-label" for="include_media">
                                            Incluir archivos multimedia
                                        </label>
                                    </div>-->
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-download me-1"></i> Crear copia de seguridad
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Sección de Restauración -->
                        <div class="col-md-4 d-flex">
                            <div class="backup-section p-3 border rounded d-flex flex-column w-100" style="min-height: 280px;">
                                <h5 class="mb-3"><i class="bi bi-upload me-2"></i>Restaurar desde Respaldo</h5>
                                <p class="flex-grow-1">Restaure el sistema a un punto previo usando un respaldo existente.</p>
                                <form action="{{ route('backup.restore') }}" method="POST" class="mt-auto">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <select name="backup_file" class="form-select" required>
                                            <option value="">Seleccione un respaldo...</option>
                                            @foreach($backups as $backup)
                                                <option value="{{ $backup['name'] }}">
                                                    {{ $backup['name'] }} ({{ $backup['date'] }}, {{ $backup['size'] }})
                                                    @if($backup['is_auto']) [Auto] @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-warning w-100" onclick="return confirm('¿Está seguro de restaurar la base de datos? Esta acción reemplazará todos los datos actuales.')">
                                        <i class="bi bi-upload me-1"></i> Restaurar sistema
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Sección de Importación -->
                        <div class="col-md-4 d-flex">
                            <div class="backup-section p-3 border rounded d-flex flex-column w-100" style="min-height: 280px;">
                                <h5 class="mb-3"><i class="bi bi-file-earmark-arrow-up me-2"></i>Importar Base de Datos</h5>
                                <p class="flex-grow-1">Importe una base de datos desde un archivo SQLite, SQL o ZIP.</p>
                                <form action="{{ route('backup.import') }}" method="POST" enctype="multipart/form-data" class="mt-auto">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="file" class="form-control" name="database_file" accept=".sqlite,.db,.sql,.zip" required>
                                        <div class="form-text">Formatos soportados: SQLite, SQL, ZIP</div>
                                    </div>
                                    <button type="submit" class="btn btn-info w-100" onclick="return confirm('¿Está seguro de importar una nueva base de datos? Esta acción reemplazará todos los datos actuales.')">
                                        <i class="bi bi-file-earmark-arrow-up me-1"></i> Importar base de datos
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Programación de respaldos automáticos -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="m-0"><i class="bi bi-calendar-check me-2"></i>Respaldos Automáticos</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Configure el sistema para realizar respaldos automáticos periódicos.</p>
                    
                    <form action="{{ route('backup.schedule') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="auto_backup" id="auto_backup" {{ $schedule->auto_backup ? 'checked' : '' }}>
                                <label class="form-check-label" for="auto_backup">Activar respaldos automáticos</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="frequency" class="form-label">Frecuencia</label>
                            <select class="form-select" id="frequency" name="frequency">
                                <option value="daily" {{ $schedule->frequency == 'daily' ? 'selected' : '' }}>Diario</option>
                                <option value="weekly" {{ $schedule->frequency == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                <option value="monthly" {{ $schedule->frequency == 'monthly' ? 'selected' : '' }}>Mensual</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="day_of_week_container" style="{{ $schedule->frequency != 'weekly' ? 'display:none' : '' }}">
                            <label for="day_of_week" class="form-label">Día de la semana</label>
                            <select class="form-select" id="day_of_week" name="day_of_week">
                                <option value="1" {{ $schedule->day_of_week == 1 ? 'selected' : '' }}>Lunes</option>
                                <option value="2" {{ $schedule->day_of_week == 2 ? 'selected' : '' }}>Martes</option>
                                <option value="3" {{ $schedule->day_of_week == 3 ? 'selected' : '' }}>Miércoles</option>
                                <option value="4" {{ $schedule->day_of_week == 4 ? 'selected' : '' }}>Jueves</option>
                                <option value="5" {{ $schedule->day_of_week == 5 ? 'selected' : '' }}>Viernes</option>
                                <option value="6" {{ $schedule->day_of_week == 6 ? 'selected' : '' }}>Sábado</option>
                                <option value="0" {{ $schedule->day_of_week == 0 ? 'selected' : '' }}>Domingo</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="day_of_month_container" style="{{ $schedule->frequency != 'monthly' ? 'display:none' : '' }}">
                            <label for="day_of_month" class="form-label">Día del mes</label>
                            <select class="form-select" id="day_of_month" name="day_of_month">
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ $schedule->day_of_month == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="time" class="form-label">Hora del día (24h)</label>
                            <input type="time" class="form-control" id="time" name="time" value="{{ $schedule->time }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="retention_count" class="form-label">Número de respaldos a conservar</label>
                            <input type="number" class="form-control" id="retention_count" name="retention_count" 
                                   min="1" max="100" value="{{ $schedule->retention_count }}">
                            <div class="form-text">Los respaldos más antiguos se eliminarán automáticamente cuando se supere este límite.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save me-1"></i> Guardar configuración
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Historial de respaldos -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="m-0"><i class="bi bi-clock-history me-2"></i>Historial de Respaldos</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Gestione los archivos de respaldo existentes en el sistema.</p>
                    
                    @if(count($backups) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Tamaño</th>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>{{ $backup['name'] }}</td>
                                            <td>{{ $backup['size'] }}</td>
                                            <td>{{ $backup['date'] }}</td>
                                            <td>
                                                @if($backup['is_auto'])
                                                    <span class="badge bg-secondary">Automático</span>
                                                @else
                                                    <span class="badge bg-primary">Manual</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('backup.download', ['filename' => $backup['name']]) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Descargar">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <form action="{{ route('backup.delete', ['filename' => $backup['name']]) }}" method="POST" 
                                                          class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este respaldo?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No hay respaldos disponibles. Cree uno usando la opción "Crear Respaldo".
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const frequencySelect = document.getElementById('frequency');
        const dayOfWeekContainer = document.getElementById('day_of_week_container');
        const dayOfMonthContainer = document.getElementById('day_of_month_container');
        
        // Manejar cambios en la frecuencia seleccionada
        frequencySelect.addEventListener('change', function() {
            if (this.value === 'weekly') {
                dayOfWeekContainer.style.display = 'block';
                dayOfMonthContainer.style.display = 'none';
            } else if (this.value === 'monthly') {
                dayOfWeekContainer.style.display = 'none';
                dayOfMonthContainer.style.display = 'block';
            } else {
                dayOfWeekContainer.style.display = 'none';
                dayOfMonthContainer.style.display = 'none';
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .backup-section {
        height: 100%;
        transition: all 0.3s ease;
    }
    .backup-section:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
</style>
@endpush
