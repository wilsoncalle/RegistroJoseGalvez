<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Aula;
use App\Models\Nivel;
use App\Models\Apoderado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Exports\EstudiantesExport;
use App\Exports\EstudiantesPlantilla;
use App\Imports\EstudiantesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Validators\ValidationException;

class EstudianteController extends Controller
{
    public function index(Request $request): View
    {
        $busqueda = $request->get('busqueda');
        $filtroAula = $request->get('aula');
        $filtroEstado = $request->get('estado');
        $filtroNivel = $request->get('nivel');
        $filtroAnioIngreso = $request->get('anio_ingreso');

        // Obtener listas para los select
        $niveles = Nivel::all();
        $aulas = Aula::with(['nivel', 'grado', 'seccion'])
            ->when($filtroNivel, function ($query) use ($filtroNivel) {
                return $query->where('id_nivel', $filtroNivel);
            })
            ->get();

        // Filtrar estudiantes según los parámetros
        $estudiantes = Estudiante::with(['aula.nivel', 'aula.grado', 'aula.seccion', 'apoderados'])
            ->when($busqueda, function ($query) use ($busqueda) {
                return $query->where(function ($query) use ($busqueda) {
                    $query->where('estudiantes.nombre', 'like', "%$busqueda%")
                        ->orWhere('estudiantes.apellido', 'like', "%$busqueda%")
                        ->orWhere('estudiantes.dni', 'like', "%$busqueda%");
                });
            })
            ->when($filtroAula, function ($query) use ($filtroAula) {
                return $query->where('estudiantes.id_aula', $filtroAula);
            })
            ->when($filtroEstado, function ($query) use ($filtroEstado) {
                return $query->estadoReal($filtroEstado);
            })
            ->when($filtroNivel, function ($query) use ($filtroNivel) {
                return $query->whereHas('aula', function ($query) use ($filtroNivel) {
                    $query->where('aulas.id_nivel', $filtroNivel);
                });
            })
            ->when($filtroAnioIngreso, function ($query) use ($filtroAnioIngreso) {
                return $query->whereYear('estudiantes.fecha_ingreso', $filtroAnioIngreso);
            })
            ->join('aulas', 'estudiantes.id_aula', '=', 'aulas.id_aula')
            ->join('niveles', 'aulas.id_nivel', '=', 'niveles.id_nivel')
            ->join('grados', 'aulas.id_grado', '=', 'grados.id_grado')
            ->leftJoin('secciones', 'aulas.id_seccion', '=', 'secciones.id_seccion')
            ->select('estudiantes.*', DB::raw("CONCAT(grados.nombre, ' - ', secciones.nombre) as aula_nombre"))
            ->orderBy('niveles.nombre', 'asc')
            ->orderBy('aula_nombre', 'asc')
            ->orderBy('estudiantes.apellido', 'asc')
            
            ->paginate(15);

        // Obtener años disponibles para el filtro (extraídos de las fechas de ingreso)
    $aniosIngreso = Estudiante::selectRaw('DISTINCT YEAR(fecha_ingreso) as anio')
        ->whereNotNull('fecha_ingreso')
        ->orderBy('anio', 'desc')
        ->pluck('anio');
        
    return view('estudiantes.index', compact('estudiantes', 'busqueda', 'filtroAula', 'filtroEstado', 
        'filtroNivel', 'filtroAnioIngreso', 'aulas', 'niveles', 'aniosIngreso'));
    }

    private function generarNombreArchivo($filtroNivel, $filtroAula, $extension)
    {
        $nombre = 'Estudiantes';

        if ($filtroAula) {
            // Si hay filtro de aula, obtenemos el aula y su nivel asociado
            $aula = Aula::with('nivel', 'grado', 'seccion')->find($filtroAula);
            if ($aula) {
                $nombre .= ' de ' . $aula->nivel->nombre . ' - ' . $aula->grado->nombre . ' - ' . $aula->seccion->nombre;
            }
        } elseif ($filtroNivel) {
            // Si solo hay filtro de nivel, obtenemos el nombre del nivel
            $nivel = Nivel::find($filtroNivel);
            if ($nivel) {
                $nombre .= ' de ' . $nivel->nombre;
            }
        } else {
            // Si no hay filtros, usamos "Todos"
            $nombre .= ' - Todos';
        }

        // Reemplazamos caracteres no permitidos en nombres de archivos
        $nombre = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $nombre);

        return $nombre . '.' . $extension;
    }

    public function exportExcel(Request $request)
    {
        $busqueda    = $request->get('busqueda');
        $filtroAula  = $request->get('aula');
        $filtroEstado = $request->get('estado');
        $filtroNivel = $request->get('nivel');

        // Generamos el nombre del archivo con extensión 'xlsx'
        $nombreArchivo = $this->generarNombreArchivo($filtroNivel, $filtroAula, 'xlsx');

        return Excel::download(
            new EstudiantesExport($busqueda, $filtroAula, $filtroEstado, $filtroNivel),
            $nombreArchivo
        );
    }
    public function exportPdf(Request $request)
    {
        $busqueda    = $request->get('busqueda');
        $filtroAula  = $request->get('aula');
        $filtroEstado = $request->get('estado');
        $filtroNivel = $request->get('nivel');

        $estudiantes = Estudiante::with(['aula.nivel', 'aula.grado', 'aula.seccion'])
            ->when($busqueda, function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('apellido', 'like', "%{$busqueda}%")
                    ->orWhere('dni', 'like', "%{$busqueda}%");
                });
            })
            ->when($filtroAula, function ($query) use ($filtroAula) {
                $query->where('id_aula', $filtroAula);
            })
            ->when($filtroEstado, function ($query) use ($filtroEstado) {
                $query->where('estado', $filtroEstado);
            })
            ->when($filtroNivel, function ($query) use ($filtroNivel) {
                $query->whereHas('aula', function ($q) use ($filtroNivel) {
                    $q->where('id_nivel', $filtroNivel);
                });
            })
            ->orderBy('id_estudiante', 'asc')
            ->get();

        $fechaActual = now()->format('d-m-Y');
        $pdf = Pdf::loadView('pdf.estudiantes', compact('estudiantes', 'fechaActual'));
        // Configurar el PDF
        $pdf->setPaper('a4', 'landscape');


        // Generamos el nombre del archivo con extensión 'pdf'
        $nombreArchivo = $this->generarNombreArchivo($filtroNivel, $filtroAula, 'pdf');

        return $pdf->download($nombreArchivo);
    }

    public function create()
    {
        $niveles = Nivel::all();
        $apoderados = Apoderado::all();
        
        return view('estudiantes.create', compact('niveles', 'apoderados'));
    }
    
    /**
     * Mostrar formulario para crear múltiples estudiantes a la vez
     */
    public function bulkCreate(): View
    {
        $niveles = Nivel::all();
        $apoderados = Apoderado::all();
        
        return view('estudiantes.bulk-create', compact('niveles', 'apoderados'));
    }
    
    /**
     * Guardar múltiples estudiantes a la vez
     */
    public function storeBulk(Request $request): RedirectResponse
    {
        // Validar datos comunes
        $request->validate([
            'id_aula' => 'required|exists:aulas,id_aula',
            'fecha_ingreso' => 'nullable|date',
            'estado' => 'required|in:Activo,Retirado,Egresado',
            'estudiantes' => 'required|array|min:1',
            'estudiantes.*.nombre' => 'required|string|max:50',
            'estudiantes.*.apellido' => 'required|string|max:50',
            'estudiantes.*.dni' => 'nullable|string|max:20',
            'estudiantes.*.fecha_nacimiento' => 'nullable|date',
            'estudiantes.*.telefono' => 'nullable|string|max:20',
            'estudiantes.*.apoderados' => 'nullable|array',
            'estudiantes.*.apoderados.*' => 'exists:apoderados,id_apoderado',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Verificar DNIs duplicados
            $dnis = collect($request->estudiantes)
                ->pluck('dni')
                ->filter()
                ->toArray();
                
            $existingDnisCount = Estudiante::whereIn('dni', $dnis)->count();
            if ($existingDnisCount > 0) {
                return back()->withInput()->with('error', 'Algunos DNI ya existen en el sistema. Por favor verifique.');
            }
            
            // Datos comunes para todos los estudiantes
            $datosComunes = [
                'id_aula' => $request->id_aula,
                'fecha_ingreso' => $request->fecha_ingreso ?? now()->format('Y-m-d'),
                'estado' => $request->estado,
            ];
            
            $estudiantesCreados = 0;
            
            // Procesar cada estudiante
            foreach ($request->estudiantes as $datos) {
                // Verificar DNI duplicado dentro del mismo lote
                $estudiante = Estudiante::create(array_merge(
                    $datosComunes,
                    [
                        'nombre' => $datos['nombre'],
                        'apellido' => $datos['apellido'],
                        'dni' => $datos['dni'] ?? null,
                        'fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
                        'telefono' => $datos['telefono'] ?? null,
                    ]
                ));
                
                // Asociar apoderados si existen
                if (isset($datos['apoderados']) && is_array($datos['apoderados'])) {
                    $estudiante->apoderados()->sync($datos['apoderados']);
                }
                
                $estudiantesCreados++;
            }
            
            DB::commit();
            return redirect()->route('estudiantes.index')
                ->with('success', "Se han registrado $estudiantesCreados estudiantes correctamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar estudiantes: ' . $e->getMessage());
        }
    }
    
    /**
     * Mostrar formulario de importación de estudiantes
     */
    public function importForm(): View
    {
        // Obtener los niveles disponibles
        $niveles = Nivel::all();
        
        // Al inicio no mostraremos aulas hasta que se seleccione un nivel
        $aulas = [];
            
        return view('estudiantes.import', compact('niveles', 'aulas'));
    }
    
    /**
     * Muestra una vista previa de los datos a importar
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'nivel' => 'nullable|exists:niveles,id_nivel',
            'id_aula' => 'nullable|exists:aulas,id_aula',
            'fecha_ingreso' => 'required|date',
        ]);
        
        // Validar que si se selecciona un aula, pertenezca al nivel seleccionado
        if ($request->filled('id_aula') && $request->filled('nivel')) {
            $aula = Aula::find($request->id_aula);
            if ($aula->id_nivel != $request->nivel) {
                return response()->json([
                    'success' => false,
                    'message' => 'El aula seleccionada no pertenece al nivel elegido.'
                ], 400);
            }
        }
        
        try {
            // Leer el archivo para generar la vista previa
            $data = [];
            $file = $request->file('file');
            $import = new EstudiantesImport($request->id_aula, $request->fecha_ingreso, 'Activo'); // 'Activo' es el estado por defecto
            
            // Intentar obtener los datos sin importarlos
            $import->setPreviewMode(true);
            Excel::import($import, $file);
            
            // Obtener los datos de la vista previa
            $data = $import->getPreviewData();
            
            // Obtener el nombre del aula si se proporcionó
            $aulaNombre = null;
            if ($request->filled('id_aula')) {
                $aula = Aula::with(['nivel', 'grado', 'seccion'])->find($request->id_aula);
                if ($aula) {
                    $aulaNombre = $aula->nombre_completo;
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'count' => count($data),
                'aula' => $aulaNombre
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesar la importación de estudiantes desde Excel/CSV
     */
    public function importar(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'nivel' => 'nullable|exists:niveles,id_nivel',
            'id_aula' => 'nullable|exists:aulas,id_aula',
            'fecha_ingreso' => 'required|date',
            'confirm_import' => 'required|in:0,1',
        ]);

        // Verificar que se haya confirmado la importación
        if ($request->confirm_import != '1') {
            return back()->withErrors(['error' => 'Debe previsualizar los datos antes de importarlos.'])->withInput();
        }

        // Validar que si se selecciona un aula, pertenezca al nivel seleccionado
        if ($request->filled('id_aula') && $request->filled('nivel')) {
            $aula = Aula::find($request->id_aula);
            if ($aula->id_nivel != $request->nivel) {
                return back()->withErrors(['id_aula' => 'El aula seleccionada no pertenece al nivel elegido.']);
            }
        }

        try {
            $import = new EstudiantesImport($request->id_aula, $request->fecha_ingreso);
            Excel::import($import, $request->file('file'));

            $importados = $import->getRowCount();

            // Registrar la importación en el historial
            $nombreArchivo = $request->file('file')->getClientOriginalName();
            
            // Obtener el año académico a partir de la fecha de ingreso
            $anioAcademicoValue = date('Y', strtotime($request->fecha_ingreso));
            
            \App\Models\ImportacionHistorial::registrarImportacion(
                $nombreArchivo,
                $request->nivel,
                $request->id_aula,
                $anioAcademicoValue,
                $importados
            );

            $mensaje = "Se han importado {$importados} estudiantes correctamente.";

            return redirect()->route('estudiantes.index')->with('success', $mensaje);

        } catch (ValidationException $e) {
            $errores = $e->failures();
            $mensajesError = [];

            foreach ($errores as $error) {
                $mensajesError[] = "Fila {$error->row()}: " . implode(', ', $error->errors());
            }

            return back()->withErrors(['validacion' => $mensajesError]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al procesar el archivo: ' . $e->getMessage()]);
        }
    }

    /**
     * Descargar plantilla para importación de estudiantes
     */
    public function descargarPlantilla(Request $request)
    {
        // Obtener el aula seleccionada si existe
        $idAula = $request->get('aula');
        $aula = null;
        
        // Validar que el ID del aula sea un valor válido
        if ($idAula && is_numeric($idAula)) {
            try {
                $aula = Aula::with(['nivel', 'grado', 'seccion'])->findOrFail($idAula);
            } catch (\Exception $e) {
                // Si no se encuentra el aula, continuar sin ella
                \Log::warning("No se pudo cargar el aula ID: $idAula para la plantilla - " . $e->getMessage());
            }
        }
        
        // Obtener la fecha de ingreso si existe
        $fechaIngreso = $request->get('fecha_ingreso');
        
        // Si no hay fecha de ingreso, usar la fecha actual
        if (empty($fechaIngreso)) {
            $fechaIngreso = now()->format('Y-m-d');
        }
        
        // Generar un nombre más descriptivo para el archivo
        $nombreArchivo = 'Plantilla_Estudiantes';
        
        if ($aula && $aula->nivel && $aula->grado && $aula->seccion) {
            $nombreArchivo .= '_' . $aula->nivel->nombre;
            $nombreArchivo .= '_' . $aula->grado->nombre;
            $nombreArchivo .= '_' . $aula->seccion->nombre;
        }
        
        $nombreArchivo .= '_' . date('Y-m-d', strtotime($fechaIngreso));
        $nombreArchivo .= '.xlsx';
        
        // Pasar el ID del aula y la fecha de ingreso a la plantilla
        return Excel::download(new EstudiantesPlantilla($idAula, $fechaIngreso), $nombreArchivo);
    }

    public function getAulasPorNivel(int $nivelId): JsonResponse
    {
        $aulas = Aula::with(['nivel', 'grado', 'seccion'])
            ->where('id_nivel', $nivelId)
            ->get()
            ->sortBy(function($aula) {
                // Ordena concatenando el nombre del grado y el nombre de la sección.
                return $aula->grado->nombre . ' ' . $aula->seccion->nombre;
            })
            ->map(function ($aula) {
                return [
                    'id' => $aula->id_aula,
                    'nombre_completo' => $aula->nivel->nombre . ' - ' . 
                                    $aula->grado->nombre . ' - ' . 
                                    $aula->seccion->nombre
                ];
            })
            ->values();
        
        return response()->json($aulas);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'dni' => 'nullable|string|max:20|unique:estudiantes',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'id_aula' => 'nullable|exists:aulas,id_aula',
            'fecha_ingreso' => 'nullable|date',
            'estado' => 'required|in:Activo,Retirado,Egresado',
            'apoderados' => 'nullable|array',
            'apoderados.*' => 'exists:apoderados,id_apoderado',
        ]);

        try {
            DB::beginTransaction();
            
            // Verificar DNI duplicado manualmente
            if ($request->dni && Estudiante::where('dni', $request->dni)->exists()) {
                return back()->withInput()->with('error', 'Ya existe un estudiante registrado con este DNI.');
            }
            
            $estudiante = Estudiante::create($request->all());
            if ($request->has('apoderados')) {
                $estudiante->apoderados()->sync($request->apoderados);
            }
            DB::commit();
            return redirect()->route('estudiantes.index')
                ->with('success', 'Estudiante registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar estudiante: ' . $e->getMessage());
        }
    }

    public function show(Estudiante $estudiante): View
    {
        $estudiante->load(['aula', 'aula.nivel', 'aula.grado', 'aula.seccion', 'apoderados']);
        
        // Accedemos al nombre completo del aula
        $nombreCompletoAula = $estudiante->nombre_completo_aula;
        
        return view('estudiantes.show', compact('estudiante', 'nombreCompletoAula'));
    }

    public function edit(Estudiante $estudiante): View
    {
        $estudiante->load('apoderados', 'aula'); // Asegúrate de cargar la relación "aula"
        $niveles = Nivel::all();
        $aulas = Aula::all();
        $apoderados = Apoderado::orderBy('nombre')->get();
        return view('estudiantes.edit', compact('estudiante', 'niveles', 'aulas', 'apoderados'));
    }

    public function update(Request $request, Estudiante $estudiante): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'dni' => 'nullable|string|max:20|unique:estudiantes,dni,' . $estudiante->id_estudiante . ',id_estudiante',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'id_aula' => 'nullable|exists:aulas,id_aula',
            'fecha_ingreso' => 'nullable|date',
            'estado' => 'required|in:Activo,Retirado,Egresado',
            'apoderados' => 'nullable|array',
            'apoderados.*' => 'exists:apoderados,id_apoderado',
        ]);

        try {
            DB::beginTransaction();
            $estudiante->update($request->all());
            $estudiante->apoderados()->sync($request->apoderados ?? []);
            DB::commit();
            return redirect()->route('estudiantes.index')
                ->with('success', 'Estudiante actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar estudiante: ' . $e->getMessage());
        }
    }

    public function destroy(Estudiante $estudiante): RedirectResponse
    {
        try {
            DB::beginTransaction();
            if ($estudiante->calificaciones()->exists() || $estudiante->asistencias()->exists()) {
                $estudiante->update(['estado' => 'Retirado']);
                $mensaje = 'El estudiante tiene registros académicos y ha sido marcado como Retirado.';
            } else {
                $estudiante->apoderados()->detach();
                $estudiante->delete();
                $mensaje = 'Estudiante eliminado correctamente.';
            }
            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar estudiante: ' . $e->getMessage());
        }
    }
    
    // Nuevo método para obtener estudiantes con información de aula
    public function getEstudiantesConAula(): JsonResponse
    {
        $estudiantes = Estudiante::with(['aula', 'aula.nivel', 'aula.grado', 'aula.seccion'])
            ->where('estado', 'Activo')
            ->whereRaw('TIMESTAMPDIFF(YEAR, fecha_ingreso, CURDATE()) < 8 OR fecha_ingreso IS NULL')
            ->get()
            ->map(function ($estudiante) {
                return [
                    'id' => $estudiante->id_estudiante,
                    'nombre' => $estudiante->nombre . ' ' . $estudiante->apellido,
                    'aula' => $estudiante->nombre_completo_aula,
                ];
            });
            
        return response()->json($estudiantes);
    }
    
    /**
     * Consulta información de persona por DNI usando la API de Migo Perú
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function consultarDni(Request $request): JsonResponse
    {
        $request->validate([
            'dni' => 'required|string|size:8',
        ]);
        
        $dni = $request->input('dni');
        
        // Token de apis.net.pe
        $token = 'apis-token-14158.uFeMfwK5k9el9LYH7077UJJuzuFqsebv';
        
        try {
            // Usando la API de apis.net.pe
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->get("https://api.apis.net.pe/v2/reniec/dni", [
                'numero' => $dni
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Verificar si la respuesta contiene los datos esperados
                // La API de apis.net.pe devuelve directamente los datos de la persona
                if (isset($data['nombres'])) {
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'nombres' => $data['nombres'],
                            'apellido_paterno' => $data['apellidoPaterno'] ?? '',
                            'apellido_materno' => $data['apellidoMaterno'] ?? ''
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontraron datos para el DNI proporcionado'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al consultar la API: ' . $response->status()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtiene el historial de importaciones de estudiantes
     * 
     * @return array
     */
    private function getHistorialImportaciones(): array
    {
        return \App\Models\ImportacionHistorial::orderBy('created_at', 'desc')
            ->get()
            ->map(function($importacion) {
                return [
                    'id' => $importacion->id_importacion,
                    'descripcion' => $importacion->descripcion_completa,
                    'fecha' => $importacion->fecha_importacion->format('Y-m-d'),
                    'nivel_id' => $importacion->id_nivel,
                    'aula_id' => $importacion->id_aula,
                    'nivel_nombre' => $importacion->nivel_nombre,
                    'aula_nombre' => $importacion->aula_nombre,
                    'nombre_archivo' => $importacion->nombre_archivo,
                    'total' => $importacion->total_importados,
                    'anio_academico' => $importacion->anio_academico,
                    'usuario' => $importacion->usuario ?: 'Sistema'
                ];
            })
            ->toArray();
    }
    
    /**
     * Muestra el formulario para eliminar estudiantes en bloque
     *
     * @return View
     */
    public function bulkDeleteForm(): View
    {
        $niveles = Nivel::all();
        $historialImportaciones = $this->getHistorialImportaciones();
            
        return view('estudiantes.bulk-delete', compact('niveles', 'historialImportaciones'));
    }
    
    /**
     * Procesa la eliminación de estudiantes en bloque
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'id_importacion' => 'required|exists:importaciones_historial,id_importacion',
            'confirm_delete' => 'required|in:1',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Obtener la información de la importación seleccionada
            $importacion = \App\Models\ImportacionHistorial::findOrFail($request->id_importacion);
            
            // Validar que la importación tenga un aula asociada
            if (!$importacion->id_aula) {
                return back()->with('error', 'Esta importación no tiene un aula específica asociada.');
            }
            
            // Obtenemos los estudiantes que cumplen con los criterios
            $estudiantes = Estudiante::where('id_aula', $importacion->id_aula)
                ->whereDate('fecha_ingreso', $importacion->fecha_importacion)
                ->get();
                
            $cantidadTotal = $estudiantes->count();
            $eliminados = 0;
            $marcados = 0;
            
            foreach ($estudiantes as $estudiante) {
                // Verificamos si tiene registros académicos
                if ($estudiante->calificaciones()->exists() || $estudiante->asistencias()->exists()) {
                    // No podemos eliminar, solo marcar como retirado
                    $estudiante->update(['estado' => 'Retirado']);
                    $marcados++;
                } else {
                    // Eliminar relaciones con apoderados
                    $estudiante->apoderados()->detach();
                    // Eliminar estudiante
                    $estudiante->delete();
                    $eliminados++;
                }
            }
            
            DB::commit();
            
            if ($cantidadTotal == 0) {
                return redirect()->route('estudiantes.index')
                    ->with('warning', 'No se encontraron estudiantes para la importación seleccionada.');
            }
            
            $mensaje = "Proceso completado: {$eliminados} estudiantes eliminados";
            if ($marcados > 0) {
                $mensaje .= " y {$marcados} estudiantes marcados como retirados (tenían registros académicos).";
            } else {
                $mensaje .= ".";
            }
            
            return redirect()->route('estudiantes.index')->with('success', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar estudiantes: ' . $e->getMessage());
        }
    }
}