<?php
declare(strict_types=1);

namespace App;

use Cake\TestSuite\Fixture\TestFixture;
use Cake\TestSuite\TestCase;

class ArticlesFixture extends TestFixture
{
    public string $connection = 'test';
    public string $table = 'articles';
    public array $records = [];
}

class ArticlesTest extends TestCase
{
    protected array $fixtures = ['app.Articles'];
}
