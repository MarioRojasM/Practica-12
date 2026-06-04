<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Producto;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    // Función de ayuda para no repetir el código de login de administrador
    private function actingAsAdmin() 
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        return $this->actingAs($admin, 'sanctum');
    }

    public function test_puede_listar_productos(): void 
    {
        // Creamos 5 productos falsos en la memoria
        Producto::factory(5)->create();
        
        // Entramos como admin y pedimos la lista
        $this->actingAsAdmin()
             ->getJson('/api/productos')
             ->assertOk() // Esperamos un status 200
             ->assertJsonCount(5, 'data'); // Esperamos que vengan 5 items
    }

    public function test_puede_crear_producto(): void 
    {
        $this->actingAsAdmin()
             ->postJson('/api/productos', [
                 'nombre' => 'Laptop Dell',
                 'precio' => 1299.99,
                 'stock'  => 10,
             ])
             ->assertCreated() 
             ->assertJsonPath('nombre', 'Laptop Dell'); // Le quitamos el 'data.'

        $this->assertDatabaseHas('productos', ['nombre' => 'Laptop Dell']);
    }

    public function test_cliente_no_puede_eliminar(): void 
    {
        // Creamos un usuario con rol de cliente
        $cliente  = User::factory()->create(['rol' => 'cliente']);
        $producto = Producto::factory()->create();

        // Entramos como cliente e intentamos borrar el producto
        $this->actingAs($cliente, 'sanctum')
             ->deleteJson("/api/productos/{$producto->id}")
             ->assertForbidden(); // Esperamos un 403 (Prohibido)
    }

    public function test_admin_puede_actualizar_producto(): void
    {
        $producto = Producto::factory()->create();

        $this->actingAsAdmin()
             ->putJson("/api/productos/{$producto->id}", [
                 'nombre' => 'Producto Modificado',
                 'precio' => 150.00,
                 'stock'  => 20,
             ])
             ->assertOk();

        // Verificamos que el cambio se guardó en BD
        $this->assertDatabaseHas('productos', ['nombre' => 'Producto Modificado']);
    }

    public function test_admin_puede_eliminar_producto(): void
    {
        $producto = Producto::factory()->create();

        $this->actingAsAdmin()
             ->deleteJson("/api/productos/{$producto->id}")
             ->assertOk();

        // Verificamos que ya no exista en la BD
        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
    }

    public function test_puede_ver_un_solo_producto(): void
    {
        $producto = Producto::factory()->create();

        // Entramos a la ruta específica de ese producto
        $this->actingAsAdmin()
             ->getJson("/api/productos/{$producto->id}")
             ->assertOk();
    }

    public function test_error_de_validacion_al_crear(): void
    {
        // Intentamos crear un producto enviando todo en blanco
        $this->actingAsAdmin()
             ->postJson('/api/productos', [])
             ->assertStatus(422); // Esperamos el clásico error 422 de validación
    }
    public function test_error_de_validacion_al_actualizar(): void
    {
        $producto = Producto::factory()->create();

        // Intentamos actualizar un producto enviando todo en blanco
        $this->actingAsAdmin()
             ->putJson("/api/productos/{$producto->id}", [])
             ->assertStatus(422); // Esperamos el error de validación
    }

    public function test_puede_listar_categorias(): void
    {
        // Creamos un par de categorías de prueba
        \App\Models\Categoria::factory(3)->create();

        // Llamamos a la ruta pública de categorías que usa tu Vue
        $this->getJson('/api/categorias')
             ->assertOk();
    }
}