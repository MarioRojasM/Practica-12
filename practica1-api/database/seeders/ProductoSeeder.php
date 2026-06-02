<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto; 

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        Producto::create([
            'nombre' => 'Teclado Mecánico',
            'descripcion' => 'Teclado RGB con switches azules',
            'precio' => 1200.50,
            'stock' => 15
        ]);

        Producto::create([
            'nombre' => 'Mouse Inalámbrico',
            'descripcion' => 'Mouse ergonómico recargable',
            'precio' => 450.00,
            'stock' => 30
        ]);

        Producto::create([
            'nombre' => 'Monitor 24 pulgadas',
            'descripcion' => 'Monitor Full HD 144Hz',
            'precio' => 3500.00,
            'stock' => 10
        ]);
    }
}