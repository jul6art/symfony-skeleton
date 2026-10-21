# {{PROJECT_NAME}} — mode {{MODE}} — Symfony {{SYMFONY_VERSION}} / PHP {{PHP_VERSION}}
# Généré par le skeleton maison le {{DATE}}.

SHELL := /bin/bash
.DEFAULT_GOAL := help

PHP      := symfony php
COMPOSER := symfony composer
CONSOLE  := symfony console

.PHONY: help install start stop logs cc db-create db-diff db-migrate db-reset \
        fixtures test test-serial cs cs-fix stan qa docs docs-check

help: ## Liste les cibles disponibles
	@grep -hE '^[a-zA-Z0-9_-]+:.*?## ' $(MAKEFILE_LIST) \
		| sort \
		| awk 'BEGIN {FS = ":.*?## "} {printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2}'

install: ## Installe les dépendances
	$(COMPOSER) install

start: ## Démarre le serveur local (https)
	symfony server:start -d
	@symfony open:local || true

stop: ## Arrête le serveur local
	symfony server:stop

logs: ## Suit les logs du serveur local
	symfony server:log

cc: ## Vide le cache
	$(CONSOLE) cache:clear

db-create: ## Crée la base de données
	$(CONSOLE) doctrine:database:create --if-not-exists

db-diff: ## Génère une migration à partir des entités
	$(CONSOLE) doctrine:migrations:diff

db-migrate: ## Applique les migrations
	$(CONSOLE) doctrine:migrations:migrate --no-interaction

db-reset: ## Recrée la base à zéro (destructif)
	$(CONSOLE) doctrine:database:drop --force --if-exists
	$(MAKE) db-create db-migrate

fixtures: ## Recharge les fixtures
	$(CONSOLE) doctrine:fixtures:load --no-interaction

# Le lanceur parallèle si le mode en fournit un, le lanceur simple sinon. La condition évite de
# forker ce Makefile par mode : un mode dont la suite s'isole naturellement (SQLite en mémoire)
# dépose son `paratest.sh` dans son overlay et gagne la parallélisation ; les autres gardent
# PHPUnit, sans qu'aucune cible ne mente sur ce qu'elle fait.
test: ## Lance les tests (en parallèle si paratest.sh est présent)
	@if [ -x ./paratest.sh ]; then ./paratest.sh; else $(PHP) vendor/bin/phpunit; fi

# ⚠️ Le mono-thread reste la RÉFÉRENCE. Un test qui ne tombe qu'en parallèle dépend d'un état
# partagé entre processus — un fichier à chemin fixe, un port, une horloge — et c'est LUI qu'il
# faut corriger : le rejouer ici sert à le confirmer, pas à s'en dispenser.
test-serial: ## Lance les tests en un seul processus (référence, pour reproduire un échec)
	$(PHP) vendor/bin/phpunit

cs: ## Vérifie le style de code
	$(PHP) vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix: ## Corrige le style de code
	$(PHP) vendor/bin/php-cs-fixer fix

stan: ## Analyse statique
	$(CONSOLE) cache:warmup --env=dev
	$(PHP) vendor/bin/phpstan analyse --memory-limit=1G

qa: cs stan test ## Style + analyse statique + tests

docs: ## Met à jour la carte des workflows (docs/workflows/), sans IA
	$(PHP) vendor/bin/devtools workflows:inspect --no-ai

docs-check: ## Échoue si la carte des workflows ne reflète plus le code
	$(PHP) vendor/bin/devtools workflows:check

# Cibles spécifiques au mode ({{MODE}}) et ajouts locaux.
-include .make/*.mk
