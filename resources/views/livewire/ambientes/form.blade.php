<main class="management-page narrow-page">
    <section class="page-heading"><div><p class="eyebrow">AMBIENTES / {{ $ambienteId ? 'EDIÇÃO' : 'NOVO' }}</p><h1>{{ $ambienteId ? 'Editar ambiente' : 'Novo ambiente' }}</h1><p class="page-subtitle">{{ $ambienteId ? 'Atualize as informações deste espaço.' : 'Cadastre um local para organizar seus sensores.' }}</p></div></section>

    @if ($errors->any())
        <div class="form-alert" role="alert"><strong>Revise os campos indicados:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form class="panel entity-form" wire:submit="save">
        <div class="form-section-heading"><span class="form-step">01</span><div><h2>Informações do ambiente</h2><p>Preencha os dados para identificar este espaço.</p></div></div>
        <div class="form-grid">
            <div class="form-field full-field"><label for="nome">Nome <span>*</span></label><input id="nome" type="text" wire:model.blur="nome" maxlength="120" required placeholder="Ex.: Sala de servidores"><small>Use um nome claro para localizar o ambiente.</small>@error('nome')<small class="field-error">{{ $message }}</small>@enderror</div>
            <div class="form-field full-field"><label for="descricao">Descrição</label><textarea id="descricao" wire:model.blur="descricao" rows="4" maxlength="1000" placeholder="Descreva a finalidade ou localização do ambiente"></textarea><small>Opcional · até 1.000 caracteres.</small>@error('descricao')<small class="field-error">{{ $message }}</small>@enderror</div>
            <div class="form-field full-field"><label class="toggle-label" for="status"><span><strong>Ambiente ativo</strong><small>Ambientes inativos continuam no histórico, mas podem ser identificados visualmente.</small></span><input id="status" type="checkbox" wire:model="status"></label>@error('status')<small class="field-error">{{ $message }}</small>@enderror</div>
        </div>
        <div class="form-footer"><a class="button button-secondary" href="{{ route('ambientes.index') }}">Cancelar</a><button class="button button-primary" type="submit" wire:loading.attr="disabled"><span wire:loading.remove wire:target="save">{{ $ambienteId ? 'Salvar alterações' : 'Cadastrar ambiente' }}</span><span wire:loading wire:target="save">Salvando…</span></button></div>
    </form>
</main>
