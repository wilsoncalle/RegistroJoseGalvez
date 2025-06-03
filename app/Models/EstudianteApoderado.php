<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudianteApoderado extends Model
{
    use HasFactory;

    protected $table = 'estudiante_apoderado';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_estudiante',
        'id_apoderado',
    ];

    protected $primaryKey = ['id_estudiante', 'id_apoderado'];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante', 'id_estudiante');
    }

    public function apoderado()
    {
        return $this->belongsTo(Apoderado::class, 'id_apoderado', 'id_apoderado');
    }
}
