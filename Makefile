# ===========================================
# Makefile for Hanacaraka API Docker
# ===========================================

.PHONY: help build up down restart logs shell mysql redis fresh test lint

# Default target
help:
	@echo "Hanacaraka API - Docker Commands"
	@echo "================================="
	@echo ""
	@echo "Setup:"
	@echo "  make setup        - Initial setup (build, up, migrate)"
	@echo "  make build        - Build Docker images"
	@echo "  make up           - Start containers"
	@echo "  make down         - Stop containers"
	@echo "  make restart      - Restart containers"
	@echo ""
	@echo "Development:"
	@echo "  make shell        - Access app container shell"
	@echo "  make logs         - View container logs"
	@echo "  make mysql        - Access MySQL CLI"
	@echo "  make redis        - Access Redis CLI"
	@echo ""
	@echo "Laravel:"
	@echo "  make migrate      - Run migrations"
	@echo "  make seed         - Run seeders"
	@echo "  make fresh        - Fresh migrate with seed"
	@echo "  make tinker       - Start Laravel Tinker"
	@echo "  make cache        - Clear all caches"
	@echo "  make optimize     - Optimize Laravel"
	@echo ""
	@echo "Testing:"
	@echo "  make test         - Run PHPUnit tests"
	@echo "  make lint         - Run PHP CS Fixer"
	@echo ""
	@echo "Utilities:"
	@echo "  make clean        - Remove all containers and volumes"
	@echo "  make prune        - Docker system prune"

# ===========================================
# Setup Commands
# ===========================================

setup: env-setup build up composer-install key-generate migrate
	@echo "Setup complete! Access the API at http://localhost:8000"

env-setup:
	@if [ ! -f .env ]; then \
		cp .env.docker .env; \
		echo ".env file created from .env.docker"; \
	fi

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose restart

# ===========================================
# Development Commands
# ===========================================

shell:
	docker-compose exec app sh

logs:
	docker-compose logs -f

logs-app:
	docker-compose logs -f app

logs-nginx:
	docker-compose logs -f nginx

logs-mysql:
	docker-compose logs -f mysql

mysql:
	docker-compose exec mysql mysql -u root -psecret be_hanacaraka

redis:
	docker-compose exec redis redis-cli

# ===========================================
# Laravel Commands
# ===========================================

composer-install:
	docker-compose exec app composer install

composer-update:
	docker-compose exec app composer update

key-generate:
	docker-compose exec app php artisan key:generate

migrate:
	docker-compose exec app php artisan migrate

migrate-rollback:
	docker-compose exec app php artisan migrate:rollback

seed:
	docker-compose exec app php artisan db:seed

fresh:
	docker-compose exec app php artisan migrate:fresh --seed

tinker:
	docker-compose exec app php artisan tinker

artisan:
	docker-compose exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

cache:
	docker-compose exec app php artisan optimize:clear

optimize:
	docker-compose exec app php artisan optimize

storage-link:
	docker-compose exec app php artisan storage:link

# ===========================================
# Testing Commands
# ===========================================

test:
	docker-compose exec app php artisan test

test-coverage:
	docker-compose exec app php artisan test --coverage

lint:
	docker-compose exec app ./vendor/bin/pint

# ===========================================
# Queue Commands
# ===========================================

queue-work:
	docker-compose exec app php artisan queue:work

queue-restart:
	docker-compose exec app php artisan queue:restart

# ===========================================
# Cleanup Commands
# ===========================================

clean:
	docker-compose down -v --remove-orphans
	docker-compose rm -f

prune:
	docker system prune -af

# Allow passing arguments to artisan
%:
	@:
