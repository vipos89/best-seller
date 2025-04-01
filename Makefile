
PHP_CONTAINER = laravel_app
PHPSTAN = docker exec $(PHP_CONTAINER) vendor/bin/phpstan
PHPCBF = docker exec $(PHP_CONTAINER) vendor/bin/phpcbf
PHPCS = docker exec $(PHP_CONTAINER) vendor/bin/phpcs
CACHE_CLEAR = docker exec $(PHP_CONTAINER) php artisan cache:clear
CONFIG_CACHE_CLEAR = docker exec $(PHP_CONTAINER) php artisan config:clear
ROUTE_CACHE_CLEAR = docker exec $(PHP_CONTAINER) php artisan route:clear
VIEW_CACHE_CLEAR = docker exec $(PHP_CONTAINER) php artisan view:clear
RUN_TEST = docker exec $(PHP_CONTAINER) php artisan test


.PHONY: all-fix phpstan phpcs phpcbf

all-fix: phpstan phpcs

phpstan:
	@echo "Running PHPStan..."
	$(PHPSTAN) analyse --memory-limit=512M

phpcs:
	@echo "Running PHP_CodeSniffer..."
	$(PHPCS) app/

phpcbf:
	@echo "Running PHP_CodeSniffer Fixer..."
	$(PHPCBF) app/

all-cache-clear: cache-clear config-clear route-clear

cache-clear:
	@echo "Clearing application cache..."
	$(CACHE_CLEAR)

config-clear:
	@echo "Clearing configuration cache..."
	$(CONFIG_CACHE_CLEAR)

route-clear:
	@echo "Clearing route cache..."
	$(ROUTE_CACHE_CLEAR)

tests:
	@echo "Running tests..."
	$(RUN_TESTS)
