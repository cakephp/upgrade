<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\BinaryColumnToVarbinaryRector\Fixture;

use Migrations\BaseMigration;

class CreateTokensTable extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('tokens');

        // Fixed-length binary - should remove 'fixed' => true
        $table->addColumn('hash', 'binary', ['length' => 32, 'fixed' => true]);

        // Variable-length binary - should change to 'varbinary'
        $table->addColumn('data', 'binary', ['length' => 255]);

        // Variable-length binary with other options - should change to 'varbinary'
        $table->addColumn('payload', 'binary', ['length' => 1024, 'null' => true]);

        // Non-binary types should not be affected
        $table->addColumn('name', 'string', ['length' => 100]);
        $table->addColumn('count', 'integer');

        $table->create();
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\BinaryColumnToVarbinaryRector\Fixture;

use Migrations\BaseMigration;

class CreateTokensTable extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('tokens');

        // Fixed-length binary - should remove 'fixed' => true
        $table->addColumn('hash', 'binary', ['length' => 32]);

        // Variable-length binary - should change to 'varbinary'
        $table->addColumn('data', 'varbinary', ['length' => 255]);

        // Variable-length binary with other options - should change to 'varbinary'
        $table->addColumn('payload', 'varbinary', ['length' => 1024, 'null' => true]);

        // Non-binary types should not be affected
        $table->addColumn('name', 'string', ['length' => 100]);
        $table->addColumn('count', 'integer');

        $table->create();
    }
}

?>
