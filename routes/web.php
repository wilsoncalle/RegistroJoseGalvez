<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\IncidenteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\DocenteController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas de autenticación
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de Estudiantes
    Route::resource('estudiantes', \App\Http\Controllers\EstudianteController::class);
    Route::get('/get-aulas-por-nivel/{nivelId}', [EstudianteController::class, 'getAulasPorNivel']);
    
    // Rutas de Docentes
    Route::resource('docentes', \App\Http\Controllers\DocenteController::class);
    Route::get('aulas/nivel/{nivelId}', [EstudianteController::class, 'getAulasPorNivel'])->name('aulas.nivel');
    Route::get('materias-por-nivel', [DocenteController::class, 'getMateriasPorNivel'])->name('materias.pornivel');


    // Rutas de Apoderados
    Route::resource('apoderados', \App\Http\Controllers\ApoderadoController::class);
    
    // Rutas de Niveles
    Route::resource('aulas', \App\Http\Controllers\AulaController::class);
    Route::get('/aulas/get-grados/{nivelId}', [AulaController::class, 'getGradosPorNivel']);
    Route::get('/aulas/get-secciones/{nivelId}/{gradoId}', [AulaController::class, 'getSeccionesPorGradoNivel']);
    Route::get('aulas/getGrados/{id_nivel}', [AulaController::class, 'getGrados'])->name('aulas.getGrados');
Route::get('aulas/getSecciones/{id_grado}', [AulaController::class, 'getSecciones'])->name('aulas.getSecciones');

    
    // Rutas de Grados
    Route::resource('grados', \App\Http\Controllers\GradoController::class);
    
    // Rutas de Secciones
    Route::resource('secciones', SeccionController::class)->parameters([
        'secciones' => 'seccion'
    ]);
    Route::get('/getGradosByNivel/{nivelId}', [SeccionController::class, 'getGradosByNivel']);
    // Rutas de Materias
    Route::resource('materias', \App\Http\Controllers\MateriaController::class);
    
    // Rutas de Años Académicos
    Route::resource('anios', \App\Http\Controllers\AnioAcademicoController::class);
    
    // Rutas de Trimestres
    Route::resource('trimestres', \App\Http\Controllers\TrimestreController::class);
    
    // Rutas de Asignaciones
    Route::resource('asignaciones', \App\Http\Controllers\AsignacionController::class);
    Route::get('/asignaciones/getAulasPorNivel/{nivelId}', [AsignacionController::class, 'getAulasPorNivel'])->name('asignaciones.aulas');
    
    // Rutas de Calificaciones
    Route::resource('calificaciones', \App\Http\Controllers\CalificacionController::class);
    
    // Rutas de Asistencias
    Route::resource('asistencia', AsistenciaController::class);
    Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
    Route::get('/asistencias/nivel/{nivel}', [AsistenciaController::class, 'indexNivel'])->name('asistencias.nivel');
    Route::get('/docentes/{materiaId}/{aulaId}', [AsistenciaController::class, 'getDocentesPorMateria']);
    Route::get('/asistencias/create', [AsistenciaController::class, 'create'])->name('asistencias.create');
    Route::post('/asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');
    Route::get('/asistencias/docentes/{materiaId}/{aulaId}', [AsistenciaController::class, 'getDocentesPorMateria'])->name('asistencias.docentes');
    Route::get('/asistencias/niveles/{nivel}', [AsistenciaController::class, 'indexNivel'])
    ->name('asistencias.index-niveles');
    Route::get('/asistencias/nivel/{nivel}/{aulaId}', [AsistenciaController::class, 'show'])
    ->name('asistencias.show');
    Route::post('/asistencias/get-details', [AsistenciaController::class, 'getAttendanceDetails'])
    ->name('asistencias.get-details');
    Route::post('/asistencias/update-attendance', [AsistenciaController::class, 'updateAttendance'])
    ->name('asistencias.update-attendance');
    Route::get('/asistencias/index-nivel', [AsistenciaController::class, 'indexNivel'])->name('asistencias.index-nivel');



   
    // Rutas de Incidentes
    Route::resource('incidentes', \App\Http\Controllers\IncidenteController::class);
    
    // Rutas de Usuarios
    Route::resource('usuarios', \App\Http\Controllers\UsuarioController::class);
});