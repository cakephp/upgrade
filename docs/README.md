# CakePHP Upgrade app "Deluxe Edition"

## Tasks available

### locations
Move files/directories around. Run this *before* adding namespaces with the namespaces command.

### namespaces
Add namespaces to files based on their file path. Only run this *after* you have moved files.

### app_uses
Replace App::uses() with use statements.

### model_to_table
Move models into Table directory.

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

### fixture_loading
Loading of fixtures is adjusted to new convention:
// Before
public $fixtures = ['app.user_role', 'plugin.foo_bar.user']

// After
public $fixtures = ['app.user_roles', 'plugin.foo_bar.users']
```

### locale
Update locale and PO file folder.

### i18n
Update translation functions regarding placeholders.
```php
// Before
__('Edit %s', $name)

// After
__('Edit {0}', $name)
```

### url
Array URL fixes.
```php
// Before
['controller' => 'my_account', 'action' => 'register_me']

// After
['controller' => 'MyAccount', 'action' => 'registerMe']
```

### skeleton
Adds basic skeleton files and folders from the "app" repository.
Use `-o` to also overwrite existing ones and diff the changes (merging in updates this way is super easy).

### prefixed_templates
Move view templates for prefixed actions to prefix subfolder. eg. `Users/admin_index.ctp` becomes `Admin/Users/index.ctp`.
By default `admin` prefix is handled, you can run this task for other routing prefixes using `--prefix=other` as well.

### custom
Custom fixes.

### cleanup
Should be run last.
