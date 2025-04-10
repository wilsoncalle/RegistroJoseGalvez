<?php

namespace App\Exports;

use App\Models\Materia;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MateriaExport implements FromArray, WithTitle, WithStyles, ShouldAutoSize, WithColumnWidths, WithEvents
{
    protected $materias;
    protected $filtroNivel;
    protected $nombreNivel;

    public function __construct($materias, $filtroNivel = null, $nombreNivel = 'General')
    {
        $this->materias = $materias;
        $this->filtroNivel = $filtroNivel;
        $this->nombreNivel = $nombreNivel;
    }

    /**
     * Genera el arreglo que representa el reporte.
     *
     * @return array
     */
    public function array(): array
    {
        $data = [];
        // Fila 1: Título
        $data[] = ['Listado de materias (Nivel ' . $this->nombreNivel . ')'];
        // Fila 2: Encabezados de la tabla
        $data[] = ['N°', 'Nombre', 'Nivel'];
        // Filas 3 en adelante: Datos
        $counter = 1;
        foreach ($this->materias as $materia) {
            $data[] = [
                $counter,
                $materia->nombre,
                $materia->nivel ? $materia->nivel->nombre : 'No especificado'
            ];
            $counter++;
        }
        return $data;
    }

    /**
     * Define el título de la hoja.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Materias';
    }

    /**
     * Personaliza los estilos base de la hoja.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
         // Estilo para la fila de encabezados (fila 2)
         $sheet->getStyle('A2:C2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'], // Texto blanco
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'], // Fondo azul
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Borde negro
                ],
            ],
        ]);
        return [];
    }

    /**
     * Define los anchos específicos de las columnas.
     *
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // N°
            'B' => 40,  // Nombre
            'C' => 25,  // Nivel
        ];
    }

    /**
     * Registra eventos para formatear la hoja después de generarla.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $lastColumn = 'C';
                $highestRow = $sheet->getHighestRow();

                // --- 1. Título Dinámico ---
                // Fusiona celdas para el título (fila 1) y aplica estilos
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->setCellValue('A1', 'Listado de materias (Nivel ' . $this->nombreNivel . ')');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // --- 2. Encabezados ---
                // Se asume que la fila 2 contiene los encabezados
                $sheet->getStyle('A2:C2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F81BD'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // --- 3. Estilizar Filas de Datos ---
                if ($highestRow >= 3) {
                    $sheet->getStyle("A3:{$lastColumn}{$highestRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'B0B0B0'],
                            ],
                        ],
                        'alignment' => [
                            'vertical'   => Alignment::VERTICAL_CENTER,
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                        ],
                    ]);

                    // Centrar columnas específicas: N° (columna A)
                    $sheet->getStyle("A3:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // --- 4. Aplicar Autofiltro ---
                $sheet->setAutoFilter("A2:{$lastColumn}2");
            },
        ];
    }
}
