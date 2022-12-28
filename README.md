# CakePHP Upgrade tool
[![Build Status](https://api.travis-ci.org/dereuromark/upgrade.svg?branch=develop)](https://travis-ci.org/dereuromark/upgrade)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)


Upgrade tool as standalone application for CakePHP meant to facilitate migrating
- ~~from CakePHP 2.x to 3.x.~~ [EOL]
- ~~supports the minor upgrades in 3.x - up until currently 3.8+.~~ [EOL]
- supports basic stuff for 4.x
- supports basic stuff for 5.x + extension development possible

**Warning** This tool is still under development and doesn't handle all aspects of migrating.

---

**CakePHP 5**

The brand new upgrade command of this tool provides a configurable approach.
Define sets/levels and run it over your app or plugin:

```
bin/cake upgrade /path/to/repo -v -d
```
Keep verbose and dry-run for checking if it works as expected, then apply your changes for real.

This tool works best in combination with the official [upgrade](https://github.com/cakephp/upgrade/) tool and its rector based approaches.
- dereuromark/upgrade handles basic cases and non-PHP files
- cakephp/upgrade handles PHP class files via rector (requires valid PHP files)

If rector fails or cannot handle your app, you can try to use this tool completely by defining more regex
based rules for example.

For docs on this check [here](docs/Upgrade.md).

---

**CakePHP 4**

Tasks available now for CakePHP4 (and super helpful):
- method_names
- method_signatures
- skeleton

Fore more please look into [wiki](https://github.com/dereuromark/upgrade/wiki/Tips#upgrading-to-4x) for hot new tips, also add yours!

---

**CakePHP 3**
Please look into [this article](https://www.dereuromark.de/2018/03/14/cakephp-3-6-is-coming/) for Upgrading applications for 3.6+. The new tool rector seems to be very promising.

---

**CakePHP 2**
When migrating from 1.x to 2.x you might want to look in the old [cakephp-upgrade plugin](https://github.com/dereuromark/cakephp-upgrade) instead.


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
```bash
bin/cake upgrade /home/mark/Sites/my-app
```

### Upgrade legacy shell
The upgrade tool provides a standalone application that can be used to upgrade
other applications or cakephp plugins. Each of the subcommands accepts a path
that points to the application you want to upgrade.

```bash
bin/cake upgrade_legacy all /home/mark/Sites/my-app
bin/cake upgrade_legacy skeleton /home/mark/Sites/my-app
```
The first command would run all the tasks at once on `/home/mark/Sites/my-app`,
which is probably the way most people will want to use it.
Additionally the second command would run the `skeleton` task on `/home/mark/Sites/my-app`.
This command is not included in `all` as it is only necessary for apps. Plugins don't need it.

For plugins, point it to the root and use the `-p` plugin syntax:
```bash
// Upgrading 2.x /home/mark/Sites/my-app/Plugin/MyPlugin/
bin/cake upgrade_legacy all -p MyPlugin /home/mark/Sites/my-app
```

It is recommended that you keep your application in version control, and keep
backups of before using the upgrade tool.

### Order matters

Several of the commands have dependencies on each other and should be run in a specific order. It
is recommended that you run the following commands first before using other commands:

```bash
bin/cake upgrade_legacy locations [path]
bin/cake upgrade_legacy namespaces [path]
bin/cake upgrade_legacy app_uses [path]
```

Once these three commands have been run, you can use the other commands in any order.
The `all` command already used the right order by default.

## Tasks Available
For detailed task descriptions and usage see [docs](docs).

Also note the [wiki](https://github.com/dereuromark/upgrade/wiki) with more recent tips.


## Using Tagged Releases
For simplicity the tool uses the latest dev-master branches of framework and app repos (or you can get there using `composer update`).
If you want to use the stable releases instead, just switch those in the composer.json and then run `composer update` again.
