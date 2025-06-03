<?php

namespace App\Services;

use App\Models\Materia;
use App\Models\Nivel;
use App\Exports\MateriaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class MateriaExportService
{
    /**
     * Exportar listado de materias a Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarExcel(Request $request)
    {
        // Obtener los filtros
        $filtroNivel = $request->input('nivel');
        
        // Iniciar la consulta
        $materias = Materia::query();
        
        // Filtrar por nivel si se especifica
        if ($filtroNivel) {
            $materias->where('id_nivel', $filtroNivel);
        }
        
        // Ordenar los resultados
        $materias = $materias->join('niveles', 'materias.id_nivel', '=', 'niveles.id_nivel')
            ->orderByRaw("CASE niveles.nombre 
                WHEN 'Inicial' THEN 1 
                WHEN 'Primaria' THEN 2 
                WHEN 'Secundaria' THEN 3 
                ELSE 4 
            END")
            ->orderBy('materias.nombre')
            ->select('materias.*')
            ->get();
        
        // Obtener el nombre del nivel para el título
        $nombreNivel = 'General';
        if ($filtroNivel) {
            $nivel = Nivel::find($filtroNivel);
            if ($nivel) {
                $nombreNivel = $nivel->nombre;
            }
        }
        
        // Generar nombre del archivo
        $fechaActual = Carbon::now()->format('d-m-Y');
        $nombreArchivo = "materias_{$nombreNivel}_{$fechaActual}.xlsx";
        
        // Reemplazar espacios y caracteres especiales en el nombre del archivo
        $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
        $nombreArchivo = preg_replace('/[^A-Za-z0-9\-_.]/', '', $nombreArchivo);
        
        // Crear y devolver el archivo Excel
        return Excel::download(
            new MateriaExport($materias, $filtroNivel, $nombreNivel),
            $nombreArchivo
        );
    }

    /**
     * Exportar listado de materias a PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportarPDF(Request $request)
    {
        // Obtener los filtros
        $filtroNivel = $request->input('nivel');
        
        // Iniciar la consulta
        $materias = Materia::query();
        
        // Filtrar por nivel si se especifica
        if ($filtroNivel) {
            $materias->where('id_nivel', $filtroNivel);
        }
        
        // Ordenar los resultados
        $materias = $materias->join('niveles', 'materias.id_nivel', '=', 'niveles.id_nivel')
            ->orderByRaw("CASE niveles.nombre 
                WHEN 'Inicial' THEN 1 
                WHEN 'Primaria' THEN 2 
                WHEN 'Secundaria' THEN 3 
                ELSE 4 
            END")
            ->orderBy('materias.nombre')
            ->select('materias.*')
            ->get();
        
        // Obtener el nombre del nivel para el título
        $nombreNivel = 'General';
        if ($filtroNivel) {
            $nivel = Nivel::find($filtroNivel);
            if ($nivel) {
                $nombreNivel = $nivel->nombre;
            }
        }
        
        // Generar nombre del archivo
        $fechaActual = Carbon::now()->format('d-m-Y');
        $nombreArchivo = "materias_{$nombreNivel}_{$fechaActual}.pdf";
        
        // Reemplazar espacios y caracteres especiales en el nombre del archivo
        $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
        $nombreArchivo = preg_replace('/[^A-Za-z0-9\-_.]/', '', $nombreArchivo);
        
        // Generar el PDF
        $pdf = Pdf::loadView('pdf.materias', [
            'materias' => $materias,
            'filtroNivel' => $filtroNivel,
            'nombreNivel' => $nombreNivel,
            'fechaActual' => $fechaActual
        ]);
        
        // Configurar el PDF
        $pdf->setPaper('a4', 'portrait');
        
        // Devolver el PDF como descarga
        return $pdf->download($nombreArchivo);
    }
}
