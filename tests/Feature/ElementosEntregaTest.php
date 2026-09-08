<?php

namespace Tests\Feature;

use App\Models\ElementosEntrega;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElementosEntregaTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_pending_endpoint_only_returns_pending_employees(): void
    {
        ElementosEntrega::factory()->create(['nombre' => 'Empleado pendiente', 'recibio' => false]);
        ElementosEntrega::factory()->create(['nombre' => 'Empleado recibido', 'recibio' => true]);

        $response = $this->getJson(route('entregas.pendientes'));

        $response->assertOk()->assertJsonPath('data.0.nombre', 'Empleado pendiente');
        $response->assertJsonMissing(['nombre' => 'Empleado recibido']);
    }

    public function test_receiving_an_employee_updates_the_database_and_renders_the_receipt(): void
    {
        $employee = ElementosEntrega::factory()->create(['recibio' => false]);

        $response = $this->post(route('entregas.recibir', $employee));

        $response->assertOk();
        $response->assertSee('Imprimir o guardar como PDF');
        $this->assertDatabaseHas('elementos_entregas', [
            'id' => $employee->id,
            'recibio' => true,
        ]);
    }

    public function test_a_received_employee_can_be_reprinted_but_a_pending_employee_cannot(): void
    {
        $received = ElementosEntrega::factory()->create(['recibio' => true]);
        $pending = ElementosEntrega::factory()->create(['recibio' => false]);

        $this->get(route('entregas.reimprimir', $received))
            ->assertOk()
            ->assertSee('Imprimir o guardar como PDF');

        $this->get(route('entregas.reimprimir', $pending))->assertNotFound();
    }

    public function test_report_can_filter_received_and_pending_records(): void
    {
        ElementosEntrega::factory()->create(['nombre' => 'Recibido', 'recibio' => true]);
        ElementosEntrega::factory()->create(['nombre' => 'Pendiente', 'recibio' => false]);

        $this->get(route('reportes.entregas', ['estado' => 'recibidos']))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $this->get(route('reportes.entregas', ['estado' => 'pendientes']))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_a_second_receive_request_does_not_change_an_already_received_employee(): void
    {
        $employee = ElementosEntrega::factory()->create(['recibio' => true]);

        $this->post(route('entregas.recibir', $employee))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }
}
