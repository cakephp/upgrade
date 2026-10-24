<?php

use Cake\Database\Query\SelectQuery;

function unchanged(SelectQuery $query, \Cake\Database\Expression\FunctionExpression $function, $other, $value, $key)
{
    $function->add(['DOW FROM' => 'literal']);
    $function->add(['RANDOM() % 1' => 'literal']);
    $query->where(['Column with spaces' => $value]);
    $other->where(['id >' => $value]);
    $data = ['id >' => $value];
    echo json_encode($data);
    $query->where($data);
    $query->where([$key => $value]);
    $query->where(['id >' => $value, $key => $value]);
    $query->where(['id >' => $value, ...$data]);
    $query->where(['data' => ['id >' => $value]]);
    $query->where(['id' => \Cake\Database\Expression\greaterThan($value)]);
}
