<?php

namespace Database\Factories;

use App\Models\Docente;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocenteFactory extends Factory
{
    protected $model = Docente::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'dni' => $this->faker->unique()->numerify('########'),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-60 years', '-25 years'),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->numerify('9########'),
            'email' => $this->faker->unique()->safeEmail(),
            'fecha_contratacion' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'id_nivel' => null // Se asignará en el seeder
        ];
    }
}