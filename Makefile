.DEFAULT_GOAL := help
.PHONY: help up down build setup test analyze fix css

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$1, $$2}'

up: ## Start development environment
	docker-compose up -d
	@echo "Application: http://localhost"
	@echo "MailHog: http://localhost:8025"

down: ## Stop development environment
	docker-compose down

build: ## Build containers
	docker-compose build
	make css

setup: ## Setup application (composer install, etc.)
	docker-compose up -d
	docker-compose exec app composer install
	@echo "Application setup complete"

css: ## Build CSS files
	cat public/assets/css/variables.css \
	    public/assets/css/base.css \
	    public/assets/css/components.css \
	    public/assets/css/utilities.css \
	    public/assets/css/sugar.css \
	    > public/assets/css/master.css
	# Compile SCSS to CSS if sass is available
	@if command -v sass >/dev/null 2>&1; then \
		sass public/assets/sass/sugar.scss:public/assets/css/sugar.css; \
		sass public/assets/sass/landing.scss:public/assets/css/landing.css; \
		echo "SCSS compiled successfully"; \
	else \
		echo "sass not found, skipping SCSS compilation"; \
	fi
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

init: ## Initialize project
	composer install
	make css
	@echo "Project initialized successfully"
