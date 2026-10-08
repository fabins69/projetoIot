<?php

namespace App\Livewire\Ambientes;

use App\Models\Ambiente;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $termo = '';

    public function updatingTermo(): void
    {
        $this->resetPage();
    }

    public function delete(int $ambienteId): void
    {
        $ambiente = Ambiente::findOrFail($ambienteId);

        if ($ambiente->sensores()->exists()) {
            session()->flash('error', 'Este ambiente possui sensores vinculados. Remova ou mova os sensores antes de excluí-lo.');

            return;
        }

        $ambiente->delete();
        session()->flash('success', 'Ambiente removido com sucesso.');
    }

    public function render(): View
    {
        $ambientes = Ambiente::query()
            ->withCount('sensores')
            ->when($this->termo !== '', fn ($query) => $query->where('nome', 'like', '%'.$this->termo.'%'))
            ->orderBy('nome')
            ->paginate(10);

        return view('livewire.ambientes.index', compact('ambientes'))
            ->layout('components.layouts.app');
    }
}
