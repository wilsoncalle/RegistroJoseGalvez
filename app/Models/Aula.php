<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;


    // Nombre de la tabla y clave primaria personalizada
    protected $table = 'aulas';
    protected $primaryKey = 'id_aula';

    public $timestamps = true;

    // Atributos asignables en masa
    protected $fillable = [
        'id_nivel',
        'id_grado',
        'id_seccion',
    ];

    /**
     * Relación con el modelo Nivel.
     */
    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'id_nivel', 'id_nivel');
    }

    /**
     * Relación con el modelo Grado.
     */
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    /**
     * Relación con el modelo Seccion.
     */
    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'id_seccion', 'id_seccion');
    }

    /**
     * Relación muchos a muchos con Estudiante a través de la tabla pivote aula_estudiante.
     */
    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'aula_estudiante', 'id_aula', 'id_estudiante')
                    ->withPivot('id_anio')
                    ->withTimestamps();
    }

    /**
     * Relación muchos a muchos con Docente a través de la tabla pivote aula_docente.
     */
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'aula_docente', 'id_aula', 'id_docente')
                    ->withPivot(['id_materia', 'id_anio'])
                    ->withTimestamps();
    }
    public function getNombreCompletoAttribute()
    {
        $gradoNombre = $this->grado ? $this->grado->nombre : 'N/A';
        $seccionNombre = $this->seccion ? $this->seccion->nombre : 'N/A';
        return $gradoNombre . ' - ' . $seccionNombre;
    }


}
