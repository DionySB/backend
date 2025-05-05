# Fly Now Systems - Backend (Laravel 12)

Teste técnico para a vaga de Desenvolvedor(a) Júnior.
API REST para gerenciamento de tarefas com autenticação, filas, testes, documentação e estrutura limpa.

---/---

## Requisitos

* Docker
* Laravel Sail
* Composer

---/---

## Subindo o projeto

Clone esse repositório, entra na pasta e sobe com o Sail:

```bash
git clone https://github.com/seu-user/backend-fly-now-systems.git
cd backend-fly-now-systems
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

---/---

## Login e Autenticação

A autenticação usa Laravel Sanctum.

Faz login via:

```http
POST /api/login
{
  "email": "admin@email.com",
  "password": "123456"
}
```

O token vem na resposta. Usa isso no header das próximas requisições (caso não use o scramble)

```
Authorization: Bearer {token}
```

---/---

## Endpoints principais

Todos os endpoints exigem autenticação.

* `POST /api/login` – login
* `GET /api/tasks` – lista todas as tarefas do usuário
* `POST /api/tasks` – cria uma nova tarefa
* `PUT /api/tasks/{id}` – edita uma tarefa
* `PATCH /api/tasks/{id}/complete` – marca como concluída
* `DELETE /api/tasks/{id}` – deleta

---/---

## Jobs e Queue

Quando uma tarefa é criada, um Job é enfileirado (`NotifyTaskCreated`) que simula o envio de uma notificação via log.

Para processar a fila:

```bash
./vendor/bin/sail artisan queue:work
```

Log padrão:
```bash
tail -f storage/logs/laravel.log
```

---/---

## Testes

Tem testes automatizados básicos com Unit

Pra rodar:

```bash
./vendor/bin/sail artisan test
```

---/---

## Documentação da API

```
GET /docs 
http://localhost/docs/api
```

---/---

## Organização do código

* `App\Http\Controllers\Api` → controladores
* `App\Http\Requests` → validação
* `App\Services` → regras de negócio
* `App\Repositories` → acesso aos models
* `App\DTOs` → objetos de transferência de dados
* `App\Jobs` → Jobs para fila

---/---

## Considerações

* Tudo funciona rodando com Sail/Docker
* Queue testada com worker em execução
* Documentação exposta em `/docs`
* Testes básicos passando
* Estrutura separada por camadas
* Sanctum funcionando como esperado

