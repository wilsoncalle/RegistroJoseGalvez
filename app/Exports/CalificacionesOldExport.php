<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Aula;
use App\Models\Estudiante;
use App\Models\CalificacionOld;
use App\Models\Asignacion;
use App\Models\Trimestre;

class CalificacionesOldExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithCustomStartCell, WithEvents
{
    protected $aulaId;
    protected $año;
    protected $trimestreId;
    protected $data = [];
    protected $headings = [];
    protected $asignaturas = [];
    protected $aula;
    protected $trimestre;

    public function __construct(int $aulaId, int $año, int $trimestreId)
    {
        $this->aulaId = $aulaId;
        $this->año = $año;
        $this->trimestreId = $trimestreId;
        
        $this->prepareData();
    }

    protected $lastColumn;

    protected function prepareData()
    {
        // Obtener datos necesarios
        $this->aula = Aula::with(['nivel', 'grado', 'seccion'])->findOrFail($this->aulaId);
        $this->trimestre = Trimestre::findOrFail($this->trimestreId);
        
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
            
        $this->asignaturas = $asignaciones;
            
        // Obtener calificaciones
        $calificaciones = CalificacionOld::where('id_trimestre', $this->trimestreId)
            ->whereIn('id_estudiante', $estudiantes->pluck('id_estudiante'))
            ->get();
            
        // Encabezados (solo para la fila de datos, los encabezados reales se manejan en el evento AfterSheet)
        $this->headings = ['N°', 'N° Matrícula', 'Condición', 'Apellidos y Nombres'];
        
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
                $estudiante->condicion ?? 'Regular', // Condición
                $estudiante->apellido . ', ' . $estudiante->nombre, // Apellidos y Nombres
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
        // Retornamos un array vacío porque manejaremos los encabezados manualmente en el evento AfterSheet
        return [];
    }

    public function startCell(): string
    {
        return 'A3'; // Comenzamos en A3 para los datos, después de los encabezados personalizados
    }

    public function styles(Worksheet $sheet)
    {
        // Obtener la última columna y fila
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();
        
        // No aplicamos estilos a la fila A3 ya que no tendrá encabezados
        
        // Bordes para toda la tabla
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Centrar columnas específicas
        $sheet->getStyle('A:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E:' . $lastColumn)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Calcular la última columna basada en la cantidad de asignaturas
                $materiasCount = count($this->asignaturas);
                $totalColumns = 7 + $materiasCount; // 4 columnas iniciales + asignaturas + 3 columnas finales
                $lastColumn = Coordinate::stringFromColumnIndex($totalColumns);
                
                // No incluimos título para evitar problemas
                
                // Definir encabezados verticales combinando celdas A1 y A2
                $verticalHeaders = [
                    'A' => 'N° Orden',
                    'B' => 'N° Matrícula',
                    'C' => 'Condición',
                    'D' => 'Apellidos y Nombres'
                ];
                
                // Establecer un solo encabezado "Asignaturas" que cubra todas las columnas de asignaturas
                $asignaturasStartCol = 'E';
                $asignaturasEndCol = Coordinate::stringFromColumnIndex(4 + count($this->asignaturas));
                
                // Usar mergeCells para crear un solo encabezado que cubra todas las asignaturas
                try {
                    // Intentar combinar celdas para el encabezado de asignaturas
                    $sheet->mergeCells($asignaturasStartCol . '1:' . $asignaturasEndCol . '1');
                    $sheet->setCellValue($asignaturasStartCol . '1', 'Asignaturas');
                    $sheet->getStyle($asignaturasStartCol . '1')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '054F9F'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                } catch (\Exception $e) {
                    // Si falla la combinación, aplicar el encabezado a cada columna individualmente
                    for ($col = 5; $col <= 4 + count($this->asignaturas); $col++) {
                        $colLetter = Coordinate::stringFromColumnIndex($col);
                        $sheet->setCellValue($colLetter . '1', 'Asignaturas');
                        $sheet->getStyle($colLetter . '1')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => 'FFFFFF'],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '054F9F'],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                    }
                }
                
                // El encabezado "Apellidos y Nombres" ya está incluido en $verticalHeaders
                
                // Calcular posiciones para columnas finales
                $materiasEndIndex = 4 + $materiasCount;
                $comportamientoCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 1);
                $desaprobadasCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 2);
                $situacionCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 3);
                
                // Añadir encabezados verticales finales
                $verticalHeaders[$comportamientoCol] = 'Comportamiento';
                $verticalHeaders[$desaprobadasCol] = 'N° asignaturas desaprobadas';
                $verticalHeaders[$situacionCol] = 'Situación Final';
                
                // Aplicar estilos a los encabezados verticales y combinar celdas A1:A2
                foreach ($verticalHeaders as $col => $text) {
                    // Combinar celdas para cada encabezado (excepto asignaturas)
                    try {
                        $sheet->mergeCells($col . '1:' . $col . '2');
                    } catch (\Exception $e) {
                        // Si falla la combinación, continuamos sin combinar
                    }
                    
                    // Establecer el texto del encabezado
                    $sheet->setCellValue($col . '1', $text);
                    
                    // Aplicar rotación vertical (excepto para Apellidos y Nombres)
                    if ($col != 'D') {
                        $sheet->getStyle($col . '1')->getAlignment()->setTextRotation(90);
                    }
                    
                    // Aplicar estilos al encabezado
                    $sheet->getStyle($col . '1')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '054F9F'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => ($col == 'D') ? Alignment::VERTICAL_CENTER : Alignment::VERTICAL_BOTTOM,
                        ],
                    ]);
                    
                    // Ajustar el ancho de las columnas
                    if ($col == 'D') {
                        $sheet->getColumnDimension($col)->setWidth(40); // Columna de nombres más ancha
                    } else {
                        $sheet->getColumnDimension($col)->setWidth(4); // Columnas verticales más estrechas
                    }
                }
                
                // Hacer las filas de encabezados más altas
                $sheet->getRowDimension(2)->setRowHeight(140);
                
                // Agregar nombres de materias en la segunda fila (en vertical)
                $colIndex = 5; // Empezamos en la columna E
                foreach ($this->asignaturas as $asignacion) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                    
                    // Establecer el nombre de la materia
                    $sheet->setCellValue($colLetter . '2', $asignacion->materia->nombre);
                    
                    // Aplicar estilo a la celda de la materia
                    $sheet->getStyle($colLetter . '2')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '054F9F'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_BOTTOM, // Alineación en la parte inferior
                        ],
                    ]);
                    
                    // Aplicar rotación vertical a los encabezados de asignaturas
                    $sheet->getStyle($colLetter . '2')->getAlignment()->setTextRotation(90);
                    
                    // Ajustar el ancho de las columnas de asignaturas (más estrechas)
                    $sheet->getColumnDimension($colLetter)->setWidth(4);
                    
                    $colIndex++;
                }
                
                // Ajustar el ancho de la columna de nombres
                $sheet->getColumnDimension('D')->setWidth(40);
                
                // No necesitamos añadir datos en la fila 3, ya que startCell() ya se encarga de eso
            },
        ];
    }
}
