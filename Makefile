# Makefile
#---VARIABLES---------------------------------#
#>---ENV------#
ENV_FILE?=.env.local
#<---ENV------#
#>---DOCKER---#
DOCKER = docker
DOCKER_RUN = $(DOCKER) run
DOCKER_COMPOSE = docker compose
DOCKER_COMPOSE_BUILD = $(DOCKER_COMPOSE) --env-file $(ENV_FILE) build
DOCKER_COMPOSE_UP = $(DOCKER_COMPOSE) --env-file $(ENV_FILE) up -d
DOCKER_COMPOSE_STOP = $(DOCKER_COMPOSE) stop
DOCKER_COMPOSE_EXEC = $(DOCKER_COMPOSE) exec
DOCKER_COMPOSE_APP = $(DOCKER_COMPOSE) exec app
DOCKER_COMPOSE_CONSOLE = $(DOCKER_COMPOSE) exec app php bin/console
DOCKER_COMPOSE_TEST = $(DOCKER_COMPOSE) exec app php bin/console
DOCKER_COMPOSE_LINT = $(DOCKER_COMPOSE) exec app php bin/console lint:
DOCKER_COMPOSE_DB = $(DOCKER_COMPOSE) exec database
#<---DOCKER---#
#>---COMPOSER-#
COMPOSER = $(DOCKER_COMPOSE_APP) composer
COMPOSER_REQUIRE = $(COMPOSER) require $(OPTIONS) $(filter-out $@,$(MAKECMDGOALS))
COMPOSER_INSTALL = $(COMPOSER) install -o
COMPOSER_UPDATE = $(COMPOSER) update -o
#<---COMPOSER-#
%:
	@:
## === 🆘  HELP ==================================================
help: ## Show this help.
	@echo "Symfony-And-Docker-Makefile"
	@echo "---------------------------"
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
#---------------------------------------------#
## === ⚙️  INSTALL ===============================================
install: ## Install the project.
	@make docker-build
	@make docker-up
	@make composer-install
## === 🐋  DOCKER ================================================
docker-build: ## Build docker containers.
	@echo "---------------------------------------------"
	@echo "Build Docker containers using environment file: $(ENV_FILE)"
	@echo "---------------------------------------------"
	$(DOCKER_COMPOSE_BUILD)
docker-up: ## Start docker containers. You can define a different .env file by running `make docker-up ENV_FILE=.another-env-file`.
	@echo "---------------------------------------------"
	@echo "Starting Docker containers using environment file: $(ENV_FILE)"
	@echo "---------------------------------------------"
	$(DOCKER_COMPOSE_UP)
.PHONY: docker-up

docker-stop: ## Stop docker containers.
	$(DOCKER_COMPOSE_STOP)
.PHONY: docker-stop
#---------------------------------------------#
## === 🎛️  SYMFONY ===============================================
sf: ## List and Use All Symfony commands (make sf command="commande-name").
	$(DOCKER_COMPOSE_CONSOLE) $(command)
.PHONY: sf

sf-cc: ## Clear symfony cache.
	$(DOCKER_COMPOSE_CONSOLE) cache:clear
.PHONY: sf-cc

sf-log: ## Show symfony logs.
	$(DOCKER_COMPOSE_CONSOLE) server:log
.PHONY: sf-log

sf-dc: ## Create symfony database.
	$(DOCKER_COMPOSE_CONSOLE) doctrine:database:create --if-not-exists --connection=default
.PHONY: sf-dc

sf-dd: ## Drop symfony database.
	$(DOCKER_COMPOSE_CONSOLE) doctrine:database:drop --if-exists --force --connection=default
.PHONY: sf-dd

sf-su: ## Update symfony schema database.
	$(DOCKER_COMPOSE_CONSOLE) doctrine:schema:update --force
.PHONY: sf-su

sf-mm: ## Make migrations.
	$(DOCKER_COMPOSE_CONSOLE) make:migration
.PHONY: sf-mm

sf-dmm: ## Migrate.
	$(DOCKER_COMPOSE_CONSOLE) doctrine:migrations:migrate --no-interaction
.PHONY: sf-dmm

sf-dmm-prev: ## Migrate to previous version.
	@echo "---------------------------------------------"
	@echo "Migrate to previous version"
	@echo "N'oubliez pas de supprimer la ligne \"$this->addSql('CREATE SCHEMA public');\" dans le \"down\" de la dernière migration"
	@echo "---------------------------------------------"
	$(DOCKER_COMPOSE_CONSOLE) doctrine:migrations:migrate prev --no-interaction

sf-fixtures: ## Load fixtures.
	$(DOCKER_COMPOSE_CONSOLE) doctrine:fixtures:load --no-interaction
.PHONY: sf-fixtures

sf-me: ## Make symfony entity
	$(DOCKER_COMPOSE_CONSOLE) make:entity
.PHONY: sf-me

sf-mc: ## Make symfony controller
	$(DOCKER_COMPOSE_CONSOLE) make:controller
.PHONY: sf-mc

sf-perm: ## Fix permissions.
	$(DOCKER_COMPOSE_APP) chmod -R 777 var
.PHONY: sf-perm

sf-sudo-perm: ## Fix permissions with sudo.
	$(DOCKER_COMPOSE_APP) sudo chmod -R 777 var
.PHONY: sf-sudo-perm

sf-dump-env: ## Dump env.
	$(SYMFONY_CONSOLE) debug:dotenv
.PHONY: sf-dump-env

sf-dump-env-container: ## Dump Env container.
	$(DOCKER_COMPOSE_CONSOLE) debug:container --env-vars
.PHONY: sf-dump-env-container

sf-dump-routes: ## Dump routes.
	$(DOCKER_COMPOSE_CONSOLE) debug:router
.PHONY: sf-dump-routes
#---------------------------------------------#
## === 📦  COMPOSER ==============================================
composer-install: ## Install composer dependencies.
	$(COMPOSER_INSTALL)
.PHONY: composer-install

composer-require: ## Require a composer package.
	$(COMPOSER_REQUIRE)

composer-update: ## Update composer dependencies.
	$(COMPOSER_UPDATE)
.PHONY: composer-update

composer-validate: ## Validate composer.json file.
	$(COMPOSER) validate
.PHONY: composer-validate

composer-validate-deep: ## Validate composer.json and composer.lock files in strict mode.
	$(COMPOSER) validate --strict --check-lock
.PHONY: composer-validate-deep
#---------------------------------------------#