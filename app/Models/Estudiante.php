<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'estudiantes';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_estudiante';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'dni',
        'fecha_nacimiento',
        'telefono',
        'id_aula',
        'fecha_ingreso',
        'estado',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
    ];

    /**
     * Relación con el aula
     */
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula', 'id_aula');
    }

    /**
     * Relación con apoderados
     */
    public function apoderados()
    {
        return $this->belongsToMany(Apoderado::class, 'estudiante_apoderado', 'id_estudiante', 'id_apoderado')
                    ->withTimestamps();
    }

    /**
     * Relación con las asistencias (si las agregas más adelante)
     */
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_estudiante', 'id_estudiante');
    }

    /**
     * Relación con las calificaciones (si las agregas más adelante)
     */
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'id_estudiante', 'id_estudiante');
    }

    /**
     * Obtiene el nombre completo del aula del estudiante
     *
     * @return string
     */
    public function getNombreCompletoAulaAttribute()
    {
        if ($this->aula) {
            return $this->aula->nombre_completo;
        }
        return 'Sin aula asignada';
    }
}