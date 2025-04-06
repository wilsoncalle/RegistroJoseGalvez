<?php

namespace App\Services;

use App\Models\Docente;
use App\Models\Nivel;
use App\Exports\DocenteExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class DocenteExportService
{
    /**
     * Exportar listado de docentes a Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarExcel(Request $request)
    {
        // Obtener los filtros
        $filtroNivel = $request->input('nivel');
        
        // Iniciar la consulta
        $docentes = Docente::query();
        
        // Filtrar por nivel si se especifica
        if ($filtroNivel) {
            $docentes->where('id_nivel', $filtroNivel);
        }
        
        // Ordenar los resultados
        $docentes = $docentes->orderBy('id_nivel')->orderBy('apellido')->get();
        
        // Obtener el nombre del nivel para el título
        $nombreNivel = 'General';
        if ($filtroNivel) {
            $nivel = Nivel::find($filtroNivel);
            if ($nivel) {
                $nombreNivel = $nivel->nombre;
            }
        }
        
        // Generar nombre del archivo
        $fechaActual = now()->format('d-m-Y');
        $nombreArchivo = "docentes_{$nombreNivel}_{$fechaActual}.xlsx";
        
        // Reemplazar espacios y caracteres especiales en el nombre del archivo
        $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
        $nombreArchivo = preg_replace('/[^A-Za-z0-9\-_.]/', '', $nombreArchivo);
        
        // Crear y devolver el archivo Excel
        return Excel::download(
            new DocenteExport($docentes, $filtroNivel, $nombreNivel),
            $nombreArchivo
        );
    }
}