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
            ->whereIn('id_asignacion', $asignaciones->pluck('id_asignacion'))
            ->get();
            
        // Preparar estructura de datos que coincida con la combinación de celdas
        // Los datos se estructuran considerando que las celdas se combinarán en el evento AfterSheet
        foreach ($estudiantes as $index => $estudiante) {
            $currentRowIndex = count($this->data); // Índice de la fila actual
            
            // Verificar si hay una observación para este estudiante
            $calificacionConObservacion = $calificaciones->first(function ($cal) use ($estudiante) {
                return $cal->id_estudiante == $estudiante->id_estudiante && 
                       !empty($cal->observacion);
            });

            $tieneObservacion = (bool) $calificacionConObservacion;
            $observacion = $tieneObservacion ? $calificacionConObservacion->observacion : '';

            // Crear la fila de datos
            $row = [];
            
            // Columnas A-B: N° Orden (se combinarán)
            $row[] = $index + 1; // A
            $row[] = ''; // B (se combinará con A)
            
            // Columnas C-D: N° Matrícula (se combinarán)
            $row[] = $estudiante->codigo ?? ''; // C
            $row[] = ''; // D (se combinará con C)
            
            // Columnas E-F: Condición (se combinarán)
            $row[] = $estudiante->condicion ?? 'Regular'; // E
            $row[] = ''; // F (se combinará con E)
            
            // Columnas G-N: Apellidos y Nombres (se combinarán)
            $row[] = $estudiante->apellido . ', ' . $estudiante->nombre; // G
            for ($i = 0; $i < 7; $i++) { // H-N
                $row[] = '';
            }

            if (!$tieneObservacion) {
                // Si no tiene observación, procesar normalmente las asignaturas
                $desaprobadas = 0;
                
                // Notas por asignatura (columnas O en adelante)
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
                    // Verificar si el estudiante está trasladado
                    if ($estudiante->estado === 'Trasladado') {
                        $situacion = 'TRA';
                    } else if ($desaprobadas === 0) {
                        $situacion = 'P'; // Promovido (0 cursos desaprobados)
                    } else if ($desaprobadas >= 1 && $desaprobadas <= 3) {
                        $situacion = 'A'; // Aplazado (1 a 3 cursos desaprobados)
                    } else {
                        $situacion = 'R'; // Reprobado (4 o más cursos desaprobados)
                    }
                }
                
                $row[] = $situacion;
            } else {
                // Si tiene observación, las celdas de asignaturas se combinarán para mostrar la observación
                // Agregar celdas vacías para cada asignatura y las columnas finales
                $totalColumnasAsignaturas = count($asignaciones) + 3; // asignaturas + comportamiento + desaprobadas + situación
                for ($i = 0; $i < $totalColumnasAsignaturas; $i++) {
                    $row[] = ''; // Estas celdas se combinarán y mostrarán la observación
                }
                
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
        return 'A26'; // Comenzamos en A26 para los datos
    }

    public function styles(Worksheet $sheet)
    {
        // Los estilos se aplicarán en el evento AfterSheet para tener mejor control
        return [];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Calcular la última columna basada en la cantidad de asignaturas
                $materiasCount = count($this->asignaturas);
                $totalColumns = 14 + $materiasCount + 3; // 14 columnas base + asignaturas + 3 columnas finales
                $lastColumn = Coordinate::stringFromColumnIndex($totalColumns);
                $lastDataRow = $sheet->getHighestRow();
                
                // ========== ENCABEZADOS Y ESTRUCTURA DEL DOCUMENTO ==========
                
                // Formato para las filas 1-10
                // Bloque superior para la imagen (A1:E4)
                $sheet->mergeCells('A1:E4'); // Espacio para la imagen
                
                // Insertar la imagen del Gran Sello de la República del Perú
                $drawing = new Drawing();
                $drawing->setName('Gran Sello');
                $drawing->setDescription('Gran Sello de la República del Perú');
                $drawing->setPath(public_path('img/Gran_Sello_de_la_República_del_Perú.png'));
                $drawing->setCoordinates('B1');
                $drawing->setOffsetX(20);
                $drawing->setOffsetY(5);
                $drawing->setWidth(200);
                $drawing->setHeight(75);
                $drawing->setWorksheet($sheet);
                
                // Ministerio de Educación (F1:V2)
                $sheet->mergeCells('F1:' . $lastColumn . '2');
                $sheet->setCellValue('F1', 'MINISTERIO DE EDUCACIÓN');
                $sheet->getStyle('F1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_BOTTOM],
                ]);
                
                // Educación Secundaria (F3:V4)
                $sheet->mergeCells('F3:' . $lastColumn . '4');
                $sheet->setCellValue('F3', 'EDUCACIÓN SECUNDARIA');
                $sheet->getStyle('F3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_TOP],
                ]);
                
                // Ajustar altura de filas 1-4
                for ($i = 1; $i <= 4; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(16);
                }
                
                // Información del centro educativo y datos administrativos (filas 5-11)
                $this->setupDocumentHeader($sheet, $lastColumn);
                
                // Título del documento (fila 12)
                $sheet->mergeCells('A12:' . $lastColumn . '12');
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
                
                // ========== ENCABEZADOS DE LA TABLA ==========
                
                // Configurar encabezados verticales principales (fila 13-25)
                $this->setupTableHeaders($sheet, $lastColumn, $materiasCount);
                
                // ========== DATOS DE ESTUDIANTES ==========
                
                // Combinar celdas para todos los estudiantes (desde fila 26)
                $this->mergeCellsForStudentData($sheet, $lastDataRow);
                
                // Procesar estudiantes con observaciones
                $this->processStudentsWithObservations($sheet, $lastColumn);
                
                // ========== ESTILOS GENERALES ==========
                
                // Aplicar estilos generales a toda la hoja
                $sheet->getStyle('A1:' . $lastColumn . $lastDataRow)->applyFromArray([
                            'font' => [
                                'name' => 'Times New Roman',
                        'size' => 12,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                            ],
                        ]);
                
                // Centrar columnas específicas
                $sheet->getStyle('A26:B' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C26:D' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E26:F' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Centrar las columnas de asignaturas y columnas finales
                $asignaturasStartCol = Coordinate::stringFromColumnIndex(15);
                $sheet->getStyle($asignaturasStartCol . '26:' . $lastColumn . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Ajustar el ancho de todas las columnas a 6
                for ($i = 1; $i <= $totalColumns; $i++) {
                    $colLetter = Coordinate::stringFromColumnIndex($i);
                    $sheet->getColumnDimension($colLetter)->setWidth(5.7)->setAutoSize(false);
                }

                // Ajustar el ancho de las columnas de asignaturas y columnas finales
                for ($i = 15; $i <= 14 + $materiasCount + 3; $i++) {
                    $colLetter = Coordinate::stringFromColumnIndex($i);
                    $sheet->getColumnDimension($colLetter)->setWidth(5.7)->setAutoSize(false);
                }

                // Configurar altura de encabezados
                $sheet->getRowDimension(13)->setRowHeight(15);
                $sheet->getRowDimension(14)->setRowHeight(15);
                $sheet->getRowDimension(15)->setRowHeight(15);
                $sheet->getRowDimension(16)->setRowHeight(15);
                $sheet->getRowDimension(17)->setRowHeight(15);
                $sheet->getRowDimension(18)->setRowHeight(15);
                $sheet->getRowDimension(19)->setRowHeight(15);
                $sheet->getRowDimension(20)->setRowHeight(15);
                $sheet->getRowDimension(21)->setRowHeight(15);
                $sheet->getRowDimension(22)->setRowHeight(15);
                $sheet->getRowDimension(23)->setRowHeight(15);
                $sheet->getRowDimension(24)->setRowHeight(15);
                $sheet->getRowDimension(25)->setRowHeight(15);

                // Agregar encabezados del resumen estadístico (fila 85)
                $sheet->mergeCells('A85:G85');
                $sheet->setCellValue('A85', 'RESUMEN ESTADISTICO');
                $sheet->getStyle('A85')->applyFromArray([
                        'font' => [
                            'bold' => true,
                        'size' => 12,
                            'name' => 'Times New Roman',
                        ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);

                $sheet->setCellValue('H85', 'Total');
                $sheet->getStyle('H85')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'name' => 'Times New Roman',
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                    ]);
                    
                $sheet->setCellValue('I85', '%');
                $sheet->getStyle('I85')->applyFromArray([
                        'font' => [
                            'bold' => true,
                        'size' => 12,
                            'name' => 'Times New Roman',
                        ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);

                $sheet->mergeCells('K85:P86');
                $sheet->setCellValue('K85', 'ASIGNATURA O LÍNEA DE ACCIÓN EDUCATIVA');
                $sheet->getStyle('K85')->applyFromArray([
                    'font' => [
                        'bold' => true,
                            'size' => 12,
                        'name' => 'Times New Roman',
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F6F5F0'],
                        ],
                    ]);
                    
                $sheet->mergeCells('Q85:W86');
                $sheet->setCellValue('Q85', 'NOMBRE DEL PROFESOR');
                $sheet->getStyle('Q85')->applyFromArray([
                        'font' => [
                            'bold' => true,
                        'size' => 12,
                            'name' => 'Times New Roman',
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F6F5F0'],
                        ],
                    ]);
                    
                $sheet->mergeCells('X85:AA86');
                $sheet->setCellValue('X85', 'FIRMA');
                $sheet->getStyle('X85')->applyFromArray([
                    'font' => [
                        'bold' => true,
                            'size' => 12,
                        'name' => 'Times New Roman',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                // Agregar datos de las asignaturas
                $row = 87;
                foreach ($this->asignaturas as $asignacion) {
                    $sheet->mergeCells('K' . $row . ':P' . $row);
                    $sheet->setCellValue('K' . $row, $asignacion->materia->nombre);
                    $sheet->getStyle('K' . $row)->applyFromArray([
                        'font' => [
                                'size' => 12,
                            'name' => 'Times New Roman',
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                    ]);

                    $sheet->mergeCells('Q' . $row . ':W' . $row);
                    $sheet->setCellValue('Q' . $row, $asignacion->docente ? $asignacion->docente->nombre . ' ' . $asignacion->docente->apellido : 'Sin asignar');
                    $sheet->getStyle('Q' . $row)->applyFromArray([
                        'font' => [
                                'size' => 12,
                            'name' => 'Times New Roman',
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                    ]);

                    $sheet->mergeCells('X' . $row . ':AA' . $row);
                    $sheet->getStyle('X' . $row)->applyFromArray([
                        'font' => [
                                'size' => 12,
                            'name' => 'Times New Roman',
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                    
                    $row++;
                }

                // Agregar datos del resumen estadístico
                $resumenData = [
                    ['Total Matriculados', 'total-matriculados'],
                    ['Retirados', 'total-retirados'],
                    ['Trasladados', 'total-trasladados'],
                    ['Promovidos', 'total-promovidos'],
                    ['Requiere Complementación Eval. Aplazados', 'total-aplazados'],
                    ['Permanecerán en el Grado. Repiten de Año', 'total-repitentes']
                ];

                $row = 86;
                foreach ($resumenData as $data) {
                    // Combinar celdas A:G para cada fila
                    $sheet->mergeCells('A' . $row . ':G' . $row);
                    $sheet->setCellValue('A' . $row, $data[0]);
                    $sheet->getStyle('A' . $row)->applyFromArray([
                        'font' => [
                            'size' => 12,
                            'name' => 'Times New Roman',
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                    
                    // Establecer valores en columnas H e I
                    $total = 0;
                    $porcentaje = 0;

                    // Obtener el total de estudiantes
                    $totalEstudiantes = count($this->data);

                    // Calcular totales según el tipo de dato
                    switch($data[1]) {
                        case 'total-matriculados':
                            $total = $totalEstudiantes;
                            $porcentaje = 100;
                            break;
                        case 'total-retirados':
                            // Contar estudiantes con observaciones que contengan palabras clave de "retirado"
                            $total = collect($this->estudiantesConObservaciones)->filter(function($estudianteData) {
                                $observacion = strtolower($estudianteData['observacion']);
                                return strpos($observacion, 'retirad') !== false || 
                                    strpos($observacion, 'retir') !== false ||
                                    strpos($observacion, 'abandono') !== false ||
                                    strpos($observacion, 'dej') !== false;
                            })->count();
                            
                            // También verificar en la situación final por si acaso
                            $totalSituacion = collect($this->data)->filter(function($row) {
                                return end($row) === 'RET';
                            })->count();
                            
                            $total = max($total, $totalSituacion); // Tomar el mayor
                            $porcentaje = $totalEstudiantes > 0 ? ($total / $totalEstudiantes) * 100 : 0;
                            break;
                            
                        case 'total-trasladados':
                            // Contar estudiantes con observaciones que contengan palabras clave de "trasladado"
                            $total = collect($this->estudiantesConObservaciones)->filter(function($estudianteData) {
                                $observacion = strtolower($estudianteData['observacion']);
                                return strpos($observacion, 'traslad') !== false || 
                                    strpos($observacion, 'cambio') !== false ||
                                    strpos($observacion, 'otra instituci') !== false ||
                                    strpos($observacion, 'otro colegio') !== false;
                            })->count();
                            
                            // También verificar en la situación final y estado del estudiante
                            $totalSituacion = collect($this->data)->filter(function($row) {
                                return end($row) === 'TRA';
                            })->count();
                            
                            // Verificar estudiantes con estado "Trasladado" directamente
                            $estudiantesTrasladados = Estudiante::where('id_aula', $this->aulaId)
                                ->where('estado', 'Trasladado')
                                ->whereIn('id_estudiante', collect($this->data)->map(function($row, $index) {
                                    // Obtener el ID del estudiante basado en el índice de la fila
                                    return Estudiante::where('id_aula', $this->aulaId)
                                        ->orderBy('apellido')
                                        ->skip($index)
                                        ->first()
                                        ->id_estudiante ?? null;
                                })->filter())
                                ->count();
                            
                            $total = max($total, $totalSituacion, $estudiantesTrasladados); // Tomar el mayor
                            $porcentaje = $totalEstudiantes > 0 ? ($total / $totalEstudiantes) * 100 : 0;
                            break;
                            
                        case 'total-promovidos':
                            $total = collect($this->data)->filter(function($row) {
                                return end($row) === 'P';
                            })->count();
                            $porcentaje = $totalEstudiantes > 0 ? ($total / $totalEstudiantes) * 100 : 0;
                            break;
                            
                        case 'total-aplazados':
                            $total = collect($this->data)->filter(function($row) {
                                return end($row) === 'A';
                            })->count();
                            $porcentaje = $totalEstudiantes > 0 ? ($total / $totalEstudiantes) * 100 : 0;
                            break;
                            
                        case 'total-repitentes':
                            $total = collect($this->data)->filter(function($row) {
                                return end($row) === 'R';
                            })->count();
                            $porcentaje = $totalEstudiantes > 0 ? ($total / $totalEstudiantes) * 100 : 0;
                            break;
                    }

                    $sheet->setCellValue('H' . $row, $total);
                    $sheet->setCellValue('I' . $row, number_format($porcentaje, 1));
                    
                    // Aplicar estilos a las celdas de total y porcentaje
                    $sheet->getStyle('H' . $row . ':I' . $row)->applyFromArray([
                        'font' => [
                            'name' => 'Times New Roman',
                            'size' => 12,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    // Aplicar bordes a las celdas
                    $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                    $row++;
                }

                // Aplicar bordes a la fila de encabezados (85)
                $sheet->getStyle('A85:I85')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $sheet->getStyle('k85:AA96')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $sheet->getStyle('A93:I93')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $sheet->getStyle('A98:I98')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $sheet->getStyle('A102:I102')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Agregar sección de fecha y firmas
                $row = 93;
                
                // Fecha
                $sheet->mergeCells('A' . $row . ':B' . $row);
                $sheet->setCellValue('A' . $row, 'FECHA');
                $sheet->getStyle('A' . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'name' => 'Times New Roman',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('C' . $row . ':I' . $row);
                $sheet->setCellValue('C' . $row, 'Talara, ' . date('d') . ' de ' . date('F') . ' del ' . $this->año);
                $sheet->getStyle('C' . $row)->applyFromArray([
                    'font' => [
                            'size' => 12,
                        'name' => 'Times New Roman',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // Espacio para firma del director
                $sheet->mergeCells('A' . ($row + 3) . ':I' . ($row + 4));
                $sheet->mergeCells('A' . ($row + 5) . ':I' . ($row + 5));
                $sheet->setCellValue('A' . ($row + 5), 'DIRECTOR DEL CENTRO EDUCATIVO');
                $sheet->getStyle('A' . ($row + 5))->applyFromArray([
                    'font' => [
                        'bold' => true,
                    'size' => 12,
                        'name' => 'Times New Roman',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
             

                // Espacio para firma del subdirector
                $sheet->mergeCells('A' . ($row + 7) . ':I' . ($row + 8));
                $sheet->mergeCells('A' . ($row + 9) . ':I' . ($row + 9));
                $sheet->setCellValue('A' . ($row + 9), 'SUB - DIRECTOR');
                $sheet->getStyle('A' . ($row + 9))->applyFromArray([
                    'font' => [
                        'bold' => true,
                    'size' => 12,
                        'name' => 'Times New Roman',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);

                // Aplicar bordes a las celdas de firma
                $sheet->getStyle('A' . ($row + 3) . ':I' . ($row + 4))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->getStyle('A' . ($row + 7) . ':I' . ($row + 8))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
    
    private function setupDocumentHeader($sheet, $lastColumn)
    {
                // Fila 5: Centro Educativo y Dirección Departamental
                $sheet->mergeCells('A5:E5');
                $sheet->setCellValue('A5', 'CENTRO EDUCATIVO');
                $sheet->getStyle('A5')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);

                $sheet->mergeCells('F5:L5');
                $sheet->setCellValue('F5', 'Nº 15510 AMPLIACIÓN SECUNDARIA');
                $sheet->getStyle('F5')->getFont()->setName('Times New Roman');

                $sheet->mergeCells('M5:T5');
                $sheet->setCellValue('M5', 'DIRECCIÓN DEPARTAMENTAL ZONAL');
                $sheet->getStyle('M5')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);

                $sheet->mergeCells('U5:' . $lastColumn . '5');
                $sheet->setCellValue('U5', 'PIURA');
                $sheet->getStyle('U5')->getFont()->setName('Times New Roman');
                
        // Continuar con las demás filas del encabezado...
        // (Se mantiene el código original para las filas 6-11)
                
                // Fila 6: Departamento, Distrito, Año, Sección
                $sheet->mergeCells('A6:E6');
                $sheet->setCellValue('A6', 'DEPARTAMENTO');
                $sheet->getStyle('A6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('F6:G6');
                $sheet->setCellValue('F6', 'PIURA');
                $sheet->getStyle('F6')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('H6:I6');
                $sheet->setCellValue('H6', 'DISTRITO');
                $sheet->getStyle('H6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('J6:L6');
                $sheet->setCellValue('J6', 'PARIÑAS');
                $sheet->getStyle('J6')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('M6:O6');
                $sheet->setCellValue('M6', 'AÑO ESCOLAR');
                $sheet->getStyle('M6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('P6:Q6');
                $sheet->setCellValue('P6', $this->año);
                $sheet->getStyle('P6')->applyFromArray([
                    'font' => ['bold' => false, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                
                $sheet->mergeCells('R6:S6');
                $sheet->setCellValue('R6', 'SECCION');
                $sheet->getStyle('R6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('T6:' . $lastColumn . '6');
                $sheet->setCellValue('T6', $this->aula->seccion->nombre);
                $sheet->getStyle('T6')->getFont()->setName('Times New Roman');
                
        // Fila 7: Provincia, Lugar, Turno, Grado
        $sheet->mergeCells('A7:E7');
        $sheet->setCellValue('A7', 'PROVINCIA');
        $sheet->getStyle('A7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('F7:G7');
                $sheet->setCellValue('F7', 'TALARA');
                $sheet->getStyle('F7')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('H7:I7');
                $sheet->setCellValue('H7', 'LUGAR');
                $sheet->getStyle('H7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('J7:L7');
                $sheet->setCellValue('J7', 'TALARA');
                $sheet->getStyle('J7')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('M7:O7');
                $sheet->setCellValue('M7', 'TURNO');
                $sheet->getStyle('M7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('P7:Q7');
                $sheet->setCellValue('P7', 'DIURNO');
                $sheet->getStyle('P7')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('R7:S7');
                $sheet->setCellValue('R7', 'GRADO');
                $sheet->getStyle('R7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('T7:' . $lastColumn . '7');
                $sheet->setCellValue('T7', strtoupper($this->aula->grado->nombre));
                $sheet->getStyle('T7')->getFont()->setName('Times New Roman');
                
        // Fila 8: Subregión, Evaluación
        $sheet->mergeCells('A8:E8');
        $sheet->setCellValue('A8', 'SUBREGIÓN');
        $sheet->getStyle('A8')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('F8:L8');
                $sheet->setCellValue('F8', 'LUCIANO CASTILLO COLONNA');
                $sheet->getStyle('F8')->getFont()->setName('Times New Roman');
                
                $sheet->mergeCells('M8:O8');
                $sheet->setCellValue('M8', 'EVALUACIÓN');
                $sheet->getStyle('M8')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
                $sheet->mergeCells('P8:' . $lastColumn . '8');
                $sheet->setCellValue('P8', 'FINAL');
                $sheet->getStyle('P8')->getFont()->setName('Times New Roman');
                
        // Fila 9: Etiqueta de evaluación
                $sheet->mergeCells('A9:' . $lastColumn . '9');
                $sheet->setCellValue('A9', '(FINAL - DE APLAZADOS - DE COMPLEMENTACION)');
                $sheet->getStyle('A9')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F6F5F0'],
                    ],
                ]);
                
        // Filas 10 y 11 sin bordes
                $sheet->mergeCells('A10:' . $lastColumn . '10');
                $sheet->mergeCells('A11:' . $lastColumn . '11');
                
                // Eliminar bordes de las filas 10 y 11
                $noBorderStyle = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_NONE,
                        ],
                        'inside' => [
                            'borderStyle' => Border::BORDER_NONE,
                        ],
                        'left' => [
                            'borderStyle' => Border::BORDER_NONE,
                        ],
                        'right' => [
                            'borderStyle' => Border::BORDER_NONE,
                        ],
                        'top' => [
                            'borderStyle' => Border::BORDER_NONE,
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_NONE,
                        ],
                    ],
                ];
                
                $sheet->getStyle('A10:' . $lastColumn . '10')->applyFromArray($noBorderStyle);
                $sheet->getStyle('A11:' . $lastColumn . '11')->applyFromArray($noBorderStyle);

                // Fila 12 con bordes
                $sheet->getStyle('A12:' . $lastColumn . '12')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
    
    private function setupTableHeaders($sheet, $lastColumn, $materiasCount)
    {
        // Encabezados principales (fila 13)
        try {
            $sheet->mergeCells('A13:B25'); // N° Orden
            $sheet->mergeCells('C13:D25'); // N° Matrícula
            $sheet->mergeCells('E13:F25'); // Condición
            $sheet->mergeCells('G13:N25'); // Apellidos y Nombres
        } catch (\Exception $e) {
            // Si falla la combinación, continuamos
        }
        
        // Configurar encabezados principales
        $mainHeaders = [
            'A13' => 'N° Orden',
            'C13' => 'N° Matrícula',
            'E13' => 'Condición',
            'G13' => 'Apellidos y Nombres'
        ];
        
        foreach ($mainHeaders as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'],
                    'name' => 'Times New Roman',
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DDD9C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_BOTTOM,
                ],
            ]);
            
            // Aplicar rotación vertical (excepto para Apellidos y Nombres)
            if ($cell != 'G13') {
                $sheet->getStyle($cell)->getAlignment()->setTextRotation(90);
            }
        }
        
        // Encabezado de asignaturas
        $asignaturasStartCol = 'O';
        $asignaturasEndCol = Coordinate::stringFromColumnIndex(14 + $materiasCount);
        
        try {
            $sheet->mergeCells($asignaturasStartCol . '13:' . $asignaturasEndCol . '13');
            $sheet->setCellValue($asignaturasStartCol . '13', 'Asignaturas');
        } catch (\Exception $e) {
            // Si falla, aplicar individualmente
            for ($col = 15; $col <= 14 + $materiasCount; $col++) {
                $colLetter = Coordinate::stringFromColumnIndex($col);
                $sheet->setCellValue($colLetter . '13', 'Asignaturas');
            }
        }
        
        $sheet->getStyle($asignaturasStartCol . '13')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
                'name' => 'Times New Roman',
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DDD9C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_BOTTOM,
            ],
        ]);
        
        // Configurar encabezados finales
        $materiasEndIndex = 14 + $materiasCount;
        $comportamientoCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 1);
        $desaprobadasCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 2);
        $situacionCol = Coordinate::stringFromColumnIndex($materiasEndIndex + 3);
        
        $finalHeaders = [
            $comportamientoCol => 'Comportamiento',
            $desaprobadasCol => 'N° asignaturas desaprobadas',
            $situacionCol => 'Situación Final'
        ];
        
        foreach ($finalHeaders as $col => $text) {
            try {
                $sheet->mergeCells($col . '13:' . $col . '25');
            } catch (\Exception $e) {
                // Continuar si falla
            }
            
            $sheet->setCellValue($col . '13', $text);
            $sheet->getStyle($col . '13')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'],
                    'name' => 'Times New Roman',
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DDD9C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_BOTTOM,
                    'textRotation' => 90,
                ],
            ]);
        }
        
        // Configurar altura de encabezados
        $sheet->getRowDimension(14)->setRowHeight(170);
        
        // Agregar nombres de materias (fila 14)
        $colIndex = 15;
        foreach ($this->asignaturas as $asignacion) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            
            try {
                $sheet->mergeCells($colLetter . '14:' . $colLetter . '25');
                    } catch (\Exception $e) {
                // Continuar si falla
            }
            
            $sheet->setCellValue($colLetter . '14', $asignacion->materia->nombre);
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
                    'vertical' => Alignment::VERTICAL_BOTTOM,
                    'textRotation' => 90,
                    'wrapText' => true,
                ],
            ]);
            
            $colIndex++;
        }
    }
    
    private function mergeCellsForStudentData($sheet, $lastDataRow)
    {
        // Combinar celdas para todos los estudiantes (desde fila 26)
        for ($row = 26; $row <= $lastDataRow; $row++) {
            try {
                $sheet->mergeCells('A' . $row . ':B' . $row); // N° Orden
                $sheet->mergeCells('C' . $row . ':D' . $row); // N° Matrícula
                $sheet->mergeCells('E' . $row . ':F' . $row); // Condición
                $sheet->mergeCells('G' . $row . ':N' . $row); // Apellidos y Nombres
            } catch (\Exception $e) {
                // Continuar si falla la combinación
            }
        }
    }
    
    private function processStudentsWithObservations($sheet, $lastColumn)
    {
        $dataStartRow = 26;
        
                foreach ($this->estudiantesConObservaciones as $estudianteData) {
                    $rowIndex = $estudianteData['rowIndex'];
                    $observacion = $estudianteData['observacion'];
                    $excelRowIndex = $rowIndex + $dataStartRow;
                    
            // Calcular el rango de columnas para las observaciones (desde O hasta la última columna)
            $startCol = 'O';
            $endCol = $lastColumn;
            
            try {
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
                        'name' => 'Times New Roman',
                        'size' => 12,
                        ],
                    ]);
            } catch (\Exception $e) {
                // Si falla la combinación, al menos establecer el valor en la primera celda
                $sheet->setCellValue($startCol . $excelRowIndex, $observacion);
            }
        }
    }
    
    private function adjustColumnWidths($sheet, $materiasCount)
    {
        // Ajustar anchos de columnas principales
        for ($col = 'A'; $col <= 'N'; $col++) {
            $sheet->getColumnDimension($col)->setWidth(5)->setAutoSize(false);
        }
        
        // Ajustar anchos de columnas de asignaturas
        for ($i = 15; $i <= 14 + $materiasCount + 3; $i++) {
            $colLetter = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setWidth(7)->setAutoSize(false);
        }
    }
    //Quien diria que esta mierda me iba a tomar mas tiempo del necesario, peru bueno por fin acabo este modulo
    //Hoy debia ser un gran dia, pero no, mi chica decidio que el acuerdo que teniamos de vernos hoy al final no se va a hace
    //No se que le sucede, pero bueno, supongo que dormire toda la tarde, mejor.
}