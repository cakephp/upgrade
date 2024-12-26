<?php
declare(strict_types=1);

class ORMMethods
{
    public function test()
    {
        $table = new Cake\ORM\Table();

        $table->newEntity([], [
            'associated' => [
                'Articles' => [
                    'accessibleFields' => ['title', 'body']
                ]
            ],
        ]);
    }
}
