<?php

namespace App\Http\Controllers;

use App\Models\Trimestre;
use App\Models\AnioAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrimestreController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->get('busqueda');
        $filtroAnio = $request->get('anio');

        // Obtener lista de años académicos para el select
        $anios = AnioAcademico::all();

        // Filtrar trimestres según los parámetros
        $trimestres = Trimestre::with('anioAcademico')
            ->when($busqueda, function ($query) use ($busqueda) {
                return $query->where('anio', 'like', "%$busqueda%");
            })
            ->when($filtroAnio, function ($query) use ($filtroAnio) {
                return $query->where('id_anio', $filtroAnio);
            })
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(10);

        return view('trimestres.index', compact('trimestres', 'busqueda', 'filtroAnio', 'anios'));
    }

    public function create()
    {
        // Cargar los años académicos para el formulario
        $anios = AnioAcademico::all();
        return view('trimestres.create', compact('anios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:50',
            'id_anio'       => 'required|exists:anios_academicos,id_anio',
            'fecha_inicio'  => 'required|date',
            'fecha_fin'     => 'required|date|after:fecha_inicio',
        ]);

        try {
            DB::beginTransaction();
            $trimestre = Trimestre::create($request->all());
            DB::commit();
            return redirect()->route('trimestres.index')
                ->with('success', 'Trimestre registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar trimestre: ' . $e->getMessage());
        }
    }

    public function show(Trimestre $trimestre)
    {
        $trimestre->load('anioAcademico');
        return view('trimestres.show', compact('trimestre'));
    }

    public function edit(Trimestre $trimestre)
    {
        $anios = AnioAcademico::all();
        return view('trimestres.edit', compact('trimestre', 'anios'));
    }

    public function update(Request $request, Trimestre $trimestre)
    {
        $request->validate([
            'nombre'        => 'required|string|max:50',
            'id_anio'       => 'required|exists:anios_academicos,id_anio',
            'fecha_inicio'  => 'required|date',
            'fecha_fin'     => 'required|date|after:fecha_inicio',
        ]);

        try {
            DB::beginTransaction();
            $trimestre->update($request->all());
            DB::commit();
            return redirect()->route('trimestres.index')
                ->with('success', 'Trimestre actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar trimestre: ' . $e->getMessage());
        }
    }

    public function destroy(Trimestre $trimestre)
    {
        try {
            DB::beginTransaction();
            // Verificar si el trimestre tiene asignaciones relacionadas
            if ($trimestre->asignaciones()->exists()) {
                return redirect()->route('trimestres.index')
                    ->with('error', 'No se puede eliminar el trimestre porque tiene asignaciones asociadas.');
            }
            $trimestre->delete();
            DB::commit();
            return redirect()->route('trimestres.index')
                ->with('success', 'Trimestre eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar trimestre: ' . $e->getMessage());
        }
    }
}
