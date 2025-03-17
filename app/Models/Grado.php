<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    use HasFactory;

    protected $table = 'grados';
    protected $primaryKey = 'id_grado';
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

    // Relación con secciones
    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'id_grado');
    }

    // Relación con aulas
    public function aulas()
    {
        return $this->hasMany(Aula::class, 'id_grado');
    }
}