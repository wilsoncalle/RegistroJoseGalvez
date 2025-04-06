<?php

namespace App\Services;

use App\Models\Aula;
use App\Models\Nivel;
use App\Exports\AulaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class AulaExportService
{
    /**
     * Exportar listado de aulas a Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarExcel(Request $request)
    {
        // Obtener los filtros
        $filtroNivel = $request->input('nivel');
        $busqueda = $request->input('busqueda');
        
        // Iniciar la consulta con joins
        $query = Aula::query()
            ->join('niveles', 'aulas.id_nivel', '=', 'niveles.id_nivel')
            ->join('grados', 'aulas.id_grado', '=', 'grados.id_grado')
            ->join('secciones', 'aulas.id_seccion', '=', 'secciones.id_seccion')
            ->join('asignaciones', 'aulas.id_aula', '=', 'asignaciones.id_aula') // Relación con asignaciones
            ->join('docentes', 'asignaciones.id_docente', '=', 'docentes.id_docente') // Relación con docentes
            ->select([
                'aulas.*',
                'niveles.nombre as nivel_nombre',
                'grados.nombre as grado_nombre',
                'secciones.nombre as seccion_nombre',
                'docentes.apellido as docente_apellido',
                'docentes.nombre as docente_nombre'
            ]);

        // Aplicar filtro por nivel
        if ($filtroNivel) {
            $query->where('aulas.id_nivel', $filtroNivel);
        }

        // Aplicar búsqueda si se proporciona
        if ($busqueda) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('niveles.nombre', 'like', "%$busqueda%")
                  ->orWhere('grados.nombre', 'like', "%$busqueda%")
                  ->orWhere('secciones.nombre', 'like', "%$busqueda%")
                  ->orWhere('docentes.apellido', 'like', "%$busqueda%")
                  ->orWhere('docentes.nombre', 'like', "%$busqueda%");
            });
        }

        // Aplicar ordenamiento
        $query->orderBy('niveles.nombre')  // Primero por nivel
              ->orderBy('grados.nombre')   // Luego por grado
              ->orderByRaw("CAST(secciones.nombre AS CHAR) ASC") // Después por sección
              ->orderBy('docentes.apellido') // Finalmente, ordenar docentes por apellido
              ->orderBy('docentes.nombre'); // En caso de apellidos iguales, ordenar por nombre
        
        // Ejecutar la consulta
        $aulas = $query->get();
        
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
        $nombreArchivo = "aulas_{$nombreNivel}_{$fechaActual}.xlsx";
        
        // Reemplazar espacios y caracteres especiales en el nombre del archivo
        $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
        $nombreArchivo = preg_replace('/[^A-Za-z0-9\-_.]/', '', $nombreArchivo);
        
        // Crear y devolver el archivo Excel
        return Excel::download(
            new AulaExport($aulas, $filtroNivel, $nombreNivel),
            $nombreArchivo
        );
    }

    /**
     * Exportar listado de aulas a PDF
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarPDF(Request $request)
    {
        // Obtener los filtros
        $filtroNivel = $request->input('nivel');
        $busqueda = $request->input('busqueda');
        
        // Iniciar la consulta con joins
        $query = Aula::query()
            ->join('niveles', 'aulas.id_nivel', '=', 'niveles.id_nivel')
            ->join('grados', 'aulas.id_grado', '=', 'grados.id_grado')
            ->join('secciones', 'aulas.id_seccion', '=', 'secciones.id_seccion')
            ->join('asignaciones', 'aulas.id_aula', '=', 'asignaciones.id_aula') // Relación con asignaciones
            ->join('docentes', 'asignaciones.id_docente', '=', 'docentes.id_docente') // Relación con docentes
            ->select([
                'aulas.*',
                'niveles.nombre as nivel_nombre',
                'grados.nombre as grado_nombre',
                'secciones.nombre as seccion_nombre',
                'docentes.apellido as docente_apellido',
                'docentes.nombre as docente_nombre'
            ]);

        // Aplicar filtro por nivel
        if ($filtroNivel) {
            $query->where('aulas.id_nivel', $filtroNivel);
        }

        // Aplicar búsqueda si se proporciona
        if ($busqueda) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('niveles.nombre', 'like', "%$busqueda%")
                  ->orWhere('grados.nombre', 'like', "%$busqueda%")
                  ->orWhere('secciones.nombre', 'like', "%$busqueda%")
                  ->orWhere('docentes.apellido', 'like', "%$busqueda%")
                  ->orWhere('docentes.nombre', 'like', "%$busqueda%");
            });
        }

        // Aplicar ordenamiento
        $query->orderBy('niveles.nombre')  // Primero por nivel
              ->orderBy('grados.nombre')   // Luego por grado
              ->orderByRaw("CAST(secciones.nombre AS CHAR) ASC") // Después por sección
              ->orderBy('docentes.apellido') // Finalmente, ordenar docentes por apellido
              ->orderBy('docentes.nombre'); // En caso de apellidos iguales, ordenar por nombre
        
        // Ejecutar la consulta
        $aulas = $query->get();
        
        // Obtener el nombre del nivel para el título
        $nombreNivel = 'General';
        if ($filtroNivel) {
            $nivel = Nivel::find($filtroNivel);
            if ($nivel) {
                $nombreNivel = $nivel->nombre;
            }
        }
        
        // Configurar la numeración
        $counter = 1;
        
        // Generar nombre del archivo
        $fechaActual = now()->format('d-m-Y');
        $nombreArchivo = "aulas_{$nombreNivel}_{$fechaActual}.pdf";
        
        // Reemplazar espacios y caracteres especiales en el nombre del archivo
        $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
        $nombreArchivo = preg_replace('/[^A-Za-z0-9\-_.]/', '', $nombreArchivo);
        
        // Generar el PDF
        $pdf = \PDF::loadView('pdf.aulas', [
            'aulas' => $aulas, 
            'counter' => $counter,
            'filtroNivel' => $filtroNivel,
            'nombreNivel' => $nombreNivel,
            'fechaActual' => $fechaActual
        ]);
        
        // Configurar el PDF
        $pdf->setPaper('a4', 'landscape');
        
        // Devolver el PDF como descarga
        return $pdf->download($nombreArchivo);
    }
}
