<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Aula;
use App\Models\Nivel;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\AnioAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request)
    {
        $request->validate([
            'id_docente' => 'required|exists:docentes,id_docente',
            'id_materia' => 'required|exists:materias,id_materia',
            'id_aula' => 'required|exists:aulas,id_aula',
            'id_anio' => 'required|exists:anios_academicos,id_anio',
        ]);

        try {
            DB::beginTransaction();
            Asignacion::create($request->all());
            DB::commit();
            return redirect()->route('asignaciones.index')->with('success', 'Asignación creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear asignación: ' . $e->getMessage());
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

    public function destroy(Asignacion $asignacion)
    {
        try {
            DB::beginTransaction();
            $asignacion->delete();
            DB::commit();
            return redirect()->route('asignaciones.index')->with('success', 'Asignación eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar asignación: ' . $e->getMessage());
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
}
