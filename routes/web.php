<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\DashboardController;

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
    
    // Rutas de Docentes
    Route::resource('docentes', \App\Http\Controllers\DocenteController::class);
    
    // Rutas de Apoderados
    Route::resource('apoderados', \App\Http\Controllers\ApoderadoController::class);
    
    // Rutas de Niveles
    Route::resource('aulas', \App\Http\Controllers\AulaController::class);
    
    // Rutas de Grados
    Route::resource('grados', \App\Http\Controllers\GradoController::class);
    
    // Rutas de Secciones
    Route::resource('secciones', SeccionController::class)->parameters([
        'secciones' => 'seccion'
    ]);
    
    // Rutas de Materias
    Route::resource('materias', \App\Http\Controllers\MateriaController::class);
    
    // Rutas de Años Académicos
    Route::resource('anios', \App\Http\Controllers\AnioAcademicoController::class);
    
    // Rutas de Trimestres
    Route::resource('trimestres', \App\Http\Controllers\TrimestreController::class);
    
    // Rutas de Asignaciones
    Route::resource('asignaciones', \App\Http\Controllers\AsignacionController::class);
    
    // Rutas de Calificaciones
    Route::resource('calificaciones', \App\Http\Controllers\CalificacionController::class);
    
    // Rutas de Asistencia
    Route::resource('asistencia', \App\Http\Controllers\AsistenciaController::class);
    
    // Rutas de Incidentes
    Route::resource('incidentes', \App\Http\Controllers\IncidenteController::class);
    
    // Rutas de Usuarios
    Route::resource('usuarios', \App\Http\Controllers\UsuarioController::class);
});