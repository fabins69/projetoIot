@include('partials.flash')
@if ($errors->any())
    <div class="form-alert" role="alert"><strong>Revise os campos indicados:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
@if ($ambientes->isEmpty())
    <div class="form-alert" role="status">Cadastre um ambiente antes de adicionar um sensor. <a href="{{ route('ambientes.create') }}">Criar ambiente</a>.</div>
@endif

<form class="panel entity-form" method="POST" action="{{ $sensor->exists ? route('sensores.update', $sensor) : route('sensores.store') }}">
    @csrf
    @if ($sensor->exists) @method('PUT') @endif
    <div class="form-section-heading"><span class="form-step">01</span><div><h2>Informações do sensor</h2><p>Identifique o dispositivo e vincule-o a um ambiente.</p></div></div>
    <div class="form-grid">
        <div class="form-field"><label for="codigo">Código do sensor <span>*</span></label><input id="codigo" name="codigo" type="text" value="{{ old('codigo', $sensor->codigo) }}" maxlength="80" required placeholder="Ex.: TEMP-01"><small>O código deve ser único e bater com o dispositivo.</small></div>
        <div class="form-field"><label for="tipo">Tipo de sensor <span>*</span></label><select id="tipo" name="tipo" required><option value="">Selecione o tipo</option>@php($tipos = ['TEMPERATURA', 'UMIDADE', 'LED', 'OUTRO'])@if ($sensor->tipo && !in_array($sensor->tipo, $tipos, true))<option value="{{ $sensor->tipo }}" @selected(old('tipo', $sensor->tipo) === $sensor->tipo)>{{ $sensor->tipo }} (atual)</option>@endif @foreach ($tipos as $tipo)<option value="{{ $tipo }}" @selected(old('tipo', $sensor->tipo) === $tipo)>{{ $tipo }}</option>@endforeach</select></div>
        <div class="form-field full-field"><label for="ambiente_id">Ambiente <span>*</span></label><select id="ambiente_id" name="ambiente_id" required><option value="">Selecione um ambiente</option>@foreach ($ambientes as $ambiente)<option value="{{ $ambiente->id }}" @selected((string) old('ambiente_id', $sensor->ambiente_id) === (string) $ambiente->id)>{{ $ambiente->nome }}{{ $ambiente->status ? '' : ' · Inativo' }}</option>@endforeach</select><small>O sensor poderá ser movido para outro ambiente posteriormente.</small></div>
        <div class="form-field full-field"><label for="descricao">Descrição <span>*</span></label><textarea id="descricao" name="descricao" rows="4" maxlength="1000" required placeholder="Ex.: Sensor de temperatura instalado próximo à janela">{{ old('descricao', $sensor->descricao) }}</textarea></div>
        <div class="form-field full-field"><label class="toggle-label" for="status"><span><strong>Sensor ativo</strong><small>Use este status para indicar se o dispositivo deve ser considerado em operação.</small></span><input type="hidden" name="status" value="0"><input id="status" name="status" type="checkbox" value="1" @checked(old('status', $sensor->exists ? $sensor->status : true))></label></div>
    </div>
    <div class="form-footer"><a class="button button-secondary" href="{{ route('sensores.index') }}">Cancelar</a><button class="button button-primary" type="submit" @disabled($ambientes->isEmpty())>{{ $sensor->exists ? 'Salvar alterações' : 'Cadastrar sensor' }}</button></div>
</form>
