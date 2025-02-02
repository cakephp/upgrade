<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Category extends Entity
{
    protected array $_accessible = [];

    public function setAccess(array|string $field, bool $set) {
        return $this;
    }

    public function getAccessible(): array {
        return [];
    }

    public function isAccessible(string $field): bool {
        return true;
    }

    public function setSource(string $name) {
        return parent::setSource($name);
    }
}
