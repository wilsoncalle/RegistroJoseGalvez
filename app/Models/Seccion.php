<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;

    protected $table = 'secciones';
    protected $primaryKey = 'id_seccion';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'id_grado',
    ];

    // Relación con el grado
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }

    // Relación con aulas
    public function aulas()
    {
        return $this->hasMany(Aula::class, 'id_seccion');
    }

}
