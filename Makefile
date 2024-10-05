DC   					= docker compose
.DEFAULT_GOAL			= help

.PHONY: help
help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

### INIT
.PHONY: build
build: ## Build application image
	@$(DC) build

.PHONY: init
init: up ## Init application
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


### K3D stuff (see github.com/mmalessa/local-kubernetes repository)

.PHONY: dev-push-image
dev-push-image: ## Push image to registry.local
	@docker tag learning-symfony-messenger-php registry.localhost:5000/learning-symfony-messenger-php:latest
	@docker push registry.localhost:5000/learning-symfony-messenger-php:latest

.PHONY: k9s-init
k9s-init: ## Init app environment in Kubernetes
	kubectl apply -f .kubernetes/environment.yaml
	kubectl apply -f .kubernetes/postgres.yaml
	kubectl wait --for=condition=available --timeout=30s deployment/postgres
	kubectl apply -f .kubernetes/kafka.yaml
	kubectl wait --for=condition=available --timeout=30s deployment/kafka
	kubectl apply -f .kubernetes/kafka-ui.yaml
	kubectl wait --for=condition=available --timeout=30s deployment/kafka-ui
	kubectl apply -f .kubernetes/external-api.yaml
	kubectl wait --for=condition=available --timeout=30s deployment/external-api
	kubectl apply -f .kubernetes/composer-job.yaml
	kubectl wait --for=condition=complete --timeout=30s job/composer-job
	kubectl apply -f .kubernetes/migrations-job.yaml
	kubectl wait --for=condition=complete --timeout=30s job/migrations-job

.PHONY: k9s-purge
k9s-purge: ## Purge app environment in Kubernetes
	kubectl delete -f .kubernetes/migrations-job.yaml
	kubectl delete -f .kubernetes/composer-job.yaml
	kubectl delete -f .kubernetes/external-api.yaml
	kubectl delete -f .kubernetes/kafka-ui.yaml
	kubectl delete -f .kubernetes/kafka.yaml
	kubectl delete -f .kubernetes/postgres.yaml
	kubectl delete -f .kubernetes/environment.yaml

.PHONY: k9s-app-up
k9s-app-up: k9s-init ## Apply dev deployment (test)
	kubectl apply -f .kubernetes/application.yaml

.PHONY: k9s-app-down
k9s-app-down: ## Delete dev deployment (test)
	kubectl delete -f .kubernetes/application.yaml
