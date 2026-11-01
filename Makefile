.PHONY: help test test-mysql test-postgres test-all

COMPOSE ?= docker compose
PHPUNIT = ./vendor/bin/phpunit --no-coverage
INSTALL = composer install --no-interaction --prefer-dist

help:
	@echo "Available targets:"
	@echo "  test          Run the test suite with SQLite in Docker"
	@echo "  test-mysql    Run tests with MySQL in Docker"
	@echo "  test-postgres Run tests with PostgreSQL in Docker"
	@echo "  test-all      Run tests with SQLite, MySQL, and PostgreSQL in Docker"

test:
	$(COMPOSE) run --rm app sh -c '$(INSTALL) && $(PHPUNIT)'

test-mysql:
	$(COMPOSE) run --rm -e DB_CONNECTION=mysql -e DB_HOST=mysql -e DB_PORT=3306 -e DB_DATABASE=laravel_userstamps_test -e DB_USERNAME=laravel_userstamps -e DB_PASSWORD=password app sh -c '$(INSTALL) && $(PHPUNIT)'

test-postgres:
	$(COMPOSE) run --rm -e DB_CONNECTION=pgsql -e DB_HOST=postgres -e DB_PORT=5432 -e DB_DATABASE=laravel_userstamps_test -e DB_USERNAME=laravel_userstamps -e DB_PASSWORD=password app sh -c '$(INSTALL) && $(PHPUNIT)'

test-all:
	$(MAKE) test
	$(MAKE) test-mysql
	$(MAKE) test-postgres