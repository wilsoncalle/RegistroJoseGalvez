<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EstudianteFactory extends Factory {
    // database/factories/EstudianteFactory.php

public function definition()
{
    // Obtener IDs de aulas existentes dinámicamente
    $aulasExistentes = \App\Models\Aula::pluck('id_aula')->toArray();
    
    return [
        'nombre' => $this->faker->firstName(),
        'apellido' => $this->faker->lastName(),
        'dni' => $this->faker->unique()->numerify('########'),
        'fecha_nacimiento' => $this->faker->dateTimeBetween('-18 years', '-12 years')->format('Y-m-d'),
        'telefono' => $this->faker->numerify('9########'),
        'id_aula' => $this->faker->randomElement(array_merge($aulasExistentes, [null])),
        'fecha_ingreso' => now()->subYears(rand(1, 3)),
        'estado' => 'Activo'
    ];
}
}