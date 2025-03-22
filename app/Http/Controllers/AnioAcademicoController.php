<?php

namespace App\Http\Controllers;

use App\Models\AnioAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnioAcademicoController extends Controller
{
    /**
     * Muestra una lista paginada de años académicos, con opción de filtrar por búsqueda y estado.
     */
    public function index(Request $request)
    {
        $busqueda = $request->get('busqueda');
        $filtroEstado = $request->get('estado');

        $anios = AnioAcademico::when($busqueda, function ($query) use ($busqueda) {
                return $query->where('anio', 'like', "%$busqueda%");
            })
            ->when($filtroEstado, function ($query) use ($filtroEstado) {
                return $query->where('estado', $filtroEstado);
            })
            ->paginate(10);

        return view('anios.index', compact('anios', 'busqueda', 'filtroEstado'));
    }

    /**
     * Muestra el formulario para crear un nuevo año académico.
     */
    public function create()
    {
        return view('anios.create');
    }

    /**
     * Almacena un nuevo año académico en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'anio'         => 'required|integer|unique:anios_academicos,anio',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'estado'       => 'required|in:Planificado,En curso,Finalizado',
            'descripcion'  => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $anio = AnioAcademico::create($request->all());
            DB::commit();
            return redirect()->route('anios.index', $anio)
                ->with('success', 'Año académico registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al registrar año académico: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un año académico, incluyendo sus trimestres.
     */
    public function show(AnioAcademico $anio)
    {
        $anio->load('trimestres');
        return view('anios.show', compact('anio'));
    }

    /**
     * Muestra el formulario para editar un año académico.
     */
    public function edit(AnioAcademico $anio)
    {
        return view('anios.edit', compact('anio'));
    }

    /**
     * Actualiza un año académico existente.
     */
    public function update(Request $request, AnioAcademico $anio)
    {
        $request->validate([
            'anio'         => 'required|integer|unique:anios_academicos,anio,' . $anio->id_anio . ',id_anio',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'estado'       => 'required|in:Planificado,En curso,Finalizado',
            'descripcion'  => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $anio->update($request->all());
            DB::commit();
            return redirect()->route('anios.index', $anio)
                ->with('success', 'Año académico actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar año académico: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un año académico de la base de datos.
     * Se verifica que no existan registros asociados (por ejemplo, trimestres o asignaciones)
     * para evitar eliminar un año académico que ya tenga dependencias.
     */
    public function destroy(AnioAcademico $anio)
    {
        try {
            DB::beginTransaction();

            if ($anio->trimestres()->exists() || $anio->asignaciones()->exists()) {
                return back()->with('error', 'No se puede eliminar el año académico, ya tiene registros asociados.');
            }

            $anio->delete();
            DB::commit();
            return redirect()->route('anios.index')
                ->with('success', 'Año académico eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar año académico: ' . $e->getMessage());
        }
    }
}
