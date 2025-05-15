<?php

namespace App\Http\Controllers;

use App\Models\CalificacionOld;
use App\Models\Estudiante;
use App\Models\Asignacion;
use App\Models\Trimestre;
use App\Models\Grado;
use App\Models\Aula;
use App\Models\Nivel;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ExportService;

class CalificacionOldController extends Controller
{
    /**
     * Mostrar listado de calificaciones
     */
    public function index(Request $request): View
    {
        $niveles = Nivel::all();
        return view('calificaciones-old.index', compact('niveles'));
    }

    /**
     * Mostrar listado de calificaciones por nivel
     */
    public function indexNivel(string $nivel): View
    {
        $nivelModel = Nivel::where('nombre', $nivel)->firstOrFail();
        $aulas = Aula::with(['nivel', 'grado', 'seccion'])
            ->where('id_nivel', $nivelModel->id_nivel)
            ->get()
            ->sortBy(function($aula) {
                return $aula->grado->nombre . ' ' . $aula->seccion->nombre;
            });

        // Extraer los grados únicos de las aulas para el filtro
        $grados = $aulas->pluck('grado')->unique('id_grado')->sortBy('nombre');

        return view('calificaciones-old.index-nivel', compact('aulas', 'nivel', 'grados'));
    }

    /**
     * Formulario para crear nuevas calificaciones
     */
    public function create(Request $request): View
    {
        $aula = Aula::with(['nivel', 'grado', 'seccion'])->findOrFail($request->id_aula);
        $asignaciones = Asignacion::with('materia', 'docente')
            ->where('id_aula', $aula->id_aula)
            ->get();
            
        $estudiantes = Estudiante::where('id_aula', $aula->id_aula)
            ->where('estado', 'Activo')
            ->orderBy('apellido')
            ->get();
            
        $trimestres = Trimestre::all();
        
        return view('calificaciones-old.create', compact('aula', 'asignaciones', 'estudiantes', 'trimestres'));
    }

    /**
     * Guardar nuevas calificaciones
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_estudiante' => 'required|exists:estudiantes,id_estudiante',
            'id_asignacion' => 'required|exists:asignaciones,id_asignacion',
            'id_trimestre' => 'required|exists:trimestres,id_trimestre',
            'comportamiento' => 'required|integer|min:0|max:20',
            'asignaturas_reprobadas' => 'required|integer|min:0',
            'conclusion' => 'required|string|max:50',
            'grado' => 'required|string|max:50',
            'nota' => 'required|numeric|min:0|max:20',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string|max:200',
        ]);

        try {
            DB::beginTransaction();

            // Verificar si ya existe una calificación para este estudiante, asignación y trimestre
            $calificacionExistente = CalificacionOld::where('id_estudiante', $request->id_estudiante)
                ->where('id_asignacion', $request->id_asignacion)
                ->where('id_trimestre', $request->id_trimestre)
                ->first();

            if ($calificacionExistente) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', 'Ya existe una calificación para este estudiante, asignación y trimestre.');
            }

            // Crear la calificación
            CalificacionOld::create($request->all());
            
            // Obtener aula para la redirección
            $estudiante = Estudiante::with('aula.nivel')->findOrFail($request->id_estudiante);
            $aula = $estudiante->aula;

            DB::commit();
            return redirect()->route('calificaciones-old.index-nivel', $aula->nivel->nombre)
                ->with('success', 'Calificación registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar calificación: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error al registrar calificación: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de calificaciones
     */
    public function show(Request $request, string $nivel, int $aulaId): View
    {
        $aula = Aula::with(['nivel', 'grado', 'seccion'])
            ->findOrFail($aulaId);
            
        $asignaciones = Asignacion::with('materia', 'docente')
            ->where('id_aula', $aulaId)
            ->get();

        $estudiantes = Estudiante::where('id_aula', $aulaId)
            ->where('estado', 'Activo')
            ->orderBy('apellido')
            ->get();
            
        $trimestres = Trimestre::all();

        return view('calificaciones-old.show', compact('aula', 'nivel', 'asignaciones', 'estudiantes', 'trimestres'));
    }

    /**
     * Formulario para editar calificación
     */
    public function edit(CalificacionOld $calificacionOld): View
    {
        $calificacionOld->load(['estudiante', 'asignacion', 'trimestre']);
        
        $estudiante = $calificacionOld->estudiante;
        $aula = $estudiante->aula;
        
        $asignaciones = Asignacion::with('materia', 'docente')
            ->where('id_aula', $aula->id_aula)
            ->get();
            
        $trimestres = Trimestre::all();
        
        return view('calificaciones-old.edit', compact('calificacionOld', 'asignaciones', 'trimestres'));
    }

    /**
     * Actualizar calificación
     */
    public function update(Request $request, CalificacionOld $calificacionOld): RedirectResponse
    {
        $request->validate([
            'id_asignacion' => 'required|exists:asignaciones,id_asignacion',
            'id_trimestre' => 'required|exists:trimestres,id_trimestre',
            'comportamiento' => 'required|integer|min:0|max:20',
            'asignaturas_reprobadas' => 'required|integer|min:0',
            'conclusion' => 'required|string|max:50',
            'grado' => 'required|string|max:50',
            'nota' => 'required|numeric|min:0|max:20',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string|max:200',
        ]);

        try {
            DB::beginTransaction();

            // Verificar si existe otra calificación con los mismos datos (excepto la actual)
            $calificacionExistente = CalificacionOld::where('id_estudiante', $calificacionOld->id_estudiante)
                ->where('id_asignacion', $request->id_asignacion)
                ->where('id_trimestre', $request->id_trimestre)
                ->where('id_calificacion', '!=', $calificacionOld->id_calificacion)
                ->first();

            if ($calificacionExistente) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', 'Ya existe otra calificación para este estudiante, asignación y trimestre.');
            }

            // Actualizar la calificación
            $calificacionOld->update($request->all());
            
            // Obtener aula para la redirección
            $estudiante = Estudiante::with('aula.nivel')->findOrFail($calificacionOld->id_estudiante);
            $aula = $estudiante->aula;

            DB::commit();
            return redirect()->route('calificaciones-old.index-nivel', $aula->nivel->nombre)
                ->with('success', 'Calificación actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar calificación: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error al actualizar calificación: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar calificación
     */
    public function destroy(CalificacionOld $calificacionOld): RedirectResponse
    {
        try {
            DB::beginTransaction();
            
            // Obtener aula para la redirección antes de eliminar
            $estudiante = Estudiante::with('aula.nivel')->findOrFail($calificacionOld->id_estudiante);
            $aula = $estudiante->aula;
            
            $calificacionOld->delete();
            
            DB::commit();
            return redirect()->route('calificaciones-old.index-nivel', $aula->nivel->nombre)
                ->with('success', 'Calificación eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar calificación: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar calificación: ' . $e->getMessage());
        }
    }

    /**
     * Obtener calificaciones por aula, asignación y trimestre
     */
    public function getCalificacionesPorAula(Request $request): JsonResponse
    {
        $request->validate([
            'id_aula' => 'required|exists:aulas,id_aula',
            'id_asignacion' => 'required|exists:asignaciones,id_asignacion',
            'id_trimestre' => 'required|exists:trimestres,id_trimestre',
        ]);

        try {
            $estudiantes = Estudiante::where('id_aula', $request->id_aula)
                ->where('estado', 'Activo')
                ->orderBy('apellido')
                ->get();
                
            $calificaciones = CalificacionOld::where('id_asignacion', $request->id_asignacion)
                ->where('id_trimestre', $request->id_trimestre)
                ->whereIn('id_estudiante', $estudiantes->pluck('id_estudiante'))
                ->get()
                ->keyBy('id_estudiante');
                
            $datosEstudiantes = [];
            
            foreach ($estudiantes as $estudiante) {
                $calificacion = $calificaciones->get($estudiante->id_estudiante);
                
                $datosEstudiantes[] = [
                    'id_estudiante' => $estudiante->id_estudiante,
                    'nombre_completo' => $estudiante->apellido . ', ' . $estudiante->nombre,
                    'tiene_calificacion' => !is_null($calificacion),
                    'calificacion' => $calificacion ? [
                        'id_calificacion' => $calificacion->id_calificacion,
                        'nota' => $calificacion->nota,
                        'comportamiento' => $calificacion->comportamiento,
                        'asignaturas_reprobadas' => $calificacion->asignaturas_reprobadas,
                        'conclusion' => $calificacion->conclusion,
                        'observacion' => $calificacion->observacion,
                    ] : null,
                ];
            }
            
            return response()->json([
                'success' => true,
                'datos' => $datosEstudiantes,
            ]);
        
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar calificaciones a Excel
     */
   /*  public function exportToExcel(Request $request)
    {
        $request->validate([
            'id_aula' => 'required|exists:aulas,id_aula',
            'id_asignacion' => 'nullable|exists:asignaciones,id_asignacion',
            'id_trimestre' => 'required|exists:trimestres,id_trimestre',
        ]);
        
        $exportService = new ExportService();
        
        return $exportService->exportCalificacionesOldToExcel(
            $request->id_aula,
     * Exportar calificaciones a PDF
     */
    public function exportToPdf(Request $request)
    {
        $request->validate([
            'id_aula' => 'required|exists:aulas,id_aula',
            'id_asignacion' => 'nullable|exists:asignaciones,id_asignacion',
            'id_trimestre' => 'required|exists:trimestres,id_trimestre',
        ]);
        
        try {
            $aula = Aula::with(['nivel', 'grado', 'seccion'])->findOrFail($request->id_aula);
            $trimestre = Trimestre::findOrFail($request->id_trimestre);
            
            $estudiantes = Estudiante::where('id_aula', $request->id_aula)
                ->where('estado', 'Activo')
                ->orderBy('apellido')
                ->get();
                
            // Si se especifica una asignación, filtrar por ella
            $calificacionesQuery = CalificacionOld::whereIn('id_estudiante', $estudiantes->pluck('id_estudiante'))
                ->where('id_trimestre', $request->id_trimestre);
                
            if ($request->id_asignacion) {
                $calificacionesQuery->where('id_asignacion', $request->id_asignacion);
                $asignacion = Asignacion::with(['materia', 'docente'])->findOrFail($request->id_asignacion);
                $titulo = 'Calificaciones de ' . $asignacion->materia->nombre;
            } else {
                $titulo = 'Calificaciones Generales';
            }
            
            $calificaciones = $calificacionesQuery->get()->groupBy('id_estudiante');
            
            $datosEstudiantes = [];
            foreach ($estudiantes as $estudiante) {
                $calificacionesEstudiante = $calificaciones->get($estudiante->id_estudiante, collect());
                
                $promedio = $calificacionesEstudiante->avg('nota') ?: 0;
                $comportamientoPromedio = $calificacionesEstudiante->avg('comportamiento') ?: 0;
                
                $datosEstudiantes[] = [
                    'estudiante' => $estudiante,
                    'calificaciones' => $calificacionesEstudiante,
                    'promedio' => round($promedio, 2),
                    'comportamiento_promedio' => round($comportamientoPromedio, 2),
                    'asignaturas_reprobadas' => $calificacionesEstudiante->sum('asignaturas_reprobadas'),
                ];
            }
            
            $fechaActual = now()->format('d-m-Y');
            
            $pdf = Pdf::loadView('pdf.calificaciones-old', compact(
                'datosEstudiantes', 
                'aula', 
                'trimestre', 
                'fechaActual',
                'titulo'
            ));
                
            // Configurar el PDF
            $pdf->setPaper('a4', 'landscape');
            
            // Generar nombre del archivo
            $nombreArchivo = 'Calificaciones_' . 
                $aula->nivel->nombre . '_' . 
                $aula->grado->nombre . '_' . 
                $aula->seccion->nombre . '_' . 
                $trimestre->nombre . '.pdf';
                
            // Reemplazar caracteres no permitidos en nombres de archivos
            $nombreArchivo = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $nombreArchivo);
            
            return $pdf->download($nombreArchivo);
            
        } catch (\Exception $e) {
            Log::error('Error al exportar calificaciones a PDF: ' . $e->getMessage());
            return back()->with('error', 'Error al exportar calificaciones: ' . $e->getMessage());
        }
    }

    /**
     * Registro masivo de calificaciones
     */
    public function storeMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'calificaciones' => 'required|array',
            'calificaciones.*.id_estudiante' => 'required|exists:estudiantes,id_estudiante',
            'calificaciones.*.id_asignacion' => 'required|exists:asignaciones,id_asignacion',
            'calificaciones.*.id_trimestre' => 'required|exists:trimestres,id_trimestre',
            'calificaciones.*.comportamiento' => 'required|integer|min:0|max:20',
            'calificaciones.*.asignaturas_reprobadas' => 'required|integer|min:0',
            'calificaciones.*.conclusion' => 'required|string|max:50',
            'calificaciones.*.grado' => 'required|string|max:50',
            'calificaciones.*.nota' => 'required|numeric|min:0|max:20',
            'calificaciones.*.fecha' => 'required|date',
            'calificaciones.*.observacion' => 'nullable|string|max:200',
        ]);

        try {
            DB::beginTransaction();

            $insertados = 0;
            $actualizados = 0;
            $errores = [];

            foreach ($request->calificaciones as $index => $calificacionData) {
                // Verificar si ya existe una calificación para este estudiante, asignación y trimestre
                $calificacionExistente = CalificacionOld::where('id_estudiante', $calificacionData['id_estudiante'])
                    ->where('id_asignacion', $calificacionData['id_asignacion'])
                    ->where('id_trimestre', $calificacionData['id_trimestre'])
                    ->first();

                if ($calificacionExistente) {
                    // Actualizar si existe y está marcado para actualizar
                    if (isset($calificacionData['actualizar']) && $calificacionData['actualizar']) {
                        $calificacionExistente->update($calificacionData);
                        $actualizados++;
                    } else {
                        $errores[] = "La calificación del estudiante #" . ($index + 1) . " ya existe y no se marcó para actualizar.";
                    }
                } else {
                    // Crear nuevo registro
                    CalificacionOld::create($calificacionData);
                    $insertados++;
                }
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'insertados' => $insertados,
                'actualizados' => $actualizados,
                'errores' => $errores
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar calificaciones masivas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar calificaciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vista combinada para crear y mostrar calificaciones de estudiantes egresados
     */
    public function createShow(string $nivel, int $aulaId): View
    {
        $aula = Aula::with(['nivel', 'grado', 'seccion'])
            ->findOrFail($aulaId);
            
        $trimestres = Trimestre::all();

        return view('calificaciones-old.create-show', compact('aula', 'nivel', 'trimestres'));
    }

    /**
     * Obtener calificaciones por aula, nivel, año y trimestre
     */
    public function getCalificaciones(Request $request): JsonResponse
    {
        $request->validate([
            'id_aula' => 'required|exists:aulas,id_aula',
            'id_nivel' => 'required|exists:niveles,id_nivel',
            'año' => 'required|integer',
            'id_trimestre' => 'required|exists:trimestres,id_trimestre',
            'promocion' => 'nullable|integer',
        ]);

        try {
            $aula = Aula::with(['nivel', 'grado', 'seccion'])->findOrFail($request->id_aula);
            
            // Obtener estudiantes del aula seleccionada
            $estudiantes = Estudiante::where('id_aula', $request->id_aula)
                ->when($request->promocion, function($query) use ($request) {
                    // Si se proporciona un año de promoción, filtrar por ese año
                    return $query->whereYear('fecha_ingreso', $request->promocion);
                })
                ->orderBy('apellido')
                ->get();
                
            // Verificar si hay estudiantes
            if ($estudiantes->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay estudiantes registrados en esta aula para el año ' . $request->año
                ]);
            }
                
            // Obtener materias y asignaciones relacionadas con este nivel y aula
            $asignaciones = Asignacion::with('materia', 'docente')
                ->where('id_aula', $request->id_aula)
                ->whereHas('anioAcademico', function($query) use ($request) {
                    $query->where('anio', $request->año);
                })
                ->get();
                
            // Extraer las materias de las asignaciones
            $materias = $asignaciones->map(function($asignacion) {
                return [
                    'id_materia' => $asignacion->materia->id_materia,
                    'id_asignacion' => $asignacion->id_asignacion,
                    'nombre' => $asignacion->materia->nombre
                ];
            });
            
            // Obtener todas las calificaciones para estos estudiantes en este trimestre
            $calificacionesData = CalificacionOld::where('id_trimestre', $request->id_trimestre)
                ->whereIn('id_estudiante', $estudiantes->pluck('id_estudiante'))
                ->get();
                
            // Organizar las calificaciones en un formato más fácil de usar
            $calificacionesPorEstudianteMateria = [];
            
            foreach ($calificacionesData as $calificacion) {
                $key = $calificacion->id_estudiante . '_' . $calificacion->id_asignacion;
                $calificacionesPorEstudianteMateria[$key] = [
                    'nota' => $calificacion->nota,
                    'comportamiento' => $calificacion->comportamiento,
                    'asignaturas_reprobadas' => $calificacion->asignaturas_reprobadas,
                    'conclusion' => $calificacion->conclusion,
                ];
                
                // Agregar el comportamiento al objeto del estudiante
                foreach ($estudiantes as $estudiante) {
                    if ($estudiante->id_estudiante == $calificacion->id_estudiante) {
                        $estudiante->comportamiento = $calificacion->comportamiento;
                        break;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'estudiantes' => $estudiantes,
                'materias' => $materias,
                'calificaciones' => $calificacionesPorEstudianteMateria
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las calificaciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guardar calificaciones de forma masiva
     */
    public function saveCalificaciones(Request $request): JsonResponse
    {
        try {
            Log::info('Datos recibidos:', $request->all());
            
            // Validación más flexible para permitir observaciones sin notas
            $request->validate([
                'calificaciones' => 'required|array',
                'calificaciones.*.id_estudiante' => 'required|exists:estudiantes,id_estudiante',
                'calificaciones.*.id_asignacion' => 'required',
                'calificaciones.*.id_trimestre' => 'required|exists:trimestres,id_trimestre',
                'calificaciones.*.nota' => 'nullable|numeric|min:0|max:20',
                'calificaciones.*.comportamiento' => 'nullable|integer|min:0|max:20',
                'calificaciones.*.asignaturas_reprobadas' => 'nullable|integer|min:0',
                'calificaciones.*.conclusion' => 'required|string|max:50',
                'calificaciones.*.grado' => 'required|string|max:50',
                'calificaciones.*.fecha' => 'required|date',
                'calificaciones.*.año' => 'required|integer',
                'calificaciones.*.observacion' => 'nullable|string|max:200',
            ]);

            DB::beginTransaction();
            
            foreach ($request->calificaciones as $calificacionData) {
                Log::info('Procesando calificación:', $calificacionData);
                
                // Verificar si ya existe una calificación para este estudiante, asignación y trimestre
                $calificacionExistente = CalificacionOld::where('id_estudiante', $calificacionData['id_estudiante'])
                    ->where('id_asignacion', $calificacionData['id_asignacion'])
                    ->where('id_trimestre', $calificacionData['id_trimestre'])
                    ->first();
                    
                if ($calificacionExistente) {
                    Log::info('Actualizando calificación existente:', ['id' => $calificacionExistente->id_calificacion]);
                    // Actualizar la calificación existente
                    $calificacionExistente->update([
                        'nota' => $calificacionData['nota'] ?? 0,
                        'comportamiento' => $calificacionData['comportamiento'] ?? 0,
                        'asignaturas_reprobadas' => $calificacionData['asignaturas_reprobadas'] ?? 0,
                        'conclusion' => $calificacionData['conclusion'],
                        'fecha' => $calificacionData['fecha'],
                        'observacion' => $calificacionData['observacion'] ?? null,
                    ]);
                } else {
                    Log::info('Creando nueva calificación');
                    // Crear una nueva calificación
                    CalificacionOld::create([
                        'id_estudiante' => $calificacionData['id_estudiante'],
                        'id_asignacion' => $calificacionData['id_asignacion'],
                        'id_trimestre' => $calificacionData['id_trimestre'],
                        'nota' => $calificacionData['nota'] ?? 0,
                        'comportamiento' => $calificacionData['comportamiento'] ?? 0,
                        'asignaturas_reprobadas' => $calificacionData['asignaturas_reprobadas'] ?? 0,
                        'conclusion' => $calificacionData['conclusion'],
                        'grado' => $calificacionData['grado'],
                        'fecha' => $calificacionData['fecha'],
                        'observacion' => $calificacionData['observacion'] ?? null,
                    ]);
                }
            }
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Calificaciones guardadas correctamente.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . json_encode($e->errors())
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar calificaciones: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar calificaciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar calificaciones a Excel
     */
    public function exportToExcel(Request $request)
    {
        $request->validate([
            'id_aula' => 'required|exists:aulas,id_aula',
            'año' => 'required|integer',
            'id_trimestre' => 'required|exists:trimestres,id_trimestre',
        ]);
        
        $aula = Aula::with(['nivel', 'grado', 'seccion'])->findOrFail($request->id_aula);
        $nivel = $aula->nivel->nombre;
        $fecha = date('Y-m-d');
        
        $exportService = new ExportService();
        
        $fileName = "calificaciones_{$nivel}_{$fecha}.xlsx";
        
        return $exportService->exportCalificacionesOldToExcel(
            $request->id_aula,
            $request->año,
            $request->id_trimestre,
            $fileName
        );
    }

    /**
     * Exportar calificaciones a PDF
     */
   
}