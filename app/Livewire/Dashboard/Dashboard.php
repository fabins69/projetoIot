<?php

namespace App\Livewire\Dashboard;

use App\Models\Ambiente;
use App\Models\Registro;
use App\Models\Sensor;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $registros = Registro::with(['sensor.ambiente'])
            ->orderByDesc('data_hora')
            ->limit(24)
            ->get()
            ->reverse()
            ->values();

        $maisRecentes = $registros->reverse()->take(6)->values();
        $ultimoRegistro = $registros->last();

        $grafico = [
            'labels' => $registros->map(fn (Registro $registro) => $registro->data_hora?->format('H:i') ?? '—')->all(),
            'temperatura' => $registros->map(fn (Registro $registro) => is_numeric($registro->valor) ? (float) $registro->valor : null)->all(),
            'umidade' => $registros->map(fn (Registro $registro) => is_numeric($registro->umidade) ? (float) $registro->umidade : null)->all(),
        ];

        return view('livewire.dashboard.dashboard', [
            'totalAmbientes' => Ambiente::count(),
            'totalSensores' => Sensor::count(),
            'sensoresAtivos' => Sensor::where('status', true)->count(),
            'totalLeituras' => Registro::count(),
            'ultimoRegistro' => $ultimoRegistro,
            'maisRecentes' => $maisRecentes,
            'grafico' => $grafico,
        ])->layout('components.layouts.app');
    }
}
