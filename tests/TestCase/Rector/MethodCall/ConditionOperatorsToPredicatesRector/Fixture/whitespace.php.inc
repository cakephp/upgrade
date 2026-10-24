<?php

namespace App;

use Cake\Database\Query\SelectQuery;

function filterWhitespace(SelectQuery $query, $value)
{
    return $query->where([
        '  id  >  ' => 1,
        'name   NOT  LIKE  ' => '%cake%',
        'parent_id IS  NOT' => null,
        'category_id  IS   DISTINCT   FROM' => $value,
        "tenant_id IS \t NOT \n DISTINCT   FROM  " => $value,
        "status\tNOT\tIN\t" => ['archived'],
        'label  NOT   ILIKE  ' => '%Cake%',
        'content NOT   SIMILAR  TO  ' => 'cake',
        'updated IS  ' => null,
        "COALESCE(title, 'two  spaces')  NOT   LIKE  " => '%cake%',
    ]);
}
-----
<?php

namespace App;

use Cake\Database\Query\SelectQuery;
use function Cake\Database\Expression\{comparison, equals, greaterThan, isDistinctFrom, isNotDistinctFrom, notEquals};
use function Cake\Database\Expression\{notIn, notLike};

function filterWhitespace(SelectQuery $query, $value)
{
    return $query->where([
        'id' => greaterThan(1),
        'name' => notLike('%cake%'),
        'parent_id' => notEquals(null),
        'category_id' => isDistinctFrom($value),
        'tenant_id' => isNotDistinctFrom($value),
        'status' => notIn(['archived']),
        'label' => comparison('NOT ILIKE', '%Cake%'),
        'content' => comparison('NOT SIMILAR TO', 'cake'),
        'updated' => equals(null),
        'COALESCE(title, \'two  spaces\')' => notLike('%cake%'),
    ]);
}
