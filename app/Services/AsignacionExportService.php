<?php

namespace App\Services;

use App\Exports\AsignacionExport;
use App\Models\Asignacion;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AsignacionExportService
{
    /**
     * Exportar listado de asignaciones a Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarExcel(Request $request)
    {
        // Obtener los filtros de la solicitud
        $filtroNivel = $request->get('nivel');
        $filtroAula = $request->get('aula');
        $filtroAnio = $request->get('anio');
        $busqueda = trim($request->get('busqueda'));

        // Determinar el nombre del nivel para el título del reporte
        $nombreNivel = 'Todos';
        if ($filtroNivel) {
            $nivel = Nivel::find($filtroNivel);
            if ($nivel) {
                $nombreNivel = $nivel->nombre;
            }
        }

        // Construir la consulta base para las asignaciones
        $query = DB::table('asignaciones')
            ->join('docentes', 'asignaciones.id_docente', '=', 'docentes.id_docente')
            ->join('materias', 'asignaciones.id_materia', '=', 'materias.id_materia')
            ->join('aulas', 'asignaciones.id_aula', '=', 'aulas.id_aula')
            ->join('niveles', 'aulas.id_nivel', '=', 'niveles.id_nivel')
            ->join('grados', 'aulas.id_grado', '=', 'grados.id_grado')
            ->join('secciones', 'aulas.id_seccion', '=', 'secciones.id_seccion')
            ->join('anios_academicos', 'asignaciones.id_anio', '=', 'anios_academicos.id_anio')
            ->select(
                'asignaciones.id_asignacion',
                'docentes.nombre as docente_nombre',
                'docentes.apellido as docente_apellido',
                'materias.nombre as materia_nombre',
                'niveles.nombre as nivel_nombre',
                DB::raw("CONCAT(grados.nombre, ' ', secciones.nombre) as aula_nombre"),
                'anios_academicos.anio as anio_academico'
            );

        // Aplicar filtros si están presentes
        if ($filtroNivel) {
            $query->where('niveles.id_nivel', $filtroNivel);
        }

        if ($filtroAula) {
            $query->where('aulas.id_aula', $filtroAula);
        }

        if ($filtroAnio) {
            $query->where('anios_academicos.id_anio', $filtroAnio);
        }

        if ($busqueda) {
            $query->where(function ($q) use ($busqueda) {
                $q->whereRaw("LOWER(docentes.nombre) LIKE ?", ['%' . strtolower($busqueda) . '%'])
                  ->orWhereRaw("LOWER(docentes.apellido) LIKE ?", ['%' . strtolower($busqueda) . '%'])
                  ->orWhereRaw("LOWER(materias.nombre) LIKE ?", ['%' . strtolower($busqueda) . '%']);
            });
        }

        // Ordenar los resultados
        $query->orderBy('niveles.nombre')
              ->orderBy('grados.nombre')
              ->orderBy('secciones.nombre')
              ->orderBy('docentes.apellido');

        // Obtener los resultados
        $asignaciones = $query->get();

        // Crear el archivo Excel
        $export = new AsignacionExport($asignaciones, $filtroNivel, $nombreNivel);
        
        // Generar el nombre del archivo
        $fileName = 'asignaciones_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Descargar el archivo
        return Excel::download($export, $fileName);
    }

    /**
     * Exportar listado de asignaciones a PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportarPDF(Request $request)
    {
        // Obtener los filtros de la solicitud
        $filtroNivel = $request->get('nivel');
        $filtroAula = $request->get('aula');
        $filtroAnio = $request->get('anio');
        $busqueda = trim($request->get('busqueda'));

        // Determinar el nombre del nivel para el título del reporte
        $nombreNivel = 'Todos';
        if ($filtroNivel) {
            $nivel = Nivel::find($filtroNivel);
            if ($nivel) {
                $nombreNivel = $nivel->nombre;
            }
        }

        // Construir la consulta base para las asignaciones
        $query = DB::table('asignaciones')
            ->join('docentes', 'asignaciones.id_docente', '=', 'docentes.id_docente')
            ->join('materias', 'asignaciones.id_materia', '=', 'materias.id_materia')
            ->join('aulas', 'asignaciones.id_aula', '=', 'aulas.id_aula')
            ->join('niveles', 'aulas.id_nivel', '=', 'niveles.id_nivel')
            ->join('grados', 'aulas.id_grado', '=', 'grados.id_grado')
            ->join('secciones', 'aulas.id_seccion', '=', 'secciones.id_seccion')
            ->join('anios_academicos', 'asignaciones.id_anio', '=', 'anios_academicos.id_anio')
            ->select(
                'asignaciones.id_asignacion',
                'docentes.nombre as docente_nombre',
                'docentes.apellido as docente_apellido',
                'materias.nombre as materia_nombre',
                'niveles.nombre as nivel_nombre',
                DB::raw("CONCAT(grados.nombre, ' ', secciones.nombre) as aula_nombre"),
                'anios_academicos.anio as anio_academico'
            );

        // Aplicar filtros si están presentes
        if ($filtroNivel) {
            $query->where('niveles.id_nivel', $filtroNivel);
        }

        if ($filtroAula) {
            $query->where('aulas.id_aula', $filtroAula);
        }

        if ($filtroAnio) {
            $query->where('anios_academicos.id_anio', $filtroAnio);
        }

        if ($busqueda) {
            $query->where(function ($q) use ($busqueda) {
                $q->whereRaw("LOWER(docentes.nombre) LIKE ?", ['%' . strtolower($busqueda) . '%'])
                  ->orWhereRaw("LOWER(docentes.apellido) LIKE ?", ['%' . strtolower($busqueda) . '%'])
                  ->orWhereRaw("LOWER(materias.nombre) LIKE ?", ['%' . strtolower($busqueda) . '%']);
            });
        }

        // Ordenar los resultados
        $query->orderBy('niveles.nombre')
              ->orderBy('grados.nombre')
              ->orderBy('secciones.nombre')
              ->orderBy('docentes.apellido');

        // Obtener los resultados
        $asignaciones = $query->get();

        // Fecha actual para el reporte
        $fechaActual = Carbon::now()->locale('es')->isoFormat('LL');

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.asignaciones', [
            'asignaciones' => $asignaciones,
            'nombreNivel' => $nombreNivel,
            'fechaActual' => $fechaActual
        ]);

        // Configurar el PDF
        $pdf->setPaper('a4', 'landscape');
        
        // Generar el nombre del archivo
        $fileName = 'asignaciones_' . date('Y-m-d_H-i-s') . '.pdf';
        
        // Descargar el archivo
        return $pdf->download($fileName);
    }
}
