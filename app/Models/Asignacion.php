<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignaciones';
    protected $primaryKey = 'id_asignacion';
    public $timestamps = true;

    protected $fillable = [
        'id_docente',
        'id_materia',
        'id_aula',
        'id_anio'
    ];

    // Relaciones
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }
    

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula');
    }

    public function anioAcademico()
    {
        return $this->belongsTo(AnioAcademico::class, 'id_anio');
    }

    public function nivel()
    {
        return $this->hasOneThrough(Nivel::class, Aula::class, 'id_aula', 'id_nivel', 'id_aula', 'id_nivel');
    }
    public function seccion()
    {
        return $this->hasOneThrough(Seccion::class, Aula::class, 'id_aula', 'id_seccion', 'id_aula', 'id_seccion');
    }
    public function grado()
    {
        return $this->hasOneThrough(Grado::class, Aula::class, 'id_aula', 'id_grado', 'id_aula', 'id_grado');
    }
    
}
