<?php
declare(strict_types=1);

namespace App;

use Cake\TestSuite\Fixture\TestFixture;
use Cake\TestSuite\TestCase;

class ArticlesFixture extends TestFixture
{
    public $connection = 'test';
    public $table = 'articles';
    public $records = [];
}

class ArticlesTest extends TestCase
{
    protected $fixtures = ['app.Articles'];
}
