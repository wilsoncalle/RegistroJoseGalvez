<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Models\Grado;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->get('busqueda');
        $filtroGrado = $request->get('grado_id');

        $secciones = Seccion::with('grado')
            ->when($busqueda, function ($query) use ($busqueda) {
                return $query->where('nombre', 'LIKE', "%{$busqueda}%");
            })
            ->when($filtroGrado, function ($query) use ($filtroGrado) {
                return $query->where('id_grado', $filtroGrado);
            })
            ->orderBy('nombre')
            ->paginate(10);

        $grados = Grado::orderBy('nombre')->get();

        return view('secciones.index', compact('secciones', 'grados', 'busqueda', 'filtroGrado'));
    }

    public function create()
    {
        $grados = Grado::orderBy('nombre')->get();
        return view('secciones.create', compact('grados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'grado_id' => 'required|exists:grados,id_grado',
        ]);

        $seccion = Seccion::create([
            'nombre' => $request->nombre,
            'id_grado' => $request->grado_id,
        ]);

        return redirect()->route('secciones.show', $seccion)
            ->with('success', 'Sección creada exitosamente.');
    }

    public function show(Seccion $seccion)
    {
        $seccion->load(['grado', ]);
        return view('secciones.show', compact('seccion'));
    }

    public function edit(Seccion $seccion)
    {
        $grados = Grado::orderBy('nombre')->get();
        return view('secciones.edit', compact('seccion', 'grados'));
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
