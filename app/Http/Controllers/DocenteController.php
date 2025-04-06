<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Nivel;
use App\Exports\DocenteExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $filtroNivel = $request->input('nivel');
        
        $docentes = Docente::query();
        
        // Aplicar filtros de búsqueda
        if ($busqueda) {
            $docentes->where(function($query) use ($busqueda) {
                $query->where('nombre', 'LIKE', "%{$busqueda}%")
                    ->orWhere('apellido', 'LIKE', "%{$busqueda}%")
                    ->orWhere('dni', 'LIKE', "%{$busqueda}%");
            });
        }
        
        // Filtrar por nivel
        if ($filtroNivel) {
            $docentes->where('id_nivel', $filtroNivel);
        }
        
        // Ordenar primero por nivel y luego por apellido
        $docentes = $docentes->orderBy('id_nivel')->orderBy('apellido')->paginate(10);
        
        $niveles = Nivel::orderBy('nombre')->get();
        
        return view('docentes.index', compact('docentes', 'niveles', 'busqueda', 'filtroNivel'));
    }

    public function exportarExcel(Request $request)
    {
        // Obtener los filtros
        $filtroNivel = $request->input('nivel');
        
        // Iniciar la consulta
        $docentes = Docente::query();
        
        // Filtrar por nivel si se especifica
        if ($filtroNivel) {
            $docentes->where('id_nivel', $filtroNivel);
        }
        
        // Ordenar los resultados
        $docentes = $docentes->orderBy('id_nivel')->orderBy('apellido')->get();
        
        // Obtener el nombre del nivel para el título
        $nombreNivel = 'General';
        if ($filtroNivel) {
            $nivel = Nivel::find($filtroNivel);
            if ($nivel) {
                $nombreNivel = $nivel->nombre;
            }
        }
        
        // Generar nombre del archivo
        $fechaActual = now()->format('d-m-Y');
        $nombreArchivo = "docentes_{$nombreNivel}_{$fechaActual}.xlsx";
        
        // Reemplazar espacios y caracteres especiales en el nombre del archivo
        $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
        $nombreArchivo = preg_replace('/[^A-Za-z0-9\-_.]/', '', $nombreArchivo);
        
        // Crear y devolver el archivo Excel
        return Excel::download(
            new DocenteExport($docentes, $filtroNivel, $nombreNivel),
            $nombreArchivo
        );
    }
    public function exportarPDF(Request $request)
    {
        // Obtener los filtros
        $filtroNivel = $request->input('nivel');
        
        // Iniciar la consulta
        $docentes = Docente::query();
        
        // Filtrar por nivel si se especifica
        if ($filtroNivel) {
            $docentes->where('id_nivel', $filtroNivel);
        }
        
        // Ordenar los resultados
        $docentes = $docentes->orderBy('id_nivel')->orderBy('apellido')->get();
        
        // Obtener el nombre del nivel para el título
        $nombreNivel = 'General';
        if ($filtroNivel) {
            $nivel = Nivel::find($filtroNivel);
            if ($nivel) {
                $nombreNivel = $nivel->nombre;
            }
        }
        
        // Configurar la numeración
        $counter = 1;
        
        // Generar nombre del archivo
        $fechaActual = now()->format('d-m-Y');
        $nombreArchivo = "docentes_{$nombreNivel}_{$fechaActual}.pdf";
        
        // Reemplazar espacios y caracteres especiales en el nombre del archivo
        $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
        $nombreArchivo = preg_replace('/[^A-Za-z0-9\-_.]/', '', $nombreArchivo);
        
        // Generar el PDF
        $pdf = PDF::loadView('pdf.docentes', [
            'docentes' => $docentes, 
            'counter' => $counter,
            'filtroNivel' => $filtroNivel,
            'nombreNivel' => $nombreNivel,
            'fechaActual' => $fechaActual
        ]);
        
        // Configurar el PDF
        $pdf->setPaper('a4', 'landscape');
        
        // Devolver el PDF como descarga
        return $pdf->download($nombreArchivo);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $niveles = Nivel::orderBy('nombre')->get();
        return view('docentes.create', compact('niveles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'nullable|string|max:20',
            'id_nivel' => 'required|exists:niveles,id_nivel',
        ]);
        
        $docente = Docente::create($request->all());
        
        return redirect()->route('docentes.index')
            ->with('success', 'Docente creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Docente $docente)
    {
        return view('docentes.show', compact('docente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Docente $docente)
    {
        $niveles = Nivel::orderBy('nombre')->get();
        return view('docentes.edit', compact('docente', 'niveles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Docente $docente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'nullable|string|max:20',
            'id_nivel' => 'required|exists:niveles,id_nivel',
        ]);
        
        $docente->update($request->all());
        
        return redirect()->route('docentes.index')
            ->with('success', 'Docente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Docente $docente)
    {
        $docente->delete();
        
        return redirect()->route('docentes.index')
            ->with('success', 'Docente eliminado correctamente.');
    }
}