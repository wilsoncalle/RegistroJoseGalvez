<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Nivel;
use App\Models\Docente;

class DocenteSeeder extends Seeder
{
    // database/seeders/DocenteSeeder.php
    public function run()
{
    // Obtener IDs de niveles (versión segura)
    $niveles = [
        'Inicial' => 3,
        'Primaria' => 12,
        'Secundaria' => 10
    ];

    foreach ($niveles as $nivelNombre => $cantidad) {
        if ($nivel = Nivel::where('nombre', $nivelNombre)->first()) {
            Docente::factory()
                ->count($cantidad)
                ->create(['id_nivel' => $nivel->id_nivel]);
        }
    }
}
}
