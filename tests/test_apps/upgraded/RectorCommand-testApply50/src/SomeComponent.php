<?php
declare(strict_types=1);

class SomeComponent extends \Cake\Controller\Component
{
    protected array $components;

    protected array $_defaultConfig = [];

    public function tableRegistryTest(): void
    {
        \Cake\ORM\TableRegistry::getTableLocator()->get('MyTable');
    }
}
