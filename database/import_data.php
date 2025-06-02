<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Leer el archivo SQL
$sqlContent = file_get_contents(__DIR__.'/../mysql_dump.sql');

// Dividir en declaraciones individuales
$statements = array_filter(
    array_map('trim', explode(';', $sqlContent))
);

// Ejecutar cada declaración
foreach ($statements as $statement) {
    if (!empty($statement)) {
        try {
            DB::statement($statement);
            echo "Ejecutado: " . substr($statement, 0, 50) . "...\n";
        } catch (Exception $e) {
            echo "Error en: " . substr($statement, 0, 50) . "...\n";
            echo $e->getMessage() . "\n";
        }
    }
}

echo "Importación completada.\n";
