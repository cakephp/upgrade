# CakePHP Upgrade tool 
[![Build Status](https://api.travis-ci.org/dereuromark/upgrade.svg?branch=develop)](https://travis-ci.org/dereuromark/upgrade)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)


Upgrade tool as standalone application for CakePHP meant to facilitate migrating from CakePHP 2.x to 3.x.

It also supports the minor upgrades in 3.x - up until 3.5.

**Warning** This tool is still under development and doesn't handle all aspects of migrating.

Note: When migrating from 1.x to 2.x you might want to look in the old [cakephp-upgrade plugin](https://github.com/dereuromark/cakephp-upgrade) instead.

## Installation

This plugin is standalone. Do not try to mix this with your existing app. Instead, put it somewhere completely separate from it.
Best to clone it (git clone ....).

After downloading/cloning the upgrade tool, you need to install dependencies with `composer`

```bash
php composer.phar install
```

Once dependencies are installed you can start using the `upgrade` shell.


## IMPORTANT NOTICE

This tool is a split-off off the original CakePHP upgrade tool and provides additional fixers:
- Templates
- Url
- Locale (fixing locale files)
- Model to Table (making the model files to Table class files)
- FixtureLoading
- Custom (tons of custom fixes)

Feel free to manually port those things back into the core one.

## Usage

The upgrade tool provides a standalone application that can be used to upgrade
other applications or cakephp plugins. Each of the subcommands accepts a path
that points to the application you want to upgrade.

```bash
cd /path/to/upgrade
bin/cake upgrade all /home/mark/Sites/my-app
bin/cake upgrade skeleton /home/mark/Sites/my-app
```
The first command would run all the tasks at once on `/home/mark/Sites/my-app`,
which is probably the way most people will want to use it.
Additionally the second command would run the `skeleton` task on `/home/mark/Sites/my-app`.
This command is not included in `all` as it is only necessary for apps. Plugins don't need it.

For plugins, point it to the root and use the `-p` plugin syntax:
```bash
// Upgrading 2.x /home/mark/Sites/my-app/Plugin/MyPlugin/ 
bin/cake upgrade all -p MyPlugin /home/mark/Sites/my-app
```

It is recommended that you keep your application in version control, and keep
backups of before using the upgrade tool.

### Order matters

Several of the commands have dependencies on each other and should be run in a specific order. It
is recommended that you run the following commands first before using other commands:

```bash
bin/cake upgrade locations [path]
bin/cake upgrade namespaces [path]
bin/cake upgrade app_uses [path]
```

Once these three commands have been run, you can use the other commands in any order.
The `all` command already used the right order by default.

## Tasks Available
For detailed task descriptions and usage see [docs](docs).

Also note the [wiki](https://github.com/dereuromark/upgrade/wiki) with more recent tips.


## Using Tagged Releases
For simplicity the tool uses the latest dev-master branches of framework and app repos (or you can get there using `composer update`).
If you want to use the stable releases instead, just switch those in the composer.json and then run `composer update` again.
