@include('partials.flash')
@if ($errors->any())
    <div class="form-alert" role="alert"><strong>Revise os campos indicados:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<form class="panel entity-form" method="POST" action="{{ $ambiente->exists ? route('ambientes.update', $ambiente) : route('ambientes.store') }}">
    @csrf
    @if ($ambiente->exists) @method('PUT') @endif
    <div class="form-section-heading"><span class="form-step">01</span><div><h2>Informações do ambiente</h2><p>Preencha os dados para identificar este espaço.</p></div></div>
    <div class="form-grid">
        <div class="form-field full-field"><label for="nome">Nome <span>*</span></label><input id="nome" name="nome" type="text" value="{{ old('nome', $ambiente->nome) }}" maxlength="120" required placeholder="Ex.: Sala de servidores"><small>Use um nome claro para localizar o ambiente.</small></div>
        <div class="form-field full-field"><label for="descricao">Descrição</label><textarea id="descricao" name="descricao" rows="4" maxlength="1000" placeholder="Descreva a finalidade ou localização do ambiente">{{ old('descricao', $ambiente->descricao) }}</textarea><small>Opcional · até 1.000 caracteres.</small></div>
        <div class="form-field full-field"><label class="toggle-label" for="status"><span><strong>Ambiente ativo</strong><small>Ambientes inativos continuam no histórico, mas podem ser identificados visualmente.</small></span><input type="hidden" name="status" value="0"><input id="status" name="status" type="checkbox" value="1" @checked(old('status', $ambiente->exists ? $ambiente->status : true))></label></div>
    </div>
    <div class="form-footer"><a class="button button-secondary" href="{{ route('ambientes.index') }}">Cancelar</a><button class="button button-primary" type="submit">{{ $ambiente->exists ? 'Salvar alterações' : 'Cadastrar ambiente' }}</button></div>
</form>
