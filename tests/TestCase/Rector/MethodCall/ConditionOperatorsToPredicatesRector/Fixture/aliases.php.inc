<?php

namespace App;

use Cake\Database\Query\SelectQuery;
use function App\Filters\gt;
use function Cake\Database\Expression\lt as less;

function filterArticles(SelectQuery $query, $value)
{
    $query->where(['id >' => $value, 'id <' => 100, 'title LIKE' => '%cake%']);
    gt($value);
}

function like()
{
}
-----
<?php

namespace App;

use Cake\Database\Query\SelectQuery;
use function App\Filters\gt;
use function Cake\Database\Expression\{gt as cakeGt, like as cakeLike, lt as less};

function filterArticles(SelectQuery $query, $value)
{
    $query->where([['id' => cakeGt($value)], ['id' => less(100)], 'title' => cakeLike('%cake%')]);
    gt($value);
}

function like()
{
}
