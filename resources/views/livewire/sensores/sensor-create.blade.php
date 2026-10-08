<main class="management-page narrow-page">
    <section class="page-heading">
        <div>
            <p class="eyebrow">SENSORES / {{ $sensorId ? 'EDIÇÃO' : 'NOVO' }}</p>
            <h1>{{ $sensorId ? 'Editar sensor' : 'Novo sensor' }}</h1>
            <p class="page-subtitle">
                {{ $sensorId ? 'Atualize as informações do dispositivo.' : 'Registre um dispositivo e associe-o ao ambiente correspondente.' }}
            </p>
        </div>
    </section>

    @if ($errors->any())
        <div class="form-alert" role="alert"><strong>Revise os campos indicados:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if ($ambientes->isEmpty())
        <div class="form-alert" role="status">Cadastre um ambiente antes de adicionar um sensor. <a
                href="{{ route('ambientes.create') }}">Criar ambiente</a>.</div>
    @endif

    <form class="panel entity-form" wire:submit="save">
        <div class="form-section-heading"><span class="form-step">01</span>
            <div>
                <h2>Informações do sensor</h2>
                <p>Identifique o dispositivo e vincule-o a um ambiente.</p>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-field"><label for="codigo">Código do sensor <span>*</span></label><input id="codigo"
                    type="text" wire:model.blur="codigo" maxlength="80" required placeholder="Ex.: TEMP-01"><small>O
                    código deve ser único e bater com o dispositivo.</small>
                @error('codigo')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-field"><label for="tipo">Tipo de sensor <span>*</span></label><select id="tipo"
                    wire:model="tipo" required>
                    <option value="">Selecione o tipo</option>@php($tipos = ['TEMPERATURA', 'UMIDADE', 'LED', 'OUTRO'])@if ($tipo && !in_array($tipo, $tipos, true))
                        <option value="{{ $tipo }}">{{ $tipo }} (atual)</option>
                        @endif @foreach ($tipos as $opcao)
                            <option value="{{ $opcao }}">{{ $opcao }}</option>
                        @endforeach
                </select>
                @error('tipo')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-field full-field"><label for="ambienteId">Ambiente <span>*</span></label><select
                    id="ambienteId" wire:model="ambienteId" required>
                    <option value="">Selecione um ambiente</option>
                    @foreach ($ambientes as $ambiente)
                        <option value="{{ $ambiente->id }}">
                            {{ $ambiente->nome }}{{ $ambiente->status ? '' : ' · Inativo' }}</option>
                    @endforeach
                </select>
                <small>O sensor pode ser movido para outro ambiente posteriormente.</small>
                @error('ambienteId')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-field full-field"><label for="descricao">Descrição <span>*</span></label>
                <textarea id="descricao" wire:model.blur="descricao" rows="4" maxlength="1000" required
                    placeholder="Ex.: Sensor instalado próximo à janela"></textarea>
                @error('descricao')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-field full-field"><label class="toggle-label" for="status"><span><strong>Sensor
                            ativo</strong><small>Indique se o dispositivo deve ser considerado em
                            operação.</small></span><input id="status" type="checkbox" wire:model="status"></label>
                @error('status')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-footer"><a class="button button-secondary"
                href="{{ route('sensores.index') }}">Cancelar</a><button class="button button-primary" type="submit"
                wire:loading.attr="disabled" @disabled($ambientes->isEmpty())><span wire:loading.remove
                    wire:target="save">{{ $sensorId ? 'Salvar alterações' : 'Cadastrar sensor' }}</span><span
                    wire:loading wire:target="save">Salvando…</span></button></div>
    </form>
</main>
