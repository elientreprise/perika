#!/bin/bash

echo ""
echo "== Running PHP CS Fixer =="
docker compose exec php php vendor/bin/php-cs-fixer fix --dry-run --diff \
  | tee "$LOG_DIR/phpcsfixer.log"
