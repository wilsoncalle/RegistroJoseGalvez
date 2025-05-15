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
                
                // Definir encabezados verticales combinando celdas A13 y A14
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
                    for ($col = 5; $col <= 4 + count($this->asignaturas); $col++) {
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
                $materiasEndIndex = 4 + $materiasCount;
                $comportamientoCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 1);
                $desaprobadasCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 2);
                $situacionCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 3);
                
                // Añadir encabezados verticales finales
                $verticalHeaders[$comportamientoCol] = 'Comportamiento';
                $verticalHeaders[$desaprobadasCol] = 'N° asignaturas desaprobadas';
                $verticalHeaders[$situacionCol] = 'Situación Final';
                
                // Aplicar estilos a los encabezados verticales y combinar celdas A11:A12
                foreach ($verticalHeaders as $col => $text) {
                    // Combinar celdas para cada encabezado (excepto asignaturas)
                    try {
                        $sheet->mergeCells($col . '13:' . $col . '14');
                    } catch (\Exception $e) {
                        // Si falla la combinación, continuamos sin combinar
                    }
                    
                    // Establecer el texto del encabezado
                    $sheet->setCellValue($col . '13', $text);
                    
                    // Aplicar rotación vertical (excepto para Apellidos y Nombres)
                    if ($col != 'D') {
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
                
                // Agregar nombres de materias en la fila 12 (en vertical)
                $colIndex = 5; // Empezamos en la columna E
                foreach ($this->asignaturas as $asignacion) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                    
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
                    
                    // Ajustar el ancho de las columnas de asignaturas 
                    $sheet->getColumnDimension($colLetter)->setWidth(5);
                    
                    // Aplicar tamaño de letra 12 a todas las celdas de la columna
                    $sheet->getStyle($colLetter . '15:' . $colLetter . $sheet->getHighestRow())->getFont()->setSize(12);
                    
                    $colIndex++;
                }
                
                // Ajustar el ancho de las columnas - Forzar ancho fijo solo para las tres primeras columnas
                $sheet->getColumnDimension('A')->setWidth(9)->setAutoSize(false); // N° Orden
                $sheet->getColumnDimension('B')->setWidth(9)->setAutoSize(false); // N° Matrícula
                $sheet->getColumnDimension('C')->setWidth(9)->setAutoSize(false); // Condición
                // Las demás columnas se autoajustarán
                
                // Agregar título general en la fila 12 que cubra todas las columnas
                $sheet->mergeCells('A12:' . $lastColumn . '12');
                $sheet->setCellValue('A12', 'ACTA CONSOLIDAD DE EVALUACIÓN INTEGRAL');
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
                // Fila 1-4: Imagen y Ministerio de Educación
                $sheet->mergeCells('A1:C4'); // Espacio para la imagen
                
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
                $sheet->mergeCells('D1:Q2');
                $sheet->setCellValue('D1', 'MINISTERIO DE EDUCACIÓN');
                $sheet->getStyle('D1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_BOTTOM],
                ]);
                
                // Fila 3-4: Educación Secundaria
                $sheet->mergeCells('D3:Q4');
                $sheet->setCellValue('D3', 'EDUCACIÓN SECUNDARIA');
                $sheet->getStyle('D3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_TOP],
                ]);
                
                // Aumentar la altura de las filas 1-2 y 3-4
                $sheet->getRowDimension(1)->setRowHeight(16);
                $sheet->getRowDimension(2)->setRowHeight(16);
                $sheet->getRowDimension(3)->setRowHeight(16);
                $sheet->getRowDimension(4)->setRowHeight(16);
                
                // Fila 5: Centro Educativo
                $sheet->mergeCells('A5:C5');
                $sheet->setCellValue('A5', 'CENTRO EDUCATIVO:');
                $sheet->setCellValue('D5', 'C.E. Nº 15510 AMPLIACIÓN SECUNDARIA');
                $sheet->mergeCells('E5:Q5');
                $sheet->setCellValue('E5', 'DIRECCIÓN DEPARTAMENTAL ZONAL: PIURA');
                
                // Aplicar estilo a las etiquetas (Times New Roman, 12, alineado a la izquierda)
                $sheet->getStyle('A5')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                $sheet->getStyle('E5')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                // Fila 6: Departamento, Distrito, Año, Sección
                $sheet->mergeCells('A6:C6');
                
                // Aplicamos formato de texto enriquecido a A6 (DEPARTAMENTO)
                $richTextA6 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldA6 = $richTextA6->createTextRun('DEPARTAMENTO: ');
                $boldA6->getFont()->setBold(true);
                $boldA6->getFont()->setName('Times New Roman');
                $normalA6 = $richTextA6->createTextRun('PIURA'); // Este texto no tendrá negrita
                $normalA6->getFont()->setName('Times New Roman');
                $sheet->setCellValue('A6', $richTextA6);
                
                // Aplicamos formato de texto enriquecido a D6 (DISTRITO)
                $richTextD6 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldD6 = $richTextD6->createTextRun('DISTRITO: ');
                $boldD6->getFont()->setBold(true);
                $boldD6->getFont()->setName('Times New Roman');
                $normalD6 = $richTextD6->createTextRun('PARIÑAS'); // Este texto no tendrá negrita
                $normalD6->getFont()->setName('Times New Roman');
                $sheet->setCellValue('D6', $richTextD6);
                
                $sheet->mergeCells('E6:J6');
                // Aplicamos formato de texto enriquecido a E6 (AÑO ESCOLAR)
                $richTextE6 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldE6 = $richTextE6->createTextRun('AÑO ESCOLAR: ');
                $boldE6->getFont()->setBold(true);
                $boldE6->getFont()->setName('Times New Roman');
                $normalE6 = $richTextE6->createTextRun($this->año); // Este texto no tendrá negrita
                $normalE6->getFont()->setName('Times New Roman');
                $sheet->setCellValue('E6', $richTextE6);
                
                $sheet->mergeCells('K6:Q6');
                // Aplicamos formato de texto enriquecido a K6 (SECCIÓN)
                $richTextK6 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldK6 = $richTextK6->createTextRun('SECCIÓN: ');
                $boldK6->getFont()->setBold(true);
                $boldK6->getFont()->setName('Times New Roman');
                $normalK6 = $richTextK6->createTextRun($this->aula->seccion->nombre); // Este texto no tendrá negrita
                $normalK6->getFont()->setName('Times New Roman');
                $sheet->setCellValue('K6', $richTextK6);
                
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
                $sheet->mergeCells('A7:C7');
                // Usamos formato de texto enriquecido para la celda A7
                $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $bold = $richText->createTextRun('PROVINCIA: ');
                $bold->getFont()->setBold(true);
                $bold->getFont()->setName('Times New Roman');
                $normal = $richText->createTextRun('TALARA'); // Este texto no tendrá negrita
                $normal->getFont()->setName('Times New Roman');
                $sheet->setCellValue('A7', $richText);
                // Aplicamos formato de texto enriquecido a D7 (LUGAR)
                $richTextD7 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldD7 = $richTextD7->createTextRun('LUGAR: ');
                $boldD7->getFont()->setBold(true);
                $boldD7->getFont()->setName('Times New Roman');
                $normalD7 = $richTextD7->createTextRun('TALARA'); // Este texto no tendrá negrita
                $normalD7->getFont()->setName('Times New Roman');
                $sheet->setCellValue('D7', $richTextD7);
                
                $sheet->mergeCells('E7:J7');
                // Aplicamos formato de texto enriquecido a E7 (TURNO)
                $richTextE7 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldE7 = $richTextE7->createTextRun('TURNO: ');
                $boldE7->getFont()->setBold(true);
                $boldE7->getFont()->setName('Times New Roman');
                $normalE7 = $richTextE7->createTextRun('DIURNO'); // Este texto no tendrá negrita
                $normalE7->getFont()->setName('Times New Roman');
                $sheet->setCellValue('E7', $richTextE7);
                
                $sheet->mergeCells('K7:Q7');
                // Aplicamos formato de texto enriquecido a K7 (GRADO)
                $richTextK7 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldK7 = $richTextK7->createTextRun('GRADO: ');
                $boldK7->getFont()->setBold(true);
                $boldK7->getFont()->setName('Times New Roman');
                $normalK7 = $richTextK7->createTextRun(strtoupper($this->aula->grado->nombre)); // Este texto no tendrá negrita
                $normalK7->getFont()->setName('Times New Roman');
                $sheet->setCellValue('K7', $richTextK7);
                
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
                $sheet->mergeCells('A8:D8');
                // Aplicamos formato de texto enriquecido a A8 (SUBREGIÓN)
                $richTextA8 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldA8 = $richTextA8->createTextRun('SUBREGIÓN: ');
                $boldA8->getFont()->setBold(true);
                $boldA8->getFont()->setName('Times New Roman');
                $normalA8 = $richTextA8->createTextRun('LUCIANO CASTILLO COLONNA'); // Este texto no tendrá negrita
                $normalA8->getFont()->setName('Times New Roman');
                $sheet->setCellValue('A8', $richTextA8);
                
                $sheet->mergeCells('E8:J8');
                // Aplicamos formato de texto enriquecido a E8 (EVALUACIÓN)
                $richTextE8 = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
                $boldE8 = $richTextE8->createTextRun('EVALUACIÓN: ');
                $boldE8->getFont()->setBold(true);
                $boldE8->getFont()->setName('Times New Roman');
                $normalE8 = $richTextE8->createTextRun('FINAL'); // Este texto no tendrá negrita
                $normalE8->getFont()->setName('Times New Roman');
                $sheet->setCellValue('E8', $richTextE8);
                $sheet->mergeCells('K8:Q8');
                
                // Aplicar estilo a las etiquetas
                $sheet->getStyle('A8')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                $sheet->getStyle('E8')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                // Combinar celdas en la fila 9
                $sheet->mergeCells('A9:C9');
                $sheet->mergeCells('E9:J9');
                $sheet->mergeCells('K9:Q9');
                
                // Combinar celdas en la fila 10
                $sheet->mergeCells('A10:C10');
                
                // Combinar celdas en la fila 11
                $sheet->mergeCells('A11:Q11');
                
                // Fila 10: Tipo de evaluación
                $sheet->mergeCells('E10:Q10');
                $sheet->setCellValue('E10', '(FINAL - DE APLAZADOS - DE COMPLEMENTACIÓN)');
                $sheet->getStyle('E10')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Asegurar que todas las celdas usen Times New Roman
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->getFont()->setName('Times New Roman');
                
                // Procesar estudiantes con observaciones
                $dataStartRow = 15; // La fila donde comienzan los datos
                
                // Recorrer los estudiantes con observaciones
                foreach ($this->estudiantesConObservaciones as $estudianteData) {
                    $rowIndex = $estudianteData['rowIndex'];
                    $observacion = $estudianteData['observacion'];
                    $excelRowIndex = $rowIndex + $dataStartRow;
                    
                    // Calcular el rango de columnas a combinar
                    $startCol = 'E'; // Columna donde comienzan las asignaturas
                    $endCol = $highestColumn; // Última columna (situación final)
                    
                    // Combinar las celdas
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
