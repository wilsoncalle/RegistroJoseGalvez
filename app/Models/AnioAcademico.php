<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnioAcademico extends Model
{
    use HasFactory;

    // Especificamos el nombre de la tabla
    protected $table = 'anios_academicos';

    // Indicamos la llave primaria ya que en la migración se definió como 'id_anio'
    protected $primaryKey = 'id_anio';

    // Definimos los campos que se pueden asignar masivamente
    protected $fillable = [
        'anio',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'descripcion',
    ];

    /**
     * Relación: Un año académico tiene muchos trimestres.
     */
    public function trimestres()
    {
        return $this->hasMany(Trimestre::class, 'id_anio', 'id_anio');
    }

    public function asignaciones()
    {
         return $this->hasMany(Asignacion::class, 'id_anio', 'id_anio');
    }
}
