<?php

namespace PropertyTypes;

class CustomComponent extends \Cake\Controller\Component
{
    protected $_defaultConfig = [];

    protected $unconfigured = [];
}

class TypedComponent extends \Cake\Controller\Component
{
    protected array $_defaultConfig = [];
}

class UnrelatedClass
{
    protected $_defaultConfig = [];
}

class GroupedComponent extends \Cake\Controller\Component
{
    protected $_defaultConfig = [], $unconfigured = 'value';
}
-----
<?php

namespace PropertyTypes;

class CustomComponent extends \Cake\Controller\Component
{
    protected array $_defaultConfig = [];

    protected $unconfigured = [];
}

class TypedComponent extends \Cake\Controller\Component
{
    protected array $_defaultConfig = [];
}

class UnrelatedClass
{
    protected $_defaultConfig = [];
}

class GroupedComponent extends \Cake\Controller\Component
{
    protected $_defaultConfig = [], $unconfigured = 'value';
}
