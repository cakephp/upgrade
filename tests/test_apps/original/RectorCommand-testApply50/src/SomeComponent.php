<?php
declare(strict_types=1);

class SomeComponent extends \Cake\Controller\Component
{
    protected $components;

    protected $_defaultConfig = [];

    public function tableRegistryTest(): void
    {
        \Cake\ORM\TableRegistry::get('MyTable');
    }
}
