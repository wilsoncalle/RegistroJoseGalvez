<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Models\Nivel;
use App\Models\Grado;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->get('busqueda');
        $filtroNivel = $request->get('nivel_id');
        $filtroGrado = $request->get('grado_id'); // Consistente con la vista

        $secciones = Seccion::with(['grado.nivel'])
            ->when($busqueda, function ($query) use ($busqueda) {
                return $query->where('secciones.nombre', 'LIKE', "%{$busqueda}%");
            })
            ->when($filtroNivel, function ($query) use ($filtroNivel) {
                return $query->where('niveles.id_nivel', $filtroNivel);
            })
            ->when($filtroGrado, function ($query) use ($filtroGrado) {
                return $query->where('secciones.id_grado', $filtroGrado);
            })
            ->join('grados', 'secciones.id_grado', '=', 'grados.id_grado')
            ->join('niveles', 'grados.id_nivel', '=', 'niveles.id_nivel')
            ->orderByRaw("CASE niveles.nombre WHEN 'Inicial' THEN 1 WHEN 'Primaria' THEN 2 WHEN 'Secundaria' THEN 3 ELSE 4 END")
            ->orderBy('grados.nombre')
            ->orderBy('secciones.nombre')
            ->select('secciones.*')
            ->paginate(10)
            ->withQueryString();

        $grados = Grado::orderBy('nombre')->get();
        $niveles = Nivel::orderBy('nombre')->get();

        return view('secciones.index', compact('secciones', 'grados', 'niveles', 'busqueda', 'filtroGrado', 'filtroNivel'));
    }



    public function create()
{
    $niveles = Nivel::orderBy('nombre')->get(); // Obtener los niveles
    $grados = Grado::orderBy('nombre')->get(); // Obtener los grados
    
    return view('secciones.create', compact('niveles', 'grados')); 
}

public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'grado_id' => 'required|exists:grados,id_grado',
    ]);

    // Verificar si la sección ya existe con el mismo nombre y grado
    $seccionExistente = Seccion::where('nombre', $request->nombre)
        ->where('id_grado', $request->grado_id)
        ->first();

    if ($seccionExistente) {
        return back()->withInput()->with('error', 'Ya existe una sección con el mismo nombre en este grado.');
    }

    // Si no existe, se procede a crear la sección
    $seccion = Seccion::create([
        'nombre' => $request->nombre,
        'id_grado' => $request->grado_id,
    ]);

    return redirect()->route('secciones.index', $seccion)
        ->with('success', 'Sección creada exitosamente.');
}

    public function getGradosByNivel($nivelId)
{
    $grados = Grado::where('nivel_id', $nivelId)->orderBy('nombre')->get();
    return response()->json($grados);
}


    public function show(Seccion $seccion)
    {
        $seccion->load(['grado', ]);
        return view('secciones.show', compact('seccion'));
    }

    public function edit(Seccion $seccion)
    {
        $niveles = Nivel::all();
        $grados = Grado::all();
        return view('secciones.edit', compact('seccion', 'niveles', 'grados'));
    }


    public function update(Request $request, Seccion $seccion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'grado_id' => 'required|exists:grados,id_grado',
        ]);

        $seccion->update([
            'nombre' => $request->nombre,
            'id_grado' => $request->grado_id,
        ]);

        return redirect()->route('secciones.show', $seccion)
            ->with('success', 'Sección actualizada exitosamente.');
    }

    public function destroy(Seccion $seccion)
    {
        try {
            $seccion->delete();
            return redirect()->route('secciones.index')
                ->with('success', 'Sección eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar la sección porque tiene registros asociados.');
        }
    }
}
