<?php

use Cake\Database\Query\SelectQuery;
use function Cake\Database\Expression\{comparison, gt, is, lt};

function filterArticles(SelectQuery $query, $minimum, $deleted)
{
    return $query->where([
        ['id' => gt($minimum)],
        ['id' => lt(100)],
        'deleted' => is($deleted),
        'metadata' => comparison('@>', '{"active":true}'),
    ]);
}
