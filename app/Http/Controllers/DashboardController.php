<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Docente;
use App\Models\Materia;
use App\Models\Asistencia;
use App\Models\Incidente;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal
     */
    public function index()
    {
        // Datos para el dashboard
        $estudiantes_count = Estudiante::where('estado', 'Activo')->count();
        $docentes_count = Docente::count();
        $materias_count = Materia::count();
        
        // Calcular el porcentaje de asistencia de hoy
        $hoy = Carbon::today();
        $total_estudiantes = Estudiante::where('estado', 'Activo')->count();
        
        if ($total_estudiantes > 0) {
            $presentes_hoy = Asistencia::where('fecha', $hoy)
                ->whereIn('estado', ['Presente', 'Tardanza'])
                ->count();
            
            $asistencia_hoy = round(($presentes_hoy / $total_estudiantes) * 100);
        } else {
            $asistencia_hoy = 0;
        }
        
        // Obtener incidentes recientes
        $incidentes_recientes = Incidente::with('estudiante')
            ->orderBy('fecha', 'desc')
            ->take(5)
            ->get();
        
        // Placeholder para eventos (en un sistema real, esta sería otra tabla)
        $proximos_eventos = collect([
            (object) [
                'titulo' => 'Reunión de padres',
                'descripcion' => 'Entrega de notas del primer trimestre',
                'fecha' => Carbon::now()->addDays(5)
            ],
            (object) [
                'titulo' => 'Exámenes finales',
                'descripcion' => 'Primer día de exámenes finales',
                'fecha' => Carbon::now()->addDays(15)
            ]
        ]);
        
        return view('dashboard', compact(
            'estudiantes_count',
            'docentes_count',
            'materias_count',
            'asistencia_hoy',
            'incidentes_recientes',
            'proximos_eventos'
        ));
    }
}