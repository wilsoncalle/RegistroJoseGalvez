<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriasSeeder extends Seeder
{
    public function run()
    {
        // Materias para Nivel Secundaria (id_nivel = 3)
        $materiasSecundaria = [
            ['nombre' => 'Lenguaje', 'id_nivel' => 3],
            ['nombre' => 'Idioma Extranjero', 'id_nivel' => 3],
            ['nombre' => 'Historia y Geografía', 'id_nivel' => 3],
            ['nombre' => 'Educación Religiosa', 'id_nivel' => 3],
            ['nombre' => 'Familia y Civismo', 'id_nivel' => 3],
            ['nombre' => 'Matemática', 'id_nivel' => 3],
            ['nombre' => 'Arte y Creatividad', 'id_nivel' => 3],
            ['nombre' => 'Educación Física', 'id_nivel' => 3],
            ['nombre' => 'Ciencias Naturales', 'id_nivel' => 3],
            ['nombre' => 'Educación para el trabajo', 'id_nivel' => 3],
        ];

        DB::table('materias')->insert($materiasSecundaria);
    }
}
