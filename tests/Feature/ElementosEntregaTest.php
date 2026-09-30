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

        $response->assertOk()
            ->assertJsonPath('data.0.nombre', 'Empleado pendiente')
            ->assertJsonMissingPath('data.0.tipo_uniforme')
            ->assertJsonMissingPath('data.0.color_franja')
            ->assertJsonMissingPath('data.0.camisola')
            ->assertJsonMissingPath('data.0.pantalon')
            ->assertJsonMissingPath('data.0.chamarra')
            ->assertJsonMissingPath('data.0.bota')
            ->assertJsonMissingPath('data.0.cinturon');
        $response->assertJsonMissing(['nombre' => 'Empleado recibido']);
    }

    public function test_the_received_endpoint_only_returns_fields_needed_to_reprint(): void
    {
        ElementosEntrega::factory()->create(['recibio' => true]);

        $response = $this->getJson(route('entregas.recibidos'));

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id', 'nombre', 'csp', 'reprint_url']]])
            ->assertJsonMissingPath('data.0.ubicacion')
            ->assertJsonMissingPath('data.0.coordinacion')
            ->assertJsonMissingPath('data.0.tipo_uniforme')
            ->assertJsonMissingPath('data.0.color_franja')
            ->assertJsonMissingPath('data.0.camisola')
            ->assertJsonMissingPath('data.0.pantalon')
            ->assertJsonMissingPath('data.0.chamarra')
            ->assertJsonMissingPath('data.0.bota')
            ->assertJsonMissingPath('data.0.cinturon');
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

        $receivedReport = $this->get(route('reportes.entregas', ['estado' => 'recibidos']))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringStartsWith('PK', $receivedReport->streamedContent());

        $this->get(route('reportes.entregas', ['estado' => 'pendientes']))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_a_second_receive_request_does_not_change_an_already_received_employee(): void
    {
        $employee = ElementosEntrega::factory()->create(['recibio' => true]);

        $this->post(route('entregas.recibir', $employee))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    public function test_a_resumed_receive_request_renders_the_existing_receipt(): void
    {
        $employee = ElementosEntrega::factory()->create(['recibio' => true]);

        $this->post(route('entregas.recibir', [$employee, 'resume' => 1]))
            ->assertOk()
            ->assertSee('Imprimir o guardar como PDF');
    }
}
