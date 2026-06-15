# Enterprise Service, Order, Customer Management and Analytics MVP

Backend demo for a senior Laravel role. The project implements a Laravel 11 API for order/service management, customer activity tracking, analytical reporting, and AI/LLM insight workflows.

## Stack

- PHP 8.2+
- Laravel 11
- PostgreSQL-ready schema, with SQLite-compatible local demo defaults
- Laravel Sanctum API tokens
- Queue/job-based delayed-order AI agent
- Service layer, form requests, RBAC middleware, cursor pagination, and feature tests

## Key Modules

- Orders: transactional placement, multi-service line items, status workflow, filtering, sorting, cursor pagination.
- Customers: profiles and activity log capture.
- Analytics: monthly sales, order distribution by service type, and top service categories.
- AI: mocked RAG retrieval, LLM provider hooks for OpenAI/Gemini, persisted AI insights.
- Agent: queued delayed-order analysis job and notification payload.

## Demo Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8000
```

Demo credentials:

```text
email: admin@example.com
password: password
```

Get an API token:

```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin@example.com\",\"password\":\"password\"}"
```

Generate an AI insight:

```bash
curl -X POST http://127.0.0.1:8000/api/admin/ai/insights \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d "{\"query\":\"Analyze last month's low-performing services\",\"provider\":\"mock\"}"
```

## API Surface

- `POST /api/auth/login`
- `GET /api/orders`
- `POST /api/orders`
- `GET /api/orders/{order}`
- `PATCH /api/orders/{order}`
- `DELETE /api/orders/{order}`
- `GET /api/admin/analytics/dashboard`
- `POST /api/admin/ai/insights`

## PostgreSQL Configuration

`.env.example` includes PostgreSQL settings. For production-like analytics, switch to:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=enterprise_mvp
DB_USERNAME=postgres
DB_PASSWORD=secret
```

The analytics service uses PostgreSQL `DATE_TRUNC` when the active connection is `pgsql`, and falls back to SQLite date functions for local demo tests.

## Verification

```bash
php artisan migrate --seed
php artisan route:list
php artisan test
```

Current verification: 5 tests passing, 17 assertions.

Security note: `composer audit` reports CVE-2026-48019 for `laravel/framework` v11.54.0. The currently visible patched line is Laravel 12.x. This demo stays on Laravel 11 to match the requested job stack; upgrade the framework before using this as a production base if your constraints allow Laravel 12.
