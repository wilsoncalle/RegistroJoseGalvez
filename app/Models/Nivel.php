<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveles';
    protected $primaryKey = 'id_nivel';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];

    // Relación con grados
    public function grados()
    {
        return $this->hasMany(Grado::class, 'id_nivel');
    }

    // Relación con materias
    public function materias()
    {
        return $this->hasMany(Materia::class, 'id_nivel');
    }

    // Relación con aulas
    public function aulas()
    {
        return $this->hasMany(Aula::class, 'id_nivel');
    }
}