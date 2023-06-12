<?php
declare(strict_types=1);

class QueryUpgrade {
    public function finders() {
        $articles = new \Cake\ORM\Table();

        $query = $articles->find('all', ['conditions' => ['Articles.slug' => 'test']]);
        $query->find('list', ['fields' => ['id', 'title']])
            ->order('id')
            ->orderAsc('id')
            ->orderDesc('id');

        $articles->query()
            ->order('id')
            ->orderAsc('id')
            ->orderDesc('id');

        $article = $articles->get(1, ['key' => 'cache-key', 'contain' => ['Users']]);
    }
}
