<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Category extends Entity
{
    protected array $patchable = [];

    public function setPatchable(array|string $field, bool $set) {
    }

    public function getPatchable(): array {
        return [];
    }

    public function isPatchable(string $field): bool {
        return true;
    }
}
