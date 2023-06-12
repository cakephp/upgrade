<?php
declare(strict_types=1);

class QueryUpgrade {
    public function finders() {
        $articles = new \Cake\ORM\Table();

        $query = $articles->find('all', conditions: ['Articles.slug' => 'test']);
        $query->find('list', fields: ['id', 'title'])
            ->orderBy('id')
            ->orderByAsc('id')
            ->orderByDesc('id');

        $articles->query()
            ->orderBy('id')
            ->orderByAsc('id')
            ->orderByDesc('id');

        $article = $articles->get(1, cacheKey: 'cache-key', contain: ['Users']);
    }
}
