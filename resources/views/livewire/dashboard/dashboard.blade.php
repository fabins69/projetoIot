<div>
    <!-- Bloco 1: Cards com os valores das variáveis PHP -->
    <div class="row g-3 mb-4">
        <!-- Card Temperatura -->
        <div class="col-12 col-md-4">
            <div class="card card-metric bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase small mb-1">Temperatura Real</h6>
                        <h2 class="fw-bold mb-0 text-danger">{{ $leituraAtual['temperatura'] }} °C</h2>
                    </div>
                    <div class="icon-circle bg-danger-subtle text-danger">
                        <i class="fa-solid fa-thermometer-half"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Umidade -->
        <div class="col-12 col-md-4">
            <div class="card card-metric bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase small mb-1">Umidade Relativa</h6>
                        <h2 class="fw-bold mb-0 text-info">{{ $leituraAtual['umidade'] }} %</h2>
                    </div>
                    <div class="icon-circle bg-info-subtle text-info">
                        <i class="fa-solid fa-droplet"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Status do Dispositivo -->
        <div class="col-12 col-md-4">
            <div class="card card-metric bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase small mb-1">Status de Conexão</h6>
                        <h2 class="fw-bold mb-0 text-success">{{ $leituraAtual['status'] }}</h2>
                    </div>
                    <div class="icon-circle bg-success-subtle text-success">
                        <i class="fa-solid fa-wifi"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bloco 2: Gráficos e Console de Logs -->
    <div class="row g-3">
        <!-- Espaço do Gráfico Chart.js -->
        <div class="col-12 col-xl-8">
            <div class="card p-3 shadow-sm border-0 rounded-3 bg-white">
                <h5 class="card-title mb-3"><i class="fa-solid fa-chart-line text-muted me-2"></i>Histórico Recente (Gráfico)</h5>
                <div style="position: relative; height:300px; width:100%">
                    <canvas id="realtimeIotChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Console / Lista de Logs à direita -->
        <div class="col-12 col-xl-4">
            <div class="card p-3 shadow-sm border-0 rounded-3 bg-white h-100">
                <h5 class="card-title mb-3"><i class="fa-solid fa-terminal text-muted me-2"></i>Logs do Sistema</h5>
                <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Horário</th>
                                <th>Mensagem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td><small class="text-muted">{{ $log['horario'] }}</small></td>
                                    <td>
                                        <span class="badge bg-{{ $log['classe'] }}-subtle text-{{ $log['classe'] }} me-1">
                                            {{ $log['status'] }}
                                        </span> 
                                        {{ $log['mensagem'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Renderização do Gráfico com JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('realtimeIotChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($historico['labels']) !!},
                    datasets: [{
                        label: 'Temperatura (°C)',
                        data: {!! json_encode($historico['temperatura']) !!},
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.3,
                        fill: true
                    }, {
                        label: 'Umidade (%)',
                        data: {!! json_encode($historico['umidade']) !!},
                        borderColor: '#0dcaf0',
                        backgroundColor: 'rgba(13, 202, 240, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: false } }
                }
            });
        });
    </script>
</div>
