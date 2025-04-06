<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Aula;
use App\Models\Nivel;
use App\Models\Apoderado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Exports\EstudiantesExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF; // Asegúrate de importar la facade PDF

class EstudianteController extends Controller
{
    public function index(Request $request): View
    {
        $busqueda = $request->get('busqueda');
        $filtroAula = $request->get('aula');
        $filtroEstado = $request->get('estado');
        $filtroNivel = $request->get('nivel');

        // Obtener listas para los select
        $niveles = Nivel::all();
        $aulas = Aula::with(['nivel', 'grado', 'seccion'])
            ->when($filtroNivel, function ($query) use ($filtroNivel) {
                return $query->where('id_nivel', $filtroNivel);
            })
            ->get();

        // Filtrar estudiantes según los parámetros
        $estudiantes = Estudiante::with(['aula.nivel', 'aula.grado', 'aula.seccion', 'apoderados'])
            ->when($busqueda, function ($query) use ($busqueda) {
                return $query->where(function ($query) use ($busqueda) {
                    $query->where('estudiantes.nombre', 'like', "%$busqueda%")
                        ->orWhere('estudiantes.apellido', 'like', "%$busqueda%")
                        ->orWhere('estudiantes.dni', 'like', "%$busqueda%");
                });
            })
            ->when($filtroAula, function ($query) use ($filtroAula) {
                return $query->where('estudiantes.id_aula', $filtroAula);
            })
            ->when($filtroEstado, function ($query) use ($filtroEstado) {
                return $query->where('estudiantes.estado', $filtroEstado);
            })
            ->when($filtroNivel, function ($query) use ($filtroNivel) {
                return $query->whereHas('aula', function ($query) use ($filtroNivel) {
                    $query->where('aulas.id_nivel', $filtroNivel);
                });
            })
            ->join('aulas', 'estudiantes.id_aula', '=', 'aulas.id_aula')
            ->join('niveles', 'aulas.id_nivel', '=', 'niveles.id_nivel')
            ->join('grados', 'aulas.id_grado', '=', 'grados.id_grado')
            ->leftJoin('secciones', 'aulas.id_seccion', '=', 'secciones.id_seccion')
            ->select('estudiantes.*', \DB::raw("CONCAT(grados.nombre, ' - ', secciones.nombre) as aula_nombre"))
            ->orderBy('niveles.nombre', 'asc')
            ->orderBy('aula_nombre', 'asc')
            ->orderBy('estudiantes.apellido', 'asc')
            
            ->paginate(15);

        return view('estudiantes.index', compact('estudiantes', 'busqueda', 'filtroAula', 'filtroEstado', 'filtroNivel', 'aulas', 'niveles'));
    }

    private function generarNombreArchivo($filtroNivel, $filtroAula, $extension)
    {
        $nombre = 'Estudiantes';

        if ($filtroAula) {
            // Si hay filtro de aula, obtenemos el aula y su nivel asociado
            $aula = Aula::with('nivel', 'grado', 'seccion')->find($filtroAula);
            if ($aula) {
                $nombre .= ' de ' . $aula->nivel->nombre . ' - ' . $aula->grado->nombre . ' - ' . $aula->seccion->nombre;
            }
        } elseif ($filtroNivel) {
            // Si solo hay filtro de nivel, obtenemos el nombre del nivel
            $nivel = Nivel::find($filtroNivel);
            if ($nivel) {
                $nombre .= ' de ' . $nivel->nombre;
            }
        } else {
            // Si no hay filtros, usamos "Todos"
            $nombre .= ' - Todos';
        }

        // Reemplazamos caracteres no permitidos en nombres de archivos
        $nombre = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $nombre);

        return $nombre . '.' . $extension;
    }

    public function exportExcel(Request $request)
    {
        $busqueda    = $request->get('busqueda');
        $filtroAula  = $request->get('aula');
        $filtroEstado = $request->get('estado');
        $filtroNivel = $request->get('nivel');

        // Generamos el nombre del archivo con extensión 'xlsx'
        $nombreArchivo = $this->generarNombreArchivo($filtroNivel, $filtroAula, 'xlsx');

        return Excel::download(
            new EstudiantesExport($busqueda, $filtroAula, $filtroEstado, $filtroNivel),
            $nombreArchivo
        );
    }
    public function exportPdf(Request $request)
    {
        $busqueda    = $request->get('busqueda');
        $filtroAula  = $request->get('aula');
        $filtroEstado = $request->get('estado');
        $filtroNivel = $request->get('nivel');

        $estudiantes = Estudiante::with(['aula.nivel', 'aula.grado', 'aula.seccion'])
            ->when($busqueda, function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('apellido', 'like', "%{$busqueda}%")
                    ->orWhere('dni', 'like', "%{$busqueda}%");
                });
            })
            ->when($filtroAula, function ($query) use ($filtroAula) {
                $query->where('id_aula', $filtroAula);
            })
            ->when($filtroEstado, function ($query) use ($filtroEstado) {
                $query->where('estado', $filtroEstado);
            })
            ->when($filtroNivel, function ($query) use ($filtroNivel) {
                $query->whereHas('aula', function ($q) use ($filtroNivel) {
                    $q->where('id_nivel', $filtroNivel);
                });
            })
            ->orderBy('id_estudiante', 'asc')
            ->get();

        $fechaActual = now()->format('d-m-Y');
        $pdf = PDF::loadView('pdf.estudiantes', compact('estudiantes', 'fechaActual'));
        // Configurar el PDF
        $pdf->setPaper('a4', 'landscape');


        // Generamos el nombre del archivo con extensión 'pdf'
        $nombreArchivo = $this->generarNombreArchivo($filtroNivel, $filtroAula, 'pdf');

        return $pdf->download($nombreArchivo);
    }

    public function create(): View
    {
        // Cargar solamente los niveles y apoderados inicialmente
        $niveles = Nivel::all();
        $apoderados = Apoderado::orderBy('nombre')->get();
        
        // No cargar aulas todavía - se cargarán vía AJAX
        return view('estudiantes.create', compact('niveles', 'apoderados'));
    }

    public function getAulasPorNivel(int $nivelId): JsonResponse
    {
        $aulas = Aula::with(['nivel', 'grado', 'seccion'])
            ->where('id_nivel', $nivelId)
            ->get()
            ->sortBy(function($aula) {
                // Ordena concatenando el nombre del grado y el nombre de la sección.
                return $aula->grado->nombre . ' ' . $aula->seccion->nombre;
            })
            ->map(function ($aula) {
                return [
                    'id' => $aula->id_aula,
                    'nombre_completo' => $aula->nivel->nombre . ' - ' . 
                                    $aula->grado->nombre . ' - ' . 
                                    $aula->seccion->nombre
                ];
            })
            ->values();
        
        return response()->json($aulas);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'dni' => 'nullable|string|max:20|unique:estudiantes',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'id_aula' => 'nullable|exists:aulas,id_aula',
            'fecha_ingreso' => 'nullable|date',
            'estado' => 'required|in:Activo,Retirado,Egresado',
            'apoderados' => 'nullable|array',
            'apoderados.*' => 'exists:apoderados,id_apoderado',
        ]);

        try {
            DB::beginTransaction();
            
            // Verificar DNI duplicado manualmente
            if ($request->dni && Estudiante::where('dni', $request->dni)->exists()) {
                return back()->withInput()->with('error', 'Ya existe un estudiante registrado con este DNI.');
            }
            
            $estudiante = Estudiante::create($request->all());
            if ($request->has('apoderados')) {
                $estudiante->apoderados()->sync($request->apoderados);
            }
            DB::commit();
            return redirect()->route('estudiantes.index')
                ->with('success', 'Estudiante registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar estudiante: ' . $e->getMessage());
        }
    }

    public function show(Estudiante $estudiante): View
    {
        $estudiante->load(['aula', 'aula.nivel', 'aula.grado', 'aula.seccion', 'apoderados']);
        
        // Accedemos al nombre completo del aula
        $nombreCompletoAula = $estudiante->nombre_completo_aula;
        
        return view('estudiantes.show', compact('estudiante', 'nombreCompletoAula'));
    }

    public function edit(Estudiante $estudiante): View
    {
        $estudiante->load('apoderados', 'aula'); // Asegúrate de cargar la relación "aula"
        $niveles = Nivel::all();
        $aulas = Aula::all();
        $apoderados = Apoderado::orderBy('nombre')->get();
        return view('estudiantes.edit', compact('estudiante', 'niveles', 'aulas', 'apoderados'));
    }

    public function update(Request $request, Estudiante $estudiante): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'dni' => 'nullable|string|max:20|unique:estudiantes,dni,' . $estudiante->id_estudiante . ',id_estudiante',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'id_aula' => 'nullable|exists:aulas,id_aula',
            'fecha_ingreso' => 'nullable|date',
            'estado' => 'required|in:Activo,Retirado,Egresado',
            'apoderados' => 'nullable|array',
            'apoderados.*' => 'exists:apoderados,id_apoderado',
        ]);

        try {
            DB::beginTransaction();
            $estudiante->update($request->all());
            $estudiante->apoderados()->sync($request->apoderados ?? []);
            DB::commit();
            return redirect()->route('estudiantes.index')
                ->with('success', 'Estudiante actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar estudiante: ' . $e->getMessage());
        }
    }

    public function destroy(Estudiante $estudiante): RedirectResponse
    {
        try {
            DB::beginTransaction();
            if ($estudiante->calificaciones()->exists() || $estudiante->asistencias()->exists()) {
                $estudiante->update(['estado' => 'Retirado']);
                $mensaje = 'El estudiante tiene registros académicos y ha sido marcado como Retirado.';
            } else {
                $estudiante->apoderados()->detach();
                $estudiante->delete();
                $mensaje = 'Estudiante eliminado correctamente.';
            }
            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar estudiante: ' . $e->getMessage());
        }
    }
    
    // Nuevo método para obtener estudiantes con información de aula
    public function getEstudiantesConAula(): JsonResponse
    {
        $estudiantes = Estudiante::with(['aula', 'aula.nivel', 'aula.grado', 'aula.seccion'])
            ->where('estado', 'Activo')
            ->get()
            ->map(function ($estudiante) {
                return [
                    'id' => $estudiante->id_estudiante,
                    'nombre' => $estudiante->nombre,
                    'aula' => $estudiante->nombre_completo_aula,
                ];
            });
            
        return response()->json($estudiantes);
    }
}