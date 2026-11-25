#  Clothing E-Commerce Platform

An e-commerce platform built with **Laravel** for browsing clothing and managing products and orders, with a **local Arabic chat assistant** that works without any external API.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Quick Start with Docker](#quick-start-with-docker)
- [Local Setup without Docker](#local-setup-without-docker)
- [Database and Demo Data](#database-and-demo-data)
- [Testing](#testing)
- [Smart Assistant](#smart-assistant)
- [Main Routes](#main-routes)
- [Project Structure](#project-structure)
- [CI and Docker Hub](#ci-and-docker-hub)
- [Security](#security)

## Overview

The assistant helps users:

- Search for products.
- Get size and outfit suggestions.
- Filter options by color, occasion, and budget.
- Track their shopping cart and complete orders.

## Features

- User registration and login.
- Separate user and admin interfaces.
- Management of products, colors, sizes, and discounts.
- Shopping cart, wishlist, and reviews.
- Local chat assistant (no external API).
- Saved user preferences and chat analytics.
- Full Docker setup with PostgreSQL.

## Tech Stack

| Area | Technology |
|---|---|
| Backend | PHP 8.2, Laravel 12, Livewire 4 |
| Database | PostgreSQL 16 |
| Frontend | Vite 7, Tailwind CSS 4, Alpine.js |
| Real-time | Laravel Reverb |
| Deployment | Nginx, PHP-FPM, Supervisor, Docker |

## Requirements

**For Docker:**

- Docker Engine or Docker Desktop.
- Docker Compose.
- Ports `8000` and `8080` available.

**For local setup without Docker:**

- PHP 8.2 or later.
- Composer.
- Node.js and npm.
- PostgreSQL (or another supported database).

## Quick Start with Docker

**1. Copy the environment file:**

```bash
cp .env.example .env
```

**2. Set the following values in `.env`:**

```dotenv
APP_KEY=
DB_DATABASE=clothing
DB_USERNAME=clothing
DB_PASSWORD=change-this-password
REVERB_APP_ID=local
REVERB_APP_KEY=local-key
REVERB_APP_SECRET=local-secret
```

> Change `DB_PASSWORD` and the Reverb secrets to strong values of your own.

**3. Generate the Laravel key and copy the output into `APP_KEY`:**

```bash
docker compose build app
docker compose run --rm --no-deps app php artisan key:generate --show
```

**4. Start the project and run migrations:**

```bash
docker compose up -d --build
docker compose exec app php artisan migrate --force
```

**5. Open the app:**

- Application: <http://localhost:8000>
- Reverb WebSocket: port `8080`

### Useful Commands

```bash
# View logs
docker compose logs -f app

# Stop the project
docker compose down
```

> ⚠️ **Warning:** the following command permanently deletes the database and persistent files:
>
> ```bash
> docker compose down -v
> ```

## Local Setup without Docker

```bash
composer install
cp .env.example .env
php artisan key:generate
npm ci
npm run build
php artisan migrate
php artisan serve
```

The app usually runs at: <http://127.0.0.1:8000>

## Database and Demo Data

```bash
# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Recreate the database with demo data
php artisan migrate:fresh --seed
```

`DatabaseSeeder` currently creates two demo accounts:

| Account | Email | Password | Role |
|---|---|---|---|
| Admin | `admin@site.com` | `password` | admin |
| User | `user@site.com` | `password` | user |

> ⚠️ These accounts are for development only and must not be used in production.

## Testing

```bash
php artisan test
```

Feature tests require a configured database. You can point them at PostgreSQL through the `DB_*` variables.

## Smart Assistant

| File | Purpose |
|---|---|
| `app/Services/ClothingAssistantService.php` | Assistant logic |
| `app/Livewire/User/ClothingAssistant.php` | Assistant interface |
| `config/clothing-assistant.php` | Intents, synonyms, and occasions |

**Capabilities:**

- Text normalization and support for Arabic dialects.
- Fuzzy matching for spelling mistakes.
- Support for synonyms, colors, sizes, and prices.
- Size-finding flow.
- Full outfit suggestion flow.
- Saved user preferences and conversation analytics.
- Adding suggestions to the cart.

> ℹ️ Suggested products may not appear until published products are added to the database.

## Main Routes

**User:**

| Route | Description |
|---|---|
| `/` | Home page |
| `/login` | Login |
| `/register` | Registration |
| `/home` | User dashboard |
| `/profile-user` | User profile |
| `/cart` | Shopping cart |
| `/checkout` | Checkout |
| `/my-orders` | User orders |
| `/chat` | Clothing assistant |

**Admin:**

| Route | Description |
|---|---|
| `/admin/dashboard` | Admin dashboard |
| `/admin/products` | Product management |
| `/admin/orders` | Order management |
| `/admin/chat-analytics` | Chat analytics |

## Project Structure

```text
app/
  Http/                  Controllers and middleware
  Livewire/              User and admin interfaces
  Models/                Eloquent models
  Services/              Assistant and cart logic
bootstrap/               Laravel bootstrapping
config/                  App and assistant configuration
database/
  migrations/            Database schema
  seeders/               Initial data
docker/
  nginx/                 Nginx configuration
  php/                   OPcache configuration
  supervisord.conf       Process management inside the container
public/                  Public entry point
resources/
  css/ and js/           Frontend sources
  views/                 Blade and Livewire views
routes/web.php           Application routes
tests/                   PHPUnit tests
Dockerfile               App image build
docker-compose.yml       Runs the app and PostgreSQL
```

## CI and Docker Hub

The workflow lives at `.github/workflows/deploy.yml` and runs on:

- Pushes to `main` or `master`.
- Pull request creation.

**Required secrets** (GitHub repository Settings → Secrets) to publish the Docker image:

- `DOCKER_USERNAME`
- `DOCKER_PASSWORD`

> Make sure the PostgreSQL password used by the test service matches the `DB_PASSWORD` value in the workflow.

## Security

Do not commit these files or values to the repository:

- `.env`
- Laravel keys
- Database passwords
- Reverb secrets
- Docker Hub passwords

Use **GitHub Actions Secrets** in the workflow and runtime secrets on the server.
