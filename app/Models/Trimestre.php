<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trimestre extends Model
{
    use HasFactory;

    // Especificamos la tabla y la clave primaria personalizada
    protected $table = 'trimestres';
    protected $primaryKey = 'id_trimestre';

    // Campos asignables masivamente
    protected $fillable = [
        'nombre',
        'id_anio',
        'fecha_inicio',
        'fecha_fin',
    ];

    // Definimos los casts para las fechas
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date'
    ];

    /**
     * Relación con el modelo AnioAcademico.
     * Se asume que el modelo correspondiente es 'AnioAcademico'
     * y que la llave primaria de la tabla anios_academicos es 'id_anio'.
     */
    public function anioAcademico()
    {
        return $this->belongsTo(AnioAcademico::class, 'id_anio', 'id_anio');
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'id_anio', 'id_anio');
    }

    /**
     * Relación con las calificaciones.
     */
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'id_trimestre', 'id_trimestre');
    }

    /**
     * Relación con las calificaciones antiguas.
     */
    public function calificacionesOld()
    {
        return $this->hasMany(CalificacionOld::class, 'id_trimestre', 'id_trimestre');
    }
}
