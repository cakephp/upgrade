# CakePHP Upgrade tool

[![CI](https://github.com/cakephp/upgrade/actions/workflows/ci.yml/badge.svg)](https://github.com/cakephp/upgrade/actions/workflows/ci.yml)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/cakephp/upgrade/license.svg)](LICENSE)

Upgrade tools for CakePHP meant to facilitate migrating between CakePHP 4.x
versions, from CakePHP 4.x to CakePHP 5.x, and between CakePHP 5.x versions.
This repository should be used as a standalone application and *not* as a plugin.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Upgrading between CakePHP 4.x versions](#upgrading-between-cakephp-4x-versions)
- [Upgrading from CakePHP 4.x to CakePHP 5.0](#upgrading-from-cakephp-4x-to-cakephp-50)
- [Upgrading between CakePHP 5.x versions](#upgrading-between-cakephp-5x-versions)
- [Additional Rulesets](#additional-rulesets)
- [Upgrading from CakePHP 3.x to CakePHP 4.x](#upgrading-from-cakephp-3x-to-cakephp-4x)
- [Development](#development)

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

Note: Don't point the tool directly to your ROOT, as this will most likely cause memory fails.
Instead, always point it to the respective sub-directories, e.g. `src`, `tests` and `config`.

## Upgrading between CakePHP 4.x versions

When upgrading between CakePHP 4.x versions the `rector` command can automate
updates for many deprecation warnings. To get the most value from the `rector`
command you should be sure to add as many typehints or parameter docblock
annotations as you can. Without these annotations or typehints rector will not
be able to be as effective as it cannot infer types.

```bash
cd /path/to/upgrade

# To apply upgrade rules from 4.3 to 4.4
bin/cake upgrade rector --rules cakephp44 /path/to/your/app/src
```

There are rules included for:

- cakephp40
- cakephp41
- cakephp42
- cakephp43
- cakephp44
- cakephp45

## Upgrading from CakePHP 4.x to CakePHP 5.0

When upgrading from CakePHP 4.x to CakePHP 5.0, use the `cakephp50` ruleset to
automate many of the required changes:

```bash
cd /path/to/upgrade

# Apply upgrade rules from 4.x to 5.0
bin/cake upgrade rector --rules cakephp50 /path/to/your/app/src
bin/cake upgrade rector --rules cakephp50 /path/to/your/app/tests
bin/cake upgrade rector --rules cakephp50 /path/to/your/app/config
```

## Upgrading between CakePHP 5.x versions

When upgrading between CakePHP 5.x versions, use the appropriate ruleset for
the target version:

```bash
cd /path/to/upgrade

# To apply upgrade rules from 5.2 to 5.3
bin/cake upgrade rector --rules cakephp53 /path/to/your/app/src
```

There are rules included for:

- cakephp50
- cakephp51
- cakephp52
- cakephp53

## Additional Rulesets

The upgrade tool also includes rulesets for related tools and libraries:

- **chronos3** - Upgrade to Chronos 3.x
- **migrations45** - Upgrade to Migrations 4.5
- **migrations50** - Upgrade to Migrations 5.0
- **phpunit80** - Upgrade to PHPUnit 8.0

```bash
cd /path/to/upgrade

# Apply Chronos 3 upgrade rules
bin/cake upgrade rector --rules chronos3 /path/to/your/app/src

# Apply Migrations 4.5 upgrade rules
bin/cake upgrade rector --rules migrations45 /path/to/your/app/config

# Apply Migrations 5.0 upgrade rules
bin/cake upgrade rector --rules migrations50 /path/to/your/app/config

# Apply PHPUnit 8.0 upgrade rules
bin/cake upgrade rector --rules phpunit80 /path/to/your/app/tests
```

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

To install dev-dependencies use `composer setup`. Then you will be able to
run `composer test` and `composer cs-check` etc.

See also `Makefile` for more shortcut commands.
