<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AulaDocente extends Model
{
    use HasFactory;

    protected $table = 'aula_docente';
    public $incrementing = false;
    public $timestamps = true;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'id_aula',
        'id_docente',
        'id_materia',
        'id_anio',
    ];

    /**
     * El modelo no tiene una clave primaria autoincremental.
     *
     * @var bool
     */

    /**
     * Define la clave primaria compuesta.
     *
     * @var array
     */
    protected $primaryKey = ['id_aula', 'id_docente', 'id_materia', 'id_anio'];

    /**
     * Obtiene el aula asociada con la relación.
     */
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula');
    }

    /**
     * Obtiene el docente asociado con la relación.
     */
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    /**
     * Obtiene la materia asociada con la relación.
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    /**
     * Obtiene el año académico asociado con la relación.
     */
    public function anioAcademico()
    {
        return $this->belongsTo(AnioAcademico::class, 'id_anio');
    }
}