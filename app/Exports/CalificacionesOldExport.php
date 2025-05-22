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
use App\Models\AnioAcademico;

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
    protected $estudiantesConObservaciones = [];

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
        
        // Identificar a los estudiantes que pertenecen al año académico seleccionado
        
        // 1. Obtener asignaciones para este aula y año académico
        $asignaciones = Asignacion::with('materia')
            ->where('id_aula', $this->aulaId)
            ->whereHas('anioAcademico', function($query) {
                $query->where('anio', $this->año);
            })
            ->get();
            
        $this->asignaturas = $asignaciones;
        
        if ($asignaciones->isEmpty()) {
            $this->data = [];
            return;
        }
        
        // 2. Obtener los IDs de los estudiantes que tienen calificaciones o observaciones en este año académico
        $estudiantesIds = CalificacionOld::whereIn('id_asignacion', $asignaciones->pluck('id_asignacion'))
            ->where(function($query) {
                $query->whereNotNull('nota')
                      ->orWhereNotNull('observacion');
            })
            ->pluck('id_estudiante')
            ->unique()
            ->toArray();
            
        // 3. Obtener estudiantes del aula que corresponden al año académico seleccionado
        // Incluimos tanto los que tienen calificaciones/observaciones como los que no
        $estudiantes = Estudiante::where('id_aula', $this->aulaId)
            ->where(function($query) use ($estudiantesIds) {
                // Incluir estudiantes que tienen alguna calificación u observación en este año académico
                $query->whereIn('id_estudiante', $estudiantesIds)
                    // O estudiantes que se crearon en este año académico
                    ->orWhereYear('created_at', $this->año);
            })
            ->orderBy('apellido')
            ->get();
            
        // Obtener calificaciones y observaciones
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
            $currentRowIndex = count($this->data); // Índice de la fila actual
            
            $row = [
                $index + 1, // N° Orden
                $estudiante->codigo, // N° Matrícula
                $estudiante->condicion ?? 'Regular', // Condición
                $estudiante->apellido . ', ' . $estudiante->nombre, // Apellidos y Nombres
            ];
            
            // Notas por asignatura
            $desaprobadas = 0;
            $tieneObservacion = false;
            $observacion = '';

            // Primero verificar si hay una observación para este estudiante
            $calificacionConObservacion = $calificaciones->first(function ($cal) use ($estudiante) {
                return $cal->id_estudiante == $estudiante->id_estudiante && 
                       !empty($cal->observacion);
            });

            if ($calificacionConObservacion) {
                $tieneObservacion = true;
                $observacion = $calificacionConObservacion->observacion;
            }

            if (!$tieneObservacion) {
                // Si no tiene observación, procesar normalmente
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
                $situacion = '';
                
                // Primero verificar si hay una situación guardada en la base de datos
                $calificacionEstudiante = $calificaciones->first(function ($cal) use ($estudiante) {
                    return $cal->id_estudiante == $estudiante->id_estudiante;
                });
                
                if ($calificacionEstudiante) {
                    // Si hay una conclusión especial, usarla
                    if (in_array($calificacionEstudiante->conclusion, ['RET', 'TRA'])) {
                        $situacion = $calificacionEstudiante->conclusion;
                    }
                }
                
                // Si no hay situación especial, aplicar la lógica normal
                if (empty($situacion)) {
                    if ($desaprobadas === 0) {
                        $situacion = 'P'; // Promovido (0 cursos desaprobados)
                    } else if ($desaprobadas >= 1 && $desaprobadas <= 3) {
                        $situacion = 'A'; // Aplazado (1 a 3 cursos desaprobados)
                    } else {
                        $situacion = 'R'; // Reprobado (4 o más cursos desaprobados)
                    }
                }
                
                $row[] = $situacion;
            } else {
                // Si tiene observación, agregar una celda vacía para cada asignatura
                // (estas celdas se combinarán después en el evento AfterSheet)
                foreach ($asignaciones as $asignacion) {
                    $row[] = '';
                }
                
                // Agregar celdas vacías para comportamiento, asignaturas desaprobadas y situación final
                $row[] = '';
                $row[] = '';
                $row[] = '';
                
                // Guardar la información de la observación para usarla en el evento AfterSheet
                $this->estudiantesConObservaciones[] = [
                    'observacion' => $observacion,
                    'rowIndex' => $currentRowIndex
                ];
            }
            
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
        return 'A15'; // Comenzamos en A15 para los datos, después de los encabezados personalizados
    }

    public function styles(Worksheet $sheet)
    {
        // Obtener la última columna y fila
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();
        
        // Aplicaremos Times New Roman a todas las celdas al final del evento AfterSheet
        
        // Bordes para toda la tabla y el encabezado y fuente Times New Roman tamaño 12
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'name' => 'Times New Roman',
                'size' => 12,
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
                
                // Definir encabezados verticales con las nuevas combinaciones de celdas
                $verticalHeaders = [
                    'A' => 'N° Orden',     // A13:B25
                    'C' => 'N° Matrícula', // C13:D25
                    'E' => 'Condición',    // E13:F25
                    'G' => 'Apellidos y Nombres' // G13:N25
                ];
                
                // Establecer un solo encabezado "Asignaturas" que cubra todas las columnas de asignaturas
                $asignaturasStartCol = 'O';
                $asignaturasEndCol = Coordinate::stringFromColumnIndex(14 + count($this->asignaturas));
                
                // Usar mergeCells para crear un solo encabezado que cubra todas las asignaturas
                try {
                    // Intentar combinar celdas para el encabezado de asignaturas
                    $sheet->mergeCells($asignaturasStartCol . '13:' . $asignaturasEndCol . '13');
                    $sheet->setCellValue($asignaturasStartCol . '13', 'Asignaturas');
                    $sheet->getStyle($asignaturasStartCol . '13')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => '000000'],
                            'name' => 'Times New Roman',
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'DDD9C4'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                } catch (\Exception $e) {
                    // Si falla la combinación, aplicar el encabezado a cada columna individualmente
                    for ($col = 15; $col <= 14 + count($this->asignaturas); $col++) {
                        $colLetter = Coordinate::stringFromColumnIndex($col);
                        $sheet->setCellValue($colLetter . '13', 'Asignaturas');
                        $sheet->getStyle($colLetter . '13')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => '000000'],
                                'name' => 'Times New Roman',
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'DDD9C4'],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                        ]);
                    }
                }
                
                // El encabezado "Apellidos y Nombres" ya está incluido en $verticalHeaders
                
                // Calcular posiciones para columnas finales
                $materiasEndIndex = 14 + $materiasCount;
                $comportamientoCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 1);
                $desaprobadasCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 2);
                $situacionCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 3);
                
                // Añadir encabezados verticales finales
                $verticalHeaders[$comportamientoCol] = 'Comportamiento';
                $verticalHeaders[$desaprobadasCol] = 'N° asignaturas desaprobadas';
                $verticalHeaders[$situacionCol] = 'Situación Final';
                
                // Aplicar estilos a los encabezados verticales y combinar celdas según las especificaciones
                // Combinaciones especiales para los encabezados principales
                try {
                    $sheet->mergeCells('A13:B25'); // N° Orden
                    $sheet->mergeCells('C13:D25'); // N° Matrícula
                    $sheet->mergeCells('E13:F25'); // Condición
                    $sheet->mergeCells('G13:N25'); // Apellidos y Nombres
                    
                    // Combinar celdas para los encabezados finales
                    $sheet->mergeCells($comportamientoCol . '13:' . $comportamientoCol . '25');
                    $sheet->mergeCells($desaprobadasCol . '13:' . $desaprobadasCol . '25');
                    $sheet->mergeCells($situacionCol . '13:' . $situacionCol . '25');
                } catch (\Exception $e) {
                    // Si falla la combinación, continuamos sin combinar
                }
                
                // Procesar cada encabezado
                foreach ($verticalHeaders as $col => $text) {
                    
                    // Establecer el texto del encabezado
                    $sheet->setCellValue($col . '13', $text);
                    
                    // Aplicar rotación vertical (excepto para Apellidos y Nombres)
                    if ($col != 'G') {
                        $sheet->getStyle($col . '13')->getAlignment()->setTextRotation(90);
                    }
                    
                    // Aplicar estilos al encabezado
                    $sheet->getStyle($col . '13')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => '000000'],
                            'name' => 'Times New Roman',
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'DDD9C4'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_BOTTOM, // Todos los encabezados alineados en la parte inferior
                        ],
                    ]);
                    
                    // Ajustar el ancho de las columnas
                    if ($col == 'D') {
                        $sheet->getColumnDimension($col)->setWidth(40); // Columna de nombres más ancha
                    } else {
                        $sheet->getColumnDimension($col)->setWidth(4); // Otras columnas verticales más estrechas
                    }
                }
                
                // Establecer una altura fija más alta para los encabezados
                $sheet->getRowDimension(14)->setRowHeight(170); // Altura suficiente para textos largos en vertical
                
                // Agregar nombres de materias en la fila 14 (en vertical)
                $colIndex = 15; // Empezamos en la columna O
                foreach ($this->asignaturas as $asignacion) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                    
                    // Combinar celdas para cada asignatura (fila 14 a 25)
                    try {
                        $sheet->mergeCells($colLetter . '14:' . $colLetter . '25');
                    } catch (\Exception $e) {
                        // Si falla la combinación, continuamos sin combinar
                    }
                    
                    // Establecer el nombre de la materia
                    $sheet->setCellValue($colLetter . '14', $asignacion->materia->nombre);
                    
                    // Aplicar estilo a la celda de la materia
                    $sheet->getStyle($colLetter . '14')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => '000000'],
                            'name' => 'Times New Roman',
                            'size' => 12,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F6F5F0'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_BOTTOM, // Alineación en la parte inferior
                        ],
                    ]);
                    
                    // Aplicar rotación vertical a los encabezados de asignaturas
                    $sheet->getStyle($colLetter . '14')->getAlignment()->setTextRotation(90);
                    
                    // Ajustar el ancho de las columnas de asignaturas a un tamaño base de 5
                    $sheet->getColumnDimension($colLetter)->setWidth(5);
                    
                    // Aplicar tamaño de letra 12 a todas las celdas de la columna
                    $sheet->getStyle($colLetter . '26:' . $colLetter . $sheet->getHighestRow())->getFont()->setSize(12);
                    
                    $colIndex++;
                }
                
                // Ajustar el ancho de las columnas según la nueva estructura
                $sheet->getColumnDimension('A')->setWidth(5)->setAutoSize(false); // N° Orden (parte 1)
                $sheet->getColumnDimension('B')->setWidth(5)->setAutoSize(false); // N° Orden (parte 2)
                $sheet->getColumnDimension('C')->setWidth(5)->setAutoSize(false); // N° Matrícula (parte 1)
                $sheet->getColumnDimension('D')->setWidth(5)->setAutoSize(false); // N° Matrícula (parte 2)
                $sheet->getColumnDimension('E')->setWidth(5)->setAutoSize(false); // Condición (parte 1)
                $sheet->getColumnDimension('F')->setWidth(5)->setAutoSize(false); // Condición (parte 2)
                
                // Ajustar el ancho para Apellidos y Nombres (G-N)
                for ($i = 'G'; $i <= 'N'; $i++) {
                    $sheet->getColumnDimension($i)->setWidth(5)->setAutoSize(false);
                }
                // Las columnas de asignaturas ya se ajustan en el bucle anterior
                
                // Agregar título general en la fila 12 que cubra todas las columnas
                $sheet->mergeCells('A12:V12');
                $sheet->setCellValue('A12', 'ACTA CONSOLIDADA DE EVALUACIÓN INTEGRAL');
                $sheet->getStyle('A12')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'name' => 'Times New Roman',
                        'color' => ['rgb' => '000000'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Formato para las filas 1-10
                // Bloque superior para la imagen (A1:E4)
                $sheet->mergeCells('A1:E4'); // Espacio para la imagen
                
                // Insertar la imagen del Gran Sello de la República del Perú
                $drawing = new Drawing();
                $drawing->setName('Gran Sello');
                $drawing->setDescription('Gran Sello de la República del Perú');
                $drawing->setPath(public_path('img/Gran_Sello_de_la_República_del_Perú.png'));
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(60); // Ajustar posición horizontal dentro de la celda
                $drawing->setOffsetY(05); // Ajustar posición vertical dentro de la celda
                $drawing->setWidth(75);   // Ajustar ancho de la imagen
                $drawing->setHeight(75);  // Ajustar altura de la imagen
                $drawing->setWorksheet($sheet);
                
                // Ministerio de Educación (F1:V2)
                $sheet->mergeCells('F1:V2');
                $sheet->setCellValue('F1', 'MINISTERIO DE EDUCACIÓN');
                $sheet->getStyle('F1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_BOTTOM],
                ]);
                
                // Educación Secundaria (F3:V4)
                $sheet->mergeCells('F3:V4');
                $sheet->setCellValue('F3', 'EDUCACIÓN SECUNDARIA');
                $sheet->getStyle('F3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_TOP],
                ]);
                
                // Aumentar la altura de las filas 1-2 y 3-4
                $sheet->getRowDimension(1)->setRowHeight(16);
                $sheet->getRowDimension(2)->setRowHeight(16);
                $sheet->getRowDimension(3)->setRowHeight(16);
                $sheet->getRowDimension(4)->setRowHeight(16);
                
                // Fila 5: Centro Educativo
                $sheet->mergeCells('A5:E5');
                $sheet->setCellValue('A5', 'CENTRO EDUCATIVO:');
                $sheet->mergeCells('F5:L5');
                $sheet->setCellValue('F5', 'Nº 15510 AMPLIACIÓN SECUNDARIA');
                $sheet->mergeCells('M5:V5');
                $sheet->setCellValue('M5', 'PIURA');
                
                // Aplicar estilo a las etiquetas (Times New Roman, 12, alineado a la izquierda)
                $sheet->getStyle('A5')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                $sheet->getStyle('F5')->applyFromArray([
                    'font' => ['size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                $sheet->getStyle('M5')->applyFromArray([
                    'font' => ['size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                // Fila 6: Departamento, Distrito, Año, Sección
                $sheet->mergeCells('A6:E6');
                
                // Aplicamos formato de texto enriquecido a A6 (DEPARTAMENTO)
                $richTextA6 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldA6 = $richTextA6->createTextRun('DEPARTAMENTO: ');
                $boldA6->getFont()->setBold(true);
                $boldA6->getFont()->setName('Times New Roman');
                $normalA6 = $richTextA6->createTextRun('PIURA'); // Este texto no tendrá negrita
                $normalA6->getFont()->setName('Times New Roman');
                $sheet->setCellValue('A6', $richTextA6);
                
                $sheet->mergeCells('F6:G6');
                $sheet->setCellValue('F6', 'PIURA');
                $sheet->getStyle('F6')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('H6:I6');
                $sheet->setCellValue('H6', 'DISTRITO:');
                $sheet->getStyle('H6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                $sheet->mergeCells('J6:L6');
                $sheet->setCellValue('J6', 'PARIÑAS');
                $sheet->getStyle('J6')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('M6:O6');
                // Aplicamos formato de texto enriquecido a M6 (AÑO ESCOLAR)
                $richTextM6 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldM6 = $richTextM6->createTextRun('AÑO ESCOLAR: ');
                $boldM6->getFont()->setBold(true);
                $boldM6->getFont()->setName('Times New Roman');
                $sheet->setCellValue('M6', $richTextM6);
                
                $sheet->mergeCells('P6:Q6');
                $sheet->setCellValue('P6', $this->año);
                $sheet->getStyle('P6')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('R6:S6');
                $sheet->setCellValue('R6', 'SECCION:');
                $sheet->getStyle('R6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                $sheet->mergeCells('T6:V6');
                $sheet->setCellValue('T6', $this->aula->seccion->nombre);
                $sheet->getStyle('T6')->getFont()->setName('Times New Roman');
                
                // Aplicar estilo a las etiquetas
                $sheet->getStyle('A6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                $sheet->getStyle('D6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                $sheet->getStyle('E6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                $sheet->getStyle('K6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                // Fila 7: Provincia, Lugar, Turno, Grado
                $sheet->mergeCells('A7:E7');
                // Usamos formato de texto enriquecido para la celda A7
                $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $bold = $richText->createTextRun('PROVINCIA: ');
                $bold->getFont()->setBold(true);
                $bold->getFont()->setName('Times New Roman');
                $sheet->setCellValue('A7', $richText);
                
                $sheet->mergeCells('F7:G7');
                $sheet->setCellValue('F7', 'TALARA');
                $sheet->getStyle('F7')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('H7:I7');
                $sheet->setCellValue('H7', 'LUGAR:');
                $sheet->getStyle('H7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                $sheet->mergeCells('J7:L7');
                $sheet->setCellValue('J7', 'TALARA');
                $sheet->getStyle('J7')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('M7:O7');
                // Aplicamos formato de texto enriquecido a M7 (TURNO)
                $richTextM7 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldM7 = $richTextM7->createTextRun('TURNO: ');
                $boldM7->getFont()->setBold(true);
                $boldM7->getFont()->setName('Times New Roman');
                $sheet->setCellValue('M7', $richTextM7);
                
                $sheet->mergeCells('P7:Q7');
                $sheet->setCellValue('P7', 'DIURNO');
                $sheet->getStyle('P7')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('R7:S7');
                $sheet->setCellValue('R7', 'GRADO:');
                $sheet->getStyle('R7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                $sheet->mergeCells('T7:V7');
                $sheet->setCellValue('T7', strtoupper($this->aula->grado->nombre));
                $sheet->getStyle('T7')->getFont()->setName('Times New Roman');
                
                // Aplicar estilo a las etiquetas (solo la etiqueta en negrita, no el valor)
                // Primero aplicamos el estilo base a toda la celda
                $sheet->getStyle('A7:K7')->applyFromArray([
                    'font' => ['size' => 12, 'name' => 'Times New Roman', 'bold' => false],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                // Luego aplicamos negrita solo a las etiquetas
                $sheet->getStyle('A7')->getFont()->setBold(true);
                $sheet->getStyle('D7')->getFont()->setBold(true);
                $sheet->getStyle('E7')->getFont()->setBold(true);
                $sheet->getStyle('K7')->getFont()->setBold(true);
                
                // Fila 8: Subregión, Evaluación
                $sheet->mergeCells('A8:E8');
                // Aplicamos formato de texto enriquecido a A8 (SUBREGIÓN)
                $richTextA8 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldA8 = $richTextA8->createTextRun('SUBREGIÓN: ');
                $boldA8->getFont()->setBold(true);
                $boldA8->getFont()->setName('Times New Roman');
                $sheet->setCellValue('A8', $richTextA8);
                
                $sheet->mergeCells('F8:L8');
                $sheet->setCellValue('F8', 'LUCIANO CASTILLO COLONNA');
                $sheet->getStyle('F8')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('M8:O8');
                // Aplicamos formato de texto enriquecido a M8 (EVALUACIÓN)
                $richTextM8 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldM8 = $richTextM8->createTextRun('EVALUACIÓN: ');
                $boldM8->getFont()->setBold(true);
                $boldM8->getFont()->setName('Times New Roman');
                $sheet->setCellValue('M8', $richTextM8);
                
                $sheet->mergeCells('P8:V8');
                $sheet->setCellValue('P8', 'FINAL');
                $sheet->getStyle('P8')->getFont()->setName('Times New Roman');
                
                // Aplicar estilo a las etiquetas
                $sheet->getStyle('A8')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                $sheet->getStyle('E8')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                // Combinar celdas en la fila 9 para la etiqueta de evaluación
                $sheet->mergeCells('A9:V9');
                $sheet->setCellValue('A9', '(FINAL - DE APLAZADOS - DE COMPLEMENTACIÓN)');
                $sheet->getStyle('A9')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Combinar celdas en la fila 10 (vacía para separación)
                $sheet->mergeCells('A10:V10');
                
                // Combinar celdas en la fila 11 (vacía para separación)
                $sheet->mergeCells('A11:V11');
                
                // Asegurar que todas las celdas usen Times New Roman
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->getFont()->setName('Times New Roman');
                
                // Procesar estudiantes con observaciones
                $dataStartRow = 26; // La fila donde comienzan los datos (ajustada a la nueva estructura)
                
                // Recorrer los estudiantes con observaciones
                foreach ($this->estudiantesConObservaciones as $estudianteData) {
                    $rowIndex = $estudianteData['rowIndex'];
                    $observacion = $estudianteData['observacion'];
                    $excelRowIndex = $rowIndex + $dataStartRow;
                    
                    // Combinar celdas para los datos de cada estudiante en la fila
                    try {
                        $sheet->mergeCells('A' . $excelRowIndex . ':B' . $excelRowIndex); // N° Orden
                        $sheet->mergeCells('C' . $excelRowIndex . ':D' . $excelRowIndex); // N° Matrícula
                        $sheet->mergeCells('E' . $excelRowIndex . ':F' . $excelRowIndex); // Condición
                        $sheet->mergeCells('G' . $excelRowIndex . ':N' . $excelRowIndex); // Apellidos y Nombres
                    } catch (\Exception $e) {
                        // Si falla la combinación, continuamos sin combinar
                    }
                    
                    // Calcular el rango de columnas a combinar para observaciones
                    $startCol = 'O'; // Nueva columna donde comienzan las asignaturas
                    $endCol = $highestColumn; // Última columna (situación final)
                    
                    // Combinar las celdas para la observación
                    $sheet->mergeCells($startCol . $excelRowIndex . ':' . $endCol . $excelRowIndex);
                    
                    // Establecer el texto de la observación
                    $sheet->setCellValue($startCol . $excelRowIndex, $observacion);
                    
                    // Aplicar estilo a la celda combinada
                    $sheet->getStyle($startCol . $excelRowIndex)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'font' => [
                            'italic' => true,
                            'color' => ['rgb' => 'FF0000'], // Rojo
                        ],
                    ]);
                }
                
                // No necesitamos añadir datos en la fila 13, ya que startCell() ya se encarga de eso
            },
        ];
    }
}