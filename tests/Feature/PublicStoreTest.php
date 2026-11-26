<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicStoreTest extends TestCase
{
    public function test_public_header_exposes_admin_login_link(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Iniciar sesión')
            ->assertSee('/admin/login');
    }

    use RefreshDatabase;

    public function test_public_pages_and_product_detail_are_available(): void
    {
        $categoria = Categoria::create(['nombre' => 'Barras', 'slug' => 'barras']);
        Producto::create([
            'nombre' => 'Producto de prueba',
            'categoria_id' => $categoria->id,
            'precio' => 10,
            'stock' => 2,
        ]);

        $this->get('/catalogo')->assertOk()->assertSee('Producto de prueba');
        $this->get('/catalogo/1')->assertOk();
    }

    public function test_whatsapp_and_phone_links_are_available_across_commercial_pages(): void
    {
        $categoria = Categoria::create(['nombre' => 'Barras', 'slug' => 'barras']);
        $producto = Producto::create([
            'nombre' => 'Chocolate de prueba',
            'categoria_id' => $categoria->id,
            'precio' => 12,
            'stock' => 3,
        ]);

        $this->get('/')->assertOk()->assertSee('59169682107');
        $this->get('/contacto')->assertOk()
            ->assertSee('+591 69682107')
            ->assertSee('wa.me/59169682107');
        $this->get('/catalogo/'.$producto->id)->assertOk()
            ->assertSee('wa.me/59169682107');
    }

    public function test_contact_page_shows_responsive_openstreetmap_location(): void
    {
        $this->get('/contacto')->assertOk()
            ->assertSee('Nuestra ubicación')
            ->assertSee('aspect-video')
            ->assertSee('marker=-19.052707%2C-65.262120')
            ->assertSee('https://www.openstreetmap.org/?mlat=-19.052707&amp;mlon=-65.262120#map=16/-19.052707/-65.262120', false)
            ->assertSee('Abrir mapa en OpenStreetMap');
    }

    public function test_contact_message_is_saved_only_after_validation(): void
    {
        $this->post('/contacto', [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'subject' => 'Disponibilidad',
            'message' => 'Quiero conocer la disponibilidad.',
        ])->assertRedirect();

        $this->assertDatabaseHas('contactos', [
            'email' => 'ana@example.com',
            'asunto' => 'Disponibilidad',
        ]);
        $this->assertSame(1, Contacto::count());
    }

    public function test_cart_validation_recalculates_product_data_from_database(): void
    {
        $categoria = Categoria::create(['nombre' => 'Barras', 'slug' => 'barras']);
        $product = Producto::create([
            'nombre' => 'Producto real',
            'categoria_id' => $categoria->id,
            'precio' => 25,
            'stock' => 2,
        ]);

        $response = $this->postJson('/carrito/validar', [
            'items' => [['id' => $product->id, 'quantity' => 3]],
        ]);

        $response->assertOk()
            ->assertJsonPath('valid', false)
            ->assertJsonPath('items.0.price', 25)
            ->assertJsonPath('items.0.stock', 2);
    }
}
