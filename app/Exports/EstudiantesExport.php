<?php

namespace App\Exports;

use App\Models\Estudiante;
use App\Models\Aula;
use App\Models\Nivel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB si usas \DB::raw

class EstudiantesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithColumnWidths, WithCustomStartCell
{
    protected $busqueda;
    protected $filtroAula;
    protected $filtroEstado;
    protected $filtroNivel;
    protected $counter = 0; // Contador para el n° de orden

    public function __construct($busqueda = null, $filtroAula = null, $filtroEstado = null, $filtroNivel = null)
    {
        $this->busqueda = $busqueda;
        $this->filtroAula = $filtroAula;
        $this->filtroEstado = $filtroEstado;
        $this->filtroNivel = $filtroNivel;
    }

    /**
     * Obtiene la colección de estudiantes filtrados y ordenados.
     */
    public function collection()
    {
        // Consulta base con relaciones necesarias
        $query = Estudiante::with(['aula.nivel', 'aula.grado', 'aula.seccion', 'apoderados']);

        // Aplicar filtro de búsqueda si se proporciona
        if ($this->busqueda) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', "%{$this->busqueda}%")
                  ->orWhere('apellido', 'like', "%{$this->busqueda}%")
                  ->orWhere('dni', 'like', "%{$this->busqueda}%");
            });
        }

        // Aplicar filtro de aula si se proporciona
        if ($this->filtroAula) {
            $query->where('estudiantes.id_aula', $this->filtroAula);
        }

        // Aplicar filtro de estado si se proporciona
        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        // Aplicar filtro de nivel si se proporciona
        if ($this->filtroNivel) {
            $query->whereHas('aula', function ($q) {
                $q->where('aulas.id_nivel', $this->filtroNivel);
            });
        }

        // Realizar joins para ordenar y seleccionar el nombre completo del aula
        $query->join('aulas', 'estudiantes.id_aula', '=', 'aulas.id_aula')
              ->join('niveles', 'aulas.id_nivel', '=', 'niveles.id_nivel')
              ->join('grados', 'aulas.id_grado', '=', 'grados.id_grado')
              ->leftJoin('secciones', 'aulas.id_seccion', '=', 'secciones.id_seccion')
              // Seleccionar datos de estudiantes y construir el nombre completo del aula
              ->select('estudiantes.*', DB::raw("CONCAT(grados.nombre, ' - ', IFNULL(secciones.nombre, '')) as nombre_completo_aula"))
              // Ordenar resultados por nivel (orden específico), nombre del aula y apellido del estudiante
              ->orderByRaw("FIELD(niveles.nombre, 'Inicial', 'Primaria', 'Secundaria')")
              ->orderBy('nombre_completo_aula', 'asc')
              ->orderBy('estudiantes.apellido', 'asc');

        return $query->get();
    }

    /**
     * Define los encabezados para el archivo Excel.
     * Se inicia en la fila 2, dejando la fila 1 para el título dinámico.
     */
    public function headings(): array
    {
        return [
            'N°',               // A
            'Nombre Completo',  // B
            'DNI',              // C
            'Edad',             // D
            'Teléfono',         // E
            'Apoderado',        // F (cambiado de posición)
            'Nivel',            // G (ahora después de Apoderado)
            'Aula',             // H
            'Fecha de Ingreso', // I
            'Estado',           // J
        ];
    }

    /**
     * Mapea los datos para cada fila.
     * Concatena nombre y apellido, calcula edad, formatea la fecha, etc.
     */
    public function map($estudiante): array
    {
        $this->counter++; // Incrementar contador de filas

        // Calcular edad a partir de la fecha de nacimiento
        $edad = $estudiante->fecha_nacimiento ? Carbon::parse($estudiante->fecha_nacimiento)->age : '';

        // Obtener información del apoderado principal si existe
        $apoderadoInfo = 'Sin apoderado';
        if ($estudiante->apoderados->isNotEmpty()) {
            $apoderado = $estudiante->apoderados->first(); // Se asume que el primero es el principal
            $apoderadoInfo = $apoderado->nombre . ($apoderado->relacion ? ' (' . $apoderado->relacion . ')' : '');
        }

        return [
            $this->counter,
            $estudiante->nombre . ' ' . $estudiante->apellido,
            $estudiante->dni,
            $edad,
            $estudiante->telefono,
            $apoderadoInfo, // Ahora en la columna F
            // Acceso seguro al nombre del nivel (columna G)
            optional(optional($estudiante->aula)->nivel)->nombre ?? 'No asignado',
            // Nombre completo del aula (columna H)
            $estudiante->nombre_completo_aula ?? 'No asignada',
            // Formatear la fecha de ingreso (columna I)
            $estudiante->fecha_ingreso ? Carbon::parse($estudiante->fecha_ingreso)->format('Y-m-d') : '',
            $estudiante->estado, // Columna J
        ];
    }

    /**
     * Define la celda de inicio para los datos (encabezados).
     */
    public function startCell(): string
    {
        return 'A2'; // Los datos comienzan debajo de la fila del título
    }

    /**
     * Define los anchos específicos de las columnas.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // N°
            'B' => 30,  // Nombre Completo
            'C' => 15,  // DNI
            'D' => 8,   // Edad
            'E' => 15,  // Teléfono
            'F' => 30,  // Apoderado (ahora columna F)
            'G' => 15,  // Nivel (ahora columna G)
            'H' => 20,  // Aula (ahora columna H)
            'I' => 18,  // Fecha de Ingreso
            'J' => 12,  // Estado
        ];
    }

    /**
     * Aplica estilos básicos a la hoja (aplicados antes de los eventos).
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para la fila de encabezados (fila 2)
        $sheet->getStyle('A2:J2')->applyFromArray([
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

        // Retornar array vacío ya que los estilos se aplican directamente
        return [];
    }

    /**
     * Registra los eventos a ejecutar después de crear la hoja.
     * Se utiliza para asignar el título, estilos finales y agregar los autofiltros.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet       = $event->sheet->getDelegate();
                $lastColumn  = 'J'; // Última columna (para el título)
                $highestRow  = $sheet->getHighestRow(); // Última fila con datos

                // --- 1. Construcción Dinámica del Título ---
                $titulo = "Listado de estudiantes";
                $filtrosAplicados = [];

                if ($this->filtroEstado) {
                    $estadoDesc = ucfirst(strtolower($this->filtroEstado));
                    if (!in_array(substr($estadoDesc, -1), ['s', 'x', 'z'])) {
                        $estadoDesc .= 's';
                    }
                    $filtrosAplicados[] = $estadoDesc;
                }

                if ($this->filtroNivel) {
                    $nivel = Nivel::find($this->filtroNivel);
                    if ($nivel) {
                        $filtrosAplicados[] = "Nivel " . $nivel->nombre;
                    }
                }

                if ($this->filtroAula) {
                    $aula = Aula::with(['grado', 'seccion'])->find($this->filtroAula);
                    if ($aula) {
                        $aulaDesc    = optional($aula->grado)->nombre ?? '';
                        $seccionDesc = optional($aula->seccion)->nombre ?? '';
                        if ($seccionDesc) {
                            $aulaDesc .= ' "' . $seccionDesc . '"';
                        }
                        $filtrosAplicados[] = " " . trim($aulaDesc); //Entre los parentesis se agrega el aula, se a omitido por que me parecia que no se veia bien
                    }
                }

                if (!empty($filtrosAplicados)) {
                    $titulo .= " (" . implode(' - ', $filtrosAplicados) . ")";
                } else {
                    $titulo = "Listado completo de estudiantes";
                }

                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->setCellValue('A1', $titulo);
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // --- 2. Estilizar Filas de Datos ---
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
                            'wrapText'   => false,
                        ],
                    ]);

                    // Centrar columnas: N° (A), DNI (C) y Edad (D)
                    $sheet->getStyle("A3:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("C3:C{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D3:D{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    // Centrar Estado (J)
                    $sheet->getStyle("J3:J{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    // Ajuste de texto para Nombre Completo (B)
                    $sheet->getStyle("B3:B{$highestRow}")->getAlignment()->setWrapText(true);
                    // Ajuste de texto para Apoderado (F) ahora en columna F
                    $sheet->getStyle("F3:F{$highestRow}")->getAlignment()->setWrapText(true);
                }

                // --- 3. Agregar Autofiltros según filtros aplicados ---
                $columnsFilter = [];
                // Para filtro de Nivel: ahora corresponde a la columna G
                if (!$this->filtroNivel) {
                    $columnsFilter[] = 'G';
                }
                // Para filtro de Aula: corresponde a la columna H
                if (!$this->filtroAula) {
                    $columnsFilter[] = 'H';
                }
                // Para filtro de Estado: corresponde a la columna J
                if (!$this->filtroEstado) {
                    $columnsFilter[] = 'J';
                }

                if (!empty($columnsFilter)) {
                    sort($columnsFilter);
                    $firstColumn = $columnsFilter[0];
                    $lastColumnFilter = end($columnsFilter);
                    // Se aplica el autofiltro en la fila de encabezados (fila 2)
                    $filterRange = "{$firstColumn}2:{$lastColumnFilter}2";
                    $sheet->setAutoFilter($filterRange);
                }
            },
        ];
    }
}
