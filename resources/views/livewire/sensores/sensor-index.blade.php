<main class="management-page">
    <section class="page-heading">
        <div><p class="eyebrow">CADASTROS</p><h1>Sensores</h1><p class="page-subtitle">Gerencie dispositivos, tipos e ambientes associados.</p></div>
        <a class="button button-primary" href="{{ route('sensores.create') }}">＋ Novo sensor</a>
    </section>

    @include('partials.flash')

    <section class="panel management-panel">
        <div class="search-form">
            <label class="search-field"><span aria-hidden="true">⌕</span><input type="search" wire:model.live.debounce.300ms="termo" placeholder="Buscar por código, tipo ou ambiente" aria-label="Buscar sensor"></label>
            <span wire:loading wire:target="termo" class="search-loading">Buscando…</span>
        </div>

        <div class="table-scroll"><table class="data-table">
            <thead><tr><th>Sensor</th><th>Tipo</th><th>Ambiente</th><th>Leituras</th><th>Status</th><th class="actions-heading">Ações</th></tr></thead>
            <tbody>
                @forelse ($sensores as $sensor)
                    <tr wire:key="sensor-{{ $sensor->id }}">
                        <td><span class="sensor-code">{{ $sensor->codigo }}</span><small class="table-description">{{ $sensor->descricao }}</small></td>
                        <td><span class="type-pill">{{ $sensor->tipo }}</span></td>
                        <td>{{ $sensor->ambiente?->nome ?? '—' }}</td>
                        <td>{{ number_format($sensor->registros_count, 0, ',', '.') }}</td>
                        <td>
                            <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                            id="status-{{$sensor->id}}"
                            wire:click='status({{$sensor->id}})'
                            @checked($sensor->status)>
                        <span class="badge bg-{{$sensor->status ? 'success' : 'danger'}}">
                            {{$sensor->status ? 'Ativo' : 'Inativo'}}</span></div>
                            {{ $sensor->status ? 'Ativo' : 'Inativo' }}</td>
                        <td class="actions-cell">
                            <a class="icon-action" href="{{ route('sensores.edit', $sensor) }}" aria-label="Editar {{ $sensor->codigo }}" title="Editar">✎</a>
                            @if ($sensor->registros_count === 0)
                                <button class="icon-action danger-action" type="button" wire:click="delete({{ $sensor->id }})" wire:confirm="Excluir o sensor {{ $sensor->codigo }}?" wire:loading.attr="disabled" aria-label="Excluir {{ $sensor->codigo }}" title="Excluir">×</button>
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
        <div class="pagination-wrap livewire-pagination">
            <button class="pagination-link" type="button" wire:click="previousPage" @disabled($sensores->onFirstPage()) aria-label="Página anterior">‹</button>
            <span>Página {{ $sensores->currentPage() }} de {{ max(1, $sensores->lastPage()) }}</span>
            <button class="pagination-link" type="button" wire:click="nextPage" @disabled(! $sensores->hasMorePages()) aria-label="Próxima página">›</button>
        </div>
    </section>
</main>
