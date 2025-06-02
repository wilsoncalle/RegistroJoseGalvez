# Script para importar datos SQL a SQLite en dos pasos
$inputFile = "c:\Users\kasey\Escritorio\Gradel\sqlite_datos.sql"
$databaseFile = "c:\Users\kasey\Escritorio\Gradel\database\database.sqlite"
$tempFile = "c:\Users\kasey\Escritorio\Gradel\temp_import.sql"

Write-Host "Procesando archivo SQL para importación..." -ForegroundColor Cyan

# Leer el contenido del archivo
$lines = Get-Content -Path $inputFile

# Filtrar líneas no deseadas y extraer comandos INSERT
$insertCommands = @()
foreach ($line in $lines) {
    # Ignorar líneas LOCK y UNLOCK
    if ($line -match "^LOCK TABLES" -or $line -match "^UNLOCK TABLES") {
        continue
    }
    
    # Guardar líneas que contienen INSERT
    if ($line -match "INSERT INTO") {
        # Modificar para usar INSERT OR IGNORE
        $line = $line -replace "INSERT INTO", "INSERT OR IGNORE INTO"
        $insertCommands += $line
    }
}

# Corregir problemas específicos de sintaxis
$cleanInserts = @()
foreach ($cmd in $insertCommands) {
    # Corregir problemas con comillas y caracteres problemáticos
    $cleanCmd = $cmd
    
    # Corregir el problema específico con VPBXcK626fJqqWMpPPOE22Wv
    if ($cleanCmd -match "VPBXcK626fJqqWMpPPOE22Wv") {
        $cleanCmd = $cleanCmd -replace "'7fkHqOvE9L7pXl2TFwbMMMi4Osr5LrDJJnX'VPBXcK626fJqqWMpPPOE22Wv''", "'7fkHqOvE9L7pXl2TFwbMMMi4Osr5LrDJJnX'"
    }
    
    # Otras correcciones generales
    $cleanCmd = $cleanCmd -replace "''VPBXcK626fJqqWMpPPOE22Wv''", "''"
    
    $cleanInserts += $cleanCmd
}

# Escribir los comandos al archivo temporal (sin PRAGMA ni transacciones)
$cleanInserts | Out-File -FilePath $tempFile -Encoding utf8

Write-Host "Archivo temporal creado en: $tempFile" -ForegroundColor Green

# Paso 1: Configurar la base de datos
Write-Host "Configurando la base de datos..." -ForegroundColor Yellow
$configScript = @"
PRAGMA journal_mode = DELETE;
PRAGMA synchronous = OFF;
PRAGMA foreign_keys = OFF;
PRAGMA ignore_check_constraints = OFF;
"@

$configFile = "c:\Users\kasey\Escritorio\Gradel\config_db.sql"
$configScript | Out-File -FilePath $configFile -Encoding utf8

try {
    $output = & sqlite3 $databaseFile ".read $configFile" 2>&1
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Error al configurar la base de datos:" -ForegroundColor Red
        Write-Host $output -ForegroundColor Red
    }
    else {
        Write-Host "Base de datos configurada correctamente" -ForegroundColor Green
    }
}
catch {
    Write-Host "Error al ejecutar SQLite: $_" -ForegroundColor Red
}

# Paso 2: Importar los datos
Write-Host "Importando datos a la base de datos..." -ForegroundColor Yellow
try {
    $output = & sqlite3 $databaseFile ".read $tempFile" 2>&1
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Error al importar datos:" -ForegroundColor Red
        Write-Host $output -ForegroundColor Red
    }
    else {
        Write-Host "Datos importados correctamente" -ForegroundColor Green
    }
}
catch {
    Write-Host "Error al ejecutar SQLite: $_" -ForegroundColor Red
}

# Limpiar archivos temporales
Remove-Item -Path $configFile -Force
Write-Host "Proceso de importación completado." -ForegroundColor Green
