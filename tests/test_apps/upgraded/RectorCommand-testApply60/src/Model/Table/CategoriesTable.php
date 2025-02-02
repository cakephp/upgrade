<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CategoriesTable extends Table
{
    public function setTable(string $table): static
    {
        return parent::setTable($table);
    }

    public function setEntityClass(string $name): static
    {
        return parent::setEntityClass($name);
    }
}
