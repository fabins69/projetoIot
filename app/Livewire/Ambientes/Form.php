<?php

namespace App\Livewire\Ambientes;

use App\Models\Ambiente;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    public ?int $ambienteId = null;

    public string $nome = '';

    public string $descricao = '';

    public bool $status = true;

    public function mount(?string $ambiente = null): void
    {
        if ($ambiente === null) {
            return;
        }

        $registro = Ambiente::findOrFail($ambiente);
        $this->ambienteId = $registro->id;
        $this->nome = $registro->nome;
        $this->descricao = $registro->descricao ?? '';
        $this->status = $registro->status;
    }

    public function save()
    {
        $dados = $this->validate([
            'nome' => ['required', 'string', 'max:120'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'boolean'],
        ]);

        if ($this->ambienteId !== null) {
            Ambiente::findOrFail($this->ambienteId)->update($dados);
            $mensagem = 'Ambiente atualizado com sucesso.';
        } else {
            Ambiente::create($dados);
            $mensagem = 'Ambiente cadastrado com sucesso.';
        }

        session()->flash('success', $mensagem);

        return $this->redirectRoute('ambientes.index');
    }

    public function render(): View
    {
        return view('livewire.ambientes.form')
            ->layout('components.layouts.app');
    }
}
