<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Aula;
use App\Models\Estudiante;
use App\Models\CalificacionOld;
use App\Models\Asignacion;
use App\Models\Trimestre;

class CalificacionesOldExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $aulaId;
    protected $año;
    protected $trimestreId;
    protected $data = [];
    protected $headings = [];

    public function __construct(int $aulaId, int $año, int $trimestreId)
    {
        $this->aulaId = $aulaId;
        $this->año = $año;
        $this->trimestreId = $trimestreId;
        
        $this->prepareData();
    }

    protected function prepareData()
    {
        // Obtener datos necesarios
        $aula = Aula::with(['nivel', 'grado', 'seccion'])->findOrFail($this->aulaId);
        $trimestre = Trimestre::findOrFail($this->trimestreId);
        
        // Obtener estudiantes del aula
        $estudiantes = Estudiante::where('id_aula', $this->aulaId)
            ->orderBy('apellido')
            ->get();
            
        // Obtener asignaciones y materias para este aula y año
        $asignaciones = Asignacion::with('materia')
            ->where('id_aula', $this->aulaId)
            ->whereHas('anioAcademico', function($query) {
                $query->where('anio', $this->año);
            })
            ->get();
            
        // Obtener calificaciones
        $calificaciones = CalificacionOld::where('id_trimestre', $this->trimestreId)
            ->whereIn('id_estudiante', $estudiantes->pluck('id_estudiante'))
            ->get();
            
        // Encabezados
        $this->headings = ['N°', 'N° Matrícula', 'Apellidos y Nombres', 'Condición'];
        
        // Agregar materias a los encabezados
        foreach ($asignaciones as $asignacion) {
            $this->headings[] = $asignacion->materia->nombre;
        }
        
        // Agregar columnas finales
        $this->headings[] = 'Comportamiento';
        $this->headings[] = 'N° Asig. Desaprobadas';
        $this->headings[] = 'Situación Final';
        
        // Datos de estudiantes
        foreach ($estudiantes as $index => $estudiante) {
            $row = [
                $index + 1, // N° Orden
                $estudiante->codigo, // N° Matrícula
                $estudiante->apellido . ', ' . $estudiante->nombre, // Apellidos y Nombres
                $estudiante->condicion ?? 'Regular', // Condición
            ];
            
            // Notas por asignatura
            $desaprobadas = 0;
            foreach ($asignaciones as $asignacion) {
                $calificacion = $calificaciones->first(function ($cal) use ($estudiante, $asignacion) {
                    return $cal->id_estudiante == $estudiante->id_estudiante && 
                           $cal->id_asignacion == $asignacion->id_asignacion;
                });
                
                $nota = $calificacion ? $calificacion->nota : '';
                $row[] = $nota;
                
                // Contar desaprobadas
                if ($nota && $nota < 11) {
                    $desaprobadas++;
                }
            }
            
            // Comportamiento
            $comportamiento = $calificaciones->first(function ($cal) use ($estudiante) {
                return $cal->id_estudiante == $estudiante->id_estudiante;
            });
            $row[] = $comportamiento ? $comportamiento->comportamiento : '';
            
            // N° Asignaturas Desaprobadas
            $row[] = $desaprobadas;
            
            // Situación Final
            $row[] = $desaprobadas > 0 ? 'P' : 'A';
            
            $this->data[] = $row;
        }
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para la fila de encabezados
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '054F9F'],
            ],
        ]);
        
        // Bordes para toda la tabla
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Centrar columnas específicas
        $sheet->getStyle('A:B')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D:' . $sheet->getHighestColumn())->getAlignment()->setHorizontal('center');
    }
}
