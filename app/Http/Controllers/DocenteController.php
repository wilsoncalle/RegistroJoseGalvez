<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Materia;
use App\Models\Nivel; // Asegúrate de tener este modelo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filtros
        $busqueda = $request->input('busqueda');
        $filtroMateria = $request->input('materia');
        $filtroNivel = $request->input('nivel');

        $docentes = Docente::with('materia.nivel')
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where(function ($q) use ($busqueda) {
                    $q->where('nombre', 'LIKE', "%{$busqueda}%")
                      ->orWhere('apellido', 'LIKE', "%{$busqueda}%")
                      ->orWhere('dni', 'LIKE', "%{$busqueda}%")
                      ->orWhere('email', 'LIKE', "%{$busqueda}%");
                });
            })
            ->when($filtroMateria, function ($query, $filtroMateria) {
                return $query->where('id_materia', $filtroMateria);
            })
            ->when($filtroNivel, function ($query, $filtroNivel) {
                // Filtrar docentes que tengan materia con el nivel seleccionado
                return $query->whereHas('materia', function ($q) use ($filtroNivel) {
                    $q->where('id_nivel', $filtroNivel);
                });
            })
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate(10);

        // Obtener materias para el filtro
        $materias = Materia::orderBy('nombre')->get();

        // Obtener niveles para el filtro
        $niveles = Nivel::orderBy('nombre')->get();

        return view('docentes.index', compact('docentes', 'materias', 'niveles', 'busqueda', 'filtroMateria', 'filtroNivel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $niveles = Nivel::orderBy('nombre')->get();
        // No se cargan materias de forma predeterminada hasta seleccionar un nivel
        $materias = [];
        
        return view('docentes.create', compact('materias', 'niveles'));
    }

    /**
     * Método para obtener las materias según el nivel seleccionado.
     */
    public function getMateriasPorNivel(Request $request)
    {
        $nivelId = $request->input('id_nivel');
        // Se asume que en la tabla de materias existe la columna 'id_nivel'
        $materias = Materia::where('id_nivel', $nivelId)->orderBy('nombre')->get();

        return response()->json($materias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:50',
            'apellido'          => 'required|string|max:50',
            'dni'               => 'nullable|string|max:20|unique:docentes',
            'fecha_nacimiento'  => 'nullable|date',
            'direccion'         => 'nullable|string|max:200',
            'telefono'          => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:100|unique:docentes',
            'fecha_contratacion'=> 'nullable|date',
            'nivel'             => 'required', // Se valida que se haya seleccionado un nivel
            'id_materia'        => 'required|exists:materias,id_materia',
        ]);

        try {
            DB::beginTransaction();

            // Crear el docente incluyendo la materia asignada
            $docente = Docente::create($request->only([
                'nombre', 'apellido', 'dni', 'fecha_nacimiento', 
                'direccion', 'telefono', 'email', 'fecha_contratacion', 'id_materia'
            ]));

            DB::commit();

            return redirect()->route('docentes.index', $docente)
                ->with('success', 'Docente registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar docente: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Docente $docente)
    {
        $docente->load('materia.nivel');
        return view('docentes.show', compact('docente'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Docente $docente)
    {
        $docente->load('materia.nivel');
        $niveles = Nivel::orderBy('nombre')->get();
        
        // Filtrar materias según el nivel asignado al docente, si tiene una materia asignada.
        $materias = [];
        if ($docente->materia) {
            $materias = Materia::where('id_nivel', $docente->materia->nivel->id_nivel)
                ->orderBy('nombre')
                ->get();
        }
        
        return view('docentes.edit', compact('docente', 'materias', 'niveles'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Docente $docente)
    {
        $request->validate([
            'nombre'            => 'required|string|max:50',
            'apellido'          => 'required|string|max:50',
            'dni'               => 'nullable|string|max:20|unique:docentes,dni,'.$docente->id_docente.',id_docente',
            'fecha_nacimiento'  => 'nullable|date',
            'direccion'         => 'nullable|string|max:200',
            'telefono'          => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:100|unique:docentes,email,'.$docente->id_docente.',id_docente',
            'fecha_contratacion'=> 'nullable|date',
            'id_materia'        => 'nullable|exists:materias,id_materia',
        ]);

        try {
            DB::beginTransaction();

            // Actualizar el docente, incluyendo el cambio de materia
            $docente->update($request->only([
                'nombre', 'apellido', 'dni', 'fecha_nacimiento', 
                'direccion', 'telefono', 'email', 'fecha_contratacion', 'id_materia'
            ]));

            DB::commit();

            return redirect()->route('docentes.show', $docente)
                ->with('success', 'Docente actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar docente: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Docente $docente)
    {
        try {
            DB::beginTransaction();
            
            // Eliminar el docente
            $docente->delete();
            
            DB::commit();
            
            return redirect()->route('docentes.index')->with('success', 'Docente eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar docente: ' . $e->getMessage());
        }
    }
}
