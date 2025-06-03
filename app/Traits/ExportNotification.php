<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

/**
 * Trait ExportNotification
 * 
 * Proporciona funcionalidades para manejar notificaciones de exportación
 * de manera consistente en todos los controladores.
 */
trait ExportNotification
{
    /**
     * Registra una notificación de exportación exitosa
     *
     * @param string $type
     * @param string $filename
     * @param int $count
     * @param array $filters
     * @return void
     */
    protected function setExportSuccessNotification(string $type, string $filename, int $count = 0, array $filters = []): void
    {
        $typeNames = [
            'excel' => 'Excel',
            'pdf' => 'PDF',
            'csv' => 'CSV'
        ];

        $typeName = $typeNames[$type] ?? ucfirst($type);
        
        $message = "Exportación a {$typeName} completada";
        if ($count > 0) {
            $message .= " ({$count} registros)";
        }

        Session::flash('export_notification', [
            'type' => 'success',
            'title' => 'Exportación Exitosa',
            'message' => $message,
            'filename' => $filename,
            'export_type' => $type,
            'record_count' => $count,
            'applied_filters' => $this->getFilterDescription($filters)
        ]);
    }

    /**
     * Registra una notificación de error en la exportación
     *
     * @param string $type
     * @param string $error
     * @return void
     */
    protected function setExportErrorNotification(string $type, string $error): void
    {
        $typeNames = [
            'excel' => 'Excel',
            'pdf' => 'PDF',
            'csv' => 'CSV'
        ];

        $typeName = $typeNames[$type] ?? ucfirst($type);

        Session::flash('export_notification', [
            'type' => 'error',
            'title' => 'Error en Exportación',
            'message' => "No se pudo completar la exportación a {$typeName}: {$error}",
            'export_type' => $type
        ]);
    }

    /**
     * Obtiene una descripción legible de los filtros aplicados
     *
     * @param array $filters
     * @return string|null
     */
    protected function getFilterDescription(array $filters): ?string
    {
        if (empty($filters)) {
            return null;
        }

        $descriptions = [];

        // Mapeo común de filtros
        $filterMap = [
            'busqueda' => 'Búsqueda',
            'nivel' => 'Nivel',
            'aula' => 'Aula',
            'estado' => 'Estado',
            'anio_ingreso' => 'Año de Ingreso',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'curso' => 'Curso',
            'docente' => 'Docente'
        ];

        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $label = $filterMap[$key] ?? ucfirst(str_replace('_', ' ', $key));
                $descriptions[] = "{$label}: {$value}";
            }
        }

        return !empty($descriptions) ? implode(', ', $descriptions) : null;
    }

    /**
     * Maneja la respuesta de exportación con notificación
     *
     * @param callable $exportCallback
     * @param string $type
     * @param string $filename
     * @param array $filters
     * @param int $count
     * @return mixed
     */
    protected function handleExportWithNotification(
        callable $exportCallback, 
        string $type, 
        string $filename, 
        array $filters = [], 
        int $count = 0
    ) {
        try {
            $result = $exportCallback();
            $this->setExportSuccessNotification($type, $filename, $count, $filters);
            return $result;
        } catch (\Exception $e) {
            $this->setExportErrorNotification($type, $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar la exportación: ' . $e->getMessage());
        }
    }

    /**
     * Genera un nombre de archivo descriptivo basado en filtros
     *
     * @param string $baseNam
     * @param array $filters
     * @param string $extension
     * @return string
     */
    protected function generateExportFilename(string $baseName, array $filters = [], string $extension = 'xlsx'): string
    {
        $filename = $baseName;
        
        // Agregar información de filtros al nombre del archivo
        if (!empty($filters['nivel_name'])) {
            $filename .= '_' . str_replace([' ', '/'], '_', $filters['nivel_name']);
        }
        
        if (!empty($filters['aula_name'])) {
            $filename .= '_' . str_replace([' ', '/'], '_', $filters['aula_name']);
        }
        
        if (!empty($filters['estado'])) {
            $filename .= '_' . $filters['estado'];
        }
        
        if (!empty($filters['anio_ingreso'])) {
            $filename .= '_' . $filters['anio_ingreso'];
        }
        
        // Agregar fecha actual
        $filename .= '_' . date('Y-m-d');
        
        // Limpiar caracteres no válidos
        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $filename);
        
        return $filename . '.' . $extension;
    }

    /**
     * Obtiene los filtros del request de manera consistente
     *
     * @param Request $request
     * @param array $allowedFilters
     * @return array
     */
    protected function getExportFilters(Request $request, array $allowedFilters = []): array
    {
        $commonFilters = [
            'busqueda', 'nivel', 'aula', 'estado', 'anio_ingreso', 
            'fecha_inicio', 'fecha_fin', 'curso', 'docente'
        ];
        
        $filters = [];
        $filtersToCheck = !empty($allowedFilters) ? $allowedFilters : $commonFilters;
        
        foreach ($filtersToCheck as $filter) {
            $value = $request->get($filter);
            if (!empty($value)) {
                $filters[$filter] = $value;
            }
        }
        
        return $filters;
    }

    /**
     * Obtiene el conteo de registros que serán exportados
     *
     * @param $query
     * @return int
     */
    protected function getExportCount($query): int
    {
        try {
            // Clonar la query para no afectar la original
            $countQuery = clone $query;
            return $countQuery->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Método auxiliar para exportaciones de Excel
     *
     * @param Request $request
     * @param callable $exportCallback
     * @param string $baseName
     * @param array $allowedFilters
     * @return mixed
     */
    protected function exportExcelWithNotification(
        Request $request, 
        callable $exportCallback, 
        string $baseName = 'Exportacion',
        array $allowedFilters = []
    ) {
        $filters = $this->getExportFilters($request, $allowedFilters);
        $filename = $this->generateExportFilename($baseName, $filters, 'xlsx');
        
        return $this->handleExportWithNotification(
            $exportCallback,
            'excel',
            $filename,
            $filters
        );
    }

    /**
     * Método auxiliar para exportaciones de PDF
     *
     * @param Request $request
     * @param callable $exportCallback
     * @param string $baseName
     * @param array $allowedFilters
     * @return mixed
     */
    protected function exportPdfWithNotification(
        Request $request, 
        callable $exportCallback, 
        string $baseName = 'Exportacion',
        array $allowedFilters = []
    ) {
        $filters = $this->getExportFilters($request, $allowedFilters);
        $filename = $this->generateExportFilename($baseName, $filters, 'pdf');
        
        return $this->handleExportWithNotification(
            $exportCallback,
            'pdf',
            $filename,
            $filters
        );
    }
}