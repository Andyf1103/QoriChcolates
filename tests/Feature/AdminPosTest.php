<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPosTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function producto(int $stock = 10, float $precio = 12.50): Producto
    {
        $categoria = Categoria::create(['nombre' => 'POS', 'slug' => 'pos']);

        return Producto::create([
            'nombre' => 'Chocolate POS',
            'categoria_id' => $categoria->id,
            'precio' => $precio,
            'stock' => $stock,
            'activo' => true,
        ]);
    }

    public function test_admin_can_load_pos_with_product_json(): void
    {
        $admin = $this->admin();
        $producto = $this->producto();

        $this->actingAs($admin)
            ->get('/admin/ventas/pos')
            ->assertOk()
            ->assertSee('function posVentas()', false)
            ->assertSeeInOrder([
                '"id":'.$producto->id,
                '"nombre":"Chocolate POS"',
                '"precio":12.5',
                '"stock":10',
            ], false)
            ->assertSee('get cambio()', false)
            ->assertSee('El efectivo recibido es insuficiente.', false)
            ->assertSee('role="dialog"', false)
            ->assertDontSee('alert(\'El efectivo recibido es insuficiente.\')', false);
    }

    public function test_admin_can_sell_with_cash_and_calculate_change(): void
    {
        $admin = $this->admin();
        $producto = $this->producto();

        $this->actingAs($admin)->post('/admin/ventas', [
            'metodo_pago' => Venta::PAGO_EFECTIVO,
            'monto_recibido' => '50.00',
            'items' => [['id' => $producto->id, 'cantidad' => 2]],
        ])->assertRedirect('/admin/ventas/pos');

        $venta = Venta::firstOrFail();
        $this->assertEquals('25.00', $venta->total);
        $this->assertEquals('25.00', $venta->cambio);
        $this->assertDatabaseHas('productos', ['id' => $producto->id, 'stock' => 8]);
        $this->assertDatabaseHas('movimientos_stock', ['producto_id' => $producto->id, 'tipo' => 'salida', 'cantidad' => 2]);
    }

    public function test_qr_requires_exact_total(): void
    {
        $admin = $this->admin();
        $producto = $this->producto();

        $this->actingAs($admin)->post('/admin/ventas', [
            'metodo_pago' => Venta::PAGO_QR,
            'monto_recibido' => '12.50',
            'items' => [['id' => $producto->id, 'cantidad' => 1]],
        ])->assertRedirect();
        $this->assertDatabaseCount('ventas', 1);

        $this->actingAs($admin)->post('/admin/ventas', [
            'metodo_pago' => Venta::PAGO_QR,
            'monto_recibido' => '12.00',
            'items' => [['id' => $producto->id, 'cantidad' => 1]],
        ])->assertSessionHasErrors('monto_recibido');
        $this->assertDatabaseCount('ventas', 1);
    }

    public function test_cash_and_stock_rejections(): void
    {
        $admin = $this->admin();
        $producto = $this->producto(1, 10);

        $this->actingAs($admin)->post('/admin/ventas', [
            'metodo_pago' => Venta::PAGO_EFECTIVO,
            'monto_recibido' => '9.00',
            'items' => [['id' => $producto->id, 'cantidad' => 1]],
        ])->assertSessionHasErrors('monto_recibido');

        $this->actingAs($admin)->post('/admin/ventas', [
            'metodo_pago' => Venta::PAGO_EFECTIVO,
            'monto_recibido' => '30.00',
            'items' => [['id' => $producto->id, 'cantidad' => 2]],
        ])->assertSessionHasErrors('items');
        $this->assertDatabaseCount('ventas', 0);
    }

    public function test_annulation_restores_stock_and_excludes_report_total(): void
    {
        $admin = $this->admin();
        $producto = $this->producto();

        $this->actingAs($admin)->post('/admin/ventas', [
            'cliente_nombre' => 'Cliente',
            'cliente_ci' => '123',
            'metodo_pago' => Venta::PAGO_EFECTIVO,
            'monto_recibido' => '20.00',
            'items' => [['id' => $producto->id, 'cantidad' => 1]],
        ]);
        $venta = Venta::firstOrFail();

        $this->actingAs($admin)->patch('/admin/ventas/'.$venta->id.'/anular', [
            'motivo' => 'Error en el cobro',
        ])->assertRedirect();

        $this->assertEquals(Venta::ESTADO_ANULADA, $venta->fresh()->estado);
        $this->assertDatabaseHas('productos', ['id' => $producto->id, 'stock' => 10]);
        $this->assertDatabaseHas('movimientos_stock', ['tipo' => 'entrada', 'motivo' => 'Anulación '.$venta->numero.': Error en el cobro']);

        $this->actingAs($admin)->get('/admin/ventas')->assertOk()->assertSee('0.00');
    }

    public function test_non_admin_cannot_access_pos_or_create_sale(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $producto = $this->producto();

        $this->actingAs($user)->get('/admin/ventas/pos')->assertForbidden();
        $this->actingAs($user)->post('/admin/ventas', [
            'metodo_pago' => Venta::PAGO_QR,
            'monto_recibido' => '12.50',
            'items' => [['id' => $producto->id, 'cantidad' => 1]],
        ])->assertForbidden();
    }
}
