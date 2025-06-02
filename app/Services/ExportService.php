<?php

namespace App\Services;

use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel as ExcelType;
use Carbon\Carbon;
use App\Models\Aula;
use App\Models\Estudiante;
use App\Models\CalificacionOld;
use App\Models\Materia;
use App\Models\Asignacion;
use App\Models\Trimestre;
use App\Models\Docente;

class ExportService
{
    /**
     * Exportar asistencia a Excel
     */
    public function exportAttendanceToExcel(int $aulaId, int $materiaId, int $docenteId, int $mes, int $año)
    {
        $export = new AttendanceExport($aulaId, $materiaId, $docenteId, $mes, $año);
        
        // Obtener información para el nombre del archivo
        $aula = Aula::with(['nivel', 'grado', 'seccion'])->find($aulaId);
        $materia = Materia::find($materiaId);
        $docente = Docente::find($docenteId);
        $mesNombre = Carbon::create($año, $mes, 1)->translatedFormat('F');
        
        // Construir nombre del archivo
        $fileName = sprintf(
            "Asistencia_%s_%s_%s_%s_%s.xlsx",
            str_replace(' ', '_', $aula->nivel->nombre),
            str_replace(' ', '_', $aula->grado->nombre),
            str_replace(' ', '_', $aula->seccion->nombre),
            str_replace(' ', '_', $materia->nombre),
            str_replace(' ', '_', $mesNombre)
        );
        
        // Limpiar caracteres no válidos del nombre del archivo
        $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $fileName);
        
        return Excel::download($export, $fileName, ExcelType::XLSX);
    }
    
    /**
     * Exportar asistencia a PDF
     * 
     * Para PDF, primero generamos un archivo Excel temporal y luego lo convertimos a PDF
     * usando dompdf, esto nos permite mantener el formato complejo de la tabla.
     */
    public function exportAttendanceToPdf(int $aulaId, int $materiaId, int $docenteId, int $mes, int $año)
    {
        $export = new AttendanceExport($aulaId, $materiaId, $docenteId, $mes, $año);
        
        // Obtener información para el nombre del archivo
        $aula = Aula::with(['nivel', 'grado', 'seccion'])->find($aulaId);
        $materia = Materia::find($materiaId);
        $docente = Docente::find($docenteId);
        $mesNombre = Carbon::create($año, $mes, 1)->translatedFormat('F');
        
        // Construir nombre del archivo
        $fileName = sprintf(
            "Asistencia_%s_%s_%s_%s_%s.pdf",
            str_replace(' ', '_', $aula->nivel->nombre),
            str_replace(' ', '_', $aula->grado->nombre),
            str_replace(' ', '_', $aula->seccion->nombre),
            str_replace(' ', '_', $materia->nombre),
            str_replace(' ', '_', $mesNombre)
        );
        
        // Limpiar caracteres no válidos del nombre del archivo
        $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $fileName);
        
        $tempFile = "temp/asistencia_{$aulaId}_{$materiaId}_{$docenteId}_{$mes}_{$año}.xlsx";
        
        // Primero generamos el Excel en un archivo temporal
        // El AttendanceExport ya implementa la ordenación alfabética respetando acentos
        Excel::store($export, $tempFile, 'local');
        
        // Luego generamos un HTML basado en los datos para convertirlo a PDF
        // La colección ya viene ordenada correctamente desde el AttendanceExport
        $data = $export->collection();
        $weeks = $export->getWeeks();
        $schoolDays = $export->getSchoolDays();
        $fechaActual = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('pdf.asistencias', compact(
            'aula', 'materia', 'docente', 'mes', 'año', 'mesNombre',
            'data', 'weeks', 'schoolDays'
        ));
        
        // Configuración para landscape y tamaño de papel
        $pdf->setPaper('a4', 'landscape');
        
        // Eliminar el archivo temporal después de usarlo
        Storage::disk('local')->delete($tempFile);
        
        return $pdf->download($fileName);
    }
    
    /**
     * Exportar calificaciones antiguas a Excel
     */
    public function exportCalificacionesOldToExcel(int $aulaId, int $año, int $trimestreId, string $fileName)
    {
        $export = new \App\Exports\CalificacionesOldExport($aulaId, $año, $trimestreId);
        return Excel::download($export, $fileName, ExcelType::XLSX);
    }
}