<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apoderado extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'apoderados';

    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_apoderado';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'relacion',
        'telefono',
    ];

    /**
     * Relación con estudiantes
     */
    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'estudiante_apoderado', 'id_apoderado', 'id_estudiante')
                    ->withTimestamps();
    }
}