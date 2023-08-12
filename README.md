# CakePHP Upgrade tool 

![Build Status](https://github.com/cakephp/upgrade/actions/workflows/ci.yml/badge.svg?branch=master)

Upgrade tools for CakePHP meant to facilitate migrating between CakePHP 4.x
versions and from CakePHP 4.x to CakePHP 5.x. This repository should be used as a standalone
application and *not* as a plugin.

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

The upgrade tool provides a standalone application that can be used to upgrade
other applications or cakephp plugins. Each of the subcommands accepts a path
that points to the application you want to upgrade.

## Upgrading between CakePHP 4.x versions

When upgrading between CakePHP 4.x versions the `rector` command can automate
updates for many deprecation warnings. To get the most value from the `rector`
command you should be sure to add as many typehints or parameter docblock
annotations as you can. Without these annotations or typehints rector will not
be able to be as effective as it cannot infer types.

```bash
cd /path/to/upgrade

# To apply upgrade rules from 4.4 to 4.5
bin/cake upgrade rector --rules cakephp45 /path/to/your/app/src
```

There are rules included for:

- cakephp41
- cakephp42
- cakephp43
- cakephp44
- cakephp45

## Upgrading from CakePHP 3.x to CakePHP 4.x

The upgrade tool is intended to be run *before* you update your application's
dependencies to 4.0. The rector based tasks will not run correctly if your
application already has its dependencies updated to 4.x.

Once you have installed the upgrade tool dependencies there are several commands
you should run:

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
