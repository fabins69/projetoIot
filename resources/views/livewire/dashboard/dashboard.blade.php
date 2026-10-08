<main class="dashboard-page">
    <section class="page-heading">
        <div>
            <p class="eyebrow">CENTRAL DE MONITORAMENTO</p>
            <h1>Visão geral</h1>
            <p class="page-subtitle">Acompanhe seus ambientes, sensores e leituras em um só lugar.</p>
        </div>
        <div class="heading-actions">
            <a class="button button-secondary" href="{{ route('ambientes.create') }}">＋ Novo ambiente</a>
            <a class="button button-primary" href="{{ route('sensores.create') }}">＋ Novo sensor</a>
        </div>
    </section>

    <section class="metrics-grid" aria-label="Resumo do sistema">
        <article class="metric-card metric-blue">
            <div class="metric-top"><span class="metric-label">Ambientes</span><span class="metric-icon">⌂</span></div>
            <strong class="metric-value">{{ $totalAmbientes }}</strong>
            <span class="metric-note">cadastrados no sistema</span>
        </article>
        <article class="metric-card metric-violet">
            <div class="metric-top"><span class="metric-label">Sensores</span><span class="metric-icon">◈</span></div>
            <strong class="metric-value">{{ $totalSensores }}</strong>
            <span class="metric-note">{{ $sensoresAtivos }} ativos</span>
        </article>
        <article class="metric-card metric-green">
            <div class="metric-top"><span class="metric-label">Leituras recebidas</span><span class="metric-icon">⌁</span></div>
            <strong class="metric-value">{{ number_format($totalLeituras, 0, ',', '.') }}</strong>
            <span class="metric-note">registros armazenados</span>
        </article>
        <article class="metric-card metric-amber">
            <div class="metric-top"><span class="metric-label">Última leitura</span><span class="metric-icon">◷</span></div>
            <strong class="metric-value metric-date">{{ $ultimoRegistro?->data_hora?->format('H:i') ?? '—' }}</strong>
            <span class="metric-note">{{ $ultimoRegistro?->data_hora?->format('d/m/Y') ?? 'Sem dados recebidos' }}</span>
        </article>
    </section>

    <section class="content-grid">
        <article class="panel chart-panel">
            <div class="panel-heading">
                <div>
                    <h2>Histórico de leituras</h2>
                    <p>Até 24 registros mais recentes</p>
                </div>
                <span class="live-indicator"><span></span> Dados do sistema</span>
            </div>
            @if ($totalLeituras > 0)
                <div class="chart-wrap"><canvas id="readingsChart" aria-label="Gráfico de leituras de temperatura e umidade"></canvas></div>
            @else
                <div class="empty-chart">
                    <span class="empty-icon">⌁</span>
                    <strong>Ainda não há leituras</strong>
                    <p>Quando seus sensores enviarem dados, o histórico aparecerá aqui.</p>
                </div>
            @endif
            <div class="chart-legend"><span><i class="legend-dot temperature"></i> Valor</span><span><i class="legend-dot humidity"></i> Umidade (%)</span></div>
        </article>

        <article class="panel status-panel">
            <div class="panel-heading">
                <div><h2>Saúde do sistema</h2><p>Resumo da infraestrutura cadastrada</p></div>
            </div>
            <div class="health-summary">
                <div class="health-ring" style="background: conic-gradient(#3f70ec {{ $totalSensores > 0 ? round(($sensoresAtivos / $totalSensores) * 100) : 0 }}%, #edf1f8 0)"><span>{{ $totalSensores > 0 ? round(($sensoresAtivos / $totalSensores) * 100) : 0 }}<small>%</small></span></div>
                <div><strong>{{ $sensoresAtivos }} de {{ $totalSensores }} sensores ativos</strong><p>O status representa a configuração cadastrada.</p></div>
            </div>
            <div class="status-divider"></div>
            <a class="quick-link" href="{{ route('ambientes.index') }}"><span class="quick-icon blue">⌂</span><span><strong>Gerenciar ambientes</strong><small>{{ $totalAmbientes }} {{ $totalAmbientes === 1 ? 'ambiente' : 'ambientes' }}</small></span><b>→</b></a>
            <a class="quick-link" href="{{ route('sensores.index') }}"><span class="quick-icon purple">◈</span><span><strong>Gerenciar sensores</strong><small>{{ $totalSensores }} {{ $totalSensores === 1 ? 'sensor' : 'sensores' }}</small></span><b>→</b></a>
        </article>
    </section>

    <section class="panel readings-panel">
        <div class="panel-heading">
            <div><h2>Atividade recente</h2><p>Últimos dados recebidos pelos sensores</p></div>
            <a class="text-link" href="{{ route('sensores.index') }}">Ver sensores <span>→</span></a>
        </div>
        @if ($maisRecentes->isNotEmpty())
            <div class="table-scroll"><table class="data-table">
                <thead><tr><th>Sensor</th><th>Ambiente</th><th>Valor</th><th>Umidade</th><th>Recebido em</th></tr></thead>
                <tbody>
                    @foreach ($maisRecentes as $registro)
                        <tr>
                            <td><span class="sensor-code">{{ $registro->sensor?->codigo ?? 'Sensor removido' }}</span></td>
                            <td>{{ $registro->sensor?->ambiente?->nome ?? '—' }}</td>
                            <td>{{ is_numeric($registro->valor) ? number_format((float) $registro->valor, 1, ',', '.') : $registro->valor }}</td>
                            <td>{{ $registro->umidade !== null ? number_format((float) $registro->umidade, 1, ',', '.') . ' %' : '—' }}</td>
                            <td>{{ $registro->data_hora?->format('d/m/Y H:i:s') ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        @else
            <div class="table-empty">Nenhuma atividade registrada até o momento.</div>
        @endif
    </section>
</main>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('readingsChart');
            if (!canvas || typeof Chart === 'undefined') return;
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: @js($grafico['labels']),
                    datasets: [
                        { label: 'Valor', data: @js($grafico['temperatura']), borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, .10)', tension: .35, fill: true, spanGaps: true },
                        { label: 'Umidade (%)', data: @js($grafico['umidade']), borderColor: '#14b8a6', backgroundColor: 'rgba(20, 184, 166, .06)', tension: .35, fill: false, spanGaps: true }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 10 } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#94a3b8', maxTicksLimit: 8 } },
                        y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8' } }
                    }
                }
            });
        });
    </script>
@endpush
