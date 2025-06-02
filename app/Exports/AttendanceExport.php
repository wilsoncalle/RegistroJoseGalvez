<?php

namespace App\Exports;

use App\Models\Aula;
use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Materia;
use App\Models\Docente;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Traits\SpanishSorting;

class AttendanceExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithCustomStartCell, ShouldAutoSize, WithColumnWidths, WithEvents
{
    use SpanishSorting;
    protected $aula;
    protected $materia;
    protected $docente;
    protected $mes;
    protected $año;
    protected $startCell = 'A7'; // Empezamos en la fila 7 para dejar espacio para el título y los filtros
    protected $weeks = [];
    protected $schoolDays = [];
    protected $firstDay;
    protected $lastDay;
    protected $students;
    protected $attendances;
    protected $headerRowCount = 3; // Número de filas del encabezado de la tabla
    protected $attendanceMap = [
        'P' => 'P', // Presente
        'T' => 'T', // Tardanza
        'F' => 'F', // Ausente
        'J' => 'J'  // Justificado
    ];

    /**
     * Constructor
     */
    public function __construct(int $aulaId, int $materiaId, int $docenteId, int $mes, int $año)
    {
        $this->aula = Aula::with(['nivel', 'grado', 'seccion'])->findOrFail($aulaId);
        $this->materia = Materia::findOrFail($materiaId);
        $this->docente = Docente::findOrFail($docenteId);
        $this->mes = $mes;
        $this->año = $año;
        
        $this->firstDay = Carbon::create($año, $mes, 1);
        $this->lastDay = $this->firstDay->copy()->endOfMonth();
        
        $this->prepareSchoolDays();
        $this->prepareWeeks();
        $this->loadStudents();
        $this->loadAttendances();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $mesNombre = Carbon::create($this->año, $this->mes, 1)->translatedFormat('F');
        return "Asistencia {$mesNombre} {$this->año}";
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return $this->startCell;
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10, // N° Orden
            'B' => 30, // Apellidos y Nombres
        ];
    }

    /**
     * Prepara los días escolares (lunes a viernes) del mes
     */
    private function prepareSchoolDays()
    {
        for ($day = 1; $day <= $this->lastDay->day; $day++) {
            $date = Carbon::create($this->año, $this->mes, $day);
            $dayOfWeek = $date->dayOfWeek;
            
            // Solo días de lunes a viernes (1-5)
            if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                $initial = '';
                switch($dayOfWeek) {
                    case 1: $initial = 'L'; break;
                    case 2: $initial = 'M'; break;
                    case 3: $initial = 'M'; break;
                    case 4: $initial = 'J'; break;
                    case 5: $initial = 'V'; break;
                }
                
                $this->schoolDays[] = [
                    'day' => $day,
                    'initial' => $initial,
                    'dayOfWeek' => $dayOfWeek,
                    'date' => $date
                ];
            }
        }
    }

    /**
     * Agrupa los días escolares en semanas
     */
    private function prepareWeeks()
    {
        $currentWeek = [];
        
        foreach ($this->schoolDays as $day) {
            if ($day['dayOfWeek'] === 1 && !empty($currentWeek)) {
                $this->weeks[] = $currentWeek;
                $currentWeek = [];
            }
            $currentWeek[] = $day;
        }
        
        if (!empty($currentWeek)) {
            $this->weeks[] = $currentWeek;
        }
    }

    /**
     * Carga los estudiantes del aula
     */
    private function loadStudents()
    {
        // Usar el método del trait SpanishSorting para obtener estudiantes ordenados correctamente
        // respetando acentos en el orden alfabético español (A, Á, B, C, Ç...)
        $this->students = $this->getStudentsWithSpanishSorting($this->aula->id_aula, 'Activo');
    }

    /**
     * Carga las asistencias para el mes y año seleccionados
     */
    private function loadAttendances()
    {
        $asignacion = Asignacion::where('id_aula', $this->aula->id_aula)
            ->where('id_materia', $this->materia->id_materia)
            ->where('id_docente', $this->docente->id_docente)
            ->first();
            
        if (!$asignacion) {
            throw new \Exception('No se encontró la asignación correspondiente');
        }
        
        $this->attendances = Asistencia::where('id_asignacion', $asignacion->id_asignacion)
            ->whereRaw("strftime('%Y-%m-%d', fecha) BETWEEN ? AND ?", [
                $this->firstDay->format('Y-m-d'),
                $this->lastDay->format('Y-m-d')
            ])
            ->get()
            ->groupBy('id_estudiante');
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $rows = [];
        $orden = 1;
        
        // Agregar filas para cada estudiante
        foreach ($this->students as $estudiante) {
            $row = [
                $orden++, // N° Orden incrementado secuencialmente
                $estudiante->apellido . ' ' . $estudiante->nombre, // Apellidos y Nombres
            ];
            
            $monthlyStats = [
                'P' => 0,
                'T' => 0,
                'F' => 0,
                'J' => 0
            ];
            
            // Agregar columnas para cada día escolar
            foreach ($this->schoolDays as $schoolDay) {
                $day = $schoolDay['day'];
                $date = $schoolDay['date']->format('Y-m-d');
                
                $attendanceRecord = $this->attendances
                    ->get($estudiante->id_estudiante, collect())
                    ->first(function($record) use ($date) {
                        return Carbon::parse($record->fecha)->format('Y-m-d') === $date;
                    });
                
                $status = $attendanceRecord ? $this->mapFullNameToEstado($attendanceRecord->estado) : '';
                $row[] = $status;
                
                if ($status) {
                    $monthlyStats[$status]++;
                }
            }
            
            // Agregar totales
            $row[] = "P:{$monthlyStats['P']} T:{$monthlyStats['T']} F:{$monthlyStats['F']} J:{$monthlyStats['J']}";
            
            $rows[] = $row;
        }
        
        return collect($rows);
    }

    /**
     * Mapea el nombre completo del estado a su abreviación
     */
    private function mapFullNameToEstado($estado)
    {
        $estadoMap = [
            'Presente' => 'P',
            'Tardanza' => 'T',
            'Ausente' => 'F',
            'Justificado' => 'J'
        ];

        return $estadoMap[$estado] ?? 'F';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Primera fila: Semanas
        $row1 = ['N° Orden', 'Apellidos y Nombres'];
        
        foreach ($this->weeks as $index => $week) {
            $row1[] = "Semana " . ($index + 1);
            
            // Agregar celdas vacías para los demás días de la semana
            for ($i = 1; $i < count($week); $i++) {
                $row1[] = '';
            }
        }
        
        // Agregar la columna de totales
        $row1[] = 'Totales';
        
        // Segunda fila: Iniciales de días
        $row2 = ['', ''];
        
        foreach ($this->schoolDays as $day) {
            $row2[] = $day['initial'];
        }
        
        // Agregar celda vacía para la columna de totales
        $row2[] = '';
        
        // Tercera fila: Números de días
        $row3 = ['', ''];
        
        foreach ($this->schoolDays as $day) {
            $row3[] = $day['day'];
        }
        
        // Agregar celda vacía para la columna de totales
        $row3[] = '';
        
        return [$row1, $row2, $row3];
    }
    
    /**
     * Aplica estilos a la hoja
     */
    public function styles(Worksheet $sheet)
    {
        // Calcular la última columna
        $lastColumn = Coordinate::stringFromColumnIndex(2 + count($this->schoolDays) + 1); // +1 por la columna de totales
        
        // Estilo de cabecera
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', "Control de Asistencia - {$this->aula->nivel->nombre} {$this->aula->grado->nombre} \"{$this->aula->seccion->nombre}\"");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Filtros aplicados
        $sheet->mergeCells("A3:{$lastColumn}3");
        $mesNombre = Carbon::create($this->año, $this->mes, 1)->translatedFormat('F');
        $sheet->setCellValue('A3', "Materia: {$this->materia->nombre} | Docente: {$this->docente->nombre} {$this->docente->apellido} | Mes: {$mesNombre} | Año: {$this->año}");
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Estilo de las cabeceras de la tabla
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DDDDDD'],
            ],
        ];
        
        // Aplicar estilo a las cabeceras
        $headerRange = 'A' . $this->getStartRow() . ':' . $lastColumn . ($this->getStartRow() + $this->headerRowCount - 1);
        $sheet->getStyle($headerRange)->applyFromArray($headerStyle);
        
        // Estilo para las celdas de datos
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        
        // Aplicar estilo a los datos
        $dataRange = 'A' . ($this->getStartRow() + $this->headerRowCount) . ':' . $lastColumn . ($this->getStartRow() + $this->headerRowCount + count($this->students) - 1);
        $sheet->getStyle($dataRange)->applyFromArray($dataStyle);
        
        // Alineación a la izquierda para nombres
        $namesRange = 'B' . ($this->getStartRow() + $this->headerRowCount) . ':B' . ($this->getStartRow() + $this->headerRowCount + count($this->students) - 1);
        $sheet->getStyle($namesRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        
        return [];
    }

    /**
     * Obtiene la fila inicial para los datos
     */
    private function getStartRow(): int
    {
        // Extraer el componente numérico de la coordenada y convertirlo a int
        $coordParts = Coordinate::coordinateFromString($this->startCell);
        return (int)$coordParts[1]; // Convertir a entero para asegurar el tipo correcto
    }

    /**
     * Eventos para la hoja
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Calcular la última columna y fila
                $lastColumn = Coordinate::stringFromColumnIndex(2 + count($this->schoolDays) + 1);
                $headerStartRow = $this->getStartRow();
                
                // Combinar celdas para N° Orden y Apellidos y Nombres
                $sheet->mergeCells("A{$headerStartRow}:A" . ($headerStartRow + 2));
                $sheet->mergeCells("B{$headerStartRow}:B" . ($headerStartRow + 2));
                
                // Ajustar las celdas para indicador de semana
                $columnIndex = 3; // Empezamos en la columna C (índice 3)
                
                foreach ($this->weeks as $week) {
                    $weekStartCol = Coordinate::stringFromColumnIndex($columnIndex);
                    $weekEndCol = Coordinate::stringFromColumnIndex($columnIndex + count($week) - 1);
                    
                    // Combinar las celdas para la semana
                    $sheet->mergeCells("{$weekStartCol}{$headerStartRow}:{$weekEndCol}{$headerStartRow}");
                    
                    // Aplicar estilo para encabezado de semana
                    $sheet->getStyle("{$weekStartCol}{$headerStartRow}:{$weekEndCol}{$headerStartRow}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '000000'],
                        ],
                        'font' => [
                            'color' => ['rgb' => 'FFFFFF'],
                            'bold' => true,
                        ],
                    ]);
                    
                    // Aplicar estilo para iniciales de días y números
                    $sheet->getStyle("{$weekStartCol}" . ($headerStartRow + 1) . ":{$weekEndCol}" . ($headerStartRow + 2))->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'CCCCCC'],
                        ],
                    ]);
                    
                    $columnIndex += count($week);
                }
                
                // Combinar celdas para la columna de totales
                $sheet->mergeCells("{$lastColumn}{$headerStartRow}:{$lastColumn}" . ($headerStartRow + 2));
                
                // Aplicar estilo para la columna de totales
                $sheet->getStyle("{$lastColumn}{$headerStartRow}:{$lastColumn}" . ($headerStartRow + 2))->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'AFDCEC'],
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);
                
                // Colorear celdas de asistencia según su valor
                $this->colorAttendanceCells($sheet);
            },
        ];
    }
    
    /**
     * Colorea las celdas de asistencia según su valor
     */
    private function colorAttendanceCells(Worksheet $sheet)
    {
        $startRow = $this->getStartRow() + $this->headerRowCount;
        $startCol = 3; // Columna C
        
        for ($row = 0; $row < count($this->students); $row++) {
            for ($col = 0; $col < count($this->schoolDays); $col++) {
                $colLetter = Coordinate::stringFromColumnIndex($startCol + $col);
                $cellCoord = $colLetter . ($startRow + $row);
                $value = $sheet->getCell($cellCoord)->getValue();
                
                if ($value === 'P') {
                    $sheet->getStyle($cellCoord)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'C6EFCE'], // Verde claro
                        ],
                    ]);
                } else if ($value === 'T') {
                    $sheet->getStyle($cellCoord)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFEB9C'], // Amarillo claro
                        ],
                    ]);
                } else if ($value === 'F') {
                    $sheet->getStyle($cellCoord)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFC7CE'], // Rojo claro
                        ],
                    ]);
                } else if ($value === 'J') {
                    $sheet->getStyle($cellCoord)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'BDD7EE'], // Azul claro
                        ],
                    ]);
                }
            }
        }
    }

    /**
     * Obtiene la información del aula
     */
    public function getAula()
    {
        return $this->aula;
    }

    /**
     * Obtiene la información de la materia
     */
    public function getMateria()
    {
        return $this->materia;
    }

    /**
     * Obtiene la información del docente
     */
    public function getDocente()
    {
        return $this->docente;
    }

    /**
     * Obtiene las semanas
     */
    public function getWeeks()
    {
        return $this->weeks;
    }

    /**
     * Obtiene los días escolares
     */
    public function getSchoolDays()
    {
        return $this->schoolDays;
    }
}