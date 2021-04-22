# CakePHP Upgrade tool 

![Build Status](https://github.com/cakephp/upgrade/actions/workflows/ci.yml/badge.svg?branch=master)

Upgrade tools for CakePHP meant to facilitate migrating from CakePHP 3.8+ to
4.0.0. This repository should be used as a standalone application and *not* as
a plugin.

## Installation

First clone this repository or download a zipball:

```bash
git clone git://github.com/cakephp/upgrade
```

Then to install dependencies with `composer`

```bash
php composer.phar install --no-dev
```

## Usage

The upgrade tool is intended to be run *before* you update your application's
dependencies to 4.0. The rector based tasks will not run correctly if your
application already has its dependencies updated to 4.x.

The upgrade tool provides a standalone application that can be used to upgrade
other applications or cakephp plugins. Each of the subcommands accepts a path
that points to the application you want to upgrade.

```bash
cd /path/to/upgrade

# Run all upgrade tasks at once.
bin/cake upgrade /home/mark/Sites/my-app

# OR run upgrade tasks individually.
# Rename locale files
bin/cake upgrade file_rename locales /home/mark/Sites/my-app

# Rename template files
bin/cake upgrade file_rename templates /home/mark/Sites/my-app

# Run rector rules.
bin/cake upgrade rector /home/mark/Sites/my-app/src
bin/cake upgrade rector /home/mark/Sites/my-app/tests
bin/cake upgrade rector /home/mark/Sites/my-app/config
```

## Development

To ease installation & usage, this package does not
use `require-dev` in `composer.json` as the installed PHPUnit and
CakePHP packages cause conflicts with the rector tasks.

To install dev-dependencies use `make install-dev`. Then you will be able to
run `vendor/bin/phpunit`. You can also use `make test` to install dependencies
and run tests.
