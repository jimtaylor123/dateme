.PHONY: up down nginx php phplog nginxlog db coverage vendor

MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PWD := $(dir $(MAKEPATH))
CONTAINERS := $(shell docker ps -a -q -f "name=date-me-api*")

up:
	docker-compose up -d --build

down:
	docker-compose down

nginx:
	docker exec -it date-me-nginx-container bash

php: 
	docker exec -it date-me-php-container bash

phplog: 
	docker logs date-me-php-container

nginxlog:
	docker logs date-me-nginx-container

db:
	docker-compose exec mysql mysql -e 'DROP DATABASE IF EXISTS date_me_api ; CREATE DATABASE date_me_api;'
	docker-compose exec mysql sh -c "mysql date_me_api < docker-entrypoint-initdb.d/database.sql"

coverage:
	docker-compose exec php-fpm sh -c "./vendor/bin/phpunit --coverage-text --coverage-html coverage"

vendor:
	docker-compose exec php-fpm sh -c "composer install"
