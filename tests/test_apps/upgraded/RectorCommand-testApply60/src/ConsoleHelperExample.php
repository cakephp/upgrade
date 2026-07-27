<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\Command\Helper\TableHelper;

class ConsoleHelperExample
{
    private \Cake\Console\Helper\TableHelper $Table;

    public function render(): void
    {
        $this->Table->output([['a', 'b']]);
    }
}
