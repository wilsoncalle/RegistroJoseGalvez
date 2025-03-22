<?php

namespace App\Http\Controllers;
use App\Models\Nivel;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\Aula;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    public function index(Request $request)
{
    // Obtener los niveles desde la base de datos
    $niveles = Nivel::all();
    $filtroNivel = $request->input('nivel');

    // Obtener la búsqueda si existe
    $busqueda = $request->input('busqueda');

    // Iniciar la consulta de aulas
    $query = Aula::with(['nivel', 'grado', 'seccion']); // Carga eager loading para optimizar rendimiento

    // Aplicar filtro por nivel si se proporciona
    if ($filtroNivel) {
        $query->where('aulas.id_nivel', $filtroNivel);
    }
    

    // Aplicar búsqueda si se proporciona
    if ($busqueda) {
        $query->where(function($q) use ($busqueda) {
            $q->whereHas('nivel', function ($subq) use ($busqueda) {
                $subq->where('nombre', 'like', "%$busqueda%");
            })->orWhereHas('grado', function ($subq) use ($busqueda) {
                $subq->where('nombre', 'like', "%$busqueda%");
            })->orWhereHas('seccion', function ($subq) use ($busqueda) {
                $subq->where('nombre', 'like', "%$busqueda%");
            });
        });
    }

    $query->select([
        'aulas.*', 
        'niveles.nombre as nivel_nombre', 
        'grados.nombre as grado_nombre', 
        'secciones.nombre as seccion_nombre'
    ])
    ->join('niveles', 'aulas.id_nivel', '=', 'niveles.id_nivel')
    ->join('grados', 'aulas.id_grado', '=', 'grados.id_grado')
    ->join('secciones', 'aulas.id_seccion', '=', 'secciones.id_seccion')
    ->orderBy('niveles.nombre')
    ->orderBy('grados.nombre')
    ->orderBy('secciones.nombre')
    ->orderBy('aulas.id_nivel');


    // Ejecutar la consulta con paginación
    $aulas = $query->paginate(10);

    return view('aulas.index', compact('aulas', 'busqueda', 'niveles', 'filtroNivel'));
}


    public function create()
    {
        $niveles = Nivel::all();
        $grados = Grado::all();
        $secciones = Seccion::all();

        return view('aulas.create', compact('niveles', 'grados', 'secciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_nivel' => 'required|exists:niveles,id_nivel',
            'id_grado' => 'required|exists:grados,id_grado',
            'id_seccion' => 'required|exists:secciones,id_seccion',
        ]);

        // Verificar si ya existe un aula con la misma combinación de nivel, grado y sección
        $aulaExistente = Aula::where('id_nivel', $request->id_nivel)
                            ->where('id_grado', $request->id_grado)
                            ->where('id_seccion', $request->id_seccion)
                            ->first();

        if ($aulaExistente) {
            // Si el aula ya existe, redirigir con mensaje de error
            return back()->withInput()->with('error', 'Aula ya registrada: ' . $aulaExistente->nombre_completo);
        }

        // Si no existe, crear el aula
        $aula = Aula::create($request->only(['id_nivel', 'id_grado', 'id_seccion']));

        return redirect()->route('aulas.show', $aula)
            ->with('success', 'Aula "' . $aula->nombre_completo . '" creada exitosamente.');
    }

    public function show(Aula $aula)
    {
        // Asegurar que las relaciones estén cargadas para usar nombre_completo
        $aula->load(['nivel', 'grado', 'seccion']);
        
        return view('aulas.show', compact('aula'));
    }

    public function edit(Aula $aula)
    {
        $niveles = Nivel::all();
        $grados = Grado::all();
        $secciones = Seccion::all();
        
        return view('aulas.edit', compact('aula', 'niveles', 'grados', 'secciones'));
    }

    public function update(Request $request, Aula $aula)
    {
        $request->validate([
            'id_nivel' => 'required|exists:niveles,id_nivel',
            'id_grado' => 'required|exists:grados,id_grado',
            'id_seccion' => 'required|exists:secciones,id_seccion',
        ]);

        // Verificar si ya existe otra aula con la misma combinación de nivel, grado y sección
        $aulaExistente = Aula::where('id_nivel', $request->id_nivel)
                            ->where('id_grado', $request->id_grado)
                            ->where('id_seccion', $request->id_seccion)
                            ->where('id_aula', '!=', $aula->id_aula) // Excluir el aula actual
                            ->first();

        if ($aulaExistente) {
            // Si ya existe otra aula con esos datos, redirigir con mensaje de error
            return back()->withInput()->with('error', 'Aula ya registrada: ' . $aulaExistente->nombre_completo);
        }

        // Guardar el nombre anterior para incluirlo en el mensaje
        $nombreAnterior = $aula->nombre_completo;
        
        $aula->update($request->only(['id_nivel', 'id_grado', 'id_seccion']));
        
        // Recargar las relaciones para mostrar el nombre actualizado
        $aula->load(['nivel', 'grado', 'seccion']);

        return redirect()->route('aulas.show', $aula)
            ->with('success', 'Aula actualizada exitosamente de "' . $nombreAnterior . '" a "' . $aula->nombre_completo . '".');
    }

    public function destroy(Aula $aula)
    {
        // Guardar el nombre para el mensaje
        $nombreAula = $aula->nombre_completo;
        
        try {
            $aula->delete();
            return redirect()->route('aulas.index')
                ->with('success', 'Aula "' . $nombreAula . '" eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar el aula "' . $nombreAula . '" porque tiene registros asociados.');
        }
    }
}