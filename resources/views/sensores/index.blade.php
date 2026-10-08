<main class="management-page">
    <section class="page-heading">
        <div><p class="eyebrow">CADASTROS</p><h1>Sensores</h1><p class="page-subtitle">Gerencie dispositivos, tipos e ambientes associados.</p></div>
        <a class="button button-primary" href="{{ route('sensores.create') }}">＋ Novo sensor</a>
    </section>

    @include('partials.flash')

    <section class="panel management-panel">
        <form class="search-form" method="GET" action="{{ route('sensores.index') }}">
            <label class="search-field"><span aria-hidden="true">⌕</span><input type="search" name="q" value="{{ $termo }}" placeholder="Buscar por código, tipo ou ambiente" aria-label="Buscar sensor"></label>
            <button class="button button-secondary" type="submit">Buscar</button>
            @if ($termo !== '')<a class="clear-search" href="{{ route('sensores.index') }}">Limpar</a>@endif
        </form>

        <div class="table-scroll"><table class="data-table">
            <thead><tr><th>Sensor</th><th>Tipo</th><th>Ambiente</th><th>Leituras</th><th>Status</th><th class="actions-heading">Ações</th></tr></thead>
            <tbody>
                @forelse ($sensores as $sensor)
                    <tr>
                        <td><span class="sensor-code">{{ $sensor->codigo }}</span><small class="table-description">{{ $sensor->descricao }}</small></td>
                        <td><span class="type-pill">{{ $sensor->tipo }}</span></td>
                        <td>{{ $sensor->ambiente?->nome ?? '—' }}</td>
                        <td>{{ number_format($sensor->registros_count, 0, ',', '.') }}</td>
                        <td><span class="status-pill {{ $sensor->status ? 'is-active' : 'is-inactive' }}"><i></i>{{ $sensor->status ? 'Ativo' : 'Inativo' }}</span></td>
                        <td class="actions-cell">
                            <a class="icon-action" href="{{ route('sensores.edit', $sensor) }}" aria-label="Editar {{ $sensor->codigo }}" title="Editar">✎</a>
                            @if ($sensor->registros_count === 0)
                                <form method="POST" action="{{ route('sensores.destroy', $sensor) }}" onsubmit="return confirm('Excluir o sensor {{ addslashes($sensor->codigo) }}?')">@csrf @method('DELETE')<button class="icon-action danger-action" type="submit" aria-label="Excluir {{ $sensor->codigo }}" title="Excluir">×</button></form>
                            @else
                                <button class="icon-action danger-action" type="button" disabled title="Este sensor tem leituras; o histórico não será apagado">×</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="table-empty">{{ $termo !== '' ? 'Nenhum sensor corresponde à busca.' : 'Nenhum sensor cadastrado. Adicione seu primeiro dispositivo.' }}</div></td></tr>
                @endforelse
            </tbody>
        </table></div>
        <div class="pagination-wrap">{{ $sensores->links('pagination.custom') }}</div>
    </section>
</main>
