<?php
declare(strict_types=1);

class QueryUpgrade {
    public function finders() {
        $articles = new \Cake\ORM\Table();

        /** @var \Cake\ORM\Query $query */
        $query = $articles->find('all', conditions: ['Articles.slug' => 'test']);
        $query->find('list', fields: ['id', 'title']);

        $article = $articles->get(1, cacheKey: 'cache-key', contain: ['Users']);
    }
}
