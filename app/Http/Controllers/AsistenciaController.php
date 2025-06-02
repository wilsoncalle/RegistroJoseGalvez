<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Aula;
use App\Models\Nivel;
use App\Models\Estudiante;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Services\ExportService;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Traits\SpanishSorting;


class AsistenciaController extends Controller
{
    use SpanishSorting;
    public function index(): View
    {
        $niveles = Nivel::all();
        return view('asistencias.index', compact('niveles'));
    }

    public function indexNivel(string $nivel): View
{
    $nivelModel = Nivel::where('nombre', $nivel)->firstOrFail();
    $aulas = Aula::with(['nivel', 'grado', 'seccion'])
        ->where('id_nivel', $nivelModel->id_nivel)
        ->get()
        ->sortBy(function($aula) {
            $gradoNombre = $aula->grado ? $aula->grado->nombre : '';
            $seccionNombre = $aula->seccion ? $aula->seccion->nombre : '';
            return $gradoNombre . ' ' . $seccionNombre;
        });

    // Extraer los grados únicos de las aulas para el filtro
    $grados = $aulas->pluck('grado')->unique('id_grado')->sortBy('nombre');

    return view('asistencias.index-nivel', compact('aulas', 'nivel', 'grados'));
}


    public function create(Request $request): View
    {
        $aula = Aula::with(['nivel', 'grado', 'seccion'])->findOrFail($request->id_aula);
        $materias = Materia::whereHas('asignaciones', function($query) use ($aula) {
            $query->where('id_aula', $aula->id_aula);
        })->get();

        // Usar el método del trait SpanishSorting para obtener estudiantes ordenados correctamente
        // respetando acentos en el orden alfabético español (A, Á, B, C, Ç...)
        $estudiantes = $this->getStudentsWithSpanishSorting($aula->id_aula);

        return view('asistencias.create', compact('aula', 'materias', 'estudiantes'));
    }

    private function normalizeString($string) {
        return iconv('UTF-8', 'ASCII//TRANSLIT', mb_strtolower($string, 'UTF-8'));
    }

    public function getDocentesPorMateria($materiaId, $aulaId) {
        try {
       
    
            // Find docentes (teachers) associated with the specific subject and classroom
            $docentes = Docente::whereHas('asignaciones', function ($query) use ($materiaId, $aulaId) {
                $query->where('id_materia', $materiaId)
                      ->where('id_aula', $aulaId);
            })->select('id_docente', 'nombre', 'apellido')->get();
    
            // Throw an exception if no teachers are found
            if ($docentes->isEmpty()) {
                throw new \Exception('No se encontraron docentes para esta materia y aula');
            }
    
            // Return the found teachers as a JSON response
            return response()->json($docentes);
    
        } catch (\Exception $e) {

            // Return an error response
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'id_aula' => 'required|exists:aulas,id_aula',
        'id_materia' => 'required|exists:materias,id_materia',
        'id_docente' => 'required|exists:docentes,id_docente',
        'fecha' => 'required|date',
        'asistencias' => 'required|array',
        'asistencias.*' => 'required|in:P,T,F,J',
    ]);

    try {
        DB::beginTransaction();

        // Find the specific asignacion
        $asignacion = Asignacion::where('id_aula', $request->id_aula)
            ->where('id_materia', $request->id_materia)
            ->where('id_docente', $request->id_docente)
            ->first();

        if (!$asignacion) {
            throw new \Exception('No se encontró la asignación correspondiente');
        }

        // Check for existing attendance records using id_asignacion
        $existingAttendance = Asistencia::where('fecha', $request->fecha)
            ->where('id_asignacion', $asignacion->id_asignacion)
            ->first();

        if ($existingAttendance) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Ya existe un registro de asistencia para esta asignación y fecha.');
        }

        // Prepare bulk insert data
        $attendanceData = [];
        foreach ($request->asistencias as $estudianteId => $estado) {
            $attendanceData[] = [
                'id_estudiante' => $estudianteId,
                'id_asignacion' => $asignacion->id_asignacion,
                'fecha' => $request->fecha,
                'estado' => $this->mapEstadoToFullName($estado),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        // Bulk insert for better performance
        Asistencia::insert($attendanceData);
        $aula = Aula::find($request->id_aula);


        DB::commit();
        return redirect()->route('asistencias.index-niveles', $aula->nivel->nombre)
            ->with('success', 'Asistencia registrada correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al registrar asistencias: ' . $e->getMessage());
        return back()->withInput()
            ->with('error', 'Error al registrar asistencia: ' . $e->getMessage());
    }
}

// Helper method to map short states to full names
private function mapEstadoToFullName($estado)
{
    $estadoMap = [
        'P' => 'Presente',
        'T' => 'Tardanza',
        'F' => 'Ausente',
        'J' => 'Justificado'
    ];

    return $estadoMap[$estado] ?? 'Ausente';
}
public function show(Request $request, string $nivel, int $aulaId): View
{
    $aula = Aula::with(['nivel', 'grado', 'seccion'])
        ->findOrFail($aulaId);

    $materias = Materia::whereHas('asignaciones', function($query) use ($aulaId) {
        $query->where('id_aula', $aulaId);
    })->get();

    // Usar el método del trait SpanishSorting para obtener estudiantes ordenados correctamente
    // respetando acentos en el orden alfabético español (A, Á, B, C, Ç...)
    $estudiantes = $this->getStudentsWithSpanishSorting($aulaId);

    return view('asistencias.show', compact('aula', 'nivel', 'materias', 'estudiantes'));
}
/**
 * Exportar asistencia a Excel
 */
public function exportToExcel(Request $request)
{
    $request->validate([
        'id_aula' => 'required|exists:aulas,id_aula',
        'id_materia' => 'required|exists:materias,id_materia',
        'id_docente' => 'required|exists:docentes,id_docente',
        'mes' => 'required|integer|between:1,12',
        'año' => 'required|integer|min:2000'
    ]);
    
    $exportService = new ExportService();
    
    return $exportService->exportAttendanceToExcel(
        $request->id_aula,
        $request->id_materia,
        $request->id_docente,
        $request->mes,
        $request->año
    );
    
}

/**
 * Exportar asistencia a PDF
 */
public function exportToPdf(Request $request)
{
    $request->validate([
        'id_aula' => 'required|exists:aulas,id_aula',
        'id_materia' => 'required|exists:materias,id_materia',
        'id_docente' => 'required|exists:docentes,id_docente',
        'mes' => 'required|integer|between:1,12',
        'año' => 'required|integer|min:2000'
    ]);
    
    $exportService = new ExportService();
    
    return $exportService->exportAttendanceToPdf(
        $request->id_aula,
        $request->id_materia,
        $request->id_docente,
        $request->mes,
        $request->año
    );
}

public function getAttendanceDetails(Request $request): JsonResponse
{
    $request->validate([
        'id_aula' => 'required|exists:aulas,id_aula',
        'id_materia' => 'required|exists:materias,id_materia',
        'id_docente' => 'required|exists:docentes,id_docente',
        'mes' => 'required|integer|between:1,12',
        'año' => 'required|integer|min:2000'
    ]);

    try {
        // Get the date range for the selected month and year using Carbon
        $primerDia = Carbon::create($request->año, $request->mes, 1)->startOfDay();
        $ultimoDia = Carbon::create($request->año, $request->mes, 1)->endOfMonth();

        // Find the specific asignacion
        $asignacion = Asignacion::where('id_aula', $request->id_aula)
            ->where('id_materia', $request->id_materia)
            ->where('id_docente', $request->id_docente)
            ->first();

        if (!$asignacion) {
            throw new \Exception('No se encontró la asignación correspondiente');
        }

        // Get all attendance records for this assignment in the specified month
        $attendances = Asistencia::where('id_asignacion', $asignacion->id_asignacion)
            ->whereRaw("strftime('%Y-%m-%d', fecha) BETWEEN ? AND ?", [
                $primerDia->format('Y-m-d'),
                $ultimoDia->format('Y-m-d')
            ])
            ->get()
            ->groupBy('id_estudiante');

        // Prepare the data to return
        $attendanceDetails = [];
        $studentStats = [];

        $estudiantes = Estudiante::where('id_aula', $request->id_aula)
            ->where('estado', 'Activo')
            ->get();

        foreach ($estudiantes as $estudiante) {
            $studentAttendance = $attendances->get($estudiante->id_estudiante, collect());
            
            $monthlyStats = [
                'P' => 0, // Presente
                'T' => 0, // Tardanza
                'F' => 0, // Ausente
                'J' => 0  // Justificado
            ];

            $dailyAttendance = [];
            for ($day = 1; $day <= $ultimoDia->day; $day++) {
                $currentDate = Carbon::create($request->año, $request->mes, $day);
                $attendanceRecord = $studentAttendance->first(function($record) use ($currentDate) {
                    return Carbon::parse($record->fecha)->format('Y-m-d') === $currentDate->format('Y-m-d');
                });
                
                $status = $attendanceRecord ? $this->mapFullNameToEstado($attendanceRecord->estado) : null;
                $dailyAttendance[$day] = $status;

                if ($status) {
                    $monthlyStats[$status]++;
                }
            }

            $studentStats[$estudiante->id_estudiante] = [
                'nombre' => $estudiante->apellido . ' ' . $estudiante->nombre,
                'daily_attendance' => $dailyAttendance,
                'monthly_stats' => $monthlyStats
            ];
        }

        return response()->json([
            'attendance_details' => $studentStats,
            'total_days' => $ultimoDia->day
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 500);
    }
}

// Helper method to map full names to short states (reverse of previous method)
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

    public function updateAttendance(Request $request): JsonResponse
    {
        $request->validate([
            'id_aula' => 'required|exists:aulas,id_aula',
            'id_materia' => 'required|exists:materias,id_materia',
            'id_docente' => 'required|exists:docentes,id_docente',
            'mes' => 'required|integer|between:1,12',
            'año' => 'required|integer|min:2000',
            'modificaciones' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            // Encontrar la asignación correspondiente
            $asignacion = Asignacion::where('id_aula', $request->id_aula)
                ->where('id_materia', $request->id_materia)
                ->where('id_docente', $request->id_docente)
                ->first();

            if (!$asignacion) {
                throw new \Exception('No se encontró la asignación correspondiente');
            }

            // Obtener el rango de fechas para el mes y año seleccionados
            $primerDia = Carbon::create($request->año, $request->mes, 1)->startOfDay();
            $ultimoDia = Carbon::create($request->año, $request->mes, 1)->endOfMonth();

            // Procesar cada modificación
            foreach ($request->modificaciones as $estudianteId => $fechas) {
                foreach ($fechas as $dia => $estado) {
                    // Crear la fecha completa usando Carbon
                    $fecha = Carbon::create($request->año, $request->mes, $dia)->format('Y-m-d');

                    // Buscar si ya existe un registro para esta fecha, estudiante y asignación
                    $asistencia = Asistencia::where('id_estudiante', $estudianteId)
                        ->where('id_asignacion', $asignacion->id_asignacion)
                        ->whereRaw("strftime('%Y-%m-%d', fecha) = ?", [$fecha])
                        ->first();

                    // Mapear el estado corto al nombre completo
                    $estadoCompleto = $this->mapEstadoToFullName($estado);

                    if ($asistencia) {
                        // Actualizar el registro existente
                        $asistencia->estado = $estadoCompleto;
                        $asistencia->save();
                    } else {
                        // Crear un nuevo registro
                        Asistencia::create([
                            'id_estudiante' => $estudianteId,
                            'id_asignacion' => $asignacion->id_asignacion,
                            'fecha' => $fecha,
                            'estado' => $estadoCompleto
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar asistencias: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}