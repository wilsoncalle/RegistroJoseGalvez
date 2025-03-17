<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriaController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $filtroNivel = $request->input('nivel');
        
        $materias = Materia::with('nivel')
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where('nombre', 'LIKE', "%{$busqueda}%");
            })
            ->when($filtroNivel, function ($query, $nivel) {
                return $query->where('id_nivel', $nivel);
            })
            ->paginate(10);
        
        $niveles = Nivel::all();
        
        return view('materias.index', compact('materias', 'busqueda', 'filtroNivel', 'niveles'));
    }

    public function create()
    {
        $niveles = Nivel::all();
        return view('materias.create', compact('niveles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_nivel' => 'required|exists:niveles,id_nivel'
        ]);

        try {
            DB::beginTransaction();
            $materia = Materia::create($request->all());
            DB::commit();
            return redirect()->route('materias.index')->with('success', 'Materia creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear la materia: ' . $e->getMessage());
        }
    }

    public function show(Materia $materia)
    {
        return view('materias.show', compact('materia'));
    }

    public function edit(Materia $materia)
    {
        $niveles = Nivel::all();
        return view('materias.edit', compact('materia', 'niveles'));
    }

    public function update(Request $request, Materia $materia)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_nivel' => 'required|exists:niveles,id_nivel'
        ]);

        try {
            DB::beginTransaction();
            $materia->update($request->all());
            DB::commit();
            return redirect()->route('materias.index')->with('success', 'Materia actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar la materia: ' . $e->getMessage());
        }
    }

    public function destroy(Materia $materia)
    {
        try {
            DB::beginTransaction();
            $materia->delete();
            DB::commit();
            return redirect()->route('materias.index')->with('success', 'Materia eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la materia: ' . $e->getMessage());
        }
    }
}
