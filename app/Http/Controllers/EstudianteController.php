<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Aula;
use App\Models\Apoderado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstudianteController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $filtroAula = $request->input('aula');
        $filtroEstado = $request->input('estado');

        $estudiantes = Estudiante::with(['aula', 'apoderados'])
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where(function ($q) use ($busqueda) {
                    $q->where('nombre', 'LIKE', "%{$busqueda}%")
                      ->orWhere('dni', 'LIKE', "%{$busqueda}%");
                });
            })
            ->when($filtroAula, function ($query, $filtroAula) {
                return $query->where('id_aula', $filtroAula);
            })
            ->when($filtroEstado, function ($query, $filtroEstado) {
                return $query->where('estado', $filtroEstado);
            })
            ->orderBy('nombre')
            ->paginate(15);

        $aulas = Aula::all();

        return view('estudiantes.index', compact('estudiantes', 'aulas', 'busqueda', 'filtroAula', 'filtroEstado'));
    }

    public function create()
    {
        $aulas = Aula::all();
        $apoderados = Apoderado::orderBy('nombre')->get();
        return view('estudiantes.create', compact('aulas', 'apoderados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
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
            $estudiante = Estudiante::create($request->all());
            if ($request->has('apoderados')) {
                $estudiante->apoderados()->sync($request->apoderados);
            }
            DB::commit();
            return redirect()->route('estudiantes.index', $estudiante)
                ->with('success', 'Estudiante registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar estudiante: ' . $e->getMessage());
        }
    }

    public function show(Estudiante $estudiante)
    {
        $estudiante->load(['aula', 'apoderados']);
        return view('estudiantes.show', compact('estudiante'));
    }

    public function edit(Estudiante $estudiante)
    {
        $estudiante->load('apoderados');
        $aulas = Aula::all();
        $apoderados = Apoderado::orderBy('nombre')->get();
        return view('estudiantes.edit', compact('estudiante', 'aulas', 'apoderados'));
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
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
            return redirect()->route('estudiantes.index', $estudiante)
                ->with('success', 'Estudiante actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar estudiante: ' . $e->getMessage());
        }
    }

    public function destroy(Estudiante $estudiante)
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
}
