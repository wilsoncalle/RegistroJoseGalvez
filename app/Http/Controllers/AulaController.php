<?php

namespace App\Http\Controllers;
use App\Models\Nivel;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\Aula;
use App\Exports\AulaExport;
use App\Services\AulaExportService;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    public function index(Request $request)
{
    $niveles = Nivel::all();
    $filtroNivel = $request->input('nivel');
    $busqueda = $request->input('busqueda');

    // Iniciar la consulta con joins
    $query = Aula::query()
        ->join('niveles', 'aulas.id_nivel', '=', 'niveles.id_nivel')
        ->join('grados', 'aulas.id_grado', '=', 'grados.id_grado')
        ->join('secciones', 'aulas.id_seccion', '=', 'secciones.id_seccion')
        ->join('asignaciones', 'aulas.id_aula', '=', 'asignaciones.id_aula') // Relación con asignaciones
        ->join('docentes', 'asignaciones.id_docente', '=', 'docentes.id_docente') // Relación con docentes
        ->select([
            'aulas.*',
            'niveles.nombre as nivel_nombre',
            'grados.nombre as grado_nombre',
            'secciones.nombre as seccion_nombre',
            'docentes.apellido as docente_apellido',
            'docentes.nombre as docente_nombre'
        ]);

    // Aplicar filtro por nivel
    if ($filtroNivel) {
        $query->where('aulas.id_nivel', $filtroNivel);
    }

    // Aplicar búsqueda si se proporciona
    if ($busqueda) {
        $query->where(function ($q) use ($busqueda) {
            $q->where('niveles.nombre', 'like', "%$busqueda%")
              ->orWhere('grados.nombre', 'like', "%$busqueda%")
              ->orWhere('secciones.nombre', 'like', "%$busqueda%")
              ->orWhere('docentes.apellido', 'like', "%$busqueda%")
              ->orWhere('docentes.nombre', 'like', "%$busqueda%");
        });
    }

    // Aplicar ordenamiento
    $query->orderBy('niveles.nombre')  // Primero por nivel
          ->orderBy('grados.nombre')   // Luego por grado
          ->orderByRaw("CAST(secciones.nombre AS CHAR) ASC") // Después por sección
          ->orderBy('docentes.apellido') // Finalmente, ordenar docentes por apellido
          ->orderBy('docentes.nombre'); // En caso de apellidos iguales, ordenar por nombre

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
    // Retorna los grados asociados a un nivel dado
public function getGrados($id_nivel)
{
    $grados = Grado::where('id_nivel', $id_nivel)->get();
    return response()->json($grados);
}

// Retorna las secciones asociadas a un grado dado
public function getSecciones($id_grado)
{
    $secciones = Seccion::where('id_grado', $id_grado)
                        ->orderBy('nombre', 'asc') // Asegura el orden alfabético
                        ->get();
    return response()->json($secciones);
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
        // Si el aula ya existe, redirigir con mensaje de error y sin conservar el valor del nivel
        return back()->withInput($request->except('id_nivel'))
                     ->with('error', 'Aula ya registrada: ' . $aulaExistente->nombre_completo);
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

    /**
     * Exportar listado de aulas a Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarExcel(Request $request)
    {
        $exportService = new AulaExportService();
        return $exportService->exportarExcel($request);
    }

    /**
     * Exportar listado de aulas a PDF
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarPDF(Request $request)
    {
        $exportService = new AulaExportService();
        return $exportService->exportarPDF($request);
    }
}