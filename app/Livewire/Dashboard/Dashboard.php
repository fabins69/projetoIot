<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Dashboard extends Component
{
    public $leituraAtual;
    public $historico;
    public $logs;

    public function mount()
    {
        $this->leituraAtual = [
            'temperatura' => 24.5,
            'umidade' => 62.0,
            'dispositivo' => 'ESP32 - Ativo',
            'status' => 'ONLINE'
        ];

        $this->historico = [
            'labels' => ['15:10', '15:11', '15:12', '15:13', '15:14', '15:15', '15:16', '15:17', '15:18'],
            'temperatura' => [23.4, 23.8, 24.1, 24.0, 24.5, 24.2, 24.6, 24.3, 24.5],
            'umidade' => [58.2, 59.0, 60.1, 61.2, 62.0, 61.5, 62.1, 63.0, 62.0]
        ];

        $this->logs = [
            ['horario' => '15:18:02', 'status' => 'Ok', 'classe' => 'success', 'mensagem' => 'Leitura de sensores processada com sucesso.'],
            ['horario' => '15:15:00', 'status' => 'Info', 'classe' => 'info', 'mensagem' => 'Dispositivo conectado com sucesso ao broker.'],
            ['horario' => '15:10:12', 'status' => 'Aviso', 'classe' => 'warning', 'mensagem' => 'Oscilação leve detectada no sinal WiFi.'],
        ];
    }

    public function render()
    {
        // Define o layout global e injeta a view de conteúdo de forma limpa
        return view('livewire.dashboard.dashboard')
            ->layout('components.layouts.app');
    }
}
