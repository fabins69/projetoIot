<?php

namespace Tests\Feature;

use App\Models\Ambiente;
use App\Models\Registro;
use App\Models\Sensor;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IoTManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_ambiente_can_be_created_updated_and_deleted(): void
    {
        $this->post(route('ambientes.store'), [
            'nome' => 'Laboratório',
            'descricao' => 'Bancada principal',
            'status' => '1',
        ])->assertRedirect(route('ambientes.index'));

        $ambiente = Ambiente::where('nome', 'Laboratório')->firstOrFail();
        $this->assertTrue($ambiente->status);

        $this->put(route('ambientes.update', $ambiente), [
            'nome' => 'Laboratório IoT',
            'descricao' => 'Bancada de testes',
        ])->assertRedirect(route('ambientes.index'));

        $this->assertDatabaseHas('ambientes', [
            'id' => $ambiente->id,
            'nome' => 'Laboratório IoT',
            'status' => 0,
        ]);

        $this->delete(route('ambientes.destroy', $ambiente))
            ->assertRedirect(route('ambientes.index'));
        $this->assertDatabaseMissing('ambientes', ['id' => $ambiente->id]);
    }

    public function test_sensor_can_be_created_updated_and_deleted(): void
    {
        $ambiente = Ambiente::create(['nome' => 'Estufa', 'status' => true]);

        $this->post(route('sensores.store'), [
            'ambiente_id' => $ambiente->id,
            'codigo' => 'TEMP-01',
            'tipo' => 'TEMPERATURA',
            'descricao' => 'Sensor central',
            'status' => '1',
        ])->assertRedirect(route('sensores.index'));

        $sensor = Sensor::where('codigo', 'TEMP-01')->firstOrFail();
        $this->assertSame($ambiente->id, $sensor->ambiente->id);

        $this->put(route('sensores.update', $sensor), [
            'ambiente_id' => $ambiente->id,
            'codigo' => 'TEMP-01',
            'tipo' => 'TEMPERATURA',
            'descricao' => 'Sensor atualizado',
            'status' => '1',
        ])->assertRedirect(route('sensores.index'));

        $this->assertDatabaseHas('sensors', [
            'id' => $sensor->id,
            'descricao' => 'Sensor atualizado',
            'status' => 1,
        ]);

        $this->delete(route('sensores.destroy', $sensor))
            ->assertRedirect(route('sensores.index'));
        $this->assertDatabaseMissing('sensors', ['id' => $sensor->id]);
    }

    public function test_records_prevent_deleting_their_sensor_and_environment(): void
    {
        $ambiente = Ambiente::create(['nome' => 'Sala técnica', 'status' => true]);
        $sensor = Sensor::create([
            'ambiente_id' => $ambiente->id,
            'codigo' => 'TEMP-02',
            'tipo' => 'TEMPERATURA',
            'descricao' => 'Sensor com histórico',
            'status' => true,
        ]);
        Registro::create([
            'sensor_id' => $sensor->id,
            'valor' => '22.5',
            'umidade' => '48.0',
            'data_hora' => now(),
        ]);

        $this->from(route('sensores.index'))
            ->delete(route('sensores.destroy', $sensor))
            ->assertRedirect(route('sensores.index'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('sensors', ['id' => $sensor->id]);

        $this->from(route('ambientes.index'))
            ->delete(route('ambientes.destroy', $ambiente))
            ->assertRedirect(route('ambientes.index'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('ambientes', ['id' => $ambiente->id]);
        $this->assertDatabaseHas('registros', ['sensor_id' => $sensor->id]);
    }

    public function test_crud_list_create_and_edit_screens_render(): void
    {
        $ambiente = Ambiente::create(['nome' => 'Estúdio', 'status' => true]);
        $sensor = Sensor::create([
            'ambiente_id' => $ambiente->id,
            'codigo' => 'UMID-01',
            'tipo' => 'UMIDADE',
            'descricao' => 'Sensor de umidade',
            'status' => true,
        ]);

        $this->get(route('ambientes.index'))->assertOk()->assertSee('Estúdio');
        $this->get(route('ambientes.create'))->assertOk()->assertSee('Novo ambiente');
        $this->get(route('ambientes.edit', $ambiente))->assertOk()->assertSee('Editar ambiente');

        $this->get(route('sensores.index'))->assertOk()->assertSee('UMID-01');
        $this->get(route('sensores.create'))->assertOk()->assertSee('Novo sensor');
        $this->get(route('sensores.edit', $sensor))->assertOk()->assertSee('Editar sensor');
    }

    public function test_dashboard_uses_database_counts_and_shows_empty_state(): void
    {
        $ambiente = Ambiente::create(['nome' => 'Escritório', 'status' => true]);
        $sensor = Sensor::create([
            'ambiente_id' => $ambiente->id,
            'codigo' => 'TEMP-03',
            'tipo' => 'TEMPERATURA',
            'descricao' => 'Sensor do escritório',
            'status' => true,
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Gerenciar ambientes')
            ->assertSee('Ainda não há leituras')
            ->assertSee('1 de 1 sensores ativos');

        Registro::create([
            'sensor_id' => $sensor->id,
            'valor' => '24.3',
            'umidade' => '51.2',
            'data_hora' => now(),
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('TEMP-03')
            ->assertSee('24,3')
            ->assertSee('51,2');
    }

    public function test_api_accepts_a_reading_and_returns_the_latest_value(): void
    {
        $ambiente = Ambiente::create(['nome' => 'Galpão', 'status' => true]);
        Sensor::create([
            'ambiente_id' => $ambiente->id,
            'codigo' => 'TEMP-API',
            'tipo' => 'TEMPERATURA',
            'descricao' => 'Dispositivo de teste',
            'status' => true,
        ]);

        $this->postJson('/api/registro', [
            'cod_sensor' => 'TEMP-API',
            'valor' => 23.7,
            'umidade' => 54.2,
        ])->assertCreated()->assertJsonPath('data.valor', 23.7);

        $this->getJson('/api/registro/valor?cod_sensor=TEMP-API')
            ->assertOk()
            ->assertJsonPath('valor', '23.7');
    }
}
