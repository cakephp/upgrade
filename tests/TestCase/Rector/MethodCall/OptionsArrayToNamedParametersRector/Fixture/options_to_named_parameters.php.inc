<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\OptionsArrayToNamedParametersRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\OptionsArrayToNamedParametersRector\Source;

function optionsToNamedParameters()
{
    $value = 1;
    $instance = new Source\ConfigurableClass();

    $instance->find();
    $instance->find('all');
    $instance->find('list', ['fields' => ['name']]);
    $instance->find('all', [
        'conditions' => ['Articles.id' => $value],
        'order' => ['Articles.id' => 'asc'],
    ]);

    // Preserve named parameters should they exist.
    $instance->find('all',
        conditions: ['Articles.id' => $value],
        order: ['Articles.id' => 'asc'],
    );
    $instance->get(1, contain: ['Articles' => ['Categories']]);

    // Array values are not spread
    $options = ['conditions' => ['Articles.id' => $value]];
    $instance->find('all', $options);

    // Can modify get as well.
    $instance->get(1);
    $instance->get(1, ['key' => 'cache-this']);
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\OptionsArrayToNamedParametersRector\Fixture;

use Cake\Upgrade\Rector\Tests\Rector\MethodCall\OptionsArrayToNamedParametersRector\Source;

function optionsToNamedParameters()
{
    $value = 1;
    $instance = new Source\ConfigurableClass();

    $instance->find();
    $instance->find('all');
    $instance->find('list', fields: ['name']);
    $instance->find('all',
    conditions: ['Articles.id' => $value],
    order: ['Articles.id' => 'asc']);

    // Preserve named parameters should they exist.
    $instance->find('all',
        conditions: ['Articles.id' => $value],
        order: ['Articles.id' => 'asc'],
    );
    $instance->get(1, contain: ['Articles' => ['Categories']]);

    // Array values are not spread
    $options = ['conditions' => ['Articles.id' => $value]];
    $instance->find('all', $options);

    // Can modify get as well.
    $instance->get(1);
    $instance->get(1, cacheKey: 'cache-this');
}

?>
