<?php

namespace Tests\Feature;

use App\Livewire\Ambientes\Form as AmbienteForm;
use App\Livewire\Ambientes\Index as AmbientesIndex;
use App\Livewire\Sensores\Form as SensorForm;
use App\Livewire\Sensores\Index as SensoresIndex;
use App\Models\Ambiente;
use App\Models\Registro;
use App\Models\Sensor;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IoTManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_ambiente_can_be_created_updated_and_deleted_with_livewire(): void
    {
        Livewire::test(AmbienteForm::class)
            ->set('nome', 'Laboratório')
            ->set('descricao', 'Bancada principal')
            ->set('status', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('ambientes.index'));

        $ambiente = Ambiente::where('nome', 'Laboratório')->firstOrFail();
        $this->assertTrue($ambiente->status);

        Livewire::test(AmbienteForm::class, ['ambiente' => (string) $ambiente->id])
            ->set('nome', 'Laboratório IoT')
            ->set('descricao', 'Bancada de testes')
            ->set('status', false)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('ambientes.index'));

        $this->assertDatabaseHas('ambientes', [
            'id' => $ambiente->id,
            'nome' => 'Laboratório IoT',
            'status' => 0,
        ]);

        Livewire::test(AmbientesIndex::class)
            ->call('delete', $ambiente->id)
            ->assertSee('Ambiente removido com sucesso.');

        $this->assertDatabaseMissing('ambientes', ['id' => $ambiente->id]);
    }

    public function test_sensor_can_be_created_updated_and_deleted_with_livewire(): void
    {
        $ambiente = Ambiente::create(['nome' => 'Estufa', 'status' => true]);

        Livewire::test(SensorForm::class)
            ->set('ambienteId', $ambiente->id)
            ->set('codigo', 'TEMP-01')
            ->set('tipo', 'TEMPERATURA')
            ->set('descricao', 'Sensor central')
            ->set('status', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('sensores.index'));

        $sensor = Sensor::where('codigo', 'TEMP-01')->firstOrFail();
        $this->assertSame($ambiente->id, $sensor->ambiente->id);

        Livewire::test(SensorForm::class, ['sensor' => (string) $sensor->id])
            ->set('ambienteId', $ambiente->id)
            ->set('codigo', 'TEMP-01')
            ->set('tipo', 'TEMPERATURA')
            ->set('descricao', 'Sensor atualizado')
            ->set('status', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('sensores.index'));

        $this->assertDatabaseHas('sensors', [
            'id' => $sensor->id,
            'descricao' => 'Sensor atualizado',
            'status' => 1,
        ]);

        Livewire::test(SensoresIndex::class)->call('delete', $sensor->id)
            ->assertSee('Sensor removido com sucesso.');
        $this->assertDatabaseMissing('sensors', ['id' => $sensor->id]);
    }

    public function test_livewire_delete_actions_preserve_linked_records(): void
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

        Livewire::test(SensoresIndex::class)
            ->call('delete', $sensor->id)
            ->assertSee('O histórico foi preservado');
        $this->assertDatabaseHas('sensors', ['id' => $sensor->id]);

        Livewire::test(AmbientesIndex::class)
            ->call('delete', $ambiente->id)
            ->assertSee('possui sensores vinculados');
        $this->assertDatabaseHas('ambientes', ['id' => $ambiente->id]);
        $this->assertDatabaseHas('registros', ['sensor_id' => $sensor->id]);
    }

    public function test_livewire_crud_list_create_and_edit_pages_render(): void
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
        $this->get(route('ambientes.create'))->assertOk()->assertSee('wire:submit="save"', false);
        $this->get(route('ambientes.edit', $ambiente))->assertOk()->assertSee('Editar ambiente');

        $this->get(route('sensores.index'))->assertOk()->assertSee('UMID-01');
        $this->get(route('sensores.create'))->assertOk()->assertSee('wire:submit="save"', false);
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
