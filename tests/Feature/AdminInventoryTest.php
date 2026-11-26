<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminInventoryTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function categoria(): Categoria
    {
        return Categoria::create([
            'nombre' => 'Barras',
            'slug' => 'barras',
        ]);
    }

    public function test_guest_cannot_enter_admin_panel(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Iniciar sesión')
            ->assertDontSee('Ver catálogo');
    }

    public function test_non_admin_cannot_enter_admin_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $this->actingAs($this->admin())->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Productos totales')
            ->assertSee('Crear producto')
            ->assertSee('Nueva venta')
            ->assertDontSee('+ Crear categoría');
    }

    public function test_admin_crud_pages_show_create_actions(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/productos')
            ->assertOk()
            ->assertSee('Crear nuevo producto');

        $this->actingAs($admin)->get('/admin/categorias')
            ->assertOk()
            ->assertSee('Crear nueva categoría');

        $this->actingAs($admin)->get('/admin/productos/create')
            ->assertOk()
            ->assertSee('Guardar producto');

        $this->actingAs($admin)->get('/admin/categorias/create')
            ->assertOk()
            ->assertSee('Guardar');
    }

    public function test_admin_can_create_edit_toggle_and_filter_product(): void
    {
        $admin = $this->admin();
        $categoria = $this->categoria();

        $this->actingAs($admin)->post('/admin/productos', [
            'nombre' => 'Chocolate Especial',
            'categoria_id' => $categoria->id,
            'precio' => 18.50,
            'stock' => 4,
        ])->assertRedirect();

        $producto = Producto::where('nombre', 'Chocolate Especial')->firstOrFail();
        $this->assertEquals(4, $producto->stock);
        $this->get('/catalogo')
            ->assertOk()
            ->assertSee('Chocolate Especial');

        $this->actingAs($admin)->get('/admin/productos?buscar=Especial&categoria='.$categoria->id.'&stock_bajo=1')
            ->assertOk()->assertSee('Chocolate Especial');

        $this->actingAs($admin)->put('/admin/productos/'.$producto->id, [
            'nombre' => 'Chocolate Editado',
            'categoria_id' => $categoria->id,
            'precio' => 20,
            'stock' => 7,
        ])->assertRedirect();
        $this->assertDatabaseHas('productos', ['id' => $producto->id, 'nombre' => 'Chocolate Editado', 'stock' => 4]);

    }

    public function test_invalid_product_data_is_rejected(): void
    {
        $categoria = $this->categoria();

        $this->actingAs($this->admin())->post('/admin/productos', [
            'nombre' => '',
            'categoria_id' => $categoria->id,
            'precio' => -1,
            'stock' => -3,
        ])->assertSessionHasErrors(['nombre', 'precio', 'stock']);
    }

    public function test_category_crud_and_association_protection(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/categorias', [
            'nombre' => 'Bombones',
            'slug' => 'bombones',
        ])->assertRedirect();
        $categoria = Categoria::where('slug', 'bombones')->firstOrFail();

        $this->actingAs($admin)->put('/admin/categorias/'.$categoria->id, [
            'nombre' => 'Bombones especiales',
            'slug' => 'bombones-especiales',
        ])->assertRedirect();

        Producto::create([
            'nombre' => 'Bombón',
            'categoria_id' => $categoria->id,
            'precio' => 5,
            'stock' => 10,
        ]);

        $this->actingAs($admin)->delete('/admin/categorias/'.$categoria->id)
            ->assertSessionHas('error');
        $this->assertDatabaseHas('categorias', ['id' => $categoria->id]);
    }

    public function test_category_validation_rejects_duplicate_values(): void
    {
        $this->categoria();

        $this->actingAs($this->admin())->post('/admin/categorias', [
            'nombre' => 'Barras',
            'slug' => 'barras',
        ])->assertSessionHasErrors(['nombre', 'slug']);
    }

    public function test_product_image_is_validated_stored_and_replaced(): void
    {
        Storage::fake('public');
        $categoria = $this->categoria();
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/productos', [
            'nombre' => 'Chocolate con imagen',
            'categoria_id' => $categoria->id,
            'precio' => 15,
            'stock' => 5,
            'imagen' => UploadedFile::fake()->image('producto.jpg'),
        ])->assertRedirect();

        $producto = Producto::where('nombre', 'Chocolate con imagen')->firstOrFail();
        $this->assertNotNull($producto->imagen_principal);
        Storage::disk('public')->assertExists($producto->imagen_principal);

        $this->actingAs($admin)->put('/admin/productos/'.$producto->id, [
            'nombre' => $producto->nombre,
            'categoria_id' => $categoria->id,
            'precio' => 16,
            'stock' => 5,
            'imagen' => UploadedFile::fake()->image('nuevo.png'),
        ])->assertSessionHasErrors('imagen');

        $this->actingAs($admin)->post('/admin/productos', [
            'nombre' => 'Archivo invalido',
            'categoria_id' => $categoria->id,
            'precio' => 10,
            'stock' => 3,
            'imagen' => UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload'),
        ])->assertSessionHasErrors('imagen');
    }

    public function test_stock_levels_are_publicly_safe(): void
    {
        $categoria = $this->categoria();
        $bajo = Producto::create([
            'nombre' => 'Stock bajo',
            'categoria_id' => $categoria->id,
            'precio' => 10,
            'stock' => 5,
        ]);
        $agotado = Producto::create([
            'nombre' => 'Agotado',
            'categoria_id' => $categoria->id,
            'precio' => 10,
            'stock' => 0,
        ]);

        $this->actingAs($this->admin())->get('/admin/dashboard')
            ->assertOk()->assertSee('Stock bajo')->assertSee('Agotado');

        $this->get('/catalogo')->assertSee('Stock bajo')->assertDontSee('Agotado');
        $this->get('/catalogo/'.$agotado->id)->assertNotFound();

        $this->postJson('/carrito/validar', [
            'items' => [['id' => $agotado->id, 'quantity' => 1]],
        ])->assertJsonValidationErrors('items.0.id');
    }

    public function test_admin_panel_has_no_contact_queries_routes_or_links(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/dashboard')
            ->assertOk()
            ->assertDontSee('Consultas')
            ->assertDontSee('/admin/contactos');
        $this->actingAs($admin)->get('/admin/contactos')->assertNotFound();
    }

    public function test_stock_adjustment_requires_reason_and_does_not_record_no_change(): void
    {
        $admin = $this->admin();
        $categoria = $this->categoria();
        $producto = Producto::create([
            'nombre' => 'Producto de stock',
            'categoria_id' => $categoria->id,
            'precio' => 10,
            'stock' => 3,
        ]);

        $this->actingAs($admin)->patch('/admin/productos/'.$producto->id.'/stock', [
            'stock' => 3,
            'motivo' => 'Sin cambio',
        ])->assertRedirect();
        $this->assertDatabaseCount('movimientos_stock', 0);

        $this->actingAs($admin)->patch('/admin/productos/'.$producto->id.'/stock', [
            'stock' => 5,
            'motivo' => '',
        ])->assertSessionHasErrors('motivo');
    }

    public function test_category_delete_and_restore_are_protected_and_recorded(): void
    {
        $admin = $this->admin();
        $categoria = $this->categoria();

        $this->actingAs($admin)->delete('/admin/categorias/'.$categoria->id)->assertRedirect();
        $this->assertSoftDeleted('categorias', ['id' => $categoria->id]);

        $this->actingAs($admin)->patch('/admin/categorias/'.$categoria->id.'/restaurar')->assertRedirect();
        $this->assertDatabaseHas('categorias', ['id' => $categoria->id, 'deleted_at' => null]);
    }

    public function test_product_deletion_can_be_restored_and_stock_changes_are_recorded(): void
    {
        $admin = $this->admin();
        $categoria = $this->categoria();
        $producto = Producto::create([
            'nombre' => 'Producto auditable',
            'categoria_id' => $categoria->id,
            'precio' => 10,
            'stock' => 2,
        ]);

        $this->actingAs($admin)->patch('/admin/productos/'.$producto->id.'/stock', [
            'stock' => 6,
            'motivo' => 'Reposición semanal',
        ])->assertRedirect();

        $this->assertDatabaseHas('movimientos_stock', [
            'producto_id' => $producto->id,
            'tipo' => 'entrada',
            'cantidad' => 4,
            'motivo' => 'Reposición semanal',
        ]);

        $this->actingAs($admin)->delete('/admin/productos/'.$producto->id)
            ->assertRedirect();
        $this->assertSoftDeleted('productos', ['id' => $producto->id]);

        $this->actingAs($admin)->patch('/admin/productos/'.$producto->id.'/restaurar')
            ->assertRedirect();
        $this->assertDatabaseHas('productos', ['id' => $producto->id, 'deleted_at' => null]);

        $this->actingAs($admin)->get('/admin/auditoria')
            ->assertOk()
            ->assertSee('stock_changed')
            ->assertSee('restored');
    }
}
