<?php

use Cake\Database\Query\SelectQuery;

function filterArticles(SelectQuery $query, $minimum, $deleted)
{
    return $query->where([
        'id >' => $minimum,
        'id <' => 100,
        'deleted IS' => $deleted,
        'metadata @>' => '{"active":true}',
    ]);
}
