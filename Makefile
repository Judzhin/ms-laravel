#!make
TAG := 1.0

ifndef TAG
$(error The TAG variable is missing.)
endif

ENV := local

ifndef ENV
$(error The ENV variable is missing.)
endif

ifeq ($(filter $(ENV),local test dev stag prod),)
$(error The ENV variable is invalid.)
endif

ifeq (,$(filter $(ENV),test dev))
COMPOSE_FILE_PATH := -f docker-compose.yml
endif

IMAGE := msbios/msbios

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

build: ## Build or rebuild services without cache when building the image
	$(info Make: Building "$(ENV)" environment images.)
	@TAG=$(TAG) docker-compose build --no-cache
	@#make -s clean

start: ## Builds, (re)creates, starts, and attaches to containers for a service in the background
	$(info Make: Starting "$(ENV)" environment containers.)
	@TAG=$(TAG) docker-compose $(COMPOSE_FILE_PATH) up -d

up: ## Builds, (re)creates, starts, and attaches to containers for a service in the background
	$(info Make: Starting "$(ENV)" environment containers.)
	@make start && sleep 5
	@docker-compose run --rm composer install

ps: ## Show information about running containers
	$(info Make: Starting "$(ENV)" environment containers.)
	@TAG=$(TAG) docker-compose $(COMPOSE_FILE_PATH) ps

stop: ## Stop running containers without removing them
	$(info Make: Stopping "$(ENV)" environment containers.)
	@docker-compose stop

down: ## Stops containers and removes containers, networks, volumes, and images created by `up`
	$(info Make: Stopping and removing "$(ENV)" environment containers, networks, and volumes.)
	@docker-compose down --remove-orphans

clear: ## Stops containers and removes containers, networks, volumes with static informations
	$(info Make: Stopping and removing "$(ENV)" environment containers, networks, and volumes with data.)
	@docker-compose down -v --remove-orphans

recreate: ## Recreate containers
	$(info Make: Recreateing "$(ENV)" environment containers.)
	@docker-compose up -d --build --force-recreate --no-deps

restart: ## Stop and start containers
	$(info Make: Restarting "$(ENV)" environment containers.)
	@make -s stop
	@make -s start

logs: ## Show logs about running containers
	@docker-compose logs --tail=100 -f $(c)

check: ## Docker check
	@$(DOCKER) info > /dev/null 2>&1 # Docker is up
	@test '"healthy"' = `$(DOCKER) inspect --format "{{json .State.Health.Status }}" sb-db` # Db container is up and healthy

push: ## Pushing image to hub
	$(info Make: Pushing "$(TAG)" tagged image.)
	@docker push $(IMAGE):$(TAG)

pull: ## Pulling image from hub
	$(info Make: Pulling "$(TAG)" tagged image.)
	@docker pull $(IMAGE):$(TAG)

purge: ## Purge cache and logs
	rm -rf var/cache/* var/log/*
	@docker-compose run --rm artisan migrate:refresh --seed -v
    @docker-compose run --rm artisan cache:clear
    @docker-compose run --rm artisan view:clear
    @docker-compose run --rm artisan config:clear

clean: ## Remove unused data without prompt for confirmation
	@docker system prune --volumes --force

login: ## Login to Docker Hub.
	$(info Make: Login to Docker Hub.)
	@docker login -u $(DOCKER_USER) -p $(DOCKER_PASS)

## Custom Commands
cli: ## Run CLI
	$(info Make: Run CLI)
	@docker-compose exec php-fpm bash

watch: ## Run CLI
	$(info Make: Run Watch)
	@docker-compose run --rm node npx mix watch
