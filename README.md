# Projeto IoT — Monitoramento

Aplicação Laravel 11 com Livewire para organizar ambientes e sensores e acompanhar leituras enviadas pela API.

## Funcionalidades

- Dashboard responsivo com contadores reais, sensores ativos, última leitura, gráfico das últimas 24 leituras e atividade recente.
- CRUD de ambientes e sensores, incluindo busca, paginação, validação e ativação/inativação.
- Associação de cada sensor a um ambiente.
- Exclusão protegida: ambientes com sensores e sensores com leituras não podem ser removidos; assim o histórico permanece preservado.
- API JSON para inserir leituras e consultar o último valor por código do sensor.

## Requisitos

- PHP 8.2 ou superior, extensões exigidas pelo Laravel 11 e Composer.
- Node.js e npm.
- Banco SQLite (padrão do `.env.example`) ou outro banco suportado pelo Laravel.

## Instalação local

```bash
cp .env.example .env
composer install
php artisan key:generate
mkdir -p database && touch database/database.sqlite
php artisan migrate
npm install
npm run build
php artisan serve
```

Acesse `http://127.0.0.1:8000`. O dashboard redireciona da raiz `/` para `/dashboard`.

Para desenvolvimento com recarga de assets, use `npm run dev` em outro terminal.

## Rotas web

- `/dashboard` — visão geral.
- `/ambientes` — listar, buscar, criar, editar e excluir ambientes.
- `/sensores` — listar, buscar, criar, editar e excluir sensores.

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

O código deve existir e `umidade` deve estar entre 0 e 100. Resposta de sucesso: HTTP 201 com o registro criado.

### Consultar último valor

`GET /api/registro/valor?cod_sensor=TEMP-01`

Responde com `{"valor":"24.3"}`. Dados inválidos retornam erros JSON de validação; sensor sem registros retorna HTTP 404.

## Testes

```bash
php artisan test
```
