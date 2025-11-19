
COMPOSE=docker compose
PHP_CONTAINER=php
COMPOSER=composer
MAX_RETRIES=20
SLEEP_TIME=3

.PHONY: up down install migrate logs bash

# -------------------------------------------------
# Démarrage complet : containers + composer + migrations
up:
	@echo "⏳ Démarrage des containers..."
	$(COMPOSE) up -d

	@echo "⏳ Attente que PHP soit prêt..."
	@$(MAKE) wait-php

	@echo "⏳ Installation des dépendances Composer..."
	@$(MAKE) install

	@echo "⏳ Exécution des migrations..."
	@$(MAKE) migrate

	@echo "✅ Tout est prêt !"

# Arrêter et supprimer les containers
down:
	$(COMPOSE) down

# Installer les dépendances PHP via Composer
install:
	$(COMPOSE) exec $(PHP_CONTAINER) $(COMPOSER) install --no-interaction --optimize-autoloader

# Lancer les migrations Doctrine
migrate:
	$(COMPOSE) exec $(PHP_CONTAINER) php bin/console doctrine:migrations:migrate --no-interaction

# Afficher les logs de tous les containers
logs:
	$(COMPOSE) logs -f

# Ouvrir un bash dans le container PHP
bash:
	$(COMPOSE) exec $(PHP_CONTAINER) bash

# -------------------------------------------------
# Attendre que PHP soit prêt avant de lancer composer/migrations
wait-php:
	@i=0; \
	while ! $(COMPOSE) exec $(PHP_CONTAINER) php -v > /dev/null 2>&1; do \
		i=$$((i+1)); \
		if [ $$i -ge $(MAX_RETRIES) ]; then \
			echo "❌ PHP container ne répond pas, abandon"; \
			exit 1; \
		fi; \
		echo "⏳ Attente PHP... ($$i/$(MAX_RETRIES))"; \
		sleep $(SLEEP_TIME); \
	done
	@echo "✅ PHP est prêt"
