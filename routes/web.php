<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\ApoderadoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\IncidenteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\CalificacionOldController;
use App\Http\Controllers\BackupController;
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

    // Rutas para el sistema de respaldo y restauración
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [BackupController::class, 'createBackup'])->name('backup.create');
    Route::post('/backup/restore', [BackupController::class, 'restoreBackup'])->name('backup.restore');
    Route::post('/backup/import', [BackupController::class, 'importDatabase'])->name('backup.import');
    Route::get('/backup/download/{filename}', [BackupController::class, 'downloadBackup'])->name('backup.download');
    Route::delete('/backup/{filename}', [BackupController::class, 'deleteBackup'])->name('backup.delete');
    Route::post('/backup/schedule', [BackupController::class, 'saveSchedule'])->name('backup.schedule');
    Route::get('/backup/run-scheduled', [BackupController::class, 'runScheduledBackup'])->name('backup.run-scheduled');
    
// Rutas de Estudiantes
Route::resource('estudiantes', \App\Http\Controllers\EstudianteController::class);
Route::get('/get-aulas-por-nivel/{nivelId}', [EstudianteController::class, 'getAulasPorNivel']);
Route::post('/estudiantes/consultar-dni', [EstudianteController::class, 'consultarDni'])->name('estudiantes.consultar-dni');

// Rutas para creación masiva de estudiantes
Route::get('/estudiantes-bulk', [EstudianteController::class, 'bulkCreate'])->name('estudiantes.bulk-create');
Route::post('/estudiantes-bulk', [EstudianteController::class, 'storeBulk'])->name('estudiantes.store-bulk');

// Rutas para importación de estudiantes
Route::get('/estudiantes-import', [EstudianteController::class, 'importForm'])->name('estudiantes.import');
Route::post('/estudiantes-import', [EstudianteController::class, 'importar'])->name('estudiantes.importar');
Route::post('/estudiantes-preview', [EstudianteController::class, 'preview'])->name('estudiantes.preview');
Route::get('/estudiantes-plantilla', [EstudianteController::class, 'descargarPlantilla'])->name('estudiantes.descargar-plantilla');

// Rutas para eliminación masiva de estudiantes
Route::get('/estudiantes-bulk-delete', [EstudianteController::class, 'bulkDeleteForm'])->name('estudiantes.bulk-delete-form');
Route::post('/estudiantes-bulk-delete', [EstudianteController::class, 'bulkDelete'])->name('estudiantes.bulk-delete');
    
// Rutas de Docentes
Route::resource('docentes', \App\Http\Controllers\DocenteController::class);
Route::get('aulas/nivel/{nivelId}', [EstudianteController::class, 'getAulasPorNivel'])->name('aulas.nivel');
Route::get('materias-por-nivel', [DocenteController::class, 'getMateriasPorNivel'])->name('materias.pornivel');



// Rutas de Apoderados
Route::resource('apoderados', \App\Http\Controllers\ApoderadoController::class);
Route::get('/apoderados/buscar-estudiantes', [ApoderadoController::class, 'buscarEstudiantes'])
     ->name('apoderados.buscar-estudiantes');
    
// Rutas de Niveles
Route::resource('aulas', \App\Http\Controllers\AulaController::class);
Route::get('/aulas/get-grados/{nivelId}', [AulaController::class, 'getGradosPorNivel']);
Route::get('/aulas/get-secciones/{nivelId}/{gradoId}', [AulaController::class, 'getSeccionesPorGradoNivel']);
Route::get('aulas/getGrados/{id_nivel}', [AulaController::class, 'getGrados'])->name('aulas.getGrados');
Route::get('aulas/getSecciones/{id_grado}', [AulaController::class, 'getSecciones'])->name('aulas.getSecciones');

    
// Rutas de Grados
Route::resource('grados', \App\Http\Controllers\GradoController::class);
    
// Rutas de Secciones
Route::resource('secciones', SeccionController::class)->parameters(['secciones' => 'seccion']);
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
Route::get('materias/nivel/{nivelId}', [AsignacionController::class, 'getMateriasPorNivel']);
Route::get('/docentes/nivel/{nivelId}', [AsignacionController::class, 'getDocentesPorNivel']);

// Rutas de Calificaciones
Route::resource('calificaciones', \App\Http\Controllers\CalificacionController::class);

// Redirección de la ruta antigua a la nueva para compatibilidad
Route::get('/calificacionesOld', function() {
    return redirect('/calificaciones-old');
});

// Rutas para calificaciones de estudiantes egresados
Route::prefix('calificaciones-old')->name('calificaciones-old.')->group(function () {
     Route::get('/', [CalificacionOldController::class, 'index'])->name('index');
     Route::get('/{nivel}', [CalificacionOldController::class, 'indexNivel'])->name('index-nivel');
     Route::get('/{nivel}/{aulaId}', [CalificacionOldController::class, 'createShow'])->name('create-show');
     
     // Rutas API para gestión de calificaciones
     Route::post('/get-calificaciones', [CalificacionOldController::class, 'getCalificaciones'])->name('get-calificaciones');
     Route::post('/save-calificaciones', [CalificacionOldController::class, 'saveCalificaciones'])->name('save-calificaciones');
     
     // Rutas para exportación
     Route::post('/export/excel', [CalificacionOldController::class, 'exportToExcel'])->name('export.excel');
     Route::post('/export/pdf', [CalificacionOldController::class, 'exportToPdf'])->name('export.pdf');
 });
    
// Rutas de Asistencias
Route::resource('asistencia', AsistenciaController::class);
Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
Route::get('/asistencias/nivel/{nivel}', [AsistenciaController::class, 'indexNivel'])->name('asistencias.nivel');
Route::get('/docentes/{materiaId}/{aulaId}', [AsistenciaController::class, 'getDocentesPorMateria']);
Route::get('/asistencias/create', [AsistenciaController::class, 'create'])->name('asistencias.create');
Route::post('/asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');
Route::get('/asistencias/docentes/{materiaId}/{aulaId}', [AsistenciaController::class, 'getDocentesPorMateria'])->name('asistencias.docentes');
Route::get('/asistencias/niveles/{nivel}', [AsistenciaController::class, 'indexNivel'])->name('asistencias.index-niveles');
Route::get('/asistencias/nivel/{nivel}/{aulaId}', [AsistenciaController::class, 'show'])->name('asistencias.show');
Route::post('/asistencias/get-details', [AsistenciaController::class, 'getAttendanceDetails'])->name('asistencias.get-details');
Route::post('/asistencias/update-attendance', [AsistenciaController::class, 'updateAttendance'])->name('asistencias.update-attendance');
Route::get('/asistencias/index-nivel', [AsistenciaController::class, 'indexNivel'])->name('asistencias.index-nivel');

// Rutas de Incidentes
Route::resource('incidentes', \App\Http\Controllers\IncidenteController::class);
    
 // Rutas de Usuarios
Route::resource('usuarios', \App\Http\Controllers\UsuarioController::class);
Route::patch('/usuarios/{usuario}/cambiar-estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.cambiar-estado');

// Rutas de Perfil
Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});


//RUTAS DE EXPORTS TO EXCEL AND TO PDF

//Ruta de exportacion de Estudiantes
Route::get('/export-excel', [EstudianteController::class, 'exportExcel'])->name('estudiantes.exportExcel');
Route::get('/export-pdf', [EstudianteController::class, 'exportPdf'])->name('estudiantes.exportPdf');

// Exportación de Asistencia
// Exportación a Excel
Route::post('/asistencias/export/excel', [AsistenciaController::class, 'exportToExcel'])->name('asistencias.export.excel');
// Exportación a Pdf
Route::post('/asistencias/pdf', [AsistenciaController::class, 'exportToPdf'])->name('asistencias.pdf');


// Rutas para docentes
Route::resource('docentes', DocenteController::class);
Route::get('docentes-export', [DocenteController::class, 'exportarExcel'])->name('docentes.export');
Route::get('docentes-pdf', [DocenteController::class, 'exportarPDF'])->name('docentes.pdf');
Route::post('/docentes/consultar-dni', [DocenteController::class, 'consultarDni'])->name('docentes.consultar-dni');

// Rutas para apoderados export
Route::get('apoderados-export', [ApoderadoController::class, 'exportarExcel'])->name('apoderados.export');
Route::get('apoderados-pdf', [ApoderadoController::class, 'exportarPDF'])->name('apoderados.pdf');

// Rutas para aulas export
Route::get('aulas-export', [AulaController::class, 'exportarExcel'])->name('aulas.export');
Route::get('aulas-pdf', [AulaController::class, 'exportarPDF'])->name('aulas.pdf');

// Rutas para asignaciones export
Route::get('asignaciones-export', [AsignacionController::class, 'exportarExcel'])->name('asignaciones.export');
Route::get('asignaciones-pdf', [AsignacionController::class, 'exportarPDF'])->name('asignaciones.pdf');

// Rutas para materias export
Route::get('materias-export', [\App\Http\Controllers\MateriaController::class, 'exportarExcel'])->name('materias.export');
Route::get('materias-pdf', [\App\Http\Controllers\MateriaController::class, 'exportarPDF'])->name('materias.pdf');



//API DE CONSULTAR DNI
//Apoderados
Route::post('/apoderados/consultar-dni', [ApoderadoController::class, 'consultarDni'])->name('apoderados.consultar-dni');