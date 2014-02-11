# CakePHP Upgrade tool

A tool for upgrading from 2.x CakePHP code to 3.x

# Behold

```
-> src/Console/cake upgrade locations
A shell to help automate upgrading from CakePHP 3.0 to 2.x.
Be sure to
have a backup of your application before running these commands.

Usage:
cake upgrade [subcommand] [options] <path>

Subcommands:

locations           Move files/directories around. Run this *before*
                    adding namespaces with the namespaces command.
namespaces          Add namespaces to files based on their file path.
                    Only run this *after* you have moved files.
rename_classes      Rename classes that have been moved/renamed. Run
                    after replacing App::uses().
rename_collections  Rename HelperCollection, ComponentCollection, and
                    TaskCollection. Will also rename component constructor arguments and
                    _Collection properties on all objects.
app_uses            Replace App::uses() with use statements
fixtures            Update fixtures to use new index/constraint
                    features. This is necessary before running tests.

To see help on a subcommand use `cake upgrade [subcommand] --help`

Options:

--help, -h       Display this help.
--verbose, -v    Enable verbose output.
--quiet, -q      Enable quiet output.
--plugin, -p     The plugin to update. Only the specified plugin will be
                 updated.
--dry-run, -d    Dry run the update, no files will actually be modified.
--git            Perform git operations. eg. git mv instead of just
                 moving files.
--namespace      Set the base namespace you want to use. Defaults to App
                 or the plugin name.
--exclude        Comma separated list of top level diretories to
                 exclude.

Arguments:

path  Path to code to upgrade
```
