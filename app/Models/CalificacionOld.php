<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalificacionOld extends Model
{
    use HasFactory;
    
    // Tabla que usará este modelo
    protected $table = 'calificacionesOld';
    
    // Clave primaria
    protected $primaryKey = 'id_calificacion';
    
    // Campos que se pueden llenar masivamente (mass assignable)
    protected $fillable = [
        'id_estudiante',
        'id_asignacion',
        'id_trimestre',
        'comportamiento',
        'asignaturas_reprobadas',
        'conclusion',
        'grado',
        'nota',
        'fecha',
        'observacion',
    ];
    
    // Conversión de tipos
    protected $casts = [
        'comportamiento' => 'integer',
        'asignaturas_reprobadas' => 'integer',
        'nota' => 'decimal:2',
        'fecha' => 'date',
    ];
    
    // Relaciones
    
    /**
     * Obtiene el estudiante asociado a esta calificación.
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante', 'id_estudiante');
    }
    
    /**
     * Obtiene la asignación asociada a esta calificación.
     */
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignacion', 'id_asignacion');
    }
    
    /**
     * Obtiene el trimestre asociado a esta calificación.
     */
    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'id_trimestre', 'id_trimestre');
    }
    
    /**
     * Obtiene el grado asociado a esta calificación.
     * Nota: Esto depende de cómo esté implementada la relación con grados
     */
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'grado', 'id_grados');
    }
}