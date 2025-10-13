<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\TextInsertPlaceholderRector\Fixture;

use Cake\Utility\Text;

function textInsertPlaceholder()
{
    // Basic transformation
    $result1 = Text::insert('Hello :name', ['name' => 'World']);

    // Multiple placeholders
    $result2 = Text::insert('Hello :first :last', ['first' => 'John', 'last' => 'Doe']);

    // With underscore
    $result3 = Text::insert('User: :user_name', ['user_name' => 'admin']);

    // Should not change - custom before/after options
    $result4 = Text::insert('Hello :name', ['name' => 'World'], ['before' => ':', 'after' => '']);
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\TextInsertPlaceholderRector\Fixture;

use Cake\Utility\Text;

function textInsertPlaceholder()
{
    // Basic transformation
    $result1 = Text::insert('Hello {name}', ['name' => 'World']);

    // Multiple placeholders
    $result2 = Text::insert('Hello {first} {last}', ['first' => 'John', 'last' => 'Doe']);

    // With underscore
    $result3 = Text::insert('User: {user_name}', ['user_name' => 'admin']);

    // Should not change - custom before/after options
    $result4 = Text::insert('Hello :name', ['name' => 'World'], ['before' => ':', 'after' => '']);
}

?>
