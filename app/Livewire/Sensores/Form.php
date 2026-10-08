<?php

namespace App\Livewire\Sensores;

use App\Models\Ambiente;
use App\Models\Sensor;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    public ?int $sensorId = null;

    public ?int $ambienteId = null;

    public string $codigo = '';

    public string $tipo = '';

    public string $descricao = '';

    public bool $status = true;

    public function mount(?string $sensor = null): void
    {
        if ($sensor === null) {
            return;
        }

        $registro = Sensor::findOrFail($sensor);
        $this->sensorId = $registro->id;
        $this->ambienteId = $registro->ambiente_id;
        $this->codigo = $registro->codigo;
        $this->tipo = $registro->tipo;
        $this->descricao = $registro->descricao;
        $this->status = $registro->status;
    }

    public function save()
    {
        $codigoUnico = Rule::unique('sensors', 'codigo');

        if ($this->sensorId !== null) {
            $codigoUnico->ignore($this->sensorId);
        }

        $dados = $this->validate([
            'ambienteId' => ['required', 'integer', 'exists:ambientes,id'],
            'codigo' => ['required', 'string', 'max:80', $codigoUnico],
            'tipo' => ['required', 'string', 'max:40'],
            'descricao' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'boolean'],
        ]);

        $dados['ambiente_id'] = $dados['ambienteId'];
        unset($dados['ambienteId']);

        if ($this->sensorId !== null) {
            Sensor::findOrFail($this->sensorId)->update($dados);
            $mensagem = 'Sensor atualizado com sucesso.';
        } else {
            Sensor::create($dados);
            $mensagem = 'Sensor cadastrado com sucesso.';
        }

        session()->flash('success', $mensagem);

        return $this->redirectRoute('sensores.index');
    }

    public function render(): View
    {
        return view('livewire.sensores.form', [
            'ambientes' => Ambiente::orderBy('nome')->get(),
        ])->layout('components.layouts.app');
    }
}
