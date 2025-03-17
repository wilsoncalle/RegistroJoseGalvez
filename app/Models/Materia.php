<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';
    protected $primaryKey = 'id_materia';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'id_nivel',
    ];

    // Relación con el nivel
    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'id_nivel');
    }

    // Relación con docentes
    public function docentes()
    {
        return $this->hasMany(Docente::class, 'id_materia');
    }

    // Relación con asignaciones
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'id_materia');
    }

    // Relación con calificaciones
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'id_materia');
    }

    // Relación con aulas a través de asignaciones
    public function aulas()
    {
        return $this->hasManyThrough(Aula::class, Asignacion::class, 'id_materia', 'id_aula', 'id_materia', 'id_aula');
    }
    
}