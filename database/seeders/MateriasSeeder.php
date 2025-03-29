<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriasSeeder extends Seeder
{
    public function run()
    {
        // Materias para Nivel Inicial (id_nivel = 1)
        $materiasInicial = [
            ['nombre' => 'Desarrollo Integral', 'id_nivel' => 1],
            ['nombre' => 'Comunicación y Expresión', 'id_nivel' => 1],
            ['nombre' => 'Pensamiento Matemático', 'id_nivel' => 1],
            ['nombre' => 'Exploración del Entorno', 'id_nivel' => 1],
            ['nombre' => 'Educación Artística', 'id_nivel' => 1],
            ['nombre' => 'Educación Física', 'id_nivel' => 1],
        ];

        // Materias para Nivel Primaria (id_nivel = 2)
        $materiasPrimaria = [
            ['nombre' => 'Comunicación', 'id_nivel' => 2],
            ['nombre' => 'Matemática', 'id_nivel' => 2],
            ['nombre' => 'Ciencias Naturales', 'id_nivel' => 2],
            ['nombre' => 'Ciencias Sociales', 'id_nivel' => 2],
            ['nombre' => 'Educación Artística', 'id_nivel' => 2],
            ['nombre' => 'Educación Física', 'id_nivel' => 2],
            ['nombre' => 'Educación en Valores', 'id_nivel' => 2],
            ['nombre' => 'Inglés', 'id_nivel' => 2],
        ];

        // Materias para Nivel Secundaria (id_nivel = 3)
        $materiasSecundaria = [
            ['nombre' => 'Comunicación', 'id_nivel' => 3],
            ['nombre' => 'Matemática', 'id_nivel' => 3],
            ['nombre' => 'Física', 'id_nivel' => 3],
            ['nombre' => 'Química', 'id_nivel' => 3],
            ['nombre' => 'Biología', 'id_nivel' => 3],
            ['nombre' => 'Historia', 'id_nivel' => 3],
            ['nombre' => 'Geografía', 'id_nivel' => 3],
            ['nombre' => 'Educación Cívica y Ética', 'id_nivel' => 3],
            ['nombre' => 'Inglés', 'id_nivel' => 3],
            ['nombre' => 'Tecnología', 'id_nivel' => 3],
        ];

        // Insertamos todas las materias
        DB::table('materias')->insert(array_merge($materiasInicial, $materiasPrimaria, $materiasSecundaria));
    }
}
