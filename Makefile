DOCKER_COMPOSE_WEBPACK ?=docker-compose exec webpack
DOCKER_COMPOSE_PHP ?=docker-compose exec php
DOCKER_COMPOSE_YARN ?=docker-compose run yarn sh -c

# TESTING
phpunit:
	rm -Rf var/cache/test
	make build-test-db
	${DOCKER_COMPOSE_PHP} ./vendor/bin/phpunit
	make tear-down-test-db

# ANALYSIS
codesniff:
	${DOCKER_COMPOSE_PHP} ./vendor/bin/phpcs --standard=PSR1,PSR2 --ignore=src/Migrations -s src/  -s tests/

codefix:
	${DOCKER_COMPOSE_PHP} ./vendor/bin/phpcbf --standard=PSR1,PSR2 --ignore=src/Migrations -s src/ -s tests/

# DOCKER COMPOSE
rebuild:
	docker-compose down --remove-orphans
	docker-compose build --force-rm --pull
	docker-compose up -d

start:
	docker-compose start

stop:
	docker-compose stop

restart:
	make stop
	make start

enter-php:
	docker-compose exec php sh

enter-webpack:
	docker-compose exec webpack sh

log-webpack:
	docker-compose logs -f webpack

ps:
	docker-compose ps

# SYMFONY
cache:
	${DOCKER_COMPOSE_PHP} ./bin/console ca:c

rebuild-db:
	${DOCKER_COMPOSE_PHP} ./bin/console do:da:dr --force --if-exists
	${DOCKER_COMPOSE_PHP} ./bin/console do:da:cr
	${DOCKER_COMPOSE_PHP} ./bin/console do:mi:mi -n
	${DOCKER_COMPOSE_PHP} ./bin/console do:fi:lo -n

build-test-db:
	${DOCKER_COMPOSE_PHP} ./bin/console --env=test do:da:cr
	${DOCKER_COMPOSE_PHP} ./bin/console --env=test do:sc:up --force
	${DOCKER_COMPOSE_PHP} ./bin/console --env=test do:fi:lo -n

tear-down-test-db:
	${DOCKER_COMPOSE_PHP} ./bin/console --env=test do:da:dr --force

create-migration-diff:
	${DOCKER_COMPOSE_PHP} ./bin/console do:mi:di

migration-migrate:
	${DOCKER_COMPOSE_PHP} ./bin/console do:mi:mi -n

# SETUP
composer-development:
	${DOCKER_COMPOSE_PHP} composer install --no-progress --prefer-dist --no-scripts

composer-production:
	${DOCKER_COMPOSE_PHP} composer install --no-dev --no-progress --prefer-dist --no-scripts

yarn:
	${DOCKER_COMPOSE_YARN} yarn install

install:
	make rebuild
	make composer-development
	cp .env.dist .env
	make rebuild-db
