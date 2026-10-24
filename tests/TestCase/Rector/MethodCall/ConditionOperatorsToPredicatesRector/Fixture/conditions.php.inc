<?php

use Cake\Database\Connection;
use Cake\Database\Expression\AggregateExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query\SelectQuery;
use Cake\ORM\Table;

function conditions(SelectQuery $query, QueryExpression $exp, AggregateExpression $aggregate, Table $table, Connection $connection, $value)
{
    $query->where(['id >' => $value, 'id <' => 100, 'OR' => ['title LIKE' => '%cake%', 'deleted IS' => $value]]);
    $query->having(['COUNT(id) >=' => 2]);
    $query->innerJoin('authors', ['authors.active IS NOT' => null]);
    $query->join(['a' => ['table' => 'authors', 'conditions' => ['a.id !=' => 0]]]);
    $exp->add(['id IN' => [1, 2], 'data @>' => $value]);
    $aggregate->filter(['rating >=' => 3]);
    new QueryExpression(['id <>' => $value]);
    $table->exists(['name IS NOT DISTINCT FROM' => $value]);
    $table->find('all', conditions: ['name NOT LIKE' => '%spam%']);
    $table->updateAll(['title' => 'title'], ['id NOT IN' => [3, 4]]);
    $query->where(fn () => ['id <=' => $value]);
    new QueryExpression(types: ['id' => 'integer'], conditions: ['id >' => $value]);
    $query->where(types: ['id' => 'integer'], conditions: ['id >' => $value]);
    $connection->update('articles', ['title' => 'updated'], ['id >' => $value]);
    $connection->delete('articles', ['id <' => $value]);
    $conditions = ['id >' => $value];
    $query->where($conditions);
}
-----
<?php

use Cake\Database\Connection;
use Cake\Database\Expression\AggregateExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query\SelectQuery;
use Cake\ORM\Table;
use function Cake\Database\Expression\{comparison, equals, greaterThan, greaterThanOrEquals, in, isNotDistinctFrom};
use function Cake\Database\Expression\{lessThan, lessThanOrEquals, like, notEquals, notIn, notLike};

function conditions(SelectQuery $query, QueryExpression $exp, AggregateExpression $aggregate, Table $table, Connection $connection, $value)
{
    $query->where([['id' => greaterThan($value)], ['id' => lessThan(100)], 'OR' => ['title' => like('%cake%'), 'deleted' => equals($value)]]);
    $query->having(['COUNT(id)' => greaterThanOrEquals(2)]);
    $query->innerJoin('authors', ['authors.active' => notEquals(null)]);
    $query->join(['a' => ['table' => 'authors', 'conditions' => ['a.id' => notEquals(0)]]]);
    $exp->add(['id' => in([1, 2]), 'data' => comparison('@>', $value)]);
    $aggregate->filter(['rating' => greaterThanOrEquals(3)]);
    new QueryExpression(['id' => notEquals($value)]);
    $table->exists(['name' => isNotDistinctFrom($value)]);
    $table->find('all', conditions: ['name' => notLike('%spam%')]);
    $table->updateAll(['title' => 'title'], ['id' => notIn([3, 4])]);
    $query->where(fn () => ['id' => lessThanOrEquals($value)]);
    new QueryExpression(types: ['id' => 'integer'], conditions: ['id' => greaterThan($value)]);
    $query->where(types: ['id' => 'integer'], conditions: ['id' => greaterThan($value)]);
    $connection->update('articles', ['title' => 'updated'], ['id' => greaterThan($value)]);
    $connection->delete('articles', ['id' => lessThan($value)]);
    $conditions = ['id' => greaterThan($value)];
    $query->where($conditions);
}
