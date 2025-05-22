<?php

namespace App\Imports;

use App\Models\Estudiante;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EstudiantesImport implements ToCollection, WithStartRow
{
    use Importable;

    protected $id_aula;
    protected $fecha_ingreso;
    protected $estado;
    protected $previewMode = false;
    protected $previewData = [];
    protected $rowCount = 0;
    
    public function __construct($id_aula = null, $fecha_ingreso = null, $estado = 'Activo')
    {
        $this->id_aula = $id_aula;
        $this->fecha_ingreso = $fecha_ingreso ?? now()->format('Y-m-d');
        $this->estado = $estado;
    }

    /**
     * Activa o desactiva el modo de vista previa
     *
     * @param bool $mode
     * @return self
     */
    public function setPreviewMode(bool $mode): self
    {
        $this->previewMode = $mode;
        return $this;
    }
    
    /**
     * Define la fila donde comienzan los datos (después de los encabezados)
     * Los encabezados están en la fila 4, así que los datos comienzan en la fila 5
     *
     * @return int
     */
    public function startRow(): int
    {
        return 5; // La fila 5 es donde comienzan los datos (después de los encabezados)
    }
    
    /**
     * Obtiene los datos de la vista previa
     *
     * @return array
     */
    public function getPreviewData(): array
    {
        return $this->previewData;
    }
    
    /**
     * Obtiene el número de filas procesadas
     *
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
    
    public function collection(Collection $rows)
    {
        $this->rowCount = $rows->filter(function ($row) {
            // Solo contar filas con datos
            return !empty($row[1]);
        })->count();
        
        // Si estamos en modo vista previa, solo almacenamos los datos sin crear registros
        if ($this->previewMode) {
            $this->previewData = $rows->filter(function ($row) {
                return !empty($row[1]); // Columna B - Nombre (índice 1)
            })->map(function ($row) {
                return [
                    'nombre' => $row[1] ?? '', // Columna B - Nombre (índice 1)
                    'apellido' => $row[5] ?? '', // Columna F - Apellido (índice 5)
                    'dni' => $row[9] ?? '', // Columna J - DNI (índice 9)
                    'fecha_nacimiento' => $row[11] ?? '', // Columna L - Fecha Nacimiento (índice 11)
                    'telefono' => $row[14] ?? '', // Columna O - Teléfono (índice 14)
                ];
            })->toArray();
            return;
        }
        
        // Filtrar filas que no tienen nombre
        $rowsWithData = $rows->filter(function ($row) {
            return !empty($row[1]); // Columna B - Nombre (índice 1)
        });
        
        // Validar los datos antes de procesarlos
        foreach ($rowsWithData as $index => $row) {
            $validator = Validator::make([
                'nombre' => $row[1] ?? '',
                'apellido' => $row[5] ?? '',
                'dni' => $row[9] ?? '',
                'fecha_nacimiento' => $row[11] ?? '',
                'telefono' => $row[14] ?? '',
            ], [
                'nombre' => 'required|string|max:50',
                'apellido' => 'required|string|max:50',
                'dni' => 'nullable|string|max:20',
                'fecha_nacimiento' => 'nullable|date',
                'telefono' => 'nullable|string|max:20',
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'apellido.required' => 'El apellido es obligatorio',
                'dni.max' => 'El DNI no debe exceder los 20 caracteres',
                'fecha_nacimiento.date' => 'La fecha de nacimiento debe tener un formato válido',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new \Exception(implode(', ', $errors) . ' en la fila ' . ($index + $this->startRow()));
            }
        }
        
        // Primero, validamos manualmente todos los DNIs para asegurar que no haya duplicados
        $dnis = $rowsWithData->map(function ($row) {
            return $row[9] ?? null; // Columna J - DNI (índice 9)
        })->filter()->toArray();
        
        $existingDnis = Estudiante::whereIn('dni', $dnis)->pluck('dni')->toArray();
        
        if (!empty($existingDnis)) {
            $message = 'Los siguientes DNIs ya existen en el sistema: ' . implode(', ', $existingDnis);
            throw new \Exception($message);
        }
        
        // Si pasamos la validación de DNI, procesamos todos los estudiantes
        foreach ($rowsWithData as $row) {
            Estudiante::create([
                'nombre' => $row[1] ?? '', // Columna B - Nombre (índice 1)
                'apellido' => $row[5] ?? '', // Columna F - Apellido (índice 5)
                'dni' => $row[9] ?? null, // Columna J - DNI (índice 9)
                'fecha_nacimiento' => !empty($row[11]) ? $row[11] : null, // Columna L - Fecha Nacimiento (índice 11)
                'telefono' => $row[14] ?? null, // Columna O - Teléfono (índice 14)
                'id_aula' => $this->id_aula,
                'fecha_ingreso' => $this->fecha_ingreso,
                'estado' => $this->estado,
            ]);
        }
    }
    
    // Ya no necesitamos los métodos de validación aquí porque hacemos la validación manualmente en el método collection
}
