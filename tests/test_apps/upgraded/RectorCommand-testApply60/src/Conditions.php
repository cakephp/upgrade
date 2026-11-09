<?php

use Cake\Database\Query\SelectQuery;
use function Cake\Database\Expression\{comparison, equals, greaterThan, lessThan};

function filterArticles(SelectQuery $query, $minimum, $deleted)
{
    return $query->where([
        ['id' => greaterThan($minimum)],
        ['id' => lessThan(100)],
        'deleted' => equals($deleted),
        'metadata' => comparison('@>', '{"active":true}'),
    ]);
}
