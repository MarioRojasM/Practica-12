<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Inventamos un nombre de 3 palabras
            'nombre' => fake()->words(3, true), 
            // Inventamos una frase para la descripción
            'descripcion' => fake()->sentence(),
            // Inventamos un precio entre 10 y 1000 con 2 decimales
            'precio' => fake()->randomFloat(2, 10, 1000),
            // Inventamos un número de stock del 1 al 100
            'stock' => fake()->numberBetween(1, 100),
        ];
    }
}