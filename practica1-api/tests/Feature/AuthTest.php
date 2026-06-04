<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // Esto resetea la base de datos falsa en cada prueba
    use RefreshDatabase; 

    public function test_usuario_puede_registrarse(): void
    {
        // Simulamos que alguien llena el formulario de registro
        $response = $this->postJson('/api/register', [
            'name'                  => 'Juan López',
            'email'                 => 'juan@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Verificamos que responda con éxito 201 y devuelva el token
        $response->assertStatus(201)
                 ->assertJsonStructure(['access_token', 'user']); // <-- ¡AQUÍ ESTÁ LA CORRECCIÓN!
                 
        // Verificamos que Juan realmente exista en la base de datos
        $this->assertDatabaseHas('users', ['email' => 'juan@test.com']); 
    }

    public function test_login_con_credenciales_incorrectas(): void
    {
        // Intentamos entrar con un usuario fantasma
        $response = $this->postJson('/api/login', [
            'email'    => 'noexiste@test.com',
            'password' => 'wrongpass',
        ]);
        
        // Laravel debe rebotarnos con un 401 No Autorizado
        $response->assertStatus(401);
    }
    public function test_usuario_puede_iniciar_sesion_correctamente(): void
    {
        // Creamos un usuario real para probar
        $user = \App\Models\User::factory()->create([
            'email' => 'mario@test.com',
            'password' => bcrypt('password123'),
        ]);

        // Intentamos loguearnos con él
        $response = $this->postJson('/api/login', [
            'email' => 'mario@test.com',
            'password' => 'password123',
        ]);

        // Verificamos que sí nos deje entrar y nos dé token
        $response->assertStatus(200)->assertJsonStructure(['access_token']);
    }

    public function test_usuario_puede_cerrar_sesion(): void
    {
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Le mandamos el token en los headers para poder desloguearnos
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->postJson('/api/logout');

        $response->assertStatus(200);
    }
}