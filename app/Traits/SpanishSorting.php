<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Estudiante;

trait SpanishSorting
{
    /**
     * Ordena una colección de estudiantes respetando el orden alfabético español con acentos.
     * Sigue el orden: A, Á, B, C, Ç, etc.
     * 
     * @param \Illuminate\Database\Eloquent\Collection $students
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function sortStudentsSpanish(Collection $students): Collection
    {
        // Establecer la configuración regional a español para garantizar la ordenación correcta
        setlocale(LC_COLLATE, 'es_ES.utf8', 'es_ES', 'es');
        
        return $students->sort(function ($a, $b) {
            // Normalizar textos para comparación (mantiene acentos pero elimina diferencia entre mayúsculas/minúsculas)
            $apellidoA = mb_strtolower($a->apellido);
            $apellidoB = mb_strtolower($b->apellido);
            $nombreA = mb_strtolower($a->nombre);
            $nombreB = mb_strtolower($b->nombre);
            
            // Primero comparamos apellidos usando strcoll que respeta la collation del sistema
            // strcoll respeta el orden alfabético español: A, Á, B, C, Ç, D, E, É, etc.
            $apellidoComparison = strcoll($apellidoA, $apellidoB);
            
            // Si los apellidos son iguales, comparamos nombres
            if ($apellidoComparison === 0) {
                return strcoll($nombreA, $nombreB);
            }
            
            return $apellidoComparison;
        });
    }
    
    /**
     * Consulta y devuelve estudiantes ordenados respetando acentos en el orden alfabético español
     * 
     * @param int $aulaId
     * @param string $estado
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStudentsWithSpanishSorting(int $aulaId, string $estado = 'Activo'): Collection
    {
        // Primero obtenemos todos los estudiantes
        $students = Estudiante::where('id_aula', $aulaId)
            ->where('estado', $estado)
            ->get();
            
        // Luego aplicamos la ordenación respetando acentos
        return $this->sortStudentsSpanish($students);
    }
}
