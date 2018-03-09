BUILD_ARTIFACTS_DIRECTORY=build-artifacts/

# CircleCI
build-artifacts:
	@mkdir -p ${BUILD_ARTIFACTS_DIRECTORY}/
copy-ci-environment:
	cp .env.circleci .env

# Analysis
phpunit:
	./vendor/bin/phpunit
codecoverage:
	./vendor/bin/phpunit --coverage-clover=${BUILD_ARTIFACTS_DIRECTORY}/coverage.xml
	./vendor/bin/codacycoverage phpunit ${BUILD_ARTIFACTS_DIRECTORY}/coverage.xml
codesniff:
	./vendor/bin/phpcs --standard=PSR1,PSR2  --ignore=src/Migrations -s src/  -s tests/ | tee  ${BUILD_ARTIFACTS_DIRECTORY}phpcs.log
codefix:
	./vendor/bin/phpcbf --standard=PSR1,PSR2 -s src/ -s tests/
lines:
	./vendor/bin/phploc src/ | tee ${BUILD_ARTIFACTS_DIRECTORY}phploc.log
mess:
	./vendor/bin/phpmd src/ text codesize --reportfile ${BUILD_ARTIFACTS_DIRECTORY}phpmd.log --suffixes php --ignore-violations-on-exit
copypaste:
	./vendor/bin/phpcpd src  | tee ${BUILD_ARTIFACTS_DIRECTORY}phpcpd.log
phpstan:
	@if ./vendor/bin/phpstan --autoload-file=vendor/autoload.php analyse src/ | tee ${BUILD_ARTIFACTS_DIRECTORY}phpstan.log; then exit 0; fi
security:
	./bin/console se:c | tee ${BUILD_ARTIFACTS_DIRECTORY}security-check.log
translation:
	./bin/console d:tr de | tee ${BUILD_ARTIFACTS_DIRECTORY}translation-de.log
	./bin/console d:tr en | tee ${BUILD_ARTIFACTS_DIRECTORY}translation-en.log
analyse:
	make codesniff
	make lines
	make mess
	make copypaste
	make phpstan
	make security

# Docker Compose
build:
	make stop
	docker-compose build
	make up
up:
	docker-compose up -d
start:
	docker-compose start
stop:
	docker-compose stop
enter-php:
	docker-compose exec php sh
enter-webpack:
	docker-compose exec webpack sh
ps:
	docker-compose ps

# Database
rebuild:
	bin/console do:da:dr --force --if-exists
	bin/console do:da:cr
	bin/console do:mi:mi -n
diff:
	bin/console do:mi:di
migrate:
	 bin/console do:mi:mi -n

# Cache
cache:
	./bin/console ca:c

# Setup
composer:
	composer install
copy-environment:
	cp .env.dist .env
install:
	make composer
	make build
	make copy-environment
