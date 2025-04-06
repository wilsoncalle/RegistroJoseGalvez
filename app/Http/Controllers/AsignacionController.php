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
            return $query->where('id_aula', $filtroAula);
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
            'id_aula' => 'required|exists:aulas,id_aula',
            'id_anio' => 'required|exists:anios_academicos,id_anio',
        ]);

        try {
            DB::beginTransaction();

            // Verificar si ya existe una asignación con los mismos datos
            $asignacionExistente = Asignacion::where('id_docente', $request->id_docente)
                ->where('id_materia', $request->id_materia)
                ->where('id_aula', $request->id_aula)
                ->where('id_anio', $request->id_anio)
                ->first();

            if ($asignacionExistente) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', 'Ya existe una asignación con estos datos. Por favor, verifique los datos ingresados.');
            }

            Asignacion::create($request->all());
            DB::commit();
            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación creada correctamente.');
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
        ]);

        try {
            DB::beginTransaction();
            
            $asignacion = Asignacion::findOrFail($id);

            // Verificar si ya existe una asignación con los mismos datos (excluyendo la actual)
            $asignacionExistente = Asignacion::where('id_docente', $request->id_docente)
                ->where('id_materia', $request->id_materia)
                ->where('id_aula', $request->id_aula)
                ->where('id_anio', $request->id_anio)
                ->where('id_asignacion', '!=', $id)
                ->first();

            if ($asignacionExistente) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', 'Ya existe una asignación con estos datos. Por favor, verifique los datos ingresados.');
            }
            
            $asignacion->update($request->all());
            
            DB::commit();
            return redirect()->route('asignaciones.index')
                ->with('success', 'Asignación actualizada correctamente.');
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
