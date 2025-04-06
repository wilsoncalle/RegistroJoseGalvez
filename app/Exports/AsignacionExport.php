<?php

namespace App\Exports;

use App\Models\Asignacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AsignacionExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $asignaciones;
    protected $filtroNivel;
    protected $nombreNivel;

    public function __construct($asignaciones, $filtroNivel, $nombreNivel)
    {
        $this->asignaciones = $asignaciones;
        $this->filtroNivel = $filtroNivel;
        $this->nombreNivel = $nombreNivel;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Preparar los datos para el Excel
        $data = [];

        // Fila 1: Título
        $data[] = ['Listado de asignaciones (Nivel ' . $this->nombreNivel . ')'];
        // Fila 2: Encabezados de la tabla
        $data[] = ['N°', 'Docente', 'Materia', 'Nivel', 'Aula', 'Año Académico'];
        // Filas 3 en adelante: Datos
        $counter = 1;
        foreach ($this->asignaciones as $asignacion) {
            $data[] = [
                $counter++,
                $asignacion->docente_apellido . ', ' . $asignacion->docente_nombre,
                $asignacion->materia_nombre,
                $asignacion->nivel_nombre,
                $asignacion->aula_nombre,
                $asignacion->anio_academico
            ];
        }

        return collect($data);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Asignaciones';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // No necesitamos definir encabezados aquí porque ya los incluimos en el método collection
        return [];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Estilo para el título
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Estilo para los encabezados
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E9ECEF'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // N°
            'B' => 30,  // Docente
            'C' => 25,  // Materia
            'D' => 15,  // Nivel
            'E' => 20,  // Aula
            'F' => 15,  // Año Académico
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Fusionar celdas para el título
                $sheet->mergeCells('A1:' . $highestColumn . '1');

                // Aplicar bordes a todas las celdas con datos
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);

                // Autofilter para los encabezados
                $sheet->setAutoFilter('A2:' . $highestColumn . '2');

                // Ajustar el alto de la fila del título
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Ajustar el alto de la fila de encabezados
                $sheet->getRowDimension(2)->setRowHeight(20);

                // Centrar verticalmente todas las celdas
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // Establecer el color de fondo para filas alternas (a partir de la fila 3)
                for ($row = 3; $row <= $highestRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8F9FA'],
                            ],
                        ]);
                    }

                    // Centrar columnas específicas: N° (columna A)
                    $sheet->getStyle("A3:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
