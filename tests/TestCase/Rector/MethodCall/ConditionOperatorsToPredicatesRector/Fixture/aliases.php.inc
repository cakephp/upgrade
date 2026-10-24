<?php

namespace App;

use Cake\Database\Query\SelectQuery;
use function App\Filters\greaterThan;
use function Cake\Database\Expression\lessThan as less;

function filterArticles(SelectQuery $query, $value)
{
    $query->where(['id >' => $value, 'id <' => 100, 'title LIKE' => '%cake%']);
    greaterThan($value);
}

function like()
{
}
-----
<?php

namespace App;

use Cake\Database\Query\SelectQuery;
use function App\Filters\greaterThan;
use function Cake\Database\Expression\{greaterThan as cakeGreaterThan, lessThan as less, like as cakeLike};

function filterArticles(SelectQuery $query, $value)
{
    $query->where([['id' => cakeGreaterThan($value)], ['id' => less(100)], 'title' => cakeLike('%cake%')]);
    greaterThan($value);
}

function like()
{
}
