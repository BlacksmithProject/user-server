dev-from-scratch: start composer #database database-test

start:
	docker-compose up -d

stop:
	docker-compose down

composer:
	docker exec -it php -r| rm -rf ./vendor
	docker exec -it php composer install

#database:
#	docker exec -it php bin/console d:d:d --force
#	docker exec -it php bin/console d:d:c
#	docker exec -it php bin/console d:m:m --no-interaction
#
#database-test:
#	docker exec -it -e APP_ENV=test php bin/console d:d:d --force
#	docker exec -it -e APP_ENV=test php bin/console d:d:c
#	docker exec -it -e APP_ENV=test php bin/console d:m:m --no-interaction

phpcs:
	docker exec -it php ./vendor/bin/phpcs

phpcs-fix:
	docker exec -it php ./vendor/bin/phpcbf

psalm:
	docker exec -it php ./vendor/bin/psalm --show-info=true

test:
	docker exec -it php ./vendor/bin/phpunit --color tests

infection:
	docker exec -it php ./vendor/bin/infection --threads=4

test-CI:
	docker exec -it php ./vendor/bin/phpunit --coverage-clover=coverage.clover

CI: psalm test-CI

ALL: phpcs psalm test infection

release:
	git add CHANGELOG.md && git commit -m "release($(VERSION))" && git tag $(VERSION) && git push && git push --tags

.PHONY: dev-from-scratch start stop composer phpcs phpcs-fix psalm test infection test-CI CI ALL release #database database-test
