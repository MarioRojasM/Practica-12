<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // <-- Agrega esta importación

class CategoriaFactory extends Factory
{
    public function definition(): array
    {
        $nombre = $this->faker->unique()->word(); // Generamos el nombre una vez
        
        return [
            'nombre' => $nombre,
            'slug'   => Str::slug($nombre), // Lo convertimos en formato slug (ej. "mi-palabra")
        ];
    }
}