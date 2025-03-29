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
    ];

    // Relación con el estudiante   
}
