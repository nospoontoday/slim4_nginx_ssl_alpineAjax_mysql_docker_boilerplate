# Modern PHP Application Boilerplate

A comprehensive, production-ready PHP 8.3 application built with **Slim 4**, **HTMX**, and **Alpine.js**, following modern development practices and the ADR (Action-Domain-Responder) pattern.

## 🚀 Features

- **PHP 8.3** with strict types and modern syntax
- **Slim Framework 4** for lightweight, fast routing
- **HTMX 2.0.3** for dynamic content without JavaScript
- **Alpine.js 3.14.1** for lightweight reactivity (15KB)
- **Custom CSS Design System** with CSS variables and utility classes
- **Component Library** for reusable UI patterns
- **ADR Architecture** for clean separation of concerns
- **Docker Development Environment** with PHP-FPM, Nginx, MySQL, Redis
- **Comprehensive Testing Setup** with PHPUnit, PHPStan, and PHP CS Fixer
- **Security Features** including JWT authentication, rate limiting, and CORS
- **Advanced Database Connection Management** with connection pooling, health monitoring, and automatic reconnection

## 🏗️ Architecture

This application follows the **ADR (Action-Domain-Responder)** pattern:

- **Actions**: Handle HTTP requests and coordinate between services
- **Services**: Contain business logic and orchestrate operations
- **Repositories**: Handle data access and persistence
- **Responders**: Format responses based on content negotiation

```
Request → Action → Service → Repository → Database
                ↓
Response ← Responder ← Service ← Repository
```

## 📁 Project Structure

```
project/
├── public/                     # Web root
│   ├── index.php              # Single entry point
│   └── assets/                # Static files
│       ├── css/               # CSS source files
│       │   ├── variables.css  # Design tokens
│       │   ├── base.css       # Base styles
│       │   ├── components.css # Component styles
│       │   ├── utilities.css  # Utility classes
│       │   └── master.css     # Compiled CSS (do not edit)
│       └── js/
│           └── app.js         # Main JavaScript
│
├── src/                       # Application code
│   ├── Actions/              # Request handlers (ADR pattern)
│   │   ├── Api/             # API endpoints
│   │   ├── Web/             # Web pages
│   │   └── Htmx/            # HTMX-specific endpoints
│   ├── Services/             # Business logic
│   ├── Repositories/         # Data access layer
│   ├── Database/             # Database connection management
│   │   ├── DatabaseConnection.php      # Connection manager with pooling
│   │   ├── DatabaseConfig.php         # Configuration management
│   │   └── DatabaseConnectionException.php # Custom exceptions
│   ├── Http/                 # HTTP utilities
│   ├── Middleware/           # Request/Response middleware
│   ├── Validators/           # Input validation
│   └── Exceptions/           # Custom exceptions
│
├── components/               # UI Component library
│   ├── UI/                  # Base components
│   └── Component.php        # Base component class
│
├── templates/               # HTML templates
│   ├── layouts/             # Page layouts
│   └── pages/               # Page content
│
├── config/                  # Configuration
│   ├── app.php             # Dependency injection
│   └── routes.php          # Route definitions
│
├── docker/                  # Docker configuration
│   ├── php/                # PHP-FPM setup
│   ├── nginx/              # Nginx configuration
│   └── mysql/              # Database setup
│
├── scripts/                 # Utility scripts
│   └── test-db-connection.php # Database connection test script
├── tests/                   # Test files
├── composer.json            # PHP dependencies
├── docker-compose.yml       # Development environment
├── Makefile                 # Development commands
└── README.md               # This file
```

## 🛠️ Technology Stack

### Backend
- **PHP 8.3** - Latest PHP with JIT compilation
- **Slim Framework 4** - Minimal routing framework
- **PDO** - Direct database access (no ORM)
- **Redis** - Sessions, caching, and rate limiting

### Frontend
- **HTMX 2.0.3** - HTML-over-the-wire (14KB)
- **Alpine.js 3.14.1** - Lightweight reactivity (15KB)
- **Custom CSS System** - Component-based design system
- **Progressive Enhancement** - Works without JavaScript

### Infrastructure
- **Docker Compose** - Development environment
- **Nginx** - Web server and reverse proxy
- **MySQL 8.0** - Primary database
- **Redis 7.2** - Caching and sessions

### Quality Assurance
- **PHPStan Level 8** - Static analysis
- **PHP CS Fixer** - Code style enforcement
- **PHPUnit** - Unit and integration testing

## 🚀 Quick Start

### Prerequisites
- Docker and Docker Compose
- Make (optional, for convenience commands)

### 1. Clone and Setup
```bash
git clone <repository-url>
cd sugar_clone
```

### 2. Start Development Environment
```bash
make up
# or manually:
docker-compose up -d
```

### 3. Install Dependencies
```bash
make build
# or manually:
docker-compose exec app composer install
```

### 4. Build CSS
```bash
make css
```

### 5. Access Application
- **Application**: http://localhost
- **MailHog**: http://localhost:8025
- **Database**: localhost:3306

## 📚 Development Commands

```bash
# Development Environment
make up              # Start environment
make down            # Stop environment
make logs            # View logs
make shell           # Enter PHP container

# CSS Development
make css             # Build CSS
make css-watch       # Watch and rebuild CSS

# Code Quality
make test            # Run all tests
make analyze         # Run PHPStan
make fix             # Fix code style

# Database
make migrate         # Run migrations
make seed            # Seed database
make fresh           # Fresh database with seeds
make db-test         # Test database connections
make db-health       # Run database health check
```

## 🎨 CSS Architecture

Our CSS system uses a modular approach with four source files compiled into one `master.css`:

1. **variables.css** - Design tokens and brand configuration
2. **base.css** - Reset and foundational styles  
3. **components.css** - Reusable component styles
4. **utilities.css** - Single-purpose helper classes

**Never edit `master.css` directly** - it's compiled from the source files. Use `make css` to rebuild.

## 🔧 Configuration

### Environment Variables
Copy `.env` to `.env.local` and customize:
```bash
# Application
APP_ENV=development
APP_DEBUG=true

# Database
DB_HOST=mysql
DB_DATABASE=app

# JWT
JWT_SECRET=your-secret-key
```

### Docker Configuration
- **PHP**: Custom PHP 8.3 with extensions
- **Nginx**: Reverse proxy to PHP-FPM
- **MySQL**: Persistent data with sample schema
- **Redis**: In-memory data store

## 🧪 Testing

### Run Tests
```bash
make test                    # All tests
docker-compose exec app vendor/bin/phpunit --filter=UserServiceTest
```

### Static Analysis
```bash
make analyze                # PHPStan + CS Fixer
docker-compose exec app vendor/bin/phpstan analyze
```

### Code Style
```bash
make fix                    # Auto-fix code style
docker-compose exec app vendor/bin/php-cs-fixer fix
```

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Set secure `JWT_SECRET`
- [ ] Enable HTTPS
- [ ] Configure Redis
- [ ] Run `make css` for production CSS

### GitHub Actions
The project includes CI/CD configuration for automated testing and deployment.

## 📖 API Documentation

### Content Negotiation
The API automatically returns:
- **JSON** for regular API requests
- **HTML** for HTMX requests (with `HX-Request` header)

### Authentication
Protected endpoints require JWT Bearer token:
```bash
Authorization: Bearer <your-jwt-token>
```

### Rate Limiting
API endpoints are rate-limited to prevent abuse.

## 🎯 HTMX Patterns

### Live Search
```html
<input hx-get="/api/search"
       hx-trigger="input changed delay:500ms"
       hx-target="#results">
```

### Infinite Scroll
```html
<div hx-get="/api/posts?page=2"
     hx-trigger="revealed"
     hx-swap="beforebegin">
```

### Form Auto-save
```html
<form hx-post="/api/draft"
      hx-trigger="input changed delay:2s"
      hx-swap="none">
```

## 🔒 Security Features

- **JWT Authentication** for API endpoints
- **Rate Limiting** to prevent abuse
- **CORS Configuration** for cross-origin requests
- **Input Validation** with comprehensive rules
- **SQL Injection Protection** with prepared statements
- **XSS Protection** with output escaping

## 🗄️ Database Connection Management

### Advanced Connection System
The application now features a robust database connection management system with the following capabilities:

- **Connection Pooling**: Persistent connections for better performance
- **Automatic Reconnection**: Handles connection failures gracefully
- **Health Monitoring**: Real-time database health checks and diagnostics
- **Configuration Management**: Centralized database settings with validation
- **Performance Metrics**: Response time monitoring and connection usage tracking

### Database Health Monitoring
- **API Endpoint**: `/api/v1/health/database` for programmatic health checks
- **Web Interface**: `/database-test` for visual monitoring and testing
- **Command Line**: `php scripts/test-db-connection.php` for CLI testing
- **Real-time Metrics**: Connection status, response times, and usage statistics

### Connection Features
- **MySQL 8.0**: Optimized connection settings with UTF8MB4 support
- **Redis 7.2**: Enhanced configuration with serialization and prefixing
- **Error Handling**: Custom exceptions with detailed context information
- **Logging**: Comprehensive logging for debugging and monitoring
- **Backward Compatibility**: Existing PDO and Redis dependencies continue to work

## 🤝 Contributing

1. Follow PSR-12 coding standards
2. Write tests for new features
3. Use the established ADR pattern
4. Update documentation as needed
5. Run `make analyze` before committing

## 📄 License

This project is licensed under the MIT License.

## 🆘 Support

- Check the `tech-stack.md` file for detailed specifications
- Review existing code for patterns and examples
- Use `make help` for available commands
- Check Docker logs with `make logs`

---

**Built with ❤️ using modern PHP development practices**
