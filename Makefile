dev-from-scratch: composer

composer:
	-rm -rf ./vendor
	-a | composer install

phpcs:
	./vendor/bin/phpcs

stan:
	./vendor/bin/phpstan analyse

test:
	./vendor/bin/phpunit --color tests

infection:
	./vendor/bin/infection --threads=4

test-CI:
	./vendor/bin/phpunit --coverage-clover=coverage.clover

CI: stan test-CI

release:
	git add CHANGELOG.md && git commit -m "release($(VERSION))" && git tag $(VERSION) && git push && git push --tags

.PHONY: dev-from-scratch composer phpcs stan test infection test-CI CI release
