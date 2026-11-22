<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSalesReportTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function sale(array $attributes = []): Venta
    {
        return Venta::create(array_merge([
            'numero' => 'V-TEST-'.uniqid(),
            'cliente_nombre' => 'Cliente reporte',
            'subtotal' => '25.00',
            'total' => '25.00',
            'monto_recibido' => '25.00',
            'cambio' => '0.00',
            'metodo_pago' => Venta::PAGO_EFECTIVO,
            'estado' => Venta::ESTADO_COMPLETADA,
        ], $attributes));
    }

    public function test_admin_can_preview_sales_report_and_filter_results(): void
    {
        $admin = $this->admin();
        $this->sale(['numero' => 'V-FILTRO-001', 'total' => '40.00']);
        $this->sale([
            'numero' => 'V-FILTRO-002',
            'metodo_pago' => Venta::PAGO_QR,
            'estado' => Venta::ESTADO_ANULADA,
            'total' => '15.00',
        ]);

        $this->actingAs($admin)
            ->get('/admin/reportes/ventas?numero=FILTRO-001&metodo=efectivo&estado=completada')
            ->assertOk()
            ->assertSee('V-FILTRO-001')
            ->assertSee('Bs 40.00')
            ->assertDontSee('V-FILTRO-002');
    }

    public function test_sales_report_totals_ignore_cancelled_sales(): void
    {
        $admin = $this->admin();
        $this->sale(['numero' => 'V-COMPLETA', 'total' => '30.00']);
        $this->sale([
            'numero' => 'V-ANULADA',
            'estado' => Venta::ESTADO_ANULADA,
            'total' => '100.00',
        ]);

        $this->actingAs($admin)
            ->get('/admin/reportes/ventas')
            ->assertOk()
            ->assertSee('Ventas completadas')
            ->assertSee('Bs 30.00')
            ->assertSee('Ventas anuladas');
    }

    public function test_admin_can_download_sales_report_pdf(): void
    {
        $admin = $this->admin();
        $this->sale(['numero' => 'V-PDF-001', 'total' => '18.50']);

        $response = $this->actingAs($admin)
            ->get('/admin/reportes/ventas/pdf?numero=PDF-001');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('attachment;', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('reporte-ventas', $response->headers->get('content-disposition'));
    }

    public function test_invalid_report_filters_are_rejected(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin/reportes/ventas?desde=2026-11-20&hasta=2026-11-01')
            ->assertSessionHasErrors('hasta');

        $this->actingAs($admin)
            ->get('/admin/reportes/ventas?metodo=transferencia')
            ->assertSessionHasErrors('metodo');
    }

    public function test_non_admin_and_guest_cannot_access_sales_report(): void
    {
        $this->get('/admin/reportes/ventas')->assertRedirect('/admin/login');

        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user)
            ->get('/admin/reportes/ventas')
            ->assertForbidden();
    }
}
