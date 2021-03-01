SYMFONY         = symfony
SYMFONY_CONSOLE = symfony console
PHPUNIT         = symfony run ./vendor/bin/simple-phpunit
DOCKER          = docker-compose

VENDOR_BIN      = ./vendor/bin
PHP_CS_FIXER    = $(VENDOR_BIN)/php-cs-fixer
PHPSTAN         = $(VENDOR_BIN)/phpstan
RECTOR          = $(VENDOR_BIN)/rector

##
## Project
## -----
##

up: ## Start Docker and Symfony services
up: vendor
	- $(DOCKER) up -d
	- $(SYMFONY) local:proxy:start
	- $(SYMFONY) serve -d
	- $(DOCKER) ps

down: ## Stop Docker and Symfony services
down: vendor
	- $(SYMFONY) local:server:stop
	- $(SYMFONY) local:proxy:stop
	- $(DOCKER) down

##
## Code Quality
## ------------
##

cq: ## Ensure project respect code quality
cq: rector php-cs-fixer phpstan

cq-fix: ## Ensure project respect code quality, otherwise try to fix it
cq-fix: rector-fix php-cs-fixer-fix phpstan

##
## Utils
## -----
##

phpstan: ## PHP Static Analysis Tool (https://github.com/phpstan/phpstan)
	$(SYMFONY) run bin/phpunit install
	$(PHPSTAN) analyse -l 8 -c phpstan.neon src tests

php-cs-fixer: ## PHP Coding Standards Fixer (http://cs.sensiolabs.org)
	$(PHP_CS_FIXER) fix --dry-run --using-cache=no --verbose --diff

php-cs-fixer-fix: ## apply PHP Coding Standards Fixer fixes
	$(PHP_CS_FIXER) fix --using-cache=no --verbose --diff

rector: ## PHP Refactoring (https://github.com/rectorphp/rector)
	$(RECTOR) process src --dry-run

rector-fix: ## apply PHP Refactoring (https://github.com/rectorphp/rector)
	$(RECTOR) process src

##
## Tests
## -----
##

tests-fresh: ## Erase database then run unit and functional tests
tests-fresh:
	- $(SYMFONY_CONSOLE) c:c --env=dev
	- $(SYMFONY_CONSOLE) d:d:d --if-exists --force --env=dev
	- $(SYMFONY_CONSOLE) d:d:c --env=dev
	- $(SYMFONY_CONSOLE) d:m:m -n --env=dev
	- $(PHPUNIT) --stderr --stop-on-failure

tests: ## Run unit and functional tests
tests:
	- $(PHPUNIT) --exclude-group functional --stderr
	- $(PHPUNIT) --group functional --stderr -vvv

.PHONY: tests

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: help
