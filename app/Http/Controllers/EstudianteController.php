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
                return $query->where('nombre', 'like', "%$busqueda%")
                ->orWhere('apellido', 'like', "%$busqueda%")
                ->orWhere('dni', 'like', "%$busqueda%");
            })
            ->when($filtroAula, function ($query) use ($filtroAula) {
                return $query->where('id_aula', $filtroAula);
            })
            ->when($filtroEstado, function ($query) use ($filtroEstado) {
                return $query->where('estado', $filtroEstado);
            })
            ->when($filtroNivel, function ($query) use ($filtroNivel) {
                return $query->whereHas('aula', function ($query) use ($filtroNivel) {
                    $query->where('id_nivel', $filtroNivel);
                });
            })
            ->paginate(10);
    
        return view('estudiantes.index', compact('estudiantes', 'busqueda', 'filtroAula', 'filtroEstado', 'filtroNivel', 'aulas', 'niveles'));
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