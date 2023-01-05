<?php
declare(strict_types=1);

use Cake\Upgrade\Rector\Rector\Namespace_\AppUsesStaticCallToUseStatementRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    # @see https://github.com/cakephp/upgrade/tree/master/src/Shell/Task
    $rectorConfig->rule(AppUsesStaticCallToUseStatementRector::class);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        # see https://github.com/cakephp/upgrade/blob/756410c8b7d5aff9daec3fa1fe750a3858d422ac/src/Shell/Task/RenameClassesTask.php#L37
        'Cake\Network\Http\HttpSocket' => 'Cake\Network\Http\Client',
        'Cake\Model\ConnectionManager' => 'Cake\Database\ConnectionManager',
        'Cake\TestSuite\CakeTestCase' => 'Cake\TestSuite\TestCase',
        'Cake\TestSuite\Fixture\CakeTestFixture' => 'Cake\TestSuite\Fixture\TestFixture',
        'Cake\Utility\String' => 'Cake\Utility\Text',
        'CakePlugin' => 'Plugin',
        'CakeException' => 'Exception',
        # see https://book.cakephp.org/3/en/appendices/3-0-migration-guide.html#configure
        'Cake\Configure\PhpReader' => 'Cake\Core\Configure\EnginePhpConfig',
        'Cake\Configure\IniReader' => 'Cake\Core\Configure\EngineIniConfig',
        'Cake\Configure\ConfigReaderInterface' => 'Cake\Core\Configure\ConfigEngineInterface',
        # https://book.cakephp.org/3/en/appendices/3-0-migration-guide.html#request
        'CakeRequest' => 'Cake\Network\Request',
    ]);
};
