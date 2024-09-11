<?php
declare(strict_types=1);

class SomeCell extends \Cake\View\Cell {
    protected $_validCellOptions;

    public function setSerialize(): void
    {
        $this->set('_serialize', 'result');
    }
}
