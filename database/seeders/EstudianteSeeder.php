<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estudiante;

class EstudianteSeeder extends Seeder
{
    // database/seeders/EstudianteSeeder.php

public function run()
{
    $aulasExistentes = \App\Models\Aula::pluck('id_aula')->toArray();
    
    // Crear 5 estudiantes sin aula
    \App\Models\Estudiante::factory()
        ->count(5)
        ->create(['id_aula' => null]);
    
    // Crear 10 estudiantes por cada aula existente
    foreach ($aulasExistentes as $aula) {
        \App\Models\Estudiante::factory()
            ->count(10)
            ->create(['id_aula' => $aula]);
    }
}
}