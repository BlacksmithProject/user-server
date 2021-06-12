dev-from-scratch: composer

composer:
	-rm -rf ./vendor
	-a | composer install

phpcs:
	./vendor/bin/phpcs

phpcs-fix:
	./vendor/bin/phpcbf

psalm:
	./vendor/bin/psalm --show-info=true

test:
	./vendor/bin/phpunit --color tests

infection:
	./vendor/bin/infection --threads=4

test-CI:
	./vendor/bin/phpunit --coverage-clover=coverage.clover

CI: psalm test-CI

ALL: phpcs psalm test infection

release:
	git add CHANGELOG.md && git commit -m "release($(VERSION))" && git tag $(VERSION) && git push && git push --tags

.PHONY: dev-from-scratch composer phpcs phpcs-fix psalm test infection test-CI CI ALL release
