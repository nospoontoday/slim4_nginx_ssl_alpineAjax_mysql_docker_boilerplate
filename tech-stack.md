# Complete Stack Documentation & Standards Guide

## 📚 The Definitive Guide for AI-Led PHP Development

### Version 1.0 | Last Updated: 18-08-2025

-----

## Table of Contents

1. [Stack Overview](#1-stack-overview)
1. [Architecture](#2-architecture)
1. [Development Environment](#3-development-environment)
1. [Coding Standards](#4-coding-standards)
1. [Documentation Standards](#5-documentation-standards)
1. [REST API Standards](#6-rest-api-standards)
1. [CSS Architecture & Design System](#7-css-architecture--design-system)
1. [Component Library](#8-component-library)
1. [HTMX Patterns](#9-htmx-patterns)
1. [Testing Strategy](#10-testing-strategy)
1. [Database Standards](#11-database-standards)
1. [Security Standards](#12-security-standards)
1. [Performance Guidelines](#13-performance-guidelines)
1. [Deployment Process](#14-deployment-process)
1. [AI Development Workflow](#15-ai-development-workflow)
1. [Quick Reference](#16-quick-reference)

-----

## 1. Stack Overview

### Technology Stack

```yaml
Frontend:
  HTML5: Semantic markup, progressive enhancement
  Custom CSS System: Component-based design system
  Alpine.js 3.14.1: Lightweight reactive framework (15KB)
  HTMX 2.0.3: HTML-over-the-wire (14KB)
  Custom Component Library: Reusable UI patterns

Backend:
  PHP 8.3: Strict types, JIT compilation enabled
  Slim Framework 4: Minimal routing framework
  Architecture: ADR (Action-Domain-Responder) pattern

API Layer:
  REST API: Content negotiation (JSON/HTML)
  OpenAPI 3.1: API documentation
  Versioning: URL-based (/api/v1/)

Data Layer:
  MySQL 8.0: Primary database
  Redis 7.2: Sessions, caching, queues
  PDO: Direct database access (no ORM)

Infrastructure:
  Docker Compose: Development environment
  Nginx: Web server, reverse proxy
  Cloudflare: CDN, DDoS protection
  GitHub Actions: CI/CD pipeline

Quality Assurance:
  PHPStan Level 8: Static analysis
  PHP CS Fixer: Code style enforcement
  PHPUnit: Unit and integration testing
  Playwright: E2E testing
```

### Architecture Diagram

```plaintext
┌─────────────────────────────────────────────────────┐
│                    Browser                          │
│         HTMX + Alpine.js + Custom CSS               │
└──────────────────────┬──────────────────────────────┘
                       │ HTTPS
┌──────────────────────▼──────────────────────────────┐
│                 Cloudflare CDN                      │
└──────────────────────┬──────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────┐
│                Nginx (Port 80/443)                  │
│              Static Files + Reverse Proxy           │
└──────────────────────┬──────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────┐
│              REST API (Content Negotiation)         │
│          Returns HTML for HTMX, JSON for API        │
│                                                     │
│  Slim 4 → Actions → Services → Repositories → PDO   │
└──────────────────────┬──────────────────────────────┘
                       │
         ┌─────────────┴─────────────┐
         │                           │
    ┌────▼────┐              ┌──────▼──────┐
    │  MySQL  │              │    Redis    │
    └─────────┘              └─────────────┘
```

-----

## 2. Architecture

### Project Structure

```plaintext
project/
├── public/                     # Web root
│   ├── index.php              # Single entry point
│   └── assets/                # Static files
│       ├── css/
│       │   ├── master.css    # Compiled CSS (do not edit)
│       │   ├── variables.css # Design tokens
│       │   ├── base.css      # Base styles
│       │   ├── components.css # Component styles
│       │   └── utilities.css  # Utility classes
│       ├── js/
│       ├── images/
│       └── fonts/
│
├── src/                       # Application code
│   ├── Actions/              # Request handlers (ADR pattern)
│   │   ├── Api/
│   │   └── Web/
│   ├── Services/             # Business logic
│   ├── Repositories/         # Data access layer
│   ├── Responders/           # Response formatting
│   ├── Middleware/           # Request/Response middleware
│   ├── Validators/           # Input validation
│   └── Exceptions/           # Custom exceptions
│
├── components/               # UI Component library
│   ├── UI/                  # Base components
│   ├── Patterns/            # Complex patterns
│   └── Layouts/             # Page layouts
│
├── templates/               # HTML templates
│   ├── layouts/
│   ├── pages/
│   └── fragments/
│
├── config/                  # Configuration
│   ├── app.php
│   ├── database.php
│   └── routes.php
│
├── database/                # Database files
│   ├── migrations/
│   ├── seeds/
│   └── schema.sql
│
├── tests/                   # Test files
│   ├── Unit/
│   ├── Integration/
│   └── E2E/
│
├── docs/                    # Documentation
│   ├── api/
│   ├── architecture/
│   └── development/
│
├── docker/                  # Docker configuration
│   ├── php/
│   ├── nginx/
│   └── mysql/
│
├── .github/                 # GitHub configuration
│   └── workflows/
│
├── vendor/                  # Composer dependencies
├── composer.json
├── phpstan.neon            # PHPStan configuration
├── .php-cs-fixer.php       # Code style configuration
├── docker-compose.yml
├── Makefile
└── README.md
```

### ADR Pattern Implementation

```php
// Action: Handles HTTP request/response
class ListUsersAction
{
    public function __construct(
        private UserService $service,      // Domain logic
        private UserResponder $responder   // Response formatting
    ) {}
    
    public function __invoke(Request $request, Response $response): Response
    {
        $users = $this->service->getUsers($request->getQueryParams());
        return $this->responder->respond($request, $response, $users);
    }
}

// Domain: Business logic
class UserService
{
    public function getUsers(array $filters): array
    {
        // Business logic here
        return $this->repository->findAll($filters);
    }
}

// Responder: Formats response
class UserResponder
{
    public function respond(Request $request, Response $response, $data): Response
    {
        if ($request->hasHeader('HX-Request')) {
            return $this->htmlResponse($response, $data);
        }
        return $this->jsonResponse($response, $data);
    }
}
```

-----

## 3. Development Environment

### Docker Setup

```yaml
# docker-compose.yml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    volumes:
      - .:/var/www
      - ./docker/php/php.ini:/usr/local/etc/php/php.ini
    environment:
      - APP_ENV=development
      - DB_HOST=mysql
      - REDIS_HOST=redis
    depends_on:
      - mysql
      - redis

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - .:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app

  mysql:
    image: mysql:8.0
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: app
    volumes:
      - mysql_data:/var/lib/mysql
      - ./docker/mysql/init.sql:/docker-entrypoint-initdb.d/init.sql

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"

  mailhog:
    image: mailhog/mailhog
    ports:
      - "1025:1025"
      - "8025:8025"

volumes:
  mysql_data:
```

### Makefile Commands

```makefile
# Makefile
.DEFAULT_GOAL := help
.PHONY: help up down build test analyze fix css

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

up: ## Start development environment
	docker-compose up -d
	@echo "Application: http://localhost"
	@echo "MailHog: http://localhost:8025"

down: ## Stop development environment
	docker-compose down

build: ## Build containers and assets
	docker-compose build
	docker-compose exec app composer install
	make css

css: ## Build CSS files
	cat public/assets/css/variables.css \
	    public/assets/css/base.css \
	    public/assets/css/components.css \
	    public/assets/css/utilities.css \
	    > public/assets/css/master.css
	@echo "CSS built successfully"

css-watch: ## Watch and rebuild CSS
	while true; do \
		inotifywait -e modify public/assets/css/*.css; \
		make css; \
		echo "CSS rebuilt at $$(date)"; \
	done

test: ## Run all tests
	docker-compose exec app vendor/bin/phpunit

analyze: ## Run static analysis
	docker-compose exec app vendor/bin/phpstan analyze
	docker-compose exec app vendor/bin/php-cs-fixer fix --dry-run

fix: ## Fix code style
	docker-compose exec app vendor/bin/php-cs-fixer fix

migrate: ## Run database migrations
	docker-compose exec app php database/migrate.php

seed: ## Seed database with test data
	docker-compose exec app php database/seed.php

fresh: ## Fresh database with seeds
	docker-compose exec app php database/fresh.php

logs: ## Show logs
	docker-compose logs -f

shell: ## Enter PHP container
	docker-compose exec app sh

db: ## Enter MySQL
	docker-compose exec mysql mysql -uroot -proot app

redis-cli: ## Enter Redis CLI
	docker-compose exec redis redis-cli
```

-----

## 4. Coding Standards

### PSR Standards

We follow **PSR-12** for code style and **PSR-4** for autoloading.

### PHP Configuration

```php
<?php
// All PHP files start with
declare(strict_types=1);

// Namespace follows PSR-4
namespace App\Services;

// Imports are alphabetically ordered
use App\Exceptions\ValidationException;
use App\Repositories\UserRepository;
use Psr\Log\LoggerInterface;
```

### PHP CS Fixer Configuration

```php
<?php
// .php-cs-fixer.php
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->in(__DIR__ . '/public');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'single_quote' => true,
        'trailing_comma_in_multiline' => true,
        'return_type_declaration' => true,
        'void_return' => true,
        'phpdoc_align' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
```

### Type Declarations

```php
// Always use strict types and full type hints
class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly LoggerInterface $logger
    ) {}
    
    /**
     * @param array{name: string, email: string, role?: string} $data
     * @return array{id: int, name: string, email: string}
     * @throws ValidationException
     */
    public function createUser(array $data): array
    {
        // Implementation
    }
    
    /**
     * @param positive-int $id
     */
    public function findById(int $id): ?User
    {
        // Implementation
    }
    
    /**
     * @return array<int, User>
     */
    public function findAll(): array
    {
        // Implementation
    }
}
```

-----

## 5. Documentation Standards

### File Documentation

```php
<?php
/**
 * @file UserController.php
 * @purpose Handles user-related HTTP requests
 * @ai-context REST API with content negotiation
 * @ai-patterns CRUD operations, pagination, filtering
 */

declare(strict_types=1);

namespace App\Controllers;
```

### Class Documentation

```php
/**
 * User management service
 * 
 * @ai-purpose Business logic for user operations
 * @ai-dependencies UserRepository, EventDispatcher, MailService
 * @ai-flow
 *   1. Validate input
 *   2. Execute business logic
 *   3. Trigger events
 *   4. Return formatted data
 * 
 * @example
 * ```php
 * $userService = new UserService($repo, $events, $mail);
 * $user = $userService->createUser(['name' => 'John', 'email' => 'john@example.com']);
 * ```
 */
class UserService
{
    // Implementation
}
```

### Method Documentation

```php
/**
 * Create a new user
 * 
 * @ai-endpoint POST /api/users
 * @ai-auth required
 * @ai-validation UserCreateValidator
 * 
 * @param array{
 *   name: string,
 *   email: string,
 *   password: string,
 *   role?: 'user'|'admin'
 * } $data User data from request
 * 
 * @return array{
 *   id: int,
 *   name: string,
 *   email: string,
 *   created_at: string
 * } Created user data
 * 
 * @throws ValidationException When validation fails
 * @throws DuplicateEmailException When email exists
 * 
 * @ai-test
 * ```php
 * $data = ['name' => 'Test', 'email' => 'test@example.com', 'password' => 'secret123'];
 * $user = $service->createUser($data);
 * assert($user['email'] === 'test@example.com');
 * ```
 */
public function createUser(array $data): array
{
    // Implementation
}
```

### Inline Comments

```php
public function processOrder(int $orderId): void
{
    // @ai-step 1: Load order with relationships
    // Includes: items, customer, shipping address
    $order = $this->orderRepository->findWithRelations($orderId);
    
    // @ai-step 2: Validate order can be processed
    // Checks: payment status, inventory availability
    $this->validator->validateProcessable($order);
    
    // @ai-step 3: Process payment
    // External API: Stripe/PayPal integration
    $payment = $this->paymentService->charge($order);
    
    // @ai-step 4: Update inventory
    // Decrements stock, triggers reorder if needed
    $this->inventoryService->decrementStock($order->items);
    
    // @ai-step 5: Send notifications
    // Emails: customer confirmation, warehouse picking list
    $this->notificationService->sendOrderConfirmation($order);
}
```

-----

## 6. REST API Standards

### API Response Structure

```php
<?php
// src/Http/ApiResponse.php
declare(strict_types=1);

namespace App\Http;

class ApiResponse
{
    /**
     * Success response
     */
    public static function success(
        Request $request,
        Response $response,
        mixed $data,
        array $meta = [],
        int $status = 200
    ): Response {
        if ($request->hasHeader('HX-Request')) {
            return self::htmxResponse($response, $data);
        }
        
        $payload = [
            'status' => 'success',
            'data' => $data,
            'meta' => array_merge([
                'timestamp' => time(),
                'version' => '1.0'
            ], $meta)
        ];
        
        return $response
            ->withJson($payload)
            ->withStatus($status);
    }
    
    /**
     * Error response
     */
    public static function error(
        Request $request,
        Response $response,
        string $message,
        array $errors = [],
        int $status = 400
    ): Response {
        if ($request->hasHeader('HX-Request')) {
            return self::htmxError($response, $message, $errors);
        }
        
        $payload = [
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
            'meta' => [
                'timestamp' => time(),
                'version' => '1.0'
            ]
        ];
        
        return $response
            ->withJson($payload)
            ->withStatus($status);
    }
    
    /**
     * Paginated response
     */
    public static function paginated(
        Request $request,
        Response $response,
        array $items,
        int $page,
        int $perPage,
        int $total
    ): Response {
        $lastPage = (int) ceil($total / $perPage);
        
        $meta = [
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => $lastPage,
                'from' => ($page - 1) * $perPage + 1,
                'to' => min($page * $perPage, $total)
            ]
        ];
        
        return self::success($request, $response, $items, $meta);
    }
}
```

### API Endpoints

```php
// routes/api.php
$app->group('/api/v1', function ($group) {
    // Users
    $group->get('/users', Actions\Users\ListAction::class);
    $group->get('/users/{id:[0-9]+}', Actions\Users\GetAction::class);
    $group->post('/users', Actions\Users\CreateAction::class);
    $group->put('/users/{id:[0-9]+}', Actions\Users\UpdateAction::class);
    $group->patch('/users/{id:[0-9]+}', Actions\Users\PatchAction::class);
    $group->delete('/users/{id:[0-9]+}', Actions\Users\DeleteAction::class);
    
    // Bulk operations
    $group->post('/users/bulk', Actions\Users\BulkCreateAction::class);
    $group->delete('/users/bulk', Actions\Users\BulkDeleteAction::class);
    
    // Special endpoints
    $group->get('/users/search', Actions\Users\SearchAction::class);
    $group->post('/users/export', Actions\Users\ExportAction::class);
})->add(new Middleware\ApiAuthentication())
  ->add(new Middleware\RateLimiting())
  ->add(new Middleware\Cors());
```

### HTTP Status Codes

```php
// Standard responses
200 OK                  // GET success
201 Created            // POST success
204 No Content         // DELETE success

// Client errors
400 Bad Request        // Invalid request
401 Unauthorized       // No authentication
403 Forbidden          // No permission
404 Not Found          // Resource not found
422 Unprocessable      // Validation error
429 Too Many Requests  // Rate limited

// Server errors
500 Internal Error     // Server fault
503 Service Unavailable // Maintenance
```

-----

## 7. CSS Architecture & Design System

### Master CSS Structure

Our CSS architecture uses a modular system with four source files compiled into one `master.css`:

1. **variables.css** - Design tokens and brand configuration
1. **base.css** - Reset and foundational styles
1. **components.css** - Reusable component styles
1. **utilities.css** - Single-purpose utility classes

### CSS Variables (Design Tokens)

```css
/* public/assets/css/variables.css */
/**
 * Design System Variables
 * 
 * @ai-purpose Central design tokens for consistent styling
 * @ai-usage These variables are used throughout all CSS files
 * @ai-modification Change brand colors and fonts here to update entire site
 */

:root {
    /* ===========================
       Brand Palette
       =========================== */
    
    /* Primary Brand Color */
    --brand-primary: #2563eb;        /* Main brand color */
    --brand-primary-light: #60a5fa;  /* Lighter variant for hover states */
    --brand-primary-dark: #1d4ed8;   /* Darker variant for active states */
    --brand-primary-rgb: 37, 99, 235; /* RGB values for opacity effects */
    
    /* Secondary Brand Color */
    --brand-secondary: #10b981;      /* Secondary/accent color */
    --brand-secondary-light: #34d399; /* Lighter variant */
    --brand-secondary-dark: #059669;  /* Darker variant */
    --brand-secondary-rgb: 16, 185, 129;
    
    /* Logo Background Color */
    /* This ensures the logo always sits on the correct background */
    --logo-background: #f8fafc;      /* Logo background color */
    --logo-border: #e2e8f0;          /* Optional border for logo area */
    
    /* ===========================
       Typography
       =========================== */
    
    /* Primary Font - Used for headings and UI elements */
    --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 
                    Roboto, Oxygen, Ubuntu, sans-serif;
    
    /* Secondary Font - Used for body text and content */
    --font-secondary: 'Georgia', Cambria, 'Times New Roman', Times, serif;
    
    /* Font Weights */
    --font-weight-light: 300;
    --font-weight-normal: 400;
    --font-weight-medium: 500;
    --font-weight-semibold: 600;
    --font-weight-bold: 700;
    
    /* Font Sizes */
    --font-size-xs: 0.75rem;    /* 12px */
    --font-size-sm: 0.875rem;   /* 14px */
    --font-size-base: 1rem;     /* 16px */
    --font-size-lg: 1.125rem;   /* 18px */
    --font-size-xl: 1.25rem;    /* 20px */
    --font-size-2xl: 1.5rem;    /* 24px */
    --font-size-3xl: 1.875rem;  /* 30px */
    --font-size-4xl: 2.25rem;   /* 36px */
    --font-size-5xl: 3rem;      /* 48px */
    
    /* ===========================
       Neutral Colors
       =========================== */
    
    --color-white: #ffffff;
    --color-black: #000000;
    --color-gray-50: #f8fafc;
    --color-gray-100: #f1f5f9;
    --color-gray-200: #e2e8f0;
    --color-gray-300: #cbd5e1;
    --color-gray-400: #94a3b8;
    --color-gray-500: #64748b;
    --color-gray-600: #475569;
    --color-gray-700: #334155;
    --color-gray-800: #1e293b;
    --color-gray-900: #0f172a;
    
    /* ===========================
       Semantic Colors
       =========================== */
    
    --color-success: #10b981;
    --color-warning: #f59e0b;
    --color-error: #ef4444;
    --color-info: #3b82f6;
    
    /* ===========================
       Spacing Scale
       =========================== */
    
    --spacing-0: 0;
    --spacing-1: 0.25rem;   /* 4px */
    --spacing-2: 0.5rem;    /* 8px */
    --spacing-3: 0.75rem;   /* 12px */
    --spacing-4: 1rem;      /* 16px */
    --spacing-6: 1.5rem;    /* 24px */
    --spacing-8: 2rem;      /* 32px */
    
    /* ===========================
       Layout
       =========================== */
    
    --container-max-width: 1280px;
    --border-radius: 0.25rem;
    --border-radius-lg: 0.5rem;
    
    /* ===========================
       Shadows & Transitions
       =========================== */
    
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --transition-fast: 150ms ease-in-out;
    --transition-base: 250ms ease-in-out;
}
```

### Base Styles

```css
/* public/assets/css/base.css */
/**
 * Base Styles & Reset
 * 
 * @ai-purpose Normalize browser defaults and set foundational styles
 */

*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    font-size: 16px;
    line-height: 1.5;
    -webkit-text-size-adjust: 100%;
    -webkit-font-smoothing: antialiased;
}

body {
    font-family: var(--font-secondary);
    font-size: var(--font-size-base);
    color: var(--color-gray-900);
    background-color: var(--color-white);
    min-height: 100vh;
}

h1, h2, h3, h4, h5, h6 {
    font-family: var(--font-primary);
    font-weight: var(--font-weight-bold);
    line-height: 1.2;
    margin-bottom: var(--spacing-4);
}

h1 { font-size: var(--font-size-5xl); }
h2 { font-size: var(--font-size-4xl); }
h3 { font-size: var(--font-size-3xl); }
h4 { font-size: var(--font-size-2xl); }
h5 { font-size: var(--font-size-xl); }
h6 { font-size: var(--font-size-lg); }

p {
    margin-bottom: var(--spacing-4);
    line-height: 1.6;
}

a {
    color: var(--brand-primary);
    text-decoration: none;
    transition: color var(--transition-fast);
}

a:hover {
    color: var(--brand-primary-dark);
    text-decoration: underline;
}

/* Logo Specific Styling */
.logo-container {
    background-color: var(--logo-background);
    padding: var(--spacing-2);
    border-radius: var(--border-radius);
    display: inline-block;
}

.logo {
    display: block;
    max-width: 200px;
    height: auto;
}
```

### Component Styles

```css
/* public/assets/css/components.css */
/**
 * Component Styles
 * 
 * @ai-purpose Reusable UI component styles
 */

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-3) var(--spacing-6);
    font-family: var(--font-primary);
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-medium);
    border: 1px solid transparent;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.btn-primary {
    background-color: var(--brand-primary);
    color: var(--color-white);
    border-color: var(--brand-primary);
}

.btn-primary:hover {
    background-color: var(--brand-primary-dark);
    border-color: var(--brand-primary-dark);
}

.btn-secondary {
    background-color: var(--brand-secondary);
    color: var(--color-white);
    border-color: var(--brand-secondary);
}

/* Cards */
.card {
    background-color: var(--color-white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.card-header {
    padding: var(--spacing-4);
    border-bottom: 1px solid var(--color-gray-200);
}

.card-body {
    padding: var(--spacing-4);
}

/* Forms */
.form-group {
    margin-bottom: var(--spacing-4);
}

.form-label {
    display: block;
    margin-bottom: var(--spacing-2);
    font-family: var(--font-primary);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
}

.form-input {
    width: 100%;
    padding: var(--spacing-2) var(--spacing-3);
    font-family: var(--font-primary);
    border: 1px solid var(--color-gray-300);
    border-radius: var(--border-radius);
    transition: border-color var(--transition-fast);
}

.form-input:focus {
    outline: none;
    border-color: var(--brand-primary);
    box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.1);
}

/* Alerts */
.alert {
    padding: var(--spacing-4);
    border-radius: var(--border-radius);
    margin-bottom: var(--spacing-4);
}

.alert-success {
    background-color: rgba(16, 185, 129, 0.1);
    border: 1px solid var(--color-success);
    color: var(--color-success);
}

.alert-error {
    background-color: rgba(239, 68, 68, 0.1);
    border: 1px solid var(--color-error);
    color: var(--color-error);
}

/* Loading States */
.spinner {
    display: inline-block;
    width: 1.5rem;
    height: 1.5rem;
    border: 2px solid var(--color-gray-300);
    border-top-color: var(--brand-primary);
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* HTMX Indicators */
.htmx-indicator {
    display: none;
}

.htmx-request .htmx-indicator {
    display: inline-block;
}
```

### Utility Classes

```css
/* public/assets/css/utilities.css */
/**
 * Utility Classes
 * 
 * @ai-purpose Single-purpose helper classes
 */

/* Display */
.d-none { display: none; }
.d-block { display: block; }
.d-flex { display: flex; }

/* Spacing */
.m-0 { margin: 0; }
.m-1 { margin: var(--spacing-1); }
.m-2 { margin: var(--spacing-2); }
.m-3 { margin: var(--spacing-3); }
.m-4 { margin: var(--spacing-4); }

.p-0 { padding: 0; }
.p-1 { padding: var(--spacing-1); }
.p-2 { padding: var(--spacing-2); }
.p-3 { padding: var(--spacing-3); }
.p-4 { padding: var(--spacing-4); }

/* Text */
.text-center { text-align: center; }
.text-primary { color: var(--brand-primary); }
.text-secondary { color: var(--brand-secondary); }
.text-muted { color: var(--color-gray-600); }

/* Container */
.container {
    max-width: var(--container-max-width);
    margin: 0 auto;
    padding: 0 var(--spacing-4);
}
```

-----

## 8. Component Library

### Base Component Class

```php
<?php
// components/Component.php
declare(strict_types=1);

namespace Components;

/**
 * Base component class
 * 
 * @ai-purpose Foundation for all UI components
 * @ai-usage Extend this class for new components
 */
abstract class Component
{
    protected array $props;
    
    public function __construct(array $props = [])
    {
        $this->props = $props;
    }
    
    abstract public function render(): string;
    
    protected function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
```

### Button Component

```php
<?php
// components/UI/Button.php
namespace Components\UI;

use Components\Component;

/**
 * @ai-component Button
 * @ai-props {
 *   text: string,
 *   type?: 'primary'|'secondary'|'ghost',
 *   htmx?: array
 * }
 */
class Button extends Component
{
    public function render(): string
    {
        $type = $this->props['type'] ?? 'primary';
        $text = $this->e($this->props['text']);
        
        $classes = ['btn', "btn-{$type}"];
        $attrs = ['class' => implode(' ', $classes)];
        
        // Add HTMX attributes if provided
        if (isset($this->props['htmx'])) {
            foreach ($this->props['htmx'] as $key => $value) {
                $attrs["hx-{$key}"] = $value;
            }
        }
        
        $attributes = [];
        foreach ($attrs as $key => $value) {
            $attributes[] = sprintf('%s="%s"', $key, $this->e($value));
        }
        
        return sprintf(
            '<button %s>%s</button>',
            implode(' ', $attributes),
            $text
        );
    }
}
```

-----

## 9. HTMX Patterns

### Live Search

```html
<!-- Live search with debounce -->
<input type="search"
       name="q"
       placeholder="Search..."
       hx-get="/api/search"
       hx-trigger="input changed delay:500ms"
       hx-target="#results"
       hx-indicator="#spinner">

<div id="spinner" class="htmx-indicator">Searching...</div>
<div id="results"></div>
```

### Infinite Scroll

```html
<!-- Infinite scroll pattern -->
<div id="content">
    <!-- Initial content -->
</div>

<div hx-get="/api/posts?page=2"
     hx-trigger="revealed"
     hx-swap="beforebegin"
     hx-indicator="#loading">
    <span id="loading" class="htmx-indicator">Loading more...</span>
</div>
```

### Inline Edit

```html
<!-- Click to edit pattern -->
<div class="editable" 
     hx-get="/api/edit-form/123"
     hx-trigger="click"
     hx-swap="outerHTML">
    <span>Click to edit</span>
</div>
```

### Auto-save Form

```html
<!-- Auto-save form -->
<form hx-post="/api/draft"
      hx-trigger="input changed delay:2s"
      hx-swap="none"
      hx-indicator="#saving">
    <textarea name="content"></textarea>
    <span id="saving" class="htmx-indicator">Saving...</span>
</form>
```

### Modal Dialog

```html
<!-- Trigger modal -->
<button hx-get="/api/modal/create"
        hx-target="#modal"
        hx-swap="innerHTML">
    Open Modal
</button>

<div id="modal"></div>
```

-----

## 10. Testing Strategy

### PHPStan Configuration

```yaml
# phpstan.neon
includes:
    - vendor/phpstan/phpstan-strict-rules/rules.neon

parameters:
    level: 8
    paths:
        - src
        - tests
    
    checkMissingIterableValueType: true
    checkGenericClassInNonGenericObjectType: true
    treatPhpDocTypesAsCertain: true
```

### Unit Testing

```php
<?php
// tests/Unit/UserServiceTest.php
declare(strict_types=1);

namespace Tests\Unit;

use App\Services\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $service;
    
    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepository::class);
        $this->service = new UserService($this->repository);
    }
    
    /**
     * @test
     */
    public function it_creates_user_with_valid_data(): void
    {
        // Arrange
        $data = ['name' => 'John', 'email' => 'john@example.com'];
        
        $this->repository
            ->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn(['id' => 1] + $data);
        
        // Act
        $user = $this->service->createUser($data);
        
        // Assert
        $this->assertArrayHasKey('id', $user);
        $this->assertEquals($data['email'], $user['email']);
    }
}
```

### Integration Testing

```php
<?php
// tests/Integration/ApiTest.php
declare(strict_types=1);

namespace Tests\Integration;

use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_json_for_api_requests(): void
    {
        $response = $this->get('/api/users', [
            'Accept' => 'application/json'
        ]);
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => ['id', 'name', 'email']
            ],
            'meta'
        ]);
    }
    
    /**
     * @test
     */
    public function it_returns_html_for_htmx_requests(): void
    {
        $response = $this->get('/api/users', [
            'HX-Request' => 'true'
        ]);
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/html');
        $response->assertSee('user-card');
    }
}
```

-----

## 11. Database Standards

### Naming Conventions

```sql
-- Tables: plural, snake_case
CREATE TABLE users (
    -- Primary keys: always 'id'
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Columns: snake_case
    first_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    
    -- Timestamps: _at suffix
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes: idx_table_column(s)
    INDEX idx_users_email (email),
    
    -- Unique constraints: uk_table_column(s)
    CONSTRAINT uk_users_email UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Foreign keys: fk_table_column_reference
ALTER TABLE posts
ADD CONSTRAINT fk_posts_user_id_users
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE;
```

### Repository Pattern

```php
<?php
// src/Repositories/UserRepository.php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class UserRepository
{
    public function __construct(
        private readonly PDO $db
    ) {}
    
    /**
     * @return array<int, array{id: int, name: string, email: string}>
     */
    public function findAll(array $filters = []): array
    {
        $sql = 'SELECT id, name, email FROM users WHERE 1=1';
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= ' AND (name LIKE :search OR email LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= ' ORDER BY created_at DESC LIMIT 20';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
```

-----

## 12. Security Standards

### Input Validation

```php
<?php
// src/Validators/UserValidator.php
declare(strict_types=1);

namespace App\Validators;

class UserValidator
{
    public function validateCreate(array $data): array
    {
        $errors = [];
        
        // Name validation
        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        } elseif (strlen($data['name']) < 2) {
            $errors['name'] = 'Name must be at least 2 characters';
        }
        
        // Email validation
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        
        return $data;
    }
}
```

### Authentication Middleware

```php
<?php
// src/Middleware/AuthMiddleware.php
declare(strict_types=1);

namespace App\Middleware;

class AuthMiddleware
{
    public function __invoke($request, $handler)
    {
        $token = $this->extractToken($request);
        
        if (!$token || !$this->validateToken($token)) {
            return ApiResponse::error(
                $request,
                new Response(),
                'Unauthorized',
                [],
                401
            );
        }
        
        $request = $request->withAttribute('user_id', $this->getUserId($token));
        
        return $handler->handle($request);
    }
    
    private function extractToken($request): ?string
    {
        $header = $request->getHeaderLine('Authorization');
        
        if (preg_match('/Bearer\s+(.+)/', $header, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}
```

-----

## 13. Performance Guidelines

### Caching Strategy

```php
<?php
// src/Services/CacheService.php
declare(strict_types=1);

namespace App\Services;

use Redis;

class CacheService
{
    private Redis $redis;
    
    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        $cached = $this->redis->get($key);
        
        if ($cached !== false) {
            return unserialize($cached);
        }
        
        $value = $callback();
        $this->redis->setex($key, $ttl, serialize($value));
        
        return $value;
    }
    
    public function forget(string $key): void
    {
        $this->redis->del($key);
    }
}
```

### Query Optimization

```php
// Use prepared statements
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);

// Use indexes
// CREATE INDEX idx_users_email ON users(email);

// Limit results
$stmt = $pdo->prepare('SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit');
$stmt->bindValue('limit', 20, PDO::PARAM_INT);

// Use joins instead of N+1
$sql = '
    SELECT u.*, COUNT(p.id) as post_count
    FROM users u
    LEFT JOIN posts p ON u.id = p.user_id
    GROUP BY u.id
';
```

-----

## 14. Deployment Process

### Environment Configuration

```bash
# .env.production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=app
DB_USERNAME=user
DB_PASSWORD=secret

REDIS_HOST=localhost
REDIS_PORT=6379

CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

### GitHub Actions CI/CD

```yaml
# .github/workflows/deploy.yml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          
      - name: Install dependencies
        run: composer install --no-dev --optimize-autoloader
        
      - name: Run tests
        run: vendor/bin/phpunit
        
      - name: Run static analysis
        run: vendor/bin/phpstan analyze
        
  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/app
            git pull origin main
            composer install --no-dev --optimize-autoloader
            php database/migrate.php
            make css
```

### Production Checklist

- [ ] Environment variables configured
- [ ] Debug mode disabled
- [ ] HTTPS enabled
- [ ] Database backed up
- [ ] Redis configured
- [ ] Error logging configured
- [ ] Monitoring setup
- [ ] Rate limiting enabled
- [ ] CORS configured
- [ ] CSS compiled and minified
- [ ] OPcache enabled

-----

## 15. AI Development Workflow

### Project Setup for AI

```bash
# 1. Initialize project
make init

# 2. Start development environment
make up

# 3. Seed test data
make seed

# 4. Generate AI context
make ai-context > AI_CONTEXT.md
```

### AI Prompt Templates

```markdown
# Feature Development
"Create a {feature} feature with:
1. Action class following ADR pattern in src/Actions/
2. Service class with business logic in src/Services/
3. Repository with PHPStan types in src/Repositories/
4. HTMX frontend using patterns from components/
5. Integration tests in tests/Integration/
Follow standards in STACK_GUIDE.md"

# Bug Fix
"Fix {issue} by:
1. Identifying the problem in {file}
2. Following coding standards from section 4
3. Adding test to prevent regression
4. Updating documentation if needed"

# Component Creation
"Create a {component} that:
1. Extends base Component class
2. Includes @ai-props documentation
3. Supports HTMX attributes
4. Uses our CSS design system variables"
```

### AI Code Review Checklist

- [ ] Follows PSR-12 standards
- [ ] Has proper type declarations
- [ ] Includes documentation blocks
- [ ] Handles errors appropriately
- [ ] Has test coverage
- [ ] Uses existing components/patterns
- [ ] Respects API response format
- [ ] Uses CSS variables for styling

-----

## 16. Quick Reference

### Common Commands

```bash
# Development
make up              # Start environment
make down            # Stop environment
make logs            # View logs
make shell           # Enter PHP container
make db              # Enter MySQL

# CSS
make css             # Build CSS
make css-watch       # Watch and rebuild CSS

# Testing
make test            # Run all tests
make analyze         # Run PHPStan
make fix             # Fix code style

# Database
make migrate         # Run migrations
make seed            # Seed database
make fresh           # Fresh database with seeds
```

### HTTP Testing Commands

```bash
# Test JSON API
curl -X GET http://localhost/api/users \
  -H "Accept: application/json"

# Test HTMX
curl -X GET http://localhost/api/users \
  -H "HX-Request: true"

# Test POST
curl -X POST http://localhost/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@example.com"}'
```

### File Templates

#### Action Template

```php
<?php
declare(strict_types=1);

namespace App\Actions\{Resource};

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @ai-endpoint {METHOD} /api/{resource}
 * @ai-auth required|optional|none
 */
final class {Action}Action
{
    public function __construct(
        private readonly {Service}Service $service,
        private readonly {Resource}Responder $responder
    ) {}
    
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $data = $this->service->process($request->getParsedBody());
        return $this->responder->respond($request, $response, $data);
    }
}
```

#### Service Template

```php
<?php
declare(strict_types=1);

namespace App\Services;

/**
 * @ai-purpose Business logic for {resource}
 */
final class {Resource}Service
{
    public function __construct(
        private readonly {Resource}Repository $repository
    ) {}
    
    public function process(array $data): array
    {
        return $this->repository->create($data);
    }
}
```

### Git Workflow

```bash
# Feature branch
git checkout -b feature/user-management

# Commit with conventional commits
git commit -m "feat(users): add search functionality"
git commit -m "fix(api): correct validation error"
git commit -m "docs: update API documentation"
git commit -m "test: add user service tests"
git commit -m "refactor: extract user repository"
git commit -m "style: fix code formatting"
git commit -m "chore: update dependencies"

# Push and create PR
git push origin feature/user-management
```

-----

## Conclusion

This guide provides a complete reference for developing with our stack. Key principles:

1. **Simplicity** - Avoid unnecessary complexity
1. **Explicitness** - Make code intent clear
1. **Consistency** - Follow established patterns
1. **Documentation** - Document for humans and AI
1. **Testing** - Test behavior, not implementation
1. **Performance** - Measure and optimize
1. **Security** - Validate input, escape output

### For AI-Assisted Development

- Use clear, descriptive names
- Include type declarations everywhere
- Follow the established patterns
- Document complex logic
- Test edge cases
- Use CSS variables for all styling
- Keep components simple and reusable

### Remember

If the AI can understand your code, so can humans. Keep it simple, keep it clear.

-----