<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asistencia';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_asistencia';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_estudiante',
        'id_asignacion',
        'asistio',
        'tardanza',
        'justificado',
        'fecha'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'asistio' => 'boolean',
        'tardanza' => 'boolean',
        'justificado' => 'boolean',
        'fecha' => 'date'
    ];

    /**
     * Get the estudiante that owns the attendance record.
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante', 'id_estudiante');
    }

    /**
     * Get the asignacion that owns the attendance record.
     */
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignacion', 'id_asignacion');
    }
    public function materia()
    {
        return $this->hasOneThrough(
            Materia::class, 
            Asignacion::class, 
            'id_asignacion', // Foreign key on asignaciones table
            'id_materia',    // Foreign key on materias table
            'id_asignacion', // Local key on asistencia table
            'id_materia'     // Local key on asignaciones table
        );
    }

    public function aula()
    {
        return $this->hasOneThrough(
            Aula::class, 
            Asignacion::class, 
            'id_asignacion', 
            'id_aula',
            'id_asignacion', 
            'id_aula'
        );
    }

    /**
     * Scope a query to only include attendance records for a specific student.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $estudianteId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeEstudiante($query, $estudianteId)
    {
        return $query->where('id_estudiante', $estudianteId);
    }

    /**
     * Scope a query to only include attendance records for a specific date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $fecha
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }

    /**
     * Scope a query to only include attendance records for a specific assignment.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $asignacionId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeAsignacion($query, $asignacionId)
    {
        return $query->where('id_asignacion', $asignacionId);
    }

    /**
     * Scope a query to only include attendance records where the student was present.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePresentes($query)
    {
        return $query->where('asistio', true);
    }

    /**
     * Scope a query to only include attendance records where the student was absent.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAusentes($query)
    {
        return $query->where('asistio', false);
    }

    /**
     * Scope a query to only include attendance records where the student was tardy.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTardanzas($query)
    {
        return $query->where('tardanza', true);
    }

    /**
     * Scope a query to only include attendance records with justified absences.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJustificados($query)
    {
        return $query->where('asistio', false)->where('justificado', true);
    }

    /**
     * Scope a query to only include attendance records with unjustified absences.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInjustificados($query)
    {
        return $query->where('asistio', false)->where('justificado', false);
    }

    /**
     * Scope a query to include attendance records within a date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $fechaInicio
     * @param  string  $fechaFin
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereDate('fecha', '>=', $fechaInicio)
                    ->whereDate('fecha', '<=', $fechaFin);
    }
}