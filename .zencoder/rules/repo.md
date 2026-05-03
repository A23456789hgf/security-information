---
description: Repository Information Overview
alwaysApply: true
---

# Security Information Project

## Summary
A PHP application built with the **Laravel Framework** (v10.x). It serves as a standard web application with a backend API (using Sanctum) and frontend assets bundled via Vite.

## Structure
- **app/**: Core application code (Controllers, Models, etc.).
- **database/**: Database migrations, factories, and seeders.
- **public/**: Web server entry point and static assets.
- **resources/**: Views (`.blade.php`), raw CSS, and JavaScript files.
- **tests/**: Automated tests (Feature and Unit).
- **vendor/**: Composer dependencies.

## Language & Runtime
**Language**: PHP  
**Version**: ^8.1  
**Framework**: Laravel 10.10+  
**Build System**: Composer (PHP), Vite (Frontend)  
**Package Manager**: Composer, NPM/Yarn

## Dependencies
**Main Dependencies**:
- **php**: ^8.1
- **laravel/framework**: ^10.10
- **laravel/sanctum**: ^3.3 (API Authentication)
- **guzzlehttp/guzzle**: ^7.2

**Development Dependencies**:
- **phpunit/phpunit**: ^10.1
- **laravel/sail**: ^1.18 (Docker environment)
- **laravel/pint**: ^1.0 (Code style)
- **spatie/laravel-ignition**: ^2.0 (Error page)

## Build & Installation
```bash
# PHP Dependencies
composer install

# Frontend Dependencies
npm install

# Build Assets
npm run build
# OR for development
npm run dev

# Setup Environment
cp .env.example .env
php artisan key:generate
```

## Docker
**Status**: The project includes `laravel/sail` for local Docker development, but no custom `Dockerfile` or `docker-compose.yml` was found in the project root. Sail likely uses default configurations or needs initialization.

## Testing

**Framework**: PHPUnit
**Test Location**: `tests/Unit` and `tests/Feature`
**Naming Convention**: `*Test.php`
**Configuration**: `phpunit.xml`

**Run Command**:

```bash
php artisan test
# OR
./vendor/bin/phpunit
```
