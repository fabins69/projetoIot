# Projeto IoT — Monitoramento
O projetoIoT FB é focado para o monitoramento dos ambientes, registrando leituras dos sensores.

## Funcionalidades

- Dashboard responsivo com contadores reais, sensores ativos, última leitura, gráfico das últimas 24 leituras e atividade recente.
- CRUDs de ambientes e sensores inteiramente em Livewire (listagem, formulários, busca, paginação e ações), sem controllers de recurso.
- Associação de cada sensor a um ambiente.
- Exclusão protegida: ambientes com sensores e sensores com leituras não podem ser removidos; assim o histórico permanece preservado.
- API JSON para inserir leituras e consultar o último valor por código do sensor.

## Instalação local

Para desenvolvimento com recarga de assets, use `npm run dev` em outro terminal.
´´´composer update´´´
´´´npm install´´´
´´´configurar .env´´´
´´´php artisan migrate´´´
´´´php artisan db:seed´´´
´´´php artisan key:generate´´´



## API de leituras

### Registrar leitura

`POST /api/registro` — JSON:

```json
{
  "cod_sensor": "TEMP-01",
  "valor": 24.3,
  "umidade": 51.2
}
```


### Consultar último valor

`GET /api/registro/valor?cod_sensor=TEMP-01`

Responde com `{"valor":"24.3"}`. Dados inválidos retornam erros JSON de validação; sensor sem registros retorna HTTP 404.

