<?php

namespace App\Http\Controllers;

use App\Models\Apoderado;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApoderadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filtros
        $busqueda = $request->input('busqueda');
        $filtroRelacion = $request->input('relacion');

        $apoderados = Apoderado::with('estudiantes.aula')
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where(function ($q) use ($busqueda) {
                    $q->where('nombre', 'LIKE', "%{$busqueda}%")
                      ->orWhere('dni', 'LIKE', "%{$busqueda}%")
                      ->orWhere('telefono', 'LIKE', "%{$busqueda}%");
                });
            })
            ->when($filtroRelacion, function ($query, $filtroRelacion) {
                return $query->where('relacion', $filtroRelacion);
            })
            ->orderBy('nombre')
            ->paginate(15);

        // Obtener relaciones únicas para el filtro
        $relaciones = Apoderado::distinct('relacion')->pluck('relacion');

        return view('apoderados.index', compact('apoderados', 'relaciones', 'busqueda', 'filtroRelacion'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $estudiantes = Estudiante::where('estado', 'Activo')
            ->with('aula')
            ->orderBy('nombre')
            ->get();
        
        return view('apoderados.create', compact('estudiantes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'dni' => 'nullable|string|max:20|unique:apoderados',
            'relacion' => 'required|string|max:30',
            'telefono' => 'nullable|string|max:20',
            'estudiantes' => 'nullable|array',
            'estudiantes.*' => 'exists:estudiantes,id_estudiante',
        ]);
        
        try {
            DB::beginTransaction();

            // Crear el apoderado
            $apoderado = Apoderado::create($request->all());

            // Asociar estudiantes
            if ($request->has('estudiantes') && is_array($request->estudiantes)) {
                foreach ($request->estudiantes as $id_estudiante) {
                    $apoderado->estudiantes()->attach($id_estudiante);
                }
            }

            DB::commit();

            return redirect()->route('apoderados.index', $apoderado)
                ->with('success', 'Apoderado registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar apoderado: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Apoderado $apoderado)
    {
        $apoderado->load('estudiantes.aula');
        
        return view('apoderados.show', compact('apoderado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apoderado $apoderado)
    {
        $apoderado->load('estudiantes');
        
        $estudiantes = Estudiante::where('estado', 'Activo')
            ->with('aula')
            ->orderBy('nombre')
            ->get();
        
        return view('apoderados.edit', compact('apoderado', 'estudiantes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Apoderado $apoderado)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'dni' => 'nullable|string|max:20|unique:apoderados,dni,'.$apoderado->id_apoderado.',id_apoderado',
            'relacion' => 'required|string|max:30',
            'telefono' => 'nullable|string|max:20',
            'estudiantes' => 'nullable|array',
            'estudiantes.*' => 'exists:estudiantes,id_estudiante',
        ]);

        try {
            DB::beginTransaction();

            // Actualizar el apoderado
            $apoderado->update($request->all());

            // Actualizar estudiantes asociados
            $apoderado->estudiantes()->detach();

            if ($request->has('estudiantes') && is_array($request->estudiantes)) {
                foreach ($request->estudiantes as $id_estudiante) {
                    $apoderado->estudiantes()->attach($id_estudiante);
                }
            }

            DB::commit();

            return redirect()->route('apoderados.show', $apoderado)
                ->with('success', 'Apoderado actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar apoderado: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apoderado $apoderado)
    {
        try {
            DB::beginTransaction();
            
            // Verificar si tiene estudiantes asociados
            $tieneEstudiantes = $apoderado->estudiantes()->count() > 0;
            
            if ($tieneEstudiantes) {
                DB::rollBack();
                return back()->with('error', 'No se puede eliminar el apoderado porque tiene estudiantes asociados.');
            }
            
            // Eliminar relaciones con estudiantes por seguridad
            $apoderado->estudiantes()->detach();
            
            // Eliminar apoderado
            $apoderado->delete();
            
            DB::commit();
            
            return redirect()->route('apoderados.index')
                ->with('success', 'Apoderado eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar apoderado: ' . $e->getMessage());
        }
    }
    
    /**
     * Buscar apoderados para selector ajax
     */
    public function buscar(Request $request)
    {
        $term = $request->input('term');
        
        $apoderados = Apoderado::where('nombre', 'LIKE', "%{$term}%")
            ->orWhere('dni', 'LIKE', "%{$term}%")
            ->select('id_apoderado as id', 'nombre', 'dni', 'relacion')
            ->limit(10)
            ->get()
            ->map(function($apoderado) {
                $apoderado->text = $apoderado->nombre . ' - ' . $apoderado->dni . ' (' . $apoderado->relacion . ')';
                return $apoderado;
            });
            
        return response()->json(['results' => $apoderados]);
    }
}
