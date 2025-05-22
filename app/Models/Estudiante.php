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
        'apellido',
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

    /**
     * Determina el estado real del estudiante basado en el tiempo transcurrido
     * desde su ingreso. Si han pasado más de 8 años, debe estar egresado.
     *
     * @return string
     */
    public function getEstadoRealAttribute()
    {
        // Verificar si tiene fecha de ingreso
        if (!$this->fecha_ingreso) {
            return $this->estado;
        }
        
        // Si ya está marcado como Egresado o Retirado, mantenerlo así
        if ($this->estado === 'Egresado' || $this->estado === 'Retirado') {
            return $this->estado;
        }
        
        // Calcular años desde el ingreso
        $aniosTranscurridos = $this->fecha_ingreso->diffInYears(now());
        
        // Si han pasado 8 años o más, debería estar egresado
        if ($aniosTranscurridos >= 8) {
            return 'Egresado';
        }
        
        return $this->estado;
    }

    /**
     * Scope para filtrar por el estado real (considerando años desde ingreso)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $estado
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEstadoReal($query, $estado = null)
    {
        if (!$estado) {
            return $query;
        }
        
        // Si buscan Activos, excluir los que llevan 8+ años 
        if ($estado === 'Activo') {
            return $query->where('estado', 'Activo')
                ->where(function($q) {
                    $q->whereNull('fecha_ingreso')
                      ->orWhereRaw('TIMESTAMPDIFF(YEAR, fecha_ingreso, CURDATE()) < 8');
                });
        }
        
        // Si buscan Egresados, incluir los marcados como egresados más los que llevan 8+ años
        if ($estado === 'Egresado') {
            return $query->where(function($q) {
                $q->where('estado', 'Egresado')
                  ->orWhere(function($q2) {
                      $q2->where('estado', 'Activo')
                        ->whereNotNull('fecha_ingreso')
                        ->whereRaw('TIMESTAMPDIFF(YEAR, fecha_ingreso, CURDATE()) >= 8');
                  });
            });
        }
        
        // Para otros estados (Retirado, etc.) usar el valor guardado
        return $query->where('estado', $estado);
    }
}