<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos tu usuario administrador siempre
        User::factory()->create([
            'name' => 'Admin Mario',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Llamamos al seeder de categorías y productos que ya hicimos
        $this->call([
            CategoriaProductoSeeder::class,
        ]);
    }
}