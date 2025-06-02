<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use ZipArchive;
use App\Models\BackupSchedule;

class BackupController extends Controller
{
    /**
     * Display the backup and restore page
     */
    public function index()
    {
        // Obtener los backups existentes
        $backups = $this->getBackupFiles();
        
        // Obtener la configuración de programación actual
        $schedule = BackupSchedule::first() ?? new BackupSchedule([
            'frequency' => 'weekly',
            'day_of_week' => 1, // Lunes
            'time' => '00:00',
            'retention_count' => 5,
            'auto_backup' => false
        ]);
        
        return view('backup.index', compact('backups', 'schedule'));
    }
    
    /**
     * Realizar un backup manual de la base de datos
     */
    public function createBackup(Request $request)
    {
        try {
            $dbPath = database_path('database.sqlite');
            
            if (!File::exists($dbPath)) {
                return redirect()->route('backup.index')
                    ->with('error', 'El archivo de base de datos no existe en la ruta esperada.');
            }
            
            // Crear directorio si no existe
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            // Nombre del archivo de backup
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $backupFileName = "backup_{$timestamp}.zip";
            $backupFilePath = "{$backupPath}/{$backupFileName}";
            
            // Crear archivo ZIP
            $zip = new ZipArchive();
            if ($zip->open($backupFilePath, ZipArchive::CREATE) === TRUE) {
                // Añadir el archivo de base de datos
                $zip->addFile($dbPath, 'database.sqlite');
                
                // Opcionalmente, añadir archivos de media si se solicita
                if ($request->has('include_media')) {
                    $mediaPath = storage_path('app/public');
                    $this->addFolderToZip($zip, $mediaPath, 'media');
                }
                
                $zip->close();
                
                // Limpiar backups antiguos si exceden el límite de retención
                $this->cleanOldBackups();
                
                return redirect()->route('backup.index')
                    ->with('success', 'Copia de seguridad creada correctamente.');
            } else {
                return redirect()->route('backup.index')
                    ->with('error', 'No se pudo crear el archivo ZIP.');
            }
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Error al crear la copia de seguridad: ' . $e->getMessage());
        }
    }
    
    /**
     * Restaurar la base de datos desde un backup
     */
    public function restoreBackup(Request $request)
    {
        try {
            $backupFileName = $request->input('backup_file');
            $backupPath = storage_path('app/backups');
            $backupFilePath = "{$backupPath}/{$backupFileName}";
            
            if (!File::exists($backupFilePath)) {
                return redirect()->route('backup.index')
                    ->with('error', 'El archivo de backup seleccionado no existe.');
            }
            
            // Extraer el backup
            $zip = new ZipArchive();
            if ($zip->open($backupFilePath) === TRUE) {
                $extractPath = storage_path('app/temp_restore');
                
                // Limpiar directorio temporal si existe
                if (File::exists($extractPath)) {
                    File::deleteDirectory($extractPath);
                }
                
                File::makeDirectory($extractPath, 0755, true);
                $zip->extractTo($extractPath);
                $zip->close();
                
                // Verificar que el archivo de la base de datos existe en el backup
                $extractedDbPath = "{$extractPath}/database.sqlite";
                if (!File::exists($extractedDbPath)) {
                    return redirect()->route('backup.index')
                        ->with('error', 'El archivo de backup no contiene una base de datos válida.');
                }
                
                // Cerrar todas las conexiones a la base de datos
                DB::disconnect();
                
                // Reemplazar la base de datos actual
                $currentDbPath = database_path('database.sqlite');
                
                // Hacer un backup de seguridad antes de restaurar
                $safetyBackup = database_path('database_before_restore.sqlite');
                File::copy($currentDbPath, $safetyBackup);
                
                // Reemplazar el archivo de base de datos
                File::copy($extractedDbPath, $currentDbPath);
                
                // Restaurar archivos de media si existen en el backup
                if (File::exists("{$extractPath}/media")) {
                    $mediaDestPath = storage_path('app/public');
                    File::copyDirectory("{$extractPath}/media", $mediaDestPath);
                }
                
                // Limpiar archivos temporales
                File::deleteDirectory($extractPath);
                
                // Regenerar caché
                Artisan::call('config:clear');
                Artisan::call('cache:clear');
                
                return redirect()->route('backup.index')
                    ->with('success', 'Base de datos restaurada correctamente.');
            } else {
                return redirect()->route('backup.index')
                    ->with('error', 'No se pudo abrir el archivo de backup.');
            }
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Error al restaurar la base de datos: ' . $e->getMessage());
        }
    }
    
    /**
     * Descargar un archivo de backup
     */
    public function downloadBackup($filename)
    {
        try {
            $backupPath = storage_path("app/backups/{$filename}");
            
            if (!File::exists($backupPath)) {
                return redirect()->route('backup.index')
                    ->with('error', 'El archivo de backup no existe.');
            }
            
            return response()->download($backupPath);
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Error al descargar el backup: ' . $e->getMessage());
        }
    }
    
    /**
     * Eliminar un archivo de backup
     */
    public function deleteBackup($filename)
    {
        try {
            $backupPath = storage_path("app/backups/{$filename}");
            
            if (File::exists($backupPath)) {
                File::delete($backupPath);
                return redirect()->route('backup.index')
                    ->with('success', 'Backup eliminado correctamente.');
            }
            
            return redirect()->route('backup.index')
                ->with('error', 'El archivo de backup no existe.');
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Error al eliminar el backup: ' . $e->getMessage());
        }
    }
    
    /**
     * Importar un archivo SQL o SQLite
     */
    public function importDatabase(Request $request)
    {
        try {
            if (!$request->hasFile('database_file')) {
                return redirect()->route('backup.index')
                    ->with('error', 'No se ha proporcionado ningún archivo.');
            }
            
            $file = $request->file('database_file');
            $extension = $file->getClientOriginalExtension();
            
            if (!in_array($extension, ['sqlite', 'db', 'sql', 'zip'])) {
                return redirect()->route('backup.index')
                    ->with('error', 'El archivo debe tener formato SQLite, SQL o ZIP.');
            }
            
            // Crear backup de seguridad primero
            $dbPath = database_path('database.sqlite');
            $safetyBackup = database_path('database_before_import.sqlite');
            File::copy($dbPath, $safetyBackup);
            
            // Procesar según el tipo de archivo
            if ($extension === 'zip') {
                // Manejar archivo ZIP
                $tempPath = storage_path('app/temp_import');
                if (File::exists($tempPath)) {
                    File::deleteDirectory($tempPath);
                }
                File::makeDirectory($tempPath, 0755, true);
                
                $zip = new ZipArchive();
                if ($zip->open($file->path()) === TRUE) {
                    $zip->extractTo($tempPath);
                    $zip->close();
                    
                    // Buscar el archivo de base de datos en el ZIP
                    $sqliteFile = null;
                    foreach (File::allFiles($tempPath) as $extractedFile) {
                        $fileExt = $extractedFile->getExtension();
                        if (in_array($fileExt, ['sqlite', 'db'])) {
                            $sqliteFile = $extractedFile->getPathname();
                            break;
                        }
                    }
                    
                    if ($sqliteFile) {
                        // Cerrar conexiones a la base de datos
                        DB::disconnect();
                        
                        // Reemplazar base de datos
                        File::copy($sqliteFile, $dbPath);
                        
                        // Limpiar archivos temporales
                        File::deleteDirectory($tempPath);
                        
                        // Regenerar caché
                        Artisan::call('config:clear');
                        Artisan::call('cache:clear');
                        
                        return redirect()->route('backup.index')
                            ->with('success', 'Base de datos importada correctamente desde archivo ZIP.');
                    } else {
                        File::deleteDirectory($tempPath);
                        return redirect()->route('backup.index')
                            ->with('error', 'No se encontró un archivo de base de datos válido en el ZIP.');
                    }
                } else {
                    return redirect()->route('backup.index')
                        ->with('error', 'No se pudo abrir el archivo ZIP.');
                }
            } elseif (in_array($extension, ['sqlite', 'db'])) {
                // Manejar archivo SQLite directo
                DB::disconnect();
                
                // Reemplazar el archivo de base de datos
                File::copy($file->path(), $dbPath);
                
                // Regenerar caché
                Artisan::call('config:clear');
                Artisan::call('cache:clear');
                
                return redirect()->route('backup.index')
                    ->with('success', 'Base de datos SQLite importada correctamente.');
            } elseif ($extension === 'sql') {
                // Manejar archivo SQL
                // Esto requeriría parsear el SQL y ejecutarlo, lo cual es más complejo
                // y depende del formato exacto del archivo SQL
                return redirect()->route('backup.index')
                    ->with('error', 'La importación de archivos SQL no está soportada actualmente.');
            }
            
            return redirect()->route('backup.index')
                ->with('error', 'Formato de archivo no soportado.');
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Error al importar la base de datos: ' . $e->getMessage());
        }
    }
    
    /**
     * Guardar la configuración de programación de backups
     */
    public function saveSchedule(Request $request)
    {
        try {
            $request->validate([
                'frequency' => 'required|in:daily,weekly,monthly',
                'day_of_week' => 'required_if:frequency,weekly|integer|min:0|max:6',
                'day_of_month' => 'required_if:frequency,monthly|integer|min:1|max:31',
                'time' => 'required|date_format:H:i',
                'retention_count' => 'required|integer|min:1|max:100',
                'auto_backup' => 'boolean'
            ]);
            
            $schedule = BackupSchedule::first();
            if (!$schedule) {
                $schedule = new BackupSchedule();
            }
            
            $schedule->frequency = $request->frequency;
            $schedule->day_of_week = $request->frequency == 'weekly' ? $request->day_of_week : null;
            $schedule->day_of_month = $request->frequency == 'monthly' ? $request->day_of_month : null;
            $schedule->time = $request->time;
            $schedule->retention_count = $request->retention_count;
            $schedule->auto_backup = $request->has('auto_backup');
            $schedule->last_run = null; // Resetear para que se ejecute en la próxima oportunidad
            
            $schedule->save();
            
            // Actualizar la tarea programada en el servidor
            // (Esto debería integrarse con el Task Scheduler de Windows o cron en Linux)
            
            return redirect()->route('backup.index')
                ->with('success', 'Configuración de programación guardada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Error al guardar la configuración: ' . $e->getMessage());
        }
    }
    
    /**
     * Ejecutar el backup programado
     * Este método sería llamado por un cron job o tarea programada
     */
    public function runScheduledBackup()
    {
        try {
            $schedule = BackupSchedule::first();
            
            if (!$schedule || !$schedule->auto_backup) {
                return "Backup automático desactivado.";
            }
            
            $now = Carbon::now();
            $lastRun = $schedule->last_run ? Carbon::parse($schedule->last_run) : null;
            
            $shouldRun = false;
            
            // Verificar si es hora de ejecutar el backup según la programación
            if ($schedule->frequency === 'daily') {
                $scheduledTime = Carbon::parse($schedule->time);
                $todayScheduled = Carbon::today()->setHour($scheduledTime->hour)->setMinute($scheduledTime->minute);
                
                $shouldRun = !$lastRun || $lastRun->lt($todayScheduled) && $now->gte($todayScheduled);
            } elseif ($schedule->frequency === 'weekly') {
                $shouldRun = $now->dayOfWeek == $schedule->day_of_week;
                
                if ($shouldRun && $lastRun) {
                    $shouldRun = $lastRun->lt($now->copy()->startOfDay());
                }
            } elseif ($schedule->frequency === 'monthly') {
                $shouldRun = $now->day == $schedule->day_of_month;
                
                if ($shouldRun && $lastRun) {
                    $shouldRun = $lastRun->lt($now->copy()->startOfDay());
                }
            }
            
            if ($shouldRun) {
                // Crear el backup
                $dbPath = database_path('database.sqlite');
                
                if (!File::exists($dbPath)) {
                    return "El archivo de base de datos no existe.";
                }
                
                // Crear directorio si no existe
                $backupPath = storage_path('app/backups');
                if (!File::exists($backupPath)) {
                    File::makeDirectory($backupPath, 0755, true);
                }
                
                // Nombre del archivo de backup
                $timestamp = $now->format('Y-m-d_H-i-s');
                $backupFileName = "backup_auto_{$timestamp}.zip";
                $backupFilePath = "{$backupPath}/{$backupFileName}";
                
                // Crear archivo ZIP
                $zip = new ZipArchive();
                if ($zip->open($backupFilePath, ZipArchive::CREATE) === TRUE) {
                    // Añadir el archivo de base de datos
                    $zip->addFile($dbPath, 'database.sqlite');
                    $zip->close();
                    
                    // Actualizar la fecha de última ejecución
                    $schedule->last_run = $now;
                    $schedule->save();
                    
                    // Limpiar backups antiguos
                    $this->cleanOldBackups();
                    
                    return "Backup automático creado correctamente: {$backupFileName}";
                } else {
                    return "No se pudo crear el archivo ZIP.";
                }
            }
            
            return "No es necesario ejecutar el backup programado en este momento.";
        } catch (\Exception $e) {
            return "Error al ejecutar el backup programado: " . $e->getMessage();
        }
    }
    
    /**
     * Obtener la lista de archivos de backup disponibles
     */
    private function getBackupFiles()
    {
        $backupPath = storage_path('app/backups');
        $files = [];
        
        if (File::exists($backupPath)) {
            $dirContents = File::files($backupPath);
            
            foreach ($dirContents as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                    $files[] = [
                        'name' => $file->getFilename(),
                        'size' => $this->formatSize($file->getSize()),
                        'date' => Carbon::createFromTimestamp($file->getMTime())->format('d/m/Y H:i:s'),
                        'is_auto' => strpos($file->getFilename(), 'backup_auto_') === 0
                    ];
                }
            }
            
            // Ordenar por fecha de modificación (más reciente primero)
            usort($files, function($a, $b) {
                $dateA = Carbon::createFromFormat('d/m/Y H:i:s', $a['date']);
                $dateB = Carbon::createFromFormat('d/m/Y H:i:s', $b['date']);
                return $dateB->timestamp - $dateA->timestamp;
            });
        }
        
        return $files;
    }
    
    /**
     * Formatear el tamaño de archivo a una representación legible
     */
    private function formatSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }
    
    /**
     * Añadir un directorio completo a un archivo ZIP
     */
    private function addFolderToZip($zip, $folder, $zipFolder)
    {
        if (!File::exists($folder)) {
            return;
        }
        
        $files = File::allFiles($folder);
        
        foreach ($files as $file) {
            $relativePath = $zipFolder . '/' . str_replace('\\', '/', $file->getRelativePathname());
            $zip->addFile($file->getPathname(), $relativePath);
        }
    }
    
    /**
     * Eliminar backups antiguos según la configuración de retención
     */
    private function cleanOldBackups()
    {
        $schedule = BackupSchedule::first();
        $retentionCount = $schedule ? $schedule->retention_count : 5;
        
        $backups = $this->getBackupFiles();
        
        if (count($backups) > $retentionCount) {
            // Separar backups automáticos y manuales
            $autoBackups = array_filter($backups, function($backup) {
                return $backup['is_auto'];
            });
            
            $manualBackups = array_filter($backups, function($backup) {
                return !$backup['is_auto'];
            });
            
            // Eliminar backups automáticos antiguos primero
            $this->deleteOldBackups($autoBackups, $retentionCount);
            
            // Si todavía hay demasiados, eliminar algunos manuales
            $totalRemaining = count($this->getBackupFiles());
            if ($totalRemaining > $retentionCount) {
                $this->deleteOldBackups($manualBackups, $retentionCount - count($autoBackups));
            }
        }
    }
    
    /**
     * Eliminar backups antiguos de una lista
     */
    private function deleteOldBackups($backups, $keepCount)
    {
        $backupPath = storage_path('app/backups');
        $toDelete = array_slice($backups, $keepCount);
        
        foreach ($toDelete as $backup) {
            $filePath = "{$backupPath}/{$backup['name']}";
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
    }
}
