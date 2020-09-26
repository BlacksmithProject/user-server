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

release:
	git add CHANGELOG.md && git commit -m "release($(VERSION))" && git tag $(VERSION) && git push && git push --tags

.PHONY: dev-from-scratch composer pretty pretty-fix psalm test infection release
