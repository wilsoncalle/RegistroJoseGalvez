<?php

namespace App\Services;

use App\Models\Apoderado;
use App\Exports\ApoderadoExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ApoderadoExportService
{
    /**
     * Exportar listado de apoderados a Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarExcel(Request $request)
    {
        // Obtener los filtros
        $filtroRelacion = $request->input('relacion');
        $busqueda = $request->input('busqueda');
        
        // Iniciar la consulta
        $apoderados = Apoderado::with('estudiantes');
        
        // Aplicar filtros de búsqueda
        if ($busqueda) {
            $apoderados->where(function($query) use ($busqueda) {
                $query->where('nombre', 'LIKE', "%{$busqueda}%")
                    ->orWhere('apellido', 'LIKE', "%{$busqueda}%")
                    ->orWhere('dni', 'LIKE', "%{$busqueda}%")
                    ->orWhere('telefono', 'LIKE', "%{$busqueda}%");
            });
        }
        
        // Filtrar por relación si se especifica
        if ($filtroRelacion) {
            $apoderados->where('relacion', $filtroRelacion);
        }
        
        // Ordenar los resultados
        $apoderados = $apoderados->orderBy('relacion')->orderBy('apellido')->get();
        
        // Obtener el nombre de la relación para el título
        $nombreRelacion = 'General';
        if ($filtroRelacion) {
            $nombreRelacion = $filtroRelacion;
        }
        
        // Generar nombre del archivo
        $fechaActual = Carbon::now()->format('d-m-Y');
        $nombreArchivo = "apoderados_{$nombreRelacion}_{$fechaActual}.xlsx";
        
        // Reemplazar espacios y caracteres especiales en el nombre del archivo
        $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
        $nombreArchivo = preg_replace('/[^A-Za-z0-9\-_.]/', '', $nombreArchivo);
        
        // Crear y devolver el archivo Excel
        return Excel::download(
            new ApoderadoExport($apoderados, $filtroRelacion, $nombreRelacion),
            $nombreArchivo
        );
    }

    /**
     * Exportar listado de apoderados a PDF
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarPDF(Request $request)
    {
        // Obtener los filtros
        $filtroRelacion = $request->input('relacion');
        $busqueda = $request->input('busqueda');
        
        // Iniciar la consulta
        $apoderados = Apoderado::with('estudiantes');
        
        // Aplicar filtros de búsqueda
        if ($busqueda) {
            $apoderados->where(function($query) use ($busqueda) {
                $query->where('nombre', 'LIKE', "%{$busqueda}%")
                    ->orWhere('apellido', 'LIKE', "%{$busqueda}%")
                    ->orWhere('dni', 'LIKE', "%{$busqueda}%")
                    ->orWhere('telefono', 'LIKE', "%{$busqueda}%");
            });
        }
        
        // Filtrar por relación si se especifica
        if ($filtroRelacion) {
            $apoderados->where('relacion', $filtroRelacion);
        }
        
        // Ordenar los resultados
        $apoderados = $apoderados->orderBy('relacion')->orderBy('apellido')->get();
        
        // Obtener el nombre de la relación para el título
        $nombreRelacion = 'General';
        if ($filtroRelacion) {
            $nombreRelacion = $filtroRelacion;
        }
        
        // Configurar la numeración
        $counter = 1;
        
        // Generar nombre del archivo
        $fechaActual = Carbon::now()->format('d-m-Y');
        $nombreArchivo = "apoderados_{$nombreRelacion}_{$fechaActual}.pdf";
        
        // Reemplazar espacios y caracteres especiales en el nombre del archivo
        $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
        $nombreArchivo = preg_replace('/[^A-Za-z0-9\-_.]/', '', $nombreArchivo);
        
        // Generar el PDF
        $pdf = Pdf::loadView('pdf.apoderados', [
            'apoderados' => $apoderados, 
            'counter' => $counter,
            'filtroRelacion' => $filtroRelacion,
            'nombreRelacion' => $nombreRelacion,
            'fechaActual' => $fechaActual
        ]);
        
        // Configurar el PDF
        $pdf->setPaper('a4', 'landscape');
        
        // Devolver el PDF como descarga
        return $pdf->download($nombreArchivo);
    }
}
