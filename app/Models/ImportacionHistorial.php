<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ImportacionHistorial extends Model
{
    protected $table = 'importaciones_historial';
    protected $primaryKey = 'id_importacion';
    
    protected $fillable = [
        'nombre_archivo',
        'anio_academico',
        'id_nivel',
        'id_aula',
        'nivel_nombre',
        'aula_nombre',
        'fecha_importacion',
        'total_importados',
        'usuario',
    ];
    
    protected $casts = [
        'fecha_importacion' => 'date',
        'total_importados' => 'integer',
    ];
    
    /**
     * Crea un nuevo registro de importación
     *
     * @param string $nombreArchivo Nombre del archivo importado
     * @param int|null $idNivel ID del nivel seleccionado
     * @param int|null $idAula ID del aula seleccionada
     * @param string|null $anioAcademico Año académico seleccionado
     * @param int $totalImportados Cantidad de estudiantes importados
     * @return self
     */
    public static function registrarImportacion(
        string $nombreArchivo, 
        ?int $idNivel = null, 
        ?int $idAula = null, 
        ?string $anioAcademico = null,
        int $totalImportados = 0
    ): self {
        $nivel = null;
        $aula = null;
        
        if ($idNivel) {
            $nivel = Nivel::find($idNivel);
        }
        
        if ($idAula) {
            $aula = Aula::find($idAula);
        }
        
        return self::create([
            'nombre_archivo' => $nombreArchivo,
            'anio_academico' => $anioAcademico,
            'id_nivel' => $idNivel,
            'id_aula' => $idAula,
            'nivel_nombre' => $nivel ? $nivel->nombre : null,
            'aula_nombre' => $aula ? $aula->nombre_completo : null,
            'fecha_importacion' => now(),
            'total_importados' => $totalImportados,
            'usuario' => Auth::user() ? Auth::user()->name : null,
        ]);
    }
    
    /**
     * Obtiene el nivel asociado a esta importación
     */
    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'id_nivel', 'id_nivel');
    }
    
    /**
     * Obtiene el aula asociada a esta importación
     */
    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class, 'id_aula', 'id_aula');
    }
    
    /**
     * Obtiene una descripción formateada de la importación
     * 
     * @return string
     */
    public function getDescripcionCompletaAttribute(): string
    {
        $descripcion = "Importación realizada el " . $this->fecha_importacion->format('d/m/Y');
        
        if ($this->nivel_nombre) {
            $descripcion .= " - Nivel: {$this->nivel_nombre}";
        }
        
        if ($this->aula_nombre) {
            $descripcion .= " - Aula: {$this->aula_nombre}";
        }
        
        if ($this->anio_academico) {
            $descripcion .= " - Año académico: {$this->anio_academico}";
        }
        
        $descripcion .= " - Total: {$this->total_importados} estudiantes";
        
        return $descripcion;
    }
}
