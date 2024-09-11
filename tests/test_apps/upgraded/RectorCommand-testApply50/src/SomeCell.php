<?php
declare(strict_types=1);

class SomeCell extends \Cake\View\Cell {
    protected array $_validCellOptions;

    public function setSerialize(): void
    {
        $this->viewBuilder()->setOption('serialize', 'result');
    }
}
