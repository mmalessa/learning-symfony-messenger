DC   					= docker compose
.DEFAULT_GOAL			= help

.PHONY: help
help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

### INIT
.PHONY: app-build
app-build: ## Build application image
	@$(DC) build

.PHONY: app-init
app-init: up ## Init application
	@$(DC) exec php sh -c 'composer install'

.PHONY: up
up: ## Start containers
	@$(DC) up -d

.PHONY: down
down: ## Stop and remove containers
	@$(DC) down

.PHONY: shell
shell: ## Shell in php container
	@$(DC) exec -it php bash
