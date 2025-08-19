**User:**

<task>
'tech-stack.md' (see below for file content) let's create a comprehensive boilerplate implementation using the exact tech stack specifications documented in the referenced file, ensuring all dependencies, configurations, and project structure align precisely with the outlined technology requirements
</task>

<file_content path="tech-stack.md">
   1 | # Complete Stack Documentation & Standards Guide
   2 | 
   3 | ## 📚 The Definitive Guide for AI-Led PHP Development
   4 | 
   5 | ### Version 1.0 | Last Updated: 18-08-2025
   6 | 
   7 | -----
   8 | 
   9 | ## Table of Contents
  10 | 
  11 | 1. [Stack Overview](#1-stack-overview)
  12 | 1. [Architecture](#2-architecture)
  13 | 1. [Development Environment](#3-development-environment)
  14 | 1. [Coding Standards](#4-coding-standards)
  15 | 1. [Documentation Standards](#5-documentation-standards)
  16 | 1. [REST API Standards](#6-rest-api-standards)
  17 | 1. [CSS Architecture & Design System](#7-css-architecture--design-system)
  18 | 1. [Component Library](#8-component-library)
  19 | 1. [HTMX Patterns](#9-htmx-patterns)
  20 | 1. [Testing Strategy](#10-testing-strategy)
  21 | 1. [Database Standards](#11-database-standards)
  22 | 1. [Security Standards](#12-security-standards)
  23 | 1. [Performance Guidelines](#13-performance-guidelines)
  24 | 1. [Deployment Process](#14-deployment-process)
  25 | 1. [AI Development Workflow](#15-ai-development-workflow)
  26 | 1. [Quick Reference](#16-quick-reference)
  27 | 
  28 | -----
  29 | 
  30 | ## 1. Stack Overview
  31 | 
  32 | ### Technology Stack
  33 | 
  34 | ```yaml
  35 | Frontend:
  36 |   HTML5: Semantic markup, progressive enhancement
  37 |   Custom CSS System: Component-based design system
  38 |   Alpine.js 3.14.1: Lightweight reactive framework (15KB)
  39 |   HTMX 2.0.3: HTML-over-the-wire (14KB)
  40 |   Custom Component Library: Reusable UI patterns
  41 | 
  42 | Backend:
  43 |   PHP 8.3: Strict types, JIT compilation enabled
  44 |   Slim Framework 4: Minimal routing framework
  45 |   Architecture: ADR (Action-Domain-Responder) pattern
  46 | 
  47 | API Layer:
  48 |   REST API: Content negotiation (JSON/HTML)
  49 |   OpenAPI 3.1: API documentation
  50 |   Versioning: URL-based (/api/v1/)
  51 | 
  52 | Data Layer:
  53 |   MySQL 8.0: Primary database
  54 |   Redis 7.2: Sessions, caching, queues
  55 |   PDO: Direct database access (no ORM)
  56 | 
  57 | Infrastructure:
  58 |   Docker Compose: Development environment
  59 |   Nginx: Web server, reverse proxy
  60 |   Cloudflare: CDN, DDoS protection
  61 |   GitHub Actions: CI/CD pipeline
  62 | 
  63 | Quality Assurance:
  64 |   PHPStan Level 8: Static analysis
  65 |   PHP CS Fixer: Code style enforcement
  66 |   PHPUnit: Unit and integration testing
  67 |   Playwright: E2E testing
  68 | ```
  69 | 
  70 | ### Architecture Diagram
  71 | 
  72 | ```plaintext
  73 | ┌─────────────────────────────────────────────────────┐
  74 | │                    Browser                          │
  75 | │         HTMX + Alpine.js + Custom CSS               │
  76 | └──────────────────────┬──────────────────────────────┘
  77 |                        │ HTTPS
  78 | ┌──────────────────────▼──────────────────────────────┐
  79 | │                 Cloudflare CDN                      │
  80 | └──────────────────────┬──────────────────────────────┘
  81 |                        │
  82 | ┌──────────────────────▼──────────────────────────────┐
  83 | │                Nginx (Port 80/443)                  │
  84 | │              Static Files + Reverse Proxy           │
  85 | └──────────────────────┬──────────────────────────────┘
  86 |                        │
  87 | ┌──────────────────────▼──────────────────────────────┐
  88 | │              REST API (Content Negotiation)         │
  89 | │          Returns HTML for HTMX, JSON for API        │
  90 | │                                                     │
  91 | │  Slim 4 → Actions → Services → Repositories → PDO   │
  92 | └──────────────────────┬──────────────────────────────┘
  93 |                        │
  94 |          ┌─────────────┴─────────────┐
  95 |          │                           │
  96 |     ┌────▼────┐              ┌──────▼──────┐
  97 |     │  MySQL  │              │    Redis    │
  98 |     └─────────┘              └─────────────┘
  99 | ```
 100 | 
 101 | -----
 102 | 
 103 | ## 2. Architecture
 104 | 
 105 | ### Project Structure
 106 | 
 107 | ```plaintext
 108 | project/
 109 | ├── public/                     # Web root
 110 | │   ├── index.php              # Single entry point
 111 | │   └── assets/                # Static files
 112 | │       ├── css/
 113 | │       │   ├── master.css    # Compiled CSS (do not edit)
 114 | │       │   ├── variables.css # Design tokens
 115 | │       │   ├── base.css      # Base styles
 116 | │       │   ├── components.css # Component styles
 117 | │       │   └── utilities.css  # Utility classes
 118 | │       ├── js/
 119 | │       ├── images/
 120 | │       └── fonts/
 121 | │
 122 | ├── src/                       # Application code
 123 | │   ├── Actions/              # Request handlers (ADR pattern)
 124 | │   │   ├── Api/
 125 | │   │   └── Web/
 126 | │   ├── Services/             # Business logic
 127 | │   ├── Repositories/         # Data access layer
 128 | │   ├── Responders/           # Response formatting
 129 | │   ├── Middleware/           # Request/Response middleware
 130 | │   ├── Validators/           # Input validation
 131 | │   └── Exceptions/           # Custom exceptions
 132 | │
 133 | ├── components/               # UI Component library
 134 | │   ├── UI/                  # Base components
 135 | │   ├── Patterns/            # Complex patterns
 136 | │   └── Layouts/             # Page layouts
 137 | │
 138 | ├── templates/               # HTML templates
 139 | │   ├── layouts/
 140 | │   ├── pages/
 141 | │   └── fragments/
 142 | │
 143 | ├── config/                  # Configuration
 144 | │   ├── app.php
 145 | │   ├── database.php
 146 | │   └── routes.php
 147 | │
 148 | ├── database/                # Database files
 149 | │   ├── migrations/
 150 | │   ├── seeds/
 151 | │   └── schema.sql
 152 | │
 153 | ├── tests/                   # Test files
 154 | │   ├── Unit/
 155 | │   ├── Integration/
 156 | │   └── E2E/
 157 | │
 158 | ├── docs/                    # Documentation
 159 | │   ├── api/
 160 | │   ├── architecture/
 161 | │   └── development/
 162 | │
 163 | ├── docker/                  # Docker configuration
 164 | │   ├── php/
 165 | │   ├── nginx/
 166 | │   └── mysql/
 167 | │
 168 | ├── .github/                 # GitHub configuration
 169 | │   └── workflows/
 170 | │
 171 | ├── vendor/                  # Composer dependencies
 172 | ├── composer.json
 173 | ├── phpstan.neon            # PHPStan configuration
 174 | ├── .php-cs-fixer.php       # Code style configuration
 175 | ├── docker-compose.yml
 176 | ├── Makefile
 177 | └── README.md
 178 | ```
 179 | 
 180 | ### ADR Pattern Implementation
 181 | 
 182 | ```php
 183 | // Action: Handles HTTP request/response
 184 | class ListUsersAction
 185 | {
 186 |     public function __construct(
 187 |         private UserService $service,      // Domain logic
 188 |         private UserResponder $responder   // Response formatting
 189 |     ) {}
 190 |     
 191 |     public function __invoke(Request $request, Response $response): Response
 192 |     {
 193 |         $users = $this->service->getUsers($request->getQueryParams());
 194 |         return $this->responder->respond($request, $response, $users);
 195 |     }
 196 | }
 197 | 
 198 | // Domain: Business logic
 199 | class UserService
 200 | {
 201 |     public function getUsers(array $filters): array
 202 |     {
 203 |         // Business logic here
 204 |         return $this->repository->findAll($filters);
 205 |     }
 206 | }
 207 | 
 208 | // Responder: Formats response
 209 | class UserResponder
 210 | {
 211 |     public function respond(Request $request, Response $response, $data): Response
 212 |     {
 213 |         if ($request->hasHeader('HX-Request')) {
 214 |             return $this->htmlResponse($response, $data);
 215 |         }
 216 |         return $this->jsonResponse($response, $data);
 217 |     }
 218 | }
 219 | ```
 220 | 
 221 | -----
 222 | 
 223 | ## 3. Development Environment
 224 | 
 225 | ### Docker Setup
 226 | 
 227 | ```yaml
 228 | # docker-compose.yml
 229 | version: '3.8'
 230 | 
 231 | services:
 232 |   app:
 233 |     build:
 234 |       context: .
 235 |       dockerfile: docker/php/Dockerfile
 236 |     volumes:
 237 |       - .:/var/www
 238 |       - ./docker/php/php.ini:/usr/local/etc/php/php.ini
 239 |     environment:
 240 |       - APP_ENV=development
 241 |       - DB_HOST=mysql
 242 |       - REDIS_HOST=redis
 243 |     depends_on:
 244 |       - mysql
 245 |       - redis
 246 | 
 247 |   nginx:
 248 |     image: nginx:alpine
 249 |     ports:
 250 |       - "80:80"
 251 |       - "443:443"
 252 |     volumes:
 253 |       - .:/var/www
 254 |       - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
 255 |     depends_on:
 256 |       - app
 257 | 
 258 |   mysql:
 259 |     image: mysql:8.0
 260 |     ports:
 261 |       - "3306:3306"
 262 |     environment:
 263 |       MYSQL_ROOT_PASSWORD: root
 264 |       MYSQL_DATABASE: app
 265 |     volumes:
 266 |       - mysql_data:/var/lib/mysql
 267 |       - ./docker/mysql/init.sql:/docker-entrypoint-initdb.d/init.sql
 268 | 
 269 |   redis:
 270 |     image: redis:7-alpine
 271 |     ports:
 272 |       - "6379:6379"
 273 | 
 274 |   mailhog:
 275 |     image: mailhog/mailhog
 276 |     ports:
 277 |       - "1025:1025"
 278 |       - "8025:8025"
 279 | 
 280 | volumes:
 281 |   mysql_data:
 282 | ```
 283 | 
 284 | ### Makefile Commands
 285 | 
 286 | ```makefile
 287 | # Makefile
 288 | .DEFAULT_GOAL := help
 289 | .PHONY: help up down build test analyze fix css
 290 | 
 291 | help: ## Show this help
 292 | 	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'
 293 | 
 294 | up: ## Start development environment
 295 | 	docker-compose up -d
 296 | 	@echo "Application: http://localhost"
 297 | 	@echo "MailHog: http://localhost:8025"
 298 | 
 299 | down: ## Stop development environment
 300 | 	docker-compose down
 301 | 
 302 | build: ## Build containers and assets
 303 | 	docker-compose build
 304 | 	docker-compose exec app composer install
 305 | 	make css
 306 | 
 307 | css: ## Build CSS files
 308 | 	cat public/assets/css/variables.css \
 309 | 	    public/assets/css/base.css \
 310 | 	    public/assets/css/components.css \
 311 | 	    public/assets/css/utilities.css \
 312 | 	    > public/assets/css/master.css
 313 | 	@echo "CSS built successfully"
 314 | 
 315 | css-watch: ## Watch and rebuild CSS
 316 | 	while true; do \
 317 | 		inotifywait -e modify public/assets/css/*.css; \
 318 | 		make css; \
 319 | 		echo "CSS rebuilt at $$(date)"; \
 320 | 	done
 321 | 
 322 | test: ## Run all tests
 323 | 	docker-compose exec app vendor/bin/phpunit
 324 | 
 325 | analyze: ## Run static analysis
 326 | 	docker-compose exec app vendor/bin/phpstan analyze
 327 | 	docker-compose exec app vendor/bin/php-cs-fixer fix --dry-run
 328 | 
 329 | fix: ## Fix code style
 330 | 	docker-compose exec app vendor/bin/php-cs-fixer fix
 331 | 
 332 | migrate: ## Run database migrations
 333 | 	docker-compose exec app php database/migrate.php
 334 | 
 335 | seed: ## Seed database with test data
 336 | 	docker-compose exec app php database/seed.php
 337 | 
 338 | fresh: ## Fresh database with seeds
 339 | 	docker-compose exec app php database/fresh.php
 340 | 
 341 | logs: ## Show logs
 342 | 	docker-compose logs -f
 343 | 
 344 | shell: ## Enter PHP container
 345 | 	docker-compose exec app sh
 346 | 
 347 | db: ## Enter MySQL
 348 | 	docker-compose exec mysql mysql -uroot -proot app
 349 | 
 350 | redis-cli: ## Enter Redis CLI
 351 | 	docker-compose exec redis redis-cli
 352 | ```
 353 | 
 354 | -----
 355 | 
 356 | ## 4. Coding Standards
 357 | 
 358 | ### PSR Standards
 359 | 
 360 | We follow **PSR-12** for code style and **PSR-4** for autoloading.
 361 | 
 362 | ### PHP Configuration
 363 | 
 364 | ```php
 365 | <?php
 366 | // All PHP files start with
 367 | declare(strict_types=1);
 368 | 
 369 | // Namespace follows PSR-4
 370 | namespace App\Services;
 371 | 
 372 | // Imports are alphabetically ordered
 373 | use App\Exceptions\ValidationException;
 374 | use App\Repositories\UserRepository;
 375 | use Psr\Log\LoggerInterface;
 376 | ```
 377 | 
 378 | ### PHP CS Fixer Configuration
 379 | 
 380 | ```php
 381 | <?php
 382 | // .php-cs-fixer.php
 383 | $finder = PhpCsFixer\Finder::create()
 384 |     ->in(__DIR__ . '/src')
 385 |     ->in(__DIR__ . '/tests')
 386 |     ->in(__DIR__ . '/public');
 387 | 
 388 | return (new PhpCsFixer\Config())
 389 |     ->setRules([
 390 |         '@PSR12' => true,
 391 |         'array_syntax' => ['syntax' => 'short'],
 392 |         'declare_strict_types' => true,
 393 |         'no_unused_imports' => true,
 394 |         'ordered_imports' => ['sort_algorithm' => 'alpha'],
 395 |         'single_quote' => true,
 396 |         'trailing_comma_in_multiline' => true,
 397 |         'return_type_declaration' => true,
 398 |         'void_return' => true,
 399 |         'phpdoc_align' => true,
 400 |         'phpdoc_order' => true,
 401 |         'phpdoc_separation' => true,
 402 |     ])
 403 |     ->setFinder($finder)
 404 |     ->setRiskyAllowed(true);
 405 | ```
 406 | 
 407 | ### Type Declarations
 408 | 
 409 | ```php
 410 | // Always use strict types and full type hints
 411 | class UserService
 412 | {
 413 |     public function __construct(
 414 |         private readonly UserRepository $repository,
 415 |         private readonly LoggerInterface $logger
 416 |     ) {}
 417 |     
 418 |     /**
 419 |      * @param array{name: string, email: string, role?: string} $data
 420 |      * @return array{id: int, name: string, email: string}
 421 |      * @throws ValidationException
 422 |      */
 423 |     public function createUser(array $data): array
 424 |     {
 425 |         // Implementation
 426 |     }
 427 |     
 428 |     /**
 429 |      * @param positive-int $id
 430 |      */
 431 |     public function findById(int $id): ?User
 432 |     {
 433 |         // Implementation
 434 |     }
 435 |     
 436 |     /**
 437 |      * @return array<int, User>
 438 |      */
 439 |     public function findAll(): array
 440 |     {
 441 |         // Implementation
 442 |     }
 443 | }
 444 | ```
 445 | 
 446 | -----
 447 | 
 448 | ## 5. Documentation Standards
 449 | 
 450 | ### File Documentation
 451 | 
 452 | ```php
 453 | <?php
 454 | /**
 455 |  * @file UserController.php
 456 |  * @purpose Handles user-related HTTP requests
 457 |  * @ai-context REST API with content negotiation
 458 |  * @ai-patterns CRUD operations, pagination, filtering
 459 |  */
 460 | 
 461 | declare(strict_types=1);
 462 | 
 463 | namespace App\Controllers;
 464 | ```
 465 | 
 466 | ### Class Documentation
 467 | 
 468 | ```php
 469 | /**
 470 |  * User management service
 471 |  * 
 472 |  * @ai-purpose Business logic for user operations
 473 |  * @ai-dependencies UserRepository, EventDispatcher, MailService
 474 |  * @ai-flow
 475 |  *   1. Validate input
 476 |  *   2. Execute business logic
 477 |  *   3. Trigger events
 478 |  *   4. Return formatted data
 479 |  * 
 480 |  * @example
 481 |  * ```php
 482 |  * $userService = new UserService($repo, $events, $mail);
 483 |  * $user = $userService->createUser(['name' => 'John', 'email' => 'john@example.com']);
 484 |  * ```
 485 |  */
 486 | class UserService
 487 | {
 488 |     // Implementation
 489 | }
 490 | ```
 491 | 
 492 | ### Method Documentation
 493 | 
 494 | ```php
 495 | /**
 496 |  * Create a new user
 497 |  * 
 498 |  * @ai-endpoint POST /api/users
 499 |  * @ai-auth required
 500 |  * @ai-validation UserCreateValidator
 501 |  * 
 502 |  * @param array{
 503 |  *   name: string,
 504 |  *   email: string,
 505 |  *   password: string,
 506 |  *   role?: 'user'|'admin'
 507 |  * } $data User data from request
 508 |  * 
 509 |  * @return array{
 510 |  *   id: int,
 511 |  *   name: string,
 512 |  *   email: string,
 513 |  *   created_at: string
 514 |  * } Created user data
 515 |  * 
 516 |  * @throws ValidationException When validation fails
 517 |  * @throws DuplicateEmailException When email exists
 518 |  * 
 519 |  * @ai-test
 520 |  * ```php
 521 |  * $data = ['name' => 'Test', 'email' => 'test@example.com', 'password' => 'secret123'];
 522 |  * $user = $service->createUser($data);
 523 |  * assert($user['email'] === 'test@example.com');
 524 |  * ```
 525 |  */
 526 | public function createUser(array $data): array
 527 | {
 528 |     // Implementation
 529 | }
 530 | ```
 531 | 
 532 | ### Inline Comments
 533 | 
 534 | ```php
 535 | public function processOrder(int $orderId): void
 536 | {
 537 |     // @ai-step 1: Load order with relationships
 538 |     // Includes: items, customer, shipping address
 539 |     $order = $this->orderRepository->findWithRelations($orderId);
 540 |     
 541 |     // @ai-step 2: Validate order can be processed
 542 |     // Checks: payment status, inventory availability
 543 |     $this->validator->validateProcessable($order);
 544 |     
 545 |     // @ai-step 3: Process payment
 546 |     // External API: Stripe/PayPal integration
 547 |     $payment = $this->paymentService->charge($order);
 548 |     
 549 |     // @ai-step 4: Update inventory
 550 |     // Decrements stock, triggers reorder if needed
 551 |     $this->inventoryService->decrementStock($order->items);
 552 |     
 553 |     // @ai-step 5: Send notifications
 554 |     // Emails: customer confirmation, warehouse picking list
 555 |     $this->notificationService->sendOrderConfirmation($order);
 556 | }
 557 | ```
 558 | 
 559 | -----
 560 | 
 561 | ## 6. REST API Standards
 562 | 
 563 | ### API Response Structure
 564 | 
 565 | ```php
 566 | <?php
 567 | // src/Http/ApiResponse.php
 568 | declare(strict_types=1);
 569 | 
 570 | namespace App\Http;
 571 | 
 572 | class ApiResponse
 573 | {
 574 |     /**
 575 |      * Success response
 576 |      */
 577 |     public static function success(
 578 |         Request $request,
 579 |         Response $response,
 580 |         mixed $data,
 581 |         array $meta = [],
 582 |         int $status = 200
 583 |     ): Response {
 584 |         if ($request->hasHeader('HX-Request')) {
 585 |             return self::htmxResponse($response, $data);
 586 |         }
 587 |         
 588 |         $payload = [
 589 |             'status' => 'success',
 590 |             'data' => $data,
 591 |             'meta' => array_merge([
 592 |                 'timestamp' => time(),
 593 |                 'version' => '1.0'
 594 |             ], $meta)
 595 |         ];
 596 |         
 597 |         return $response
 598 |             ->withJson($payload)
 599 |             ->withStatus($status);
 600 |     }
 601 |     
 602 |     /**
 603 |      * Error response
 604 |      */
 605 |     public static function error(
 606 |         Request $request,
 607 |         Response $response,
 608 |         string $message,
 609 |         array $errors = [],
 610 |         int $status = 400
 611 |     ): Response {
 612 |         if ($request->hasHeader('HX-Request')) {
 613 |             return self::htmxError($response, $message, $errors);
 614 |         }
 615 |         
 616 |         $payload = [
 617 |             'status' => 'error',
 618 |             'message' => $message,
 619 |             'errors' => $errors,
 620 |             'meta' => [
 621 |                 'timestamp' => time(),
 622 |                 'version' => '1.0'
 623 |             ]
 624 |         ];
 625 |         
 626 |         return $response
 627 |             ->withJson($payload)
 628 |             ->withStatus($status);
 629 |     }
 630 |     
 631 |     /**
 632 |      * Paginated response
 633 |      */
 634 |     public static function paginated(
 635 |         Request $request,
 636 |         Response $response,
 637 |         array $items,
 638 |         int $page,
 639 |         int $perPage,
 640 |         int $total
 641 |     ): Response {
 642 |         $lastPage = (int) ceil($total / $perPage);
 643 |         
 644 |         $meta = [
 645 |             'pagination' => [
 646 |                 'page' => $page,
 647 |                 'per_page' => $perPage,
 648 |                 'total' => $total,
 649 |                 'last_page' => $lastPage,
 650 |                 'from' => ($page - 1) * $perPage + 1,
 651 |                 'to' => min($page * $perPage, $total)
 652 |             ]
 653 |         ];
 654 |         
 655 |         return self::success($request, $response, $items, $meta);
 656 |     }
 657 | }
 658 | ```
 659 | 
 660 | ### API Endpoints
 661 | 
 662 | ```php
 663 | // routes/api.php
 664 | $app->group('/api/v1', function ($group) {
 665 |     // Users
 666 |     $group->get('/users', Actions\Users\ListAction::class);
 667 |     $group->get('/users/{id:[0-9]+}', Actions\Users\GetAction::class);
 668 |     $group->post('/users', Actions\Users\CreateAction::class);
 669 |     $group->put('/users/{id:[0-9]+}', Actions\Users\UpdateAction::class);
 670 |     $group->patch('/users/{id:[0-9]+}', Actions\Users\PatchAction::class);
 671 |     $group->delete('/users/{id:[0-9]+}', Actions\Users\DeleteAction::class);
 672 |     
 673 |     // Bulk operations
 674 |     $group->post('/users/bulk', Actions\Users\BulkCreateAction::class);
 675 |     $group->delete('/users/bulk', Actions\Users\BulkDeleteAction::class);
 676 |     
 677 |     // Special endpoints
 678 |     $group->get('/users/search', Actions\Users\SearchAction::class);
 679 |     $group->post('/users/export', Actions\Users\ExportAction::class);
 680 | })->add(new Middleware\ApiAuthentication())
 681 |   ->add(new Middleware\RateLimiting())
 682 |   ->add(new Middleware\Cors());
 683 | ```
 684 | 
 685 | ### HTTP Status Codes
 686 | 
 687 | ```php
 688 | // Standard responses
 689 | 200 OK                  // GET success
 690 | 201 Created            // POST success
 691 | 204 No Content         // DELETE success
 692 | 
 693 | // Client errors
 694 | 400 Bad Request        // Invalid request
 695 | 401 Unauthorized       // No authentication
 696 | 403 Forbidden          // No permission
 697 | 404 Not Found          // Resource not found
 698 | 422 Unprocessable      // Validation error
 699 | 429 Too Many Requests  // Rate limited
 700 | 
 701 | // Server errors
 702 | 500 Internal Error     // Server fault
 703 | 503 Service Unavailable // Maintenance
 704 | ```
 705 | 
 706 | -----
 707 | 
 708 | ## 7. CSS Architecture & Design System
 709 | 
 710 | ### Master CSS Structure
 711 | 
 712 | Our CSS architecture uses a modular system with four source files compiled into one `master.css`:
 713 | 
 714 | 1. **variables.css** - Design tokens and brand configuration
 715 | 1. **base.css** - Reset and foundational styles
 716 | 1. **components.css** - Reusable component styles
 717 | 1. **utilities.css** - Single-purpose utility classes
 718 | 
 719 | ### CSS Variables (Design Tokens)
 720 | 
 721 | ```css
 722 | /* public/assets/css/variables.css */
 723 | /**
 724 |  * Design System Variables
 725 |  * 
 726 |  * @ai-purpose Central design tokens for consistent styling
 727 |  * @ai-usage These variables are used throughout all CSS files
 728 |  * @ai-modification Change brand colors and fonts here to update entire site
 729 |  */
 730 | 
 731 | :root {
 732 |     /* ===========================
 733 |        Brand Palette
 734 |        =========================== */
 735 |     
 736 |     /* Primary Brand Color */
 737 |     --brand-primary: #2563eb;        /* Main brand color */
 738 |     --brand-primary-light: #60a5fa;  /* Lighter variant for hover states */
 739 |     --brand-primary-dark: #1d4ed8;   /* Darker variant for active states */
 740 |     --brand-primary-rgb: 37, 99, 235; /* RGB values for opacity effects */
 741 |     
 742 |     /* Secondary Brand Color */
 743 |     --brand-secondary: #10b981;      /* Secondary/accent color */
 744 |     --brand-secondary-light: #34d399; /* Lighter variant */
 745 |     --brand-secondary-dark: #059669;  /* Darker variant */
 746 |     --brand-secondary-rgb: 16, 185, 129;
 747 |     
 748 |     /* Logo Background Color */
 749 |     /* This ensures the logo always sits on the correct background */
 750 |     --logo-background: #f8fafc;      /* Logo background color */
 751 |     --logo-border: #e2e8f0;          /* Optional border for logo area */
 752 |     
 753 |     /* ===========================
 754 |        Typography
 755 |        =========================== */
 756 |     
 757 |     /* Primary Font - Used for headings and UI elements */
 758 |     --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 
 759 |                     Roboto, Oxygen, Ubuntu, sans-serif;
 760 |     
 761 |     /* Secondary Font - Used for body text and content */
 762 |     --font-secondary: 'Georgia', Cambria, 'Times New Roman', Times, serif;
 763 |     
 764 |     /* Font Weights */
 765 |     --font-weight-light: 300;
 766 |     --font-weight-normal: 400;
 767 |     --font-weight-medium: 500;
 768 |     --font-weight-semibold: 600;
 769 |     --font-weight-bold: 700;
 770 |     
 771 |     /* Font Sizes */
 772 |     --font-size-xs: 0.75rem;    /* 12px */
 773 |     --font-size-sm: 0.875rem;   /* 14px */
 774 |     --font-size-base: 1rem;     /* 16px */
 775 |     --font-size-lg: 1.125rem;   /* 18px */
 776 |     --font-size-xl: 1.25rem;    /* 20px */
 777 |     --font-size-2xl: 1.5rem;    /* 24px */
 778 |     --font-size-3xl: 1.875rem;  /* 30px */
 779 |     --font-size-4xl: 2.25rem;   /* 36px */
 780 |     --font-size-5xl: 3rem;      /* 48px */
 781 |     
 782 |     /* ===========================
 783 |        Neutral Colors
 784 |        =========================== */
 785 |     
 786 |     --color-white: #ffffff;
 787 |     --color-black: #000000;
 788 |     --color-gray-50: #f8fafc;
 789 |     --color-gray-100: #f1f5f9;
 790 |     --color-gray-200: #e2e8f0;
 791 |     --color-gray-300: #cbd5e1;
 792 |     --color-gray-400: #94a3b8;
 793 |     --color-gray-500: #64748b;
 794 |     --color-gray-600: #475569;
 795 |     --color-gray-700: #334155;
 796 |     --color-gray-800: #1e293b;
 797 |     --color-gray-900: #0f172a;
 798 |     
 799 |     /* ===========================
 800 |        Semantic Colors
 801 |        =========================== */
 802 |     
 803 |     --color-success: #10b981;
 804 |     --color-warning: #f59e0b;
 805 |     --color-error: #ef4444;
 806 |     --color-info: #3b82f6;
 807 |     
 808 |     /* ===========================
 809 |        Spacing Scale
 810 |        =========================== */
 811 |     
 812 |     --spacing-0: 0;
 813 |     --spacing-1: 0.25rem;   /* 4px */
 814 |     --spacing-2: 0.5rem;    /* 8px */
 815 |     --spacing-3: 0.75rem;   /* 12px */
 816 |     --spacing-4: 1rem;      /* 16px */
 817 |     --spacing-6: 1.5rem;    /* 24px */
 818 |     --spacing-8: 2rem;      /* 32px */
 819 |     
 820 |     /* ===========================
 821 |        Layout
 822 |        =========================== */
 823 |     
 824 |     --container-max-width: 1280px;
 825 |     --border-radius: 0.25rem;
 826 |     --border-radius-lg: 0.5rem;
 827 |     
 828 |     /* ===========================
 829 |        Shadows & Transitions
 830 |        =========================== */
 831 |     
 832 |     --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
 833 |     --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
 834 |     --transition-fast: 150ms ease-in-out;
 835 |     --transition-base: 250ms ease-in-out;
 836 | }
 837 | ```
 838 | 
 839 | ### Base Styles
 840 | 
 841 | ```css
 842 | /* public/assets/css/base.css */
 843 | /**
 844 |  * Base Styles & Reset
 845 |  * 
 846 |  * @ai-purpose Normalize browser defaults and set foundational styles
 847 |  */
 848 | 
 849 | *, *::before, *::after {
 850 |     box-sizing: border-box;
 851 |     margin: 0;
 852 |     padding: 0;
 853 | }
 854 | 
 855 | html {
 856 |     font-size: 16px;
 857 |     line-height: 1.5;
 858 |     -webkit-text-size-adjust: 100%;
 859 |     -webkit-font-smoothing: antialiased;
 860 | }
 861 | 
 862 | body {
 863 |     font-family: var(--font-secondary);
 864 |     font-size: var(--font-size-base);
 865 |     color: var(--color-gray-900);
 866 |     background-color: var(--color-white);
 867 |     min-height: 100vh;
 868 | }
 869 | 
 870 | h1, h2, h3, h4, h5, h6 {
 871 |     font-family: var(--font-primary);
 872 |     font-weight: var(--font-weight-bold);
 873 |     line-height: 1.2;
 874 |     margin-bottom: var(--spacing-4);
 875 | }
 876 | 
 877 | h1 { font-size: var(--font-size-5xl); }
 878 | h2 { font-size: var(--font-size-4xl); }
 879 | h3 { font-size: var(--font-size-3xl); }
 880 | h4 { font-size: var(--font-size-2xl); }
 881 | h5 { font-size: var(--font-size-xl); }
 882 | h6 { font-size: var(--font-size-lg); }
 883 | 
 884 | p {
 885 |     margin-bottom: var(--spacing-4);
 886 |     line-height: 1.6;
 887 | }
 888 | 
 889 | a {
 890 |     color: var(--brand-primary);
 891 |     text-decoration: none;
 892 |     transition: color var(--transition-fast);
 893 | }
 894 | 
 895 | a:hover {
 896 |     color: var(--brand-primary-dark);
 897 |     text-decoration: underline;
 898 | }
 899 | 
 900 | /* Logo Specific Styling */
 901 | .logo-container {
 902 |     background-color: var(--logo-background);
 903 |     padding: var(--spacing-2);
 904 |     border-radius: var(--border-radius);
 905 |     display: inline-block;
 906 | }
 907 | 
 908 | .logo {
 909 |     display: block;
 910 |     max-width: 200px;
 911 |     height: auto;
 912 | }
 913 | ```
 914 | 
 915 | ### Component Styles
 916 | 
 917 | ```css
 918 | /* public/assets/css/components.css */
 919 | /**
 920 |  * Component Styles
 921 |  * 
 922 |  * @ai-purpose Reusable UI component styles
 923 |  */
 924 | 
 925 | /* Buttons */
 926 | .btn {
 927 |     display: inline-flex;
 928 |     align-items: center;
 929 |     justify-content: center;
 930 |     padding: var(--spacing-3) var(--spacing-6);
 931 |     font-family: var(--font-primary);
 932 |     font-size: var(--font-size-base);
 933 |     font-weight: var(--font-weight-medium);
 934 |     border: 1px solid transparent;
 935 |     border-radius: var(--border-radius);
 936 |     cursor: pointer;
 937 |     transition: all var(--transition-fast);
 938 | }
 939 | 
 940 | .btn-primary {
 941 |     background-color: var(--brand-primary);
 942 |     color: var(--color-white);
 943 |     border-color: var(--brand-primary);
 944 | }
 945 | 
 946 | .btn-primary:hover {
 947 |     background-color: var(--brand-primary-dark);
 948 |     border-color: var(--brand-primary-dark);
 949 | }
 950 | 
 951 | .btn-secondary {
 952 |     background-color: var(--brand-secondary);
 953 |     color: var(--color-white);
 954 |     border-color: var(--brand-secondary);
 955 | }
 956 | 
 957 | /* Cards */
 958 | .card {
 959 |     background-color: var(--color-white);
 960 |     border-radius: var(--border-radius-lg);
 961 |     box-shadow: var(--shadow);
 962 |     overflow: hidden;
 963 | }
 964 | 
 965 | .card-header {
 966 |     padding: var(--spacing-4);
 967 |     border-bottom: 1px solid var(--color-gray-200);
 968 | }
 969 | 
 970 | .card-body {
 971 |     padding: var(--spacing-4);
 972 | }
 973 | 
 974 | /* Forms */
 975 | .form-group {
 976 |     margin-bottom: var(--spacing-4);
 977 | }
 978 | 
 979 | .form-label {
 980 |     display: block;
 981 |     margin-bottom: var(--spacing-2);
 982 |     font-family: var(--font-primary);
 983 |     font-size: var(--font-size-sm);
 984 |     font-weight: var(--font-weight-medium);
 985 | }
 986 | 
 987 | .form-input {
 988 |     width: 100%;
 989 |     padding: var(--spacing-2) var(--spacing-3);
 990 |     font-family: var(--font-primary);
 991 |     border: 1px solid var(--color-gray-300);
 992 |     border-radius: var(--border-radius);
 993 |     transition: border-color var(--transition-fast);
 994 | }
 995 | 
 996 | .form-input:focus {
 997 |     outline: none;
 998 |     border-color: var(--brand-primary);
 999 |     box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.1);
1000 | }
1001 | 
1002 | /* Alerts */
1003 | .alert {
1004 |     padding: var(--spacing-4);
1005 |     border-radius: var(--border-radius);
1006 |     margin-bottom: var(--spacing-4);
1007 | }
1008 | 
1009 | .alert-success {
1010 |     background-color: rgba(16, 185, 129, 0.1);
1011 |     border: 1px solid var(--color-success);
1012 |     color: var(--color-success);
1013 | }
1014 | 
1015 | .alert-error {
1016 |     background-color: rgba(239, 68, 68, 0.1);
1017 |     border: 1px solid var(--color-error);
1018 |     color: var(--color-error);
1019 | }
1020 | 
1021 | /* Loading States */
1022 | .spinner {
1023 |     display: inline-block;
1024 |     width: 1.5rem;
1025 |     height: 1.5rem;
1026 |     border: 2px solid var(--color-gray-300);
1027 |     border-top-color: var(--brand-primary);
1028 |     border-radius: 50%;
1029 |     animation: spin 0.6s linear infinite;
1030 | }
1031 | 
1032 | @keyframes spin {
1033 |     to { transform: rotate(360deg); }
1034 | }
1035 | 
1036 | /* HTMX Indicators */
1037 | .htmx-indicator {
1038 |     display: none;
1039 | }
1040 | 
1041 | .htmx-request .htmx-indicator {
1042 |     display: inline-block;
1043 | }
1044 | ```
1045 | 
1046 | ### Utility Classes
1047 | 
1048 | ```css
1049 | /* public/assets/css/utilities.css */
1050 | /**
1051 |  * Utility Classes
1052 |  * 
1053 |  * @ai-purpose Single-purpose helper classes
1054 |  */
1055 | 
1056 | /* Display */
1057 | .d-none { display: none; }
1058 | .d-block { display: block; }
1059 | .d-flex { display: flex; }
1060 | 
1061 | /* Spacing */
1062 | .m-0 { margin: 0; }
1063 | .m-1 { margin: var(--spacing-1); }
1064 | .m-2 { margin: var(--spacing-2); }
1065 | .m-3 { margin: var(--spacing-3); }
1066 | .m-4 { margin: var(--spacing-4); }
1067 | 
1068 | .p-0 { padding: 0; }
1069 | .p-1 { padding: var(--spacing-1); }
1070 | .p-2 { padding: var(--spacing-2); }
1071 | .p-3 { padding: var(--spacing-3); }
1072 | .p-4 { padding: var(--spacing-4); }
1073 | 
1074 | /* Text */
1075 | .text-center { text-align: center; }
1076 | .text-primary { color: var(--brand-primary); }
1077 | .text-secondary { color: var(--brand-secondary); }
1078 | .text-muted { color: var(--color-gray-600); }
1079 | 
1080 | /* Container */
1081 | .container {
1082 |     max-width: var(--container-max-width);
1083 |     margin: 0 auto;
1084 |     padding: 0 var(--spacing-4);
1085 | }
1086 | ```
1087 | 
1088 | -----
1089 | 
1090 | ## 8. Component Library
1091 | 
1092 | ### Base Component Class
1093 | 
1094 | ```php
1095 | <?php
1096 | // components/Component.php
1097 | declare(strict_types=1);
1098 | 
1099 | namespace Components;
1100 | 
1101 | /**
1102 |  * Base component class
1103 |  * 
1104 |  * @ai-purpose Foundation for all UI components
1105 |  * @ai-usage Extend this class for new components
1106 |  */
1107 | abstract class Component
1108 | {
1109 |     protected array $props;
1110 |     
1111 |     public function __construct(array $props = [])
1112 |     {
1113 |         $this->props = $props;
1114 |     }
1115 |     
1116 |     abstract public function render(): string;
1117 |     
1118 |     protected function e(string $value): string
1119 |     {
1120 |         return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
1121 |     }
1122 | }
1123 | ```
1124 | 
1125 | ### Button Component
1126 | 
1127 | ```php
1128 | <?php
1129 | // components/UI/Button.php
1130 | namespace Components\UI;
1131 | 
1132 | use Components\Component;
1133 | 
1134 | /**
1135 |  * @ai-component Button
1136 |  * @ai-props {
1137 |  *   text: string,
1138 |  *   type?: 'primary'|'secondary'|'ghost',
1139 |  *   htmx?: array
1140 |  * }
1141 |  */
1142 | class Button extends Component
1143 | {
1144 |     public function render(): string
1145 |     {
1146 |         $type = $this->props['type'] ?? 'primary';
1147 |         $text = $this->e($this->props['text']);
1148 |         
1149 |         $classes = ['btn', "btn-{$type}"];
1150 |         $attrs = ['class' => implode(' ', $classes)];
1151 |         
1152 |         // Add HTMX attributes if provided
1153 |         if (isset($this->props['htmx'])) {
1154 |             foreach ($this->props['htmx'] as $key => $value) {
1155 |                 $attrs["hx-{$key}"] = $value;
1156 |             }
1157 |         }
1158 |         
1159 |         $attributes = [];
1160 |         foreach ($attrs as $key => $value) {
1161 |             $attributes[] = sprintf('%s="%s"', $key, $this->e($value));
1162 |         }
1163 |         
1164 |         return sprintf(
1165 |             '<button %s>%s</button>',
1166 |             implode(' ', $attributes),
1167 |             $text
1168 |         );
1169 |     }
1170 | }
1171 | ```
1172 | 
1173 | -----
1174 | 
1175 | ## 9. HTMX Patterns
1176 | 
1177 | ### Live Search
1178 | 
1179 | ```html
1180 | <!-- Live search with debounce -->
1181 | <input type="search"
1182 |        name="q"
1183 |        placeholder="Search..."
1184 |        hx-get="/api/search"
1185 |        hx-trigger="input changed delay:500ms"
1186 |        hx-target="#results"
1187 |        hx-indicator="#spinner">
1188 | 
1189 | <div id="spinner" class="htmx-indicator">Searching...</div>
1190 | <div id="results"></div>
1191 | ```
1192 | 
1193 | ### Infinite Scroll
1194 | 
1195 | ```html
1196 | <!-- Infinite scroll pattern -->
1197 | <div id="content">
1198 |     <!-- Initial content -->
1199 | </div>
1200 | 
1201 | <div hx-get="/api/posts?page=2"
1202 |      hx-trigger="revealed"
1203 |      hx-swap="beforebegin"
1204 |      hx-indicator="#loading">
1205 |     <span id="loading" class="htmx-indicator">Loading more...</span>
1206 | </div>
1207 | ```
1208 | 
1209 | ### Inline Edit
1210 | 
1211 | ```html
1212 | <!-- Click to edit pattern -->
1213 | <div class="editable" 
1214 |      hx-get="/api/edit-form/123"
1215 |      hx-trigger="click"
1216 |      hx-swap="outerHTML">
1217 |     <span>Click to edit</span>
1218 | </div>
1219 | ```
1220 | 
1221 | ### Auto-save Form
1222 | 
1223 | ```html
1224 | <!-- Auto-save form -->
1225 | <form hx-post="/api/draft"
1226 |       hx-trigger="input changed delay:2s"
1227 |       hx-swap="none"
1228 |       hx-indicator="#saving">
1229 |     <textarea name="content"></textarea>
1230 |     <span id="saving" class="htmx-indicator">Saving...</span>
1231 | </form>
1232 | ```
1233 | 
1234 | ### Modal Dialog
1235 | 
1236 | ```html
1237 | <!-- Trigger modal -->
1238 | <button hx-get="/api/modal/create"
1239 |         hx-target="#modal"
1240 |         hx-swap="innerHTML">
1241 |     Open Modal
1242 | </button>
1243 | 
1244 | <div id="modal"></div>
1245 | ```
1246 | 
1247 | -----
1248 | 
1249 | ## 10. Testing Strategy
1250 | 
1251 | ### PHPStan Configuration
1252 | 
1253 | ```yaml
1254 | # phpstan.neon
1255 | includes:
1256 |     - vendor/phpstan/phpstan-strict-rules/rules.neon
1257 | 
1258 | parameters:
1259 |     level: 8
1260 |     paths:
1261 |         - src
1262 |         - tests
1263 |     
1264 |     checkMissingIterableValueType: true
1265 |     checkGenericClassInNonGenericObjectType: true
1266 |     treatPhpDocTypesAsCertain: true
1267 | ```
1268 | 
1269 | ### Unit Testing
1270 | 
1271 | ```php
1272 | <?php
1273 | // tests/Unit/UserServiceTest.php
1274 | declare(strict_types=1);
1275 | 
1276 | namespace Tests\Unit;
1277 | 
1278 | use App\Services\UserService;
1279 | use PHPUnit\Framework\TestCase;
1280 | 
1281 | class UserServiceTest extends TestCase
1282 | {
1283 |     private UserService $service;
1284 |     
1285 |     protected function setUp(): void
1286 |     {
1287 |         $this->repository = $this->createMock(UserRepository::class);
1288 |         $this->service = new UserService($this->repository);
1289 |     }
1290 |     
1291 |     /**
1292 |      * @test
1293 |      */
1294 |     public function it_creates_user_with_valid_data(): void
1295 |     {
1296 |         // Arrange
1297 |         $data = ['name' => 'John', 'email' => 'john@example.com'];
1298 |         
1299 |         $this->repository
1300 |             ->expects($this->once())
1301 |             ->method('create')
1302 |             ->with($data)
1303 |             ->willReturn(['id' => 1] + $data);
1304 |         
1305 |         // Act
1306 |         $user = $this->service->createUser($data);
1307 |         
1308 |         // Assert
1309 |         $this->assertArrayHasKey('id', $user);
1310 |         $this->assertEquals($data['email'], $user['email']);
1311 |     }
1312 | }
1313 | ```
1314 | 
1315 | ### Integration Testing
1316 | 
1317 | ```php
1318 | <?php
1319 | // tests/Integration/ApiTest.php
1320 | declare(strict_types=1);
1321 | 
1322 | namespace Tests\Integration;
1323 | 
1324 | use Tests\TestCase;
1325 | 
1326 | class ApiTest extends TestCase
1327 | {
1328 |     /**
1329 |      * @test
1330 |      */
1331 |     public function it_returns_json_for_api_requests(): void
1332 |     {
1333 |         $response = $this->get('/api/users', [
1334 |             'Accept' => 'application/json'
1335 |         ]);
1336 |         
1337 |         $response->assertStatus(200);
1338 |         $response->assertHeader('Content-Type', 'application/json');
1339 |         $response->assertJsonStructure([
1340 |             'status',
1341 |             'data' => [
1342 |                 '*' => ['id', 'name', 'email']
1343 |             ],
1344 |             'meta'
1345 |         ]);
1346 |     }
1347 |     
1348 |     /**
1349 |      * @test
1350 |      */
1351 |     public function it_returns_html_for_htmx_requests(): void
1352 |     {
1353 |         $response = $this->get('/api/users', [
1354 |             'HX-Request' => 'true'
1355 |         ]);
1356 |         
1357 |         $response->assertStatus(200);
1358 |         $response->assertHeader('Content-Type', 'text/html');
1359 |         $response->assertSee('user-card');
1360 |     }
1361 | }
1362 | ```
1363 | 
1364 | -----
1365 | 
1366 | ## 11. Database Standards
1367 | 
1368 | ### Naming Conventions
1369 | 
1370 | ```sql
1371 | -- Tables: plural, snake_case
1372 | CREATE TABLE users (
1373 |     -- Primary keys: always 'id'
1374 |     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
1375 |     
1376 |     -- Columns: snake_case
1377 |     first_name VARCHAR(100) NOT NULL,
1378 |     email VARCHAR(255) NOT NULL,
1379 |     
1380 |     -- Timestamps: _at suffix
1381 |     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
1382 |     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
1383 |     
1384 |     -- Indexes: idx_table_column(s)
1385 |     INDEX idx_users_email (email),
1386 |     
1387 |     -- Unique constraints: uk_table_column(s)
1388 |     CONSTRAINT uk_users_email UNIQUE (email)
1389 | ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
1390 | 
1391 | -- Foreign keys: fk_table_column_reference
1392 | ALTER TABLE posts
1393 | ADD CONSTRAINT fk_posts_user_id_users
1394 |     FOREIGN KEY (user_id) REFERENCES users(id)
1395 |     ON DELETE CASCADE;
1396 | ```
1397 | 
1398 | ### Repository Pattern
1399 | 
1400 | ```php
1401 | <?php
1402 | // src/Repositories/UserRepository.php
1403 | declare(strict_types=1);
1404 | 
1405 | namespace App\Repositories;
1406 | 
1407 | use PDO;
1408 | 
1409 | class UserRepository
1410 | {
1411 |     public function __construct(
1412 |         private readonly PDO $db
1413 |     ) {}
1414 |     
1415 |     /**
1416 |      * @return array<int, array{id: int, name: string, email: string}>
1417 |      */
1418 |     public function findAll(array $filters = []): array
1419 |     {
1420 |         $sql = 'SELECT id, name, email FROM users WHERE 1=1';
1421 |         $params = [];
1422 |         
1423 |         if (!empty($filters['search'])) {
1424 |             $sql .= ' AND (name LIKE :search OR email LIKE :search)';
1425 |             $params['search'] = '%' . $filters['search'] . '%';
1426 |         }
1427 |         
1428 |         $sql .= ' ORDER BY created_at DESC LIMIT 20';
1429 |         
1430 |         $stmt = $this->db->prepare($sql);
1431 |         $stmt->execute($params);
1432 |         
1433 |         return $stmt->fetchAll(PDO::FETCH_ASSOC);
1434 |     }
1435 | }
1436 | ```
1437 | 
1438 | -----
1439 | 
1440 | ## 12. Security Standards
1441 | 
1442 | ### Input Validation
1443 | 
1444 | ```php
1445 | <?php
1446 | // src/Validators/UserValidator.php
1447 | declare(strict_types=1);
1448 | 
1449 | namespace App\Validators;
1450 | 
1451 | class UserValidator
1452 | {
1453 |     public function validateCreate(array $data): array
1454 |     {
1455 |         $errors = [];
1456 |         
1457 |         // Name validation
1458 |         if (empty($data['name'])) {
1459 |             $errors['name'] = 'Name is required';
1460 |         } elseif (strlen($data['name']) < 2) {
1461 |             $errors['name'] = 'Name must be at least 2 characters';
1462 |         }
1463 |         
1464 |         // Email validation
1465 |         if (empty($data['email'])) {
1466 |             $errors['email'] = 'Email is required';
1467 |         } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
1468 |             $errors['email'] = 'Invalid email format';
1469 |         }
1470 |         
1471 |         if (!empty($errors)) {
1472 |             throw new ValidationException($errors);
1473 |         }
1474 |         
1475 |         return $data;
1476 |     }
1477 | }
1478 | ```
1479 | 
1480 | ### Authentication Middleware
1481 | 
1482 | ```php
1483 | <?php
1484 | // src/Middleware/AuthMiddleware.php
1485 | declare(strict_types=1);
1486 | 
1487 | namespace App\Middleware;
1488 | 
1489 | class AuthMiddleware
1490 | {
1491 |     public function __invoke($request, $handler)
1492 |     {
1493 |         $token = $this->extractToken($request);
1494 |         
1495 |         if (!$token || !$this->validateToken($token)) {
1496 |             return ApiResponse::error(
1497 |                 $request,
1498 |                 new Response(),
1499 |                 'Unauthorized',
1500 |                 [],
1501 |                 401
1502 |             );
1503 |         }
1504 |         
1505 |         $request = $request->withAttribute('user_id', $this->getUserId($token));
1506 |         
1507 |         return $handler->handle($request);
1508 |     }
1509 |     
1510 |     private function extractToken($request): ?string
1511 |     {
1512 |         $header = $request->getHeaderLine('Authorization');
1513 |         
1514 |         if (preg_match('/Bearer\s+(.+)/', $header, $matches)) {
1515 |             return $matches[1];
1516 |         }
1517 |         
1518 |         return null;
1519 |     }
1520 | }
1521 | ```
1522 | 
1523 | -----
1524 | 
1525 | ## 13. Performance Guidelines
1526 | 
1527 | ### Caching Strategy
1528 | 
1529 | ```php
1530 | <?php
1531 | // src/Services/CacheService.php
1532 | declare(strict_types=1);
1533 | 
1534 | namespace App\Services;
1535 | 
1536 | use Redis;
1537 | 
1538 | class CacheService
1539 | {
1540 |     private Redis $redis;
1541 |     
1542 |     public function remember(string $key, int $ttl, callable $callback): mixed
1543 |     {
1544 |         $cached = $this->redis->get($key);
1545 |         
1546 |         if ($cached !== false) {
1547 |             return unserialize($cached);
1548 |         }
1549 |         
1550 |         $value = $callback();
1551 |         $this->redis->setex($key, $ttl, serialize($value));
1552 |         
1553 |         return $value;
1554 |     }
1555 |     
1556 |     public function forget(string $key): void
1557 |     {
1558 |         $this->redis->del($key);
1559 |     }
1560 | }
1561 | ```
1562 | 
1563 | ### Query Optimization
1564 | 
1565 | ```php
1566 | // Use prepared statements
1567 | $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
1568 | $stmt->execute(['email' => $email]);
1569 | 
1570 | // Use indexes
1571 | // CREATE INDEX idx_users_email ON users(email);
1572 | 
1573 | // Limit results
1574 | $stmt = $pdo->prepare('SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit');
1575 | $stmt->bindValue('limit', 20, PDO::PARAM_INT);
1576 | 
1577 | // Use joins instead of N+1
1578 | $sql = '
1579 |     SELECT u.*, COUNT(p.id) as post_count
1580 |     FROM users u
1581 |     LEFT JOIN posts p ON u.id = p.user_id
1582 |     GROUP BY u.id
1583 | ';
1584 | ```
1585 | 
1586 | -----
1587 | 
1588 | ## 14. Deployment Process
1589 | 
1590 | ### Environment Configuration
1591 | 
1592 | ```bash
1593 | # .env.production
1594 | APP_ENV=production
1595 | APP_DEBUG=false
1596 | APP_URL=https://example.com
1597 | 
1598 | DB_CONNECTION=mysql
1599 | DB_HOST=localhost
1600 | DB_PORT=3306
1601 | DB_DATABASE=app
1602 | DB_USERNAME=user
1603 | DB_PASSWORD=secret
1604 | 
1605 | REDIS_HOST=localhost
1606 | REDIS_PORT=6379
1607 | 
1608 | CACHE_DRIVER=redis
1609 | SESSION_DRIVER=redis
1610 | ```
1611 | 
1612 | ### GitHub Actions CI/CD
1613 | 
1614 | ```yaml
1615 | # .github/workflows/deploy.yml
1616 | name: Deploy
1617 | 
1618 | on:
1619 |   push:
1620 |     branches: [main]
1621 | 
1622 | jobs:
1623 |   test:
1624 |     runs-on: ubuntu-latest
1625 |     steps:
1626 |       - uses: actions/checkout@v2
1627 |       
1628 |       - name: Setup PHP
1629 |         uses: shivammathur/setup-php@v2
1630 |         with:
1631 |           php-version: '8.3'
1632 |           
1633 |       - name: Install dependencies
1634 |         run: composer install --no-dev --optimize-autoloader
1635 |         
1636 |       - name: Run tests
1637 |         run: vendor/bin/phpunit
1638 |         
1639 |       - name: Run static analysis
1640 |         run: vendor/bin/phpstan analyze
1641 |         
1642 |   deploy:
1643 |     needs: test
1644 |     runs-on: ubuntu-latest
1645 |     steps:
1646 |       - name: Deploy to server
1647 |         uses: appleboy/ssh-action@master
1648 |         with:
1649 |           host: ${{ secrets.HOST }}
1650 |           username: ${{ secrets.USERNAME }}
1651 |           key: ${{ secrets.SSH_KEY }}
1652 |           script: |
1653 |             cd /var/www/app
1654 |             git pull origin main
1655 |             composer install --no-dev --optimize-autoloader
1656 |             php database/migrate.php
1657 |             make css
1658 | ```
1659 | 
1660 | ### Production Checklist
1661 | 
1662 | - [ ] Environment variables configured
1663 | - [ ] Debug mode disabled
1664 | - [ ] HTTPS enabled
1665 | - [ ] Database backed up
1666 | - [ ] Redis configured
1667 | - [ ] Error logging configured
1668 | - [ ] Monitoring setup
1669 | - [ ] Rate limiting enabled
1670 | - [ ] CORS configured
1671 | - [ ] CSS compiled and minified
1672 | - [ ] OPcache enabled
1673 | 
1674 | -----
1675 | 
1676 | ## 15. AI Development Workflow
1677 | 
1678 | ### Project Setup for AI
1679 | 
1680 | ```bash
1681 | # 1. Initialize project
1682 | make init
1683 | 
1684 | # 2. Start development environment
1685 | make up
1686 | 
1687 | # 3. Seed test data
1688 | make seed
1689 | 
1690 | # 4. Generate AI context
1691 | make ai-context > AI_CONTEXT.md
1692 | ```
1693 | 
1694 | ### AI Prompt Templates
1695 | 
1696 | ```markdown
1697 | # Feature Development
1698 | "Create a {feature} feature with:
1699 | 1. Action class following ADR pattern in src/Actions/
1700 | 2. Service class with business logic in src/Services/
1701 | 3. Repository with PHPStan types in src/Repositories/
1702 | 4. HTMX frontend using patterns from components/
1703 | 5. Integration tests in tests/Integration/
1704 | Follow standards in STACK_GUIDE.md"
1705 | 
1706 | # Bug Fix
1707 | "Fix {issue} by:
1708 | 1. Identifying the problem in {file}
1709 | 2. Following coding standards from section 4
1710 | 3. Adding test to prevent regression
1711 | 4. Updating documentation if needed"
1712 | 
1713 | # Component Creation
1714 | "Create a {component} that:
1715 | 1. Extends base Component class
1716 | 2. Includes @ai-props documentation
1717 | 3. Supports HTMX attributes
1718 | 4. Uses our CSS design system variables"
1719 | ```
1720 | 
1721 | ### AI Code Review Checklist
1722 | 
1723 | - [ ] Follows PSR-12 standards
1724 | - [ ] Has proper type declarations
1725 | - [ ] Includes documentation blocks
1726 | - [ ] Handles errors appropriately
1727 | - [ ] Has test coverage
1728 | - [ ] Uses existing components/patterns
1729 | - [ ] Respects API response format
1730 | - [ ] Uses CSS variables for styling
1731 | 
1732 | -----
1733 | 
1734 | ## 16. Quick Reference
1735 | 
1736 | ### Common Commands
1737 | 
1738 | ```bash
1739 | # Development
1740 | make up              # Start environment
1741 | make down            # Stop environment
1742 | make logs            # View logs
1743 | make shell           # Enter PHP container
1744 | make db              # Enter MySQL
1745 | 
1746 | # CSS
1747 | make css             # Build CSS
1748 | make css-watch       # Watch and rebuild CSS
1749 | 
1750 | # Testing
1751 | make test            # Run all tests
1752 | make analyze         # Run PHPStan
1753 | make fix             # Fix code style
1754 | 
1755 | # Database
1756 | make migrate         # Run migrations
1757 | make seed            # Seed database
1758 | make fresh           # Fresh database with seeds
1759 | ```
1760 | 
1761 | ### HTTP Testing Commands
1762 | 
1763 | ```bash
1764 | # Test JSON API
1765 | curl -X GET http://localhost/api/users \
1766 |   -H "Accept: application/json"
1767 | 
1768 | # Test HTMX
1769 | curl -X GET http://localhost/api/users \
1770 |   -H "HX-Request: true"
1771 | 
1772 | # Test POST
1773 | curl -X POST http://localhost/api/users \
1774 |   -H "Content-Type: application/json" \
1775 |   -d '{"name":"John","email":"john@example.com"}'
1776 | ```
1777 | 
1778 | ### File Templates
1779 | 
1780 | #### Action Template
1781 | 
1782 | ```php
1783 | <?php
1784 | declare(strict_types=1);
1785 | 
1786 | namespace App\Actions\{Resource};
1787 | 
1788 | use Psr\Http\Message\ResponseInterface as Response;
1789 | use Psr\Http\Message\ServerRequestInterface as Request;
1790 | 
1791 | /**
1792 |  * @ai-endpoint {METHOD} /api/{resource}
1793 |  * @ai-auth required|optional|none
1794 |  */
1795 | final class {Action}Action
1796 | {
1797 |     public function __construct(
1798 |         private readonly {Service}Service $service,
1799 |         private readonly {Resource}Responder $responder
1800 |     ) {}
1801 |     
1802 |     public function __invoke(Request $request, Response $response, array $args): Response
1803 |     {
1804 |         $data = $this->service->process($request->getParsedBody());
1805 |         return $this->responder->respond($request, $response, $data);
1806 |     }
1807 | }
1808 | ```
1809 | 
1810 | #### Service Template
1811 | 
1812 | ```php
1813 | <?php
1814 | declare(strict_types=1);
1815 | 
1816 | namespace App\Services;
1817 | 
1818 | /**
1819 |  * @ai-purpose Business logic for {resource}
1820 |  */
1821 | final class {Resource}Service
1822 | {
1823 |     public function __construct(
1824 |         private readonly {Resource}Repository $repository
1825 |     ) {}
1826 |     
1827 |     public function process(array $data): array
1828 |     {
1829 |         return $this->repository->create($data);
1830 |     }
1831 | }
1832 | ```
1833 | 
1834 | ### Git Workflow
1835 | 
1836 | ```bash
1837 | # Feature branch
1838 | git checkout -b feature/user-management
1839 | 
1840 | # Commit with conventional commits
1841 | git commit -m "feat(users): add search functionality"
1842 | git commit -m "fix(api): correct validation error"
1843 | git commit -m "docs: update API documentation"
1844 | git commit -m "test: add user service tests"
1845 | git commit -m "refactor: extract user repository"
1846 | git commit -m "style: fix code formatting"
1847 | git commit -m "chore: update dependencies"
1848 | 
1849 | # Push and create PR
1850 | git push origin feature/user-management
1851 | ```
1852 | 
1853 | -----
1854 | 
1855 | ## Conclusion
1856 | 
1857 | This guide provides a complete reference for developing with our stack. Key principles:
1858 | 
1859 | 1. **Simplicity** - Avoid unnecessary complexity
1860 | 1. **Explicitness** - Make code intent clear
1861 | 1. **Consistency** - Follow established patterns
1862 | 1. **Documentation** - Document for humans and AI
1863 | 1. **Testing** - Test behavior, not implementation
1864 | 1. **Performance** - Measure and optimize
1865 | 1. **Security** - Validate input, escape output
1866 | 
1867 | ### For AI-Assisted Development
1868 | 
1869 | - Use clear, descriptive names
1870 | - Include type declarations everywhere
1871 | - Follow the established patterns
1872 | - Document complex logic
1873 | - Test edge cases
1874 | - Use CSS variables for all styling
1875 | - Keep components simple and reusable
1876 | 
1877 | ### Remember
1878 | 
1879 | If the AI can understand your code, so can humans. Keep it simple, keep it clear.
1880 | 
1881 | -----

</file_content>
<environment_details>
# VSCode Visible Files
tech-stack.md

# VSCode Open Tabs
public/index.php,tech-stack.md,compose.test.yaml,docker/php-fpm/Dockerfile.test,docker/php-fpm/php.ini,docker/nginx/common/shared-app-rules.conf,.gitignore,backend/public/.htaccess,backend/public/index.php,frontend/app.js,docker/nginx/Dockerfile.test,docker/nginx/nginx.conf,.env.backend.test

# Current Time
Current time in ISO 8601 UTC format: 2025-08-18T13:15:58.591Z
User time zone: Asia/Shanghai, UTC+8:00

# Current Cost
$0.00

# Current Mode
<slug>code</slug>
<name>Code</name>
<model>qwen/qwen3-coder</model>


# Current Workspace Directory (/home/john/training/sugar_clone) Files
.gitignore
tech-stack.md
.docs/
You have not created a todo list yet. Create one with `update_todo_list` if your task is complicated or involves multiple steps.
</environment_details>

---

**Assistant:**

<thinking>
I need to create a comprehensive boilerplate implementation using the exact tech stack specifications documented in the provided `tech-stack.md` file. Let me analyze the requirements:

1. The tech stack includes:
   - Frontend: HTML5, Custom CSS System, Alpine.js 3.14.1, HTMX 2.0.3, Custom Component Library
   - Backend: PHP 8.3, Slim Framework 4, ADR pattern
   - API Layer: REST API with content negotiation, OpenAPI 3.1
   - Data Layer: MySQL 8.0, Redis 7.2, PDO
   - Infrastructure: Docker Compose, Nginx, Cloudflare
   - Quality Assurance: PHPStan Level 8, PHP CS Fixer, PHPUnit, Playwright

2. The project structure is well-defined in the documentation, including directories like:
   - public/ for web root
   - src/ for application code
   - components/ for UI components
   - templates/ for HTML templates
   - config/ for configuration
   - database/ for database files
   - tests/ for test files
   - docs/ for documentation
   - docker/ for Docker configuration

3. I need to implement the ADR pattern with Actions, Services, and Responders.

4. I need to set up Docker configuration files for the development environment.

5. I need to create configuration files for PHPStan, PHP CS Fixer, and other tools.

6. I need to implement the CSS architecture with variables, base styles, components, and utilities.

Let me start by creating the project structure and implementing the core files according to the specifications.
</thinking>

<update_todo_list>
<todos>
[ ] Create project structure directories
[ ] Implement Docker configuration files
[ ] Create PHP configuration files
[ ] Implement ADR pattern base classes
[ ] Create CSS architecture files
[ ] Set up configuration files for PHPStan and PHP CS Fixer
[ ] Implement basic routing and entry point
[ ] Create component library base classes
[ ] Set up Makefile with required commands
[ ] Create README.md with project documentation
</todos>
</update_todo_list>

---

**User:**

[update_todo_list] Result:
Todo list updated successfully.
<environment_details>
# VSCode Visible Files
tech-stack.md

# VSCode Open Tabs
public/index.php,tech-stack.md,compose.test.yaml,docker/php-fpm/Dockerfile.test,docker/php-fpm/php.ini,docker/nginx/common/shared-app-rules.conf,.gitignore,backend/public/.htaccess,backend/public/index.php,frontend/app.js,docker/nginx/Dockerfile.test,docker/nginx/nginx.conf,.env.backend.test

# Current Time
Current time in ISO 8601 UTC format: 2025-08-18T13:16:15.008Z
User time zone: Asia/Shanghai, UTC+8:00

# Current Cost
$0.01

# Current Mode
<slug>code</slug>
<name>Code</name>
<model>qwen/qwen3-coder</model>
====

REMINDERS

Below is your current list of reminders for this task. Keep them updated as you progress.

| # | Content | Status |
|---|---------|--------|
| 1 | Create project structure directories | Pending |
| 2 | Implement Docker configuration files | Pending |
| 3 | Create PHP configuration files | Pending |
| 4 | Implement ADR pattern base classes | Pending |
| 5 | Create CSS architecture files | Pending |
| 6 | Set up configuration files for PHPStan and PHP CS Fixer | Pending |
| 7 | Implement basic routing and entry point | Pending |
| 8 | Create component library base classes | Pending |
| 9 | Set up Makefile with required commands | Pending |
| 10 | Create README.md with project documentation | Pending |


IMPORTANT: When task status changes, remember to call the `update_todo_list` tool to update your progress.

</environment_details>

---

**Assistant:**

I'll start by creating the project structure directories as outlined in the tech stack documentation.

<write_to_file>
<path>public/index.php</path>
<content>
<?php
/**
 * @file index.php
 * @purpose Single entry point for the application
 * @ai-context Main entry point for all requests
 */

declare(strict_types=1);

// Include Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Bootstrap the application
$app = require __DIR__ . '/../config/app.php';

// Run the application
$app->run();
</content>
<line_count>22</line_count>
</write_to_file>

---

**User:**

[write_to_file for 'public/index.php'] Result:
<file_write_result>
<path>public/index.php</path>
<operation>created</operation>
<notice>
<i>You do not need to re-read the file, as you have seen all changes</i>
<i>Proceed with the task using these changes as the new baseline.</i>
</notice>
</file_write_result>

<environment_details>
# VSCode Visible Files
public/index.php

# VSCode Open Tabs
tech-stack.md,public/index.php,compose.test.yaml,docker/php-fpm/Dockerfile.test,docker/php-fpm/php.ini,docker/nginx/common/shared-app-rules.conf,.gitignore,backend/public/.htaccess,backend/public/index.php,frontend/app.js,docker/nginx/Dockerfile.test,docker/nginx/nginx.conf,.env.backend.test

# Current Time
Current time in ISO 8601 UTC format: 2025-08-18T13:16:25.366Z
User time zone: Asia/Shanghai, UTC+8:00

# Current Cost
$0.02

# Current Mode
<slug>code</slug>
<name>Code</name>
<model>qwen/qwen3-coder</model>
====

REMINDERS

Below is your current list of reminders for this task. Keep them updated as you progress.

| # | Content | Status |
|---|---------|--------|
| 1 | Create project structure directories | Pending |
| 2 | Implement Docker configuration files | Pending |
| 3 | Create PHP configuration files | Pending |
| 4 | Implement ADR pattern base classes | Pending |
| 5 | Create CSS architecture files | Pending |
| 6 | Set up configuration files for PHPStan and PHP CS Fixer | Pending |
| 7 | Implement basic routing and entry point | Pending |
| 8 | Create component library base classes | Pending |
| 9 | Set up Makefile with required commands | Pending |
| 10 | Create README.md with project documentation | Pending |


IMPORTANT: When task status changes, remember to call the `update_todo_list` tool to update your progress.

</environment_details>

---

**Assistant:**

Now I'll create the main configuration file for the application.

<write_to_file>
<path>config/app.php</path>
<content>
<?php
/**
 * @file app.php
 * @purpose Application bootstrap and configuration
 * @ai-context Main application configuration file
 */

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Slim\Middleware\RoutingMiddleware;

// Create app
$app = AppFactory::create();

// Register middleware
$app->addRoutingMiddleware();
$app->add(new ErrorMiddleware(
    $app->getCallableResolver(),
    $app->getResponseFactory(),
    $_ENV['APP_DEBUG'] ?? false,
    true,
    true
));

// Register routes
(require __DIR__ . '/routes.php')($app);

return $app;
</content>
<line_count>28</line_count>
</write_to_file>

---

**User:**

[write_to_file for 'config/app.php'] Result:
<file_write_result>
<path>config/app.php</path>
<operation>created</operation>
<notice>
<i>You do not need to re-read the file, as you have seen all changes</i>
<i>Proceed with the task using these changes as the new baseline.</i>
</notice>
</file_write_result>

<environment_details>
# VSCode Visible Files
config/app.php

# VSCode Open Tabs
tech-stack.md,public/index.php,config/app.php,compose.test.yaml,docker/php-fpm/Dockerfile.test,docker/php-fpm/php.ini,docker/nginx/common/shared-app-rules.conf,.gitignore,backend/public/.htaccess,backend/public/index.php,frontend/app.js,docker/nginx/Dockerfile.test,docker/nginx/nginx.conf,.env.backend.test

# Current Time
Current time in ISO 8601 UTC format: 2025-08-18T13:16:35.251Z
User time zone: Asia/Shanghai, UTC+8:00

# Current Cost
$0.03

# Current Mode
<slug>code</slug>
<name>Code</name>
<model>qwen/qwen3-coder</model>
====

REMINDERS

Below is your current list of reminders for this task. Keep them updated as you progress.

| # | Content | Status |
|---|---------|--------|
| 1 | Create project structure directories | Pending |
| 2 | Implement Docker configuration files | Pending |
| 3 | Create PHP configuration files | Pending |
| 4 | Implement ADR pattern base classes | Pending |
| 5 | Create CSS architecture files | Pending |
| 6 | Set up configuration files for PHPStan and PHP CS Fixer | Pending |
| 7 | Implement basic routing and entry point | Pending |
| 8 | Create component library base classes | Pending |
| 9 | Set up Makefile with required commands | Pending |
| 10 | Create README.md with project documentation | Pending |


IMPORTANT: When task status changes, remember to call the `update_todo_list` tool to update your progress.

</environment_details>

---

**Assistant:**

Now I'll create the routes configuration file.

<write_to_file>
<path>config/routes.php</path>
<content>
<?php
/**
 * @file routes.php
 * @purpose Route definitions
 * @ai-context Main routing configuration
 */

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    // Web routes
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello World!');
        return $response;
    });

    // API routes
    $app->group('/api/v1', function ($group) {
        // Example API route
        $group->get('/status', function (Request $request, Response $response) {
            $payload = json_encode(['status' => 'ok', 'timestamp' => time()]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        });
    });
};
</content>
<line_count>32</line_count>
</write_to_file>

---

**User:**



New instructions for task continuation:
<user_message>
can you see the projects in archon?
</user_message>
<environment_details>
# VSCode Visible Files
README.md

# VSCode Open Tabs
docker/php/Dockerfile,tech-stack.md,public/index.php,README.md,config/app.php,config/routes.php,compose.test.yaml,docker/php-fpm/Dockerfile.test,docker/php-fpm/php.ini,docker/nginx/common/shared-app-rules.conf,.gitignore,backend/public/.htaccess,backend/public/index.php,frontend/app.js,docker/nginx/Dockerfile.test,docker/nginx/nginx.conf,.env.backend.test

# Current Time
Current time in ISO 8601 UTC format: 2025-08-18T14:26:52.056Z
User time zone: Asia/Shanghai, UTC+8:00

# Current Cost
$0.04

# Current Mode
<slug>code</slug>
<name>Code</name>
<model>qwen/qwen3-coder</model>


# Current Workspace Directory (/home/john/training/sugar_clone) Files
.env.backend.test
.gitignore
.php-cs-fixer.php
composer.json
composer.lock
docker-compose.yml
Makefile
phpstan.neon
phpunit.xml
README.md
tech-stack.md
.docs/
components/
components/Component.php
components/UI/
components/UI/Button.php
config/
config/app.php
config/routes.php
docker/
docker/mysql/
docker/mysql/init.sql
docker/nginx/
docker/nginx/default.conf
docker/php/
docker/php/Dockerfile
docker/php/php.ini
public/
public/index.php
public/assets/
public/assets/css/
public/assets/css/base.css
public/assets/css/components.css
public/assets/css/utilities.css
public/assets/css/variables.css
public/assets/js/
public/assets/js/app.js
src/
src/Actions/
src/Actions/Api/
src/Actions/Api/Users/
src/Actions/Api/Users/ListAction.php
src/Actions/Web/
src/Actions/Web/HomeAction.php
src/Exceptions/
src/Exceptions/DuplicateEmailException.php
src/Exceptions/ValidationException.php
src/Http/
src/Http/ApiResponse.php
src/Middleware/
src/Middleware/ApiAuthenticationMiddleware.php
src/Middleware/CorsMiddleware.php
src/Middleware/RateLimitingMiddleware.php
src/Repositories/
src/Repositories/UserRepository.php
src/Services/
src/Services/UserService.php
src/Validators/
src/Validators/UserValidator.php
templates/
templates/layouts/
templates/layouts/base.php
templates/pages/
templates/pages/home.php
tests/
tests/Unit/
tests/Unit/UserServiceTest.php
====

REMINDERS

Below is your current list of reminders for this task. Keep them updated as you progress.

| # | Content | Status |
|---|---------|--------|
| 1 | Create project structure directories | Pending |
| 2 | Implement Docker configuration files | Pending |
| 3 | Create PHP configuration files | Pending |
| 4 | Implement ADR pattern base classes | Pending |
| 5 | Create CSS architecture files | Pending |
| 6 | Set up configuration files for PHPStan and PHP CS Fixer | Pending |
| 7 | Implement basic routing and entry point | Pending |
| 8 | Create component library base classes | Pending |
| 9 | Set up Makefile with required commands | Pending |
| 10 | Create README.md with project documentation | Pending |


IMPORTANT: When task status changes, remember to call the `update_todo_list` tool to update your progress.

</environment_details>

---

**Assistant:**

<thinking>
I can see that the project structure has been created and many files have been implemented already. The current workspace directory shows a comprehensive implementation of the tech stack requirements from the `tech-stack.md` file. Let me check what's been completed and what still needs to be done.

Looking at the current files, I can see:
1. Project structure directories

[Response interrupted by user]

