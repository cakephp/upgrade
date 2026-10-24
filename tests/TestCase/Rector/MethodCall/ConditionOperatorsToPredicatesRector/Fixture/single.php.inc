<?php

namespace App;

use Cake\Database\Query\SelectQuery;

function filterOne(SelectQuery $query, $minimum)
{
    return $query->where(['id >' => $minimum]);
}
-----
<?php

namespace App;

use Cake\Database\Query\SelectQuery;
use function Cake\Database\Expression\gt;

function filterOne(SelectQuery $query, $minimum)
{
    return $query->where(['id' => gt($minimum)]);
}
