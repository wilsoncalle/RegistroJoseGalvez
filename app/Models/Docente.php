<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model {
    use HasFactory;
    
    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'docentes';
    
    /**
     * La clave primaria asociada a la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_docente';
    
    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'email',
        'fecha_contratacion',
        'id_nivel', // Reemplazamos id_materia por id_nivel
    ];
    
    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_contratacion' => 'date',
    ];
    
    /**
     * Relación con el nivel asignado al docente.
     */
    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'id_nivel', 'id_nivel');
    }
    
    /**
     * Relación con las asignaciones del docente.
     */
    public function asignaciones() {
        return $this->hasMany(Asignacion::class, 'id_docente', 'id_docente');
    }
    
    /**
     * Relación con materias a través de asignaciones.
     */
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'asignaciones', 'id_docente', 'id_materia');
    }
}