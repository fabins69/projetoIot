<?php

namespace App\Livewire\Sensores;

use App\Models\Sensor;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class SensorIndex extends Component
{
    use WithPagination;

    public string $termo = '';

    public function updatingTermo(): void
    {
        $this->resetPage();
    }

    public function delete(int $sensorId): void
    {
        $sensor = Sensor::findOrFail($sensorId);

        if ($sensor->registros()->exists()) {
            session()->flash('error', 'Este sensor possui leituras salvas. O histórico foi preservado e o sensor não pode ser excluído.');

            return;
        }

        $sensor->delete();
        session()->flash('success', 'Sensor removido com sucesso.');
    }

    public function render(): View
    {
        $sensores = Sensor::query()
            ->with('ambiente')
            ->withCount('registros')
            ->when($this->termo !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where('codigo', 'like', '%'.$this->termo.'%')
                        ->orWhere('tipo', 'like', '%'.$this->termo.'%')
                        ->orWhereHas('ambiente', fn ($ambiente) => $ambiente->where('nome', 'like', '%'.$this->termo.'%'));
                });
            })
            ->orderBy('codigo')
            ->paginate(10);

        return view('livewire.sensores.sensor-index', compact('sensores'))
            ->layout('components.layouts.app');
    }

    public function status($id){
        $sensor = Sensor::find($id);
        $sensor->status = !$sensor->status;
        $sensor->save();
    }

}
