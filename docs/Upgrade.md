## Upgrade command

### Develop tasks

Add a ` {Name}Task` class inside
```
Task/CakeXY/
```
E.g. `Task/Cake50/MySpecialTask.php`

Make it either extend
- `FileTaskInterface` if this is supposed to be run on each file with the specific extension or path
- `RepoTaskInterface` if this is supposed to be run only once per repo

In the end you just call
```php
$this->persistFile($filePath, $content, $newContent);
```
to persist the changes done to the file content.

Important: Do not write yourself to disk, as dry-run mode would otherwise by impaired.
