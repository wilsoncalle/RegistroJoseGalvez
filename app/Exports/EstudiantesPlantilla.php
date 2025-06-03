<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Aula;
use Illuminate\Support\Facades\Log;

class EstudiantesPlantilla implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $aula = null;
    protected $fechaIngreso;
    
    /**
     * Constructor
     * 
     * @param int|null $idAula ID del aula seleccionada
     * @param string|null $fechaIngreso Fecha de ingreso seleccionada
     */
    public function __construct($idAula = null, $fechaIngreso = null)
    {
        // Cargar el aula con sus relaciones si existe
        if ($idAula) {
            try {
                $this->aula = Aula::with(['nivel', 'grado', 'seccion'])->findOrFail($idAula);
            } catch (\Exception $e) {
                // En caso de que no se encuentre el aula, dejamos el valor en null
                // y registramos el error (opcional)
                Log::warning("No se pudo cargar el aula ID: $idAula - " . $e->getMessage());
            }
        }
        
        // Establecer la fecha de ingreso usando Carbon
        $this->fechaIngreso = $fechaIngreso ? Carbon::parse($fechaIngreso) : Carbon::now();
    }
    
    /**
     * @return array
     */
    public function array(): array
    {
        $rows = [];
        
        // Aseguramos que haya 54 filas en total (3 para encabezados + 1 vacía + 50 para datos)
        // Las primeras 4 filas se usan para los encabezados y la fila vacía
        for ($i = -3; $i <= 50; $i++) {
            if ($i <= 0) {
                // Las primeras 4 filas se dejan totalmente vacías para los encabezados
                $rows[] = ['', '', '', '', '', ''];
            } else {
                // A partir de la fila 5, mostramos la numeración empezando por 1
                $rows[] = [($i - 0), '', '', '', '', ''];
            }
        }
        
        return $rows;
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return 'Plantilla de Estudiantes';
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        // Columnas con ancho de 5 como solicitado
        $columnWidths = [];
        
        // Establecer el ancho de todas las columnas A-Z a 5
        foreach (range('A', 'Z') as $column) {
            $columnWidths[$column] = 6;
        }
        
        return $columnWidths;
    }

    /**
     * No usamos headings convencionales ya que serán personalizados en el método styles
     * @return array
     */
    public function headings(): array
    {
        return []; // Los encabezados se crean manualmente en el método styles
    }
    
    /**
     * Define los eventos de la hoja de cálculo
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Configurar fuente predeterminada Times New Roman 12
                $event->sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
            },
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
         // Configurar la fuente predeterminada a Times New Roman tamaño 12
         $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
         $sheet->getParent()->getDefaultStyle()->getFont()->setSize(12);
         
        // Color de fondo para encabezados
        $headerBackground = 'F2F2F2';
        
        // Obtener información del aula (si está disponible)
        $gradoText = '';
        $seccionText = '';
        if ($this->aula) {
            $gradoText = $this->aula->grado ? $this->aula->grado->nombre : '';
            $seccionText = $this->aula->seccion ? $this->aula->seccion->nombre : '';
        }
        
        // Obtener el año académico a partir de la fecha de ingreso
        // Este enfoque garantiza consistencia con el método de importación
        $anioEscolar = $this->fechaIngreso->format('Y');

        // ===== FILA 1 =====
        // Nombre del centro educativo
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'CENTRO CREATIVO');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true],
            //'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // Número del centro educativo
        $sheet->mergeCells('G1:P1');
        $sheet->setCellValue('G1', 'Nº 15510 JOSÉ GALVEZ EGÚSQUIZA');
        $sheet->getStyle('G1')->applyFromArray([
            //'font' => ['bold' => true],
            //'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            //'fill' => [
            //    'fillType' => Fill::FILL_SOLID,
            //    'startColor' => ['rgb' => $headerBackground]
            //],
        ]);
        
        // Título de registro de estudiantes
        $sheet->mergeCells('S1:Z2');
        $sheet->setCellValue('S1', 'REGISTRO DE ESTUDIANTES');
        $sheet->getStyle('S1:Z2')->applyFromArray([
           'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);

        // ===== FILA 2 =====
        // Año escolar
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'AÑO ESCOLAR');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true],
            //'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // Valor del año
        $sheet->mergeCells('E2:F2');
        $sheet->setCellValue('E2', $anioEscolar);
        $sheet->getStyle('E2')->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
        
        // Grado
        $sheet->mergeCells('G2:H2');
        $sheet->setCellValue('G2', 'GRADO:');
        $sheet->getStyle('G2')->applyFromArray([
            'font' => ['bold' => true],
            //'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // Valor del grado
        $sheet->mergeCells('I2:K2');
        $sheet->setCellValue('I2', $gradoText);
        $sheet->getStyle('I2')->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
        
        // Sección
        $sheet->mergeCells('L2:N2');
        $sheet->setCellValue('L2', 'SECCION:');
        $sheet->getStyle('L2')->applyFromArray([
            'font' => ['bold' => true],
            //'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // Valor de la sección
        $sheet->mergeCells('O2:P2');
        $sheet->setCellValue('O2', $seccionText);
        $sheet->getStyle('O2')->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
        
        // ===== FILA 3 =====
        // Combinar toda la fila 3
        $sheet->mergeCells('A3:P3');
        
        // ===== FILA 4 (Encabezados de tabla) =====
        // Número
        $sheet->setCellValue('A4', 'N°');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // Nombre
        $sheet->mergeCells('B4:E4');
        $sheet->setCellValue('B4', 'Nombre');
        $sheet->getStyle('B4:E4')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // Apellido
        $sheet->mergeCells('F4:I4');
        $sheet->setCellValue('F4', 'Apellido');
        $sheet->getStyle('F4:I4')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // DNI
        $sheet->mergeCells('J4:K4');
        $sheet->setCellValue('J4', 'DNI');
        $sheet->getStyle('J4:K4')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // Fecha de Nacimiento
        $sheet->mergeCells('L4:N4');
        $sheet->setCellValue('L4', 'Fecha Nacimiento');
        $sheet->getStyle('L4:N4')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // Teléfono
        $sheet->mergeCells('O4:P4');
        $sheet->setCellValue('O4', 'Teléfono');
        $sheet->getStyle('O4:P4')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // ===== CAMPOS REQUERIDOS Y SECCIÓN DE INSTRUCCIONES =====
        // Título - Campos Requeridos
        $sheet->mergeCells('S4:Z4');
        $sheet->setCellValue('S4', 'CAMPOS REQUERIDOS:');
        $sheet->getStyle('S4')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // Detalle - Campos requeridos
        $sheet->mergeCells('S5:Z5');
        $sheet->setCellValue('S5', 'Nombres y Apellidos');
        
        // Título - Instrucciones
        $sheet->mergeCells('S6:Z6');
        $sheet->setCellValue('S6', 'INSTRUCCIONES:');
        $sheet->getStyle('S6')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $headerBackground]
            ],
        ]);
        
        // Instrucciones
        $instrucciones = [
            '1. No modifique los encabezados.',
            '2. Complete una fila por estudiante.',
            '3. La fecha debe estar en formato DD/MM/AAAA',
            '4. El DNI debe tener 8 dígitos sin puntos ni guiones.',
            '5. Guarde el archivo antes de importarlo.'
        ];
        
        foreach ($instrucciones as $index => $instruccion) {
            $row = $index + 7;
            $sheet->mergeCells("S{$row}:Z{$row}");
            $sheet->setCellValue("S{$row}", $instruccion);
        }
        
        // ===== FORMATEAR DATOS =====
        // Combinar celdas para todas las filas debajo de los encabezados
        for ($i = 5; $i <= 54; $i++) { // 54 = 50 filas de datos + 4 filas de encabezados
            // Combinar celdas para Nombre, Apellido, DNI, Fecha de Nacimiento y Teléfono
            $sheet->mergeCells("B{$i}:E{$i}");
            $sheet->mergeCells("F{$i}:I{$i}");
            $sheet->mergeCells("J{$i}:K{$i}");
            $sheet->mergeCells("L{$i}:N{$i}");
            $sheet->mergeCells("O{$i}:P{$i}");
            
            // Alineación a la izquierda para los datos
            $sheet->getStyle("A{$i}:P{$i}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }
        
        // Bordes para todas las celdas
        $sheet->getStyle('A1:P54')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('S1:Z2')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('S4:Z11')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Auto-ajustar altura de filas para los encabezados
        for ($i = 1; $i <= 4; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
        }
    }
}
