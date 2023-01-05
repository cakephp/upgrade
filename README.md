# CakePHP Upgrade tool
[![Build Status](https://api.travis-ci.org/dereuromark/upgrade.svg?branch=develop)](https://travis-ci.org/dereuromark/upgrade)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)


Upgrade tool as standalone application for CakePHP meant to facilitate migrating
- ~~from CakePHP 2.x to 3.x.~~ [EOL]
- ~~supports the minor upgrades in 3.x - up until currently 3.8+.~~ [EOL]
- ~~supports basic stuff for 4.x~~ [See cake4 branch]
- supports basic stuff for 5.x + extension development possible

**Warning** This tool is still under development and doesn't handle all aspects of migrating.

---

### CakePHP 5

The brand new upgrade command of this tool provides a configurable approach.
Define sets/levels and run it over your app or plugin:

```
bin/cake upgrade files /path/to/repo -v -d
```
Keep verbose and dry-run for checking if it works as expected, then apply your changes for real.

This tool works best in combination with the official [upgrade](https://github.com/cakephp/upgrade/) tool and its rector based approaches.
- dereuromark/upgrade handles basic cases and non-PHP files
- cakephp/upgrade handles PHP class files via rector (requires valid PHP files)

If rector fails or cannot handle your app, you can try to use this tool completely by defining more regex
based rules for example.

You can check active vs available tasks using `--help` together with `-v`.
It will list all available ones, active ones are in green.

For docs on this check [here](docs/Upgrade.md).

---

## Installation

This plugin is standalone. Do not try to mix this with your existing app. Instead, put it somewhere completely separate from it.
Best to clone it (git clone ....).

After downloading/cloning the upgrade tool, you need to install dependencies with `composer`

```bash
composer install
```

Once dependencies are installed you can start using the `upgrade` shell.

Note: If you want to get the latest master, you can run `composer update` at your own risk.
It will download also all recent changes done.
Alternatively, you can lock it down to a stable version and then update.

## IMPORTANT NOTICE

This tool is a split-off off the original CakePHP upgrade tool and provides additional fixers:
- Templates
- Url
- Locale (fixing locale files)
- Model to Table (making the model files to Table class files)
- Fixture loading and casing
- Custom (tons of custom fixes)

Feel free to manually port those things back into the core one.

## Usage

### Upgrade command
All at once:
```bash
bin/cake upgrade /home/mark/Sites/my-app
```
### Rector command
```bash
bin/cake upgrade rector /home/mark/Sites/my-app/src
bin/cake upgrade rector /home/mark/Sites/my-app/tests
bin/cake upgrade rector /home/mark/Sites/my-app/config
```

### Upgrade legacy shell
See cake4 branch.
