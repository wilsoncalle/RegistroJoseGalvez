<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Aula;
use App\Models\Nivel;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\AnioAcademico;
use App\Exports\AsignacionExport;
use App\Services\AsignacionExportService;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class AsignacionController extends Controller
{
    public function index(Request $request)
    {
        $filtroNivel = $request->get('nivel');
        $filtroAula = $request->get('aula');
        $filtroAnio = $request->get('anio');
        $busqueda = trim($request->get('busqueda'));

        $anios = AnioAcademico::all();
        $niveles = Nivel::all();
        $aulas = Aula::with('nivel')
            ->when($filtroNivel, function ($query) use ($filtroNivel) {
                return $query->where('id_nivel', $filtroNivel);
            })
            ->get();

        $asignaciones = Asignacion::with(['docente', 'materia', 'aula.nivel', 'anioAcademico'])
            ->when($filtroNivel, function ($query) use ($filtroNivel) {
                return $query->whereHas('aula', function ($query) use ($filtroNivel) {
                    $query->where('id_nivel', $filtroNivel);
                });
            })
            ->when($filtroAula, function ($query) use ($filtroAula) {
                return $query->where('asignaciones.id_aula', $filtroAula);
            })
            ->when($filtroAnio, function ($query) use ($filtroAnio) {
                return $query->where('id_anio', $filtroAnio);
            })
            ->when($busqueda, function ($query) use ($busqueda) {
                return $query->whereHas('docente', function ($query) use ($busqueda) {
                    $query->whereRaw("LOWER(nombre) LIKE ?", ["%" . strtolower($busqueda) . "%"])
                        ->orWhereRaw("LOWER(apellido) LIKE ?", ["%" . strtolower($busqueda) . "%"])
                        ->orWhere('dni', 'LIKE', "%$busqueda%");
                });
            })
            // Agregar JOIN para poder ordenar por nivel, grado, sección y apellido del docente
            ->join('aulas', 'asignaciones.id_aula', '=', 'aulas.id_aula')
            ->join('niveles', 'aulas.id_nivel', '=', 'niveles.id_nivel')
            ->join('grados', 'aulas.id_grado', '=', 'grados.id_grado')
            ->join('secciones', 'aulas.id_seccion', '=', 'secciones.id_seccion')
            ->join('docentes', 'asignaciones.id_docente', '=', 'docentes.id_docente')
            // Ordenar por nivel, grado, sección y apellido del docente
            ->orderBy('niveles.nombre')  // Ordenar por nombre del nivel
            ->orderBy('grados.nombre')   // Ordenar por grado
            ->orderBy('secciones.nombre') // Ordenar por sección
            ->orderBy('docentes.apellido') // Ordenar por apellido del docente
            ->paginate(10);

        return view('asignaciones.index', compact('asignaciones', 'filtroNivel', 'filtroAula', 'filtroAnio', 'niveles', 'aulas', 'anios', 'busqueda'));
    }


    public function create()
    {
        $niveles = Nivel::orderBy('nombre')->get();
        $docentes = Docente::orderBy('apellido')->get();
        $materias = Materia::orderBy('nombre')->get();
        $anios = AnioAcademico::orderBy('anio', 'desc')->get();

        return view('asignaciones.create', compact('niveles', 'docentes', 'materias', 'anios'));
    }
    public function getDocentesPorNivel($nivelId)
    {
        try {
            $docentes = Docente::where('id_nivel', $nivelId)
                            ->orderBy('apellido')
                            ->get()
                            ->map(function($docente) {
                                    return [
                                        'id_docente' => $docente->id_docente,
                                        'nombre_completo' => $docente->nombre . ' ' . $docente->apellido
                                    ];
                            });
            return response()->json($docentes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar los docentes'], 500);
        }
    }

    public function getMateriasPorNivel($nivelId)
    {
        try {
            $materias = Materia::where('id_nivel', $nivelId)
                            ->orderBy('nombre')
                            ->get();
            return response()->json($materias);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar las materias'], 500);
        }
    }



    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_docente' => 'required|exists:docentes,id_docente',
            'id_materia' => 'required|exists:materias,id_materia',
            'aulas' => 'required|array',
            'aulas.*' => 'required|exists:aulas,id_aula',
            'id_anio' => 'required|exists:anios_academicos,id_anio',
            'materias_adicionales' => 'sometimes|array',
            'materias_adicionales.*' => 'exists:materias,id_materia',
        ]);

        try {
            DB::beginTransaction();
            
            $asignacionesCreadas = 0;
            $aulasConAsignaciones = [];
            
            // Procesar la materia principal y las materias adicionales
            $materias = array_merge([$request->id_materia], $request->materias_adicionales ?? []);
            
            // Procesar cada materia para cada aula seleccionada
            foreach ($materias as $idMateria) {
                foreach ($request->aulas as $idAula) {
                    // Verificar si ya existe una asignación con los mismos datos
                    $asignacionExistente = Asignacion::where('id_docente', $request->id_docente)
                        ->where('id_materia', $idMateria)
                        ->where('id_aula', $idAula)
                        ->where('id_anio', $request->id_anio)
                        ->first();

                    if ($asignacionExistente) {
                        // Registrar aulas con asignaciones existentes
                        $aula = Aula::find($idAula);
                        $materia = Materia::find($idMateria);
                        if ($aula && $materia) {
                            $aulasConAsignaciones[] = $aula->nombre . ' (' . $materia->nombre . ')';
                        } else {
                            $aulasConAsignaciones[] = 'ID: ' . $idAula;
                        }
                        continue; // Continuar con la siguiente combinación
                    }

                    // Crear nueva asignación para esta combinación de materia y aula
                    Asignacion::create([
                        'id_docente' => $request->id_docente,
                        'id_materia' => $idMateria,
                        'id_aula' => $idAula,
                        'id_anio' => $request->id_anio
                    ]);
                    
                    $asignacionesCreadas++;
                }
            }
            
            DB::commit();
            
            // Limpiar cualquier mensaje de sesión anterior para evitar duplicación
            session()->forget(['success', 'error', 'warning']);
            
            // Mostrar mensaje según el resultado
            if ($asignacionesCreadas > 0) {
                $mensaje = 'Se ' . ($asignacionesCreadas == 1 ? 'ha' : 'han') . ' creado ' . $asignacionesCreadas . ' asignación' . ($asignacionesCreadas == 1 ? '' : 'es') . ' correctamente.';
                $tipoMensaje = 'success';
                
                if (!empty($aulasConAsignaciones)) {
                    $mensaje .= ' No se crearon asignaciones para las siguientes combinaciones porque ya existían: ' . implode(', ', $aulasConAsignaciones);
                    $tipoMensaje = 'warning';
                }
                
                return redirect()->route('asignaciones.index')->with($tipoMensaje, $mensaje);
            } else {
                return back()->withInput()
                    ->with('error', 'No se crearon asignaciones. ' . (!empty($aulasConAsignaciones) ? 'Ya existen asignaciones para las combinaciones seleccionadas: ' . implode(', ', $aulasConAsignaciones) : ''));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear asignación: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $asignacion = Asignacion::with(['aula.nivel', 'docente', 'materia', 'anioAcademico'])
                ->findOrFail($id);
            
            return view('asignaciones.show', compact('asignacion'));
        } catch (\Exception $e) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'Error al cargar la asignación: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $asignacion = Asignacion::with(['aula.nivel', 'docente', 'materia', 'anioAcademico'])
                ->findOrFail($id);

            if (!$asignacion->aula) {
                return redirect()->route('asignaciones.index')
                    ->with('error', 'No se encontró el aula asociada a esta asignación.');
            }

            $niveles = Nivel::orderBy('nombre')->get();
            $docentes = Docente::orderBy('apellido')->get();
            $materias = Materia::orderBy('nombre')->get();
            $anios = AnioAcademico::orderBy('anio', 'desc')->get();

            return view('asignaciones.edit', compact('asignacion', 'niveles', 'docentes', 'materias', 'anios'));
        } catch (\Exception $e) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'Error al cargar la asignación: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_docente' => 'required|exists:docentes,id_docente',
            'id_materia' => 'required|exists:materias,id_materia',
            'id_aula' => 'required|exists:aulas,id_aula',
            'id_anio' => 'required|exists:anios_academicos,id_anio',
            'aulas_adicionales' => 'sometimes|array',
            'aulas_adicionales.*' => 'sometimes|exists:aulas,id_aula',
            'materias_adicionales' => 'sometimes|array',
            'materias_adicionales.*' => 'exists:materias,id_materia',
        ]);

        try {
            DB::beginTransaction();
            
            $asignacion = Asignacion::findOrFail($id);
            $asignacionesCreadas = 0;
            $combinacionesExistentes = [];

            // Procesar la materia principal y las materias adicionales
            $materias = array_merge([$request->id_materia], $request->materias_adicionales ?? []);
            
            // Procesar cada materia para cada aula
            $aulas = array_merge([$request->id_aula], $request->aulas_adicionales ?? []);
            
            foreach ($materias as $idMateria) {
                foreach ($aulas as $idAula) {
                    // Verificar si ya existe una asignación con los mismos datos (excluyendo la actual)
                    $asignacionExistente = Asignacion::where('id_docente', $request->id_docente)
                        ->where('id_materia', $idMateria)
                        ->where('id_aula', $idAula)
                        ->where('id_anio', $request->id_anio)
                        ->where('id_asignacion', '!=', $id)
                        ->first();

                    if ($asignacionExistente) {
                        $aula = Aula::find($idAula);
                        $materia = Materia::find($idMateria);
                        if ($aula && $materia) {
                            $combinacionesExistentes[] = $aula->nombre . ' (' . $materia->nombre . ')';
                        }
                        continue;
                    }

                    // Si es la asignación principal, actualizarla
                    if ($idMateria == $request->id_materia && $idAula == $request->id_aula) {
                        $asignacion->update([
                            'id_docente' => $request->id_docente,
                            'id_materia' => $idMateria,
                            'id_aula' => $idAula,
                            'id_anio' => $request->id_anio
                        ]);
                    } else {
                        // Crear nueva asignación para las combinaciones adicionales
                        Asignacion::create([
                            'id_docente' => $request->id_docente,
                            'id_materia' => $idMateria,
                            'id_aula' => $idAula,
                            'id_anio' => $request->id_anio
                        ]);
                    }
                    
                    $asignacionesCreadas++;
                }
            }
            
            DB::commit();
            
            if ($asignacionesCreadas > 0) {
                $mensaje = 'Se ' . ($asignacionesCreadas == 1 ? 'ha' : 'han') . ' actualizado ' . $asignacionesCreadas . ' asignación' . ($asignacionesCreadas == 1 ? '' : 'es') . ' correctamente.';
                $tipoMensaje = 'success';
                
                if (!empty($combinacionesExistentes)) {
                    $mensaje .= ' No se crearon asignaciones para las siguientes combinaciones porque ya existían: ' . implode(', ', $combinacionesExistentes);
                    $tipoMensaje = 'warning';
                }
                
                return redirect()->route('asignaciones.index')->with($tipoMensaje, $mensaje);
            } else {
                return back()->withInput()
                    ->with('error', 'No se actualizaron asignaciones. ' . (!empty($combinacionesExistentes) ? 'Ya existen asignaciones para las combinaciones seleccionadas: ' . implode(', ', $combinacionesExistentes) : ''));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar asignación: ' . $e->getMessage());
        }
    }

    public function destroy($id) 
{
    try {
        $asignacion = Asignacion::find($id);
        if ($asignacion) {
            $asignacion->delete();
            return redirect()->back()->with('success', 'Asignación eliminada correctamente.');
        } else {
            return redirect()->back()->with('error', 'La asignación no fue encontrada.');
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'No se puede eliminar debido a restricciones de clave foránea.');
    }
}


    public function getAulasPorNivel($nivelId)
    {
        try {
            $aulas = Aula::where('id_nivel', $nivelId)
                ->orderBy('nombre')
                ->get()
                ->map(function ($aula) {
                    return [
                        'id' => $aula->id_aula,
                        'nombre' => $aula->nombre
                    ];
                });
            return response()->json($aulas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar las aulas'], 500);
        }
    }

    /**
     * Exportar listado de asignaciones a Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarExcel(Request $request)
    {
        $exportService = new AsignacionExportService();
        return $exportService->exportarExcel($request);
    }

    /**
     * Exportar listado de asignaciones a PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportarPDF(Request $request)
    {
        $exportService = new AsignacionExportService();
        return $exportService->exportarPDF($request);
    }
}
