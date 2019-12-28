.PHONY: install-dev test check-cs

DEV_DEPENDENCIES = cakephp/cakephp:^4.0 \
  cakephp/cakephp-codesniffer:^4.0 \
  mikey179/vfsstream:^1.6 \
  phpunit/phpunit:^8.4

install-dev:
	composer require --dev $(DEV_DEPENDENCIES)

test: install-dev
	vendor/bin/phpunit

check-cs: install-dev
	composer cs-check
