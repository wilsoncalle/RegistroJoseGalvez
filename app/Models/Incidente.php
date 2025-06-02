<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
    use HasFactory;

    protected $table = 'incidentes';
    protected $primaryKey = 'id_incidente';
    public $timestamps = true;

    protected $fillable = [
        'id_estudiante',
        'id_aula',
        'fecha',
        'descripcion',
        'tipo_incidente',
        'accion_tomada',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    // Relación con el estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante', 'id_estudiante');
    }

    // Relación con el aula
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula', 'id_aula');
    }
}
