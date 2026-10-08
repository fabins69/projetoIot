<main class="management-page">
    <section class="page-heading">
        <div><p class="eyebrow">CADASTROS</p><h1>Ambientes</h1><p class="page-subtitle">Organize os espaços onde seus dispositivos IoT estão instalados.</p></div>
        <a class="button button-primary" href="{{ route('ambientes.create') }}">＋ Novo ambiente</a>
    </section>

    @include('partials.flash')

    <section class="panel management-panel">
        <form class="search-form" method="GET" action="{{ route('ambientes.index') }}">
            <label class="search-field"><span aria-hidden="true">⌕</span><input type="search" name="q" value="{{ $termo }}" placeholder="Buscar ambiente pelo nome" aria-label="Buscar ambiente"></label>
            <button class="button button-secondary" type="submit">Buscar</button>
            @if ($termo !== '')<a class="clear-search" href="{{ route('ambientes.index') }}">Limpar</a>@endif
        </form>

        <div class="table-scroll"><table class="data-table">
            <thead><tr><th>Ambiente</th><th>Sensores</th><th>Status</th><th class="actions-heading">Ações</th></tr></thead>
            <tbody>
                @forelse ($ambientes as $ambiente)
                    <tr>
                        <td><strong>{{ $ambiente->nome }}</strong><small class="table-description">{{ $ambiente->descricao ?: 'Sem descrição' }}</small></td>
                        <td>{{ $ambiente->sensores_count }} {{ $ambiente->sensores_count === 1 ? 'sensor' : 'sensores' }}</td>
                        <td><span class="status-pill {{ $ambiente->status ? 'is-active' : 'is-inactive' }}"><i></i>{{ $ambiente->status ? 'Ativo' : 'Inativo' }}</span></td>
                        <td class="actions-cell">
                            <a class="icon-action" href="{{ route('ambientes.edit', $ambiente) }}" aria-label="Editar {{ $ambiente->nome }}" title="Editar">✎</a>
                            @if ($ambiente->sensores_count === 0)
                                <form method="POST" action="{{ route('ambientes.destroy', $ambiente) }}" onsubmit="return confirm('Excluir o ambiente {{ addslashes($ambiente->nome) }}?')">@csrf @method('DELETE')<button class="icon-action danger-action" type="submit" aria-label="Excluir {{ $ambiente->nome }}" title="Excluir">×</button></form>
                            @else
                                <button class="icon-action danger-action" type="button" disabled title="Remova ou mova os sensores antes de excluir">×</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="table-empty">{{ $termo !== '' ? 'Nenhum ambiente corresponde à busca.' : 'Nenhum ambiente cadastrado. Crie o primeiro para começar.' }}</div></td></tr>
                @endforelse
            </tbody>
        </table></div>
        <div class="pagination-wrap">{{ $ambientes->links('pagination.custom') }}</div>
    </section>
</main>
