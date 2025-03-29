<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Http\Request;

class GradoController extends Controller
{
    public function index(Request $request)
{
    $busqueda = $request->get('busqueda');
    $filtroNivel = $request->get('nivel');

    $grados = Grado::with('nivel')
        ->when($busqueda, function ($query) use ($busqueda) {
            return $query->where('nombre', 'LIKE', "%{$busqueda}%");
        })
        ->when($filtroNivel, function ($query) use ($filtroNivel) {
            return $query->where('id_nivel', $filtroNivel);
        })
        ->select('*', 
            \DB::raw('CAST(SUBSTRING(nombre, 1, 1) AS UNSIGNED) as numero_grado')
        )
        ->orderBy('id_nivel')
        ->orderBy('numero_grado')
        ->orderBy('nombre')
        ->paginate(10)
        ->withQueryString();

    $niveles = Nivel::orderBy('nombre')->get();

    return view('grados.index', compact('grados', 'niveles', 'busqueda', 'filtroNivel'));
}

    public function create()
    {
        $niveles = Nivel::orderBy('nombre')->get();
        return view('grados.create', compact('niveles'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nombre' => [
            'required',
            'string',
            'max:255',
            'unique:grados,nombre,NULL,id_grado,id_nivel,' . $request->nivel_id
        ],
        'nivel_id' => 'required|exists:niveles,id_nivel',
    ], [
        'nombre.unique' => 'Ya existe un grado con este nombre en el nivel seleccionado.'
    ]);

    $grado = Grado::create([
        'nombre' => $request->nombre,
        'id_nivel' => $request->nivel_id,
    ]);

    return redirect()->route('grados.show', $grado)
        ->with('success', 'Grado creado exitosamente.');
}

    public function show(Grado $grado)
    {
        $grado->load(['nivel', 'secciones']);
        return view('grados.show', compact('grado'));
    }

    public function edit(Grado $grado)
    {
        $niveles = Nivel::orderBy('nombre')->get();
        return view('grados.edit', compact('grado', 'niveles'));
    }

    public function update(Request $request, Grado $grado)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nivel_id' => 'required|exists:niveles,id_nivel',
        ]);

        $grado->update([
            'nombre' => $request->nombre,
            'id_nivel' => $request->nivel_id,
        ]);

        return redirect()->route('grados.show', $grado)
            ->with('success', 'Grado actualizado exitosamente.');
    }

    public function destroy(Grado $grado)
    {
        try {
            $grado->delete();
            return redirect()->route('grados.index')
                ->with('success', 'Grado eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar el grado porque tiene registros asociados.');
        }
    }
}
