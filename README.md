# CakePHP Upgrade tool 
[![Build Status](https://api.travis-ci.org/dereuromark/upgrade.svg?branch=develop)](https://travis-ci.org/dereuromark/upgrade)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)


Upgrade tool as standalone application for CakePHP meant to facilitate migrating from CakePHP 2.x to 3.x.

It also supports the minor upgrades in 3.x - up until 3.5.

**Warning** This tool is still under development and doesn't handle all aspects of migrating.

Note: When migrating from 1.x to 2.x you might want to look in the old [cakephp-upgrade plugin](https://github.com/dereuromark/cakephp-upgrade) instead.

## Installation

After downloading/cloning the upgrade tool, you need to install dependencies with `composer`

```bash
php composer.phar install
```

Once dependencies are installed you can start using the `upgrade` shell.


## IMPORTANT NOTICE

This tool is a split-off off the original CakePHP upgrade tool and provides additional fixers:
- Locale (fixing locale files)
- Model to Table (making the model files to Table class files)
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

### locations
Move files/directories around. Run this *before* adding namespaces with the namespaces command.

### namespaces
Add namespaces to files based on their file path. Only run this *after* you have moved files.

### app_uses
Replace App::uses() with use statements

### rename_classes
Rename classes that have been moved/renamed. Run after replacing App::uses().

### rename_collections
Rename HelperCollection, ComponentCollection, and TaskCollection. Will also
rename component constructor arguments and \_Collection properties on all
objects.

### method_names
Updates the method names for a number of methods.

### method_signatures
Updates the method signatures for a number of methods.

### fixtures
Update fixtures to use new index/constraint features. This is necessary before running tests.

### tests
Update test cases regarding fixtures.

### i18n
Update translation functions regarding placeholders.

### skeleton
Add basic skeleton files and folders from the "app" repository.

### prefixed_templates
Move view templates for prefixed actions to prefix subfolder. eg. Users/admin_index.ctp becomes Admin/Users/index.ctp.
By default `admin` prefix is handled, you can run this task for other routing prefixes using `--prefix=other` as well.
