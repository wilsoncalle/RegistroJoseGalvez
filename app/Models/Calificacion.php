<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificacion';

    protected $fillable = [
        'id_estudiante',
        'id_asignacion',
        'id_trimestre',
        'nota',
        'observacion',
        'fecha'
    ];

    protected $casts = [
        'nota' => 'decimal:2',
        'fecha' => 'date'
    ];

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
}
