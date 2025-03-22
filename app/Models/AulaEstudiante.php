<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AulaEstudiante extends Model
{
    use HasFactory;

    protected $table = 'aula_estudiante';
    public $incrementing = false;
     public $timestamps = true;

    protected $fillable = [
        'id_aula',
        'id_estudiante',
        'id_anio',
    ];

    /**
     * Define la clave primaria compuesta.
     *
     * @var array
     */
    protected $primaryKey = ['id_aula', 'id_estudiante', 'id_anio'];

    /**
     * Obtiene el aula asociada con la relación.
     */
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula');
    }

    /**
     * Obtiene el estudiante asociado con la relación.
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    /**
     * Obtiene el año académico asociado con la relación.
     */
    public function anioAcademico()
    {
        return $this->belongsTo(AnioAcademico::class, 'id_anio');
    }
}