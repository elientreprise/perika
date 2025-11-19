#!/bin/bash

LOG_DIR="var/log/quality"
mkdir -p $LOG_DIR

echo "== Running PHPStan =="
docker compose exec php php vendor/bin/phpstan analyse --error-format=table \
  | tee "$LOG_DIR/phpstan.log"

echo ""
echo "== Running PHP CS Fixer =="
docker compose exec php php vendor/bin/php-cs-fixer fix --dry-run --diff \
  | tee "$LOG_DIR/phpcsfixer.log"

echo ""
echo "== Running PHPUnit =="
docker compose exec php php bin/phpunit --testdox \
  | tee "$LOG_DIR/phpunit.log"
