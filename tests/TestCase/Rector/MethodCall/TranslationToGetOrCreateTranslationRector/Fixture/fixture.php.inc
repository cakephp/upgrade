<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\TranslationToGetOrCreateTranslationRector\Fixture;

use Cake\ORM\Entity;

class Article extends Entity
{
}

class ArticlesController
{
    public function edit()
    {
        $article = new Article();

        // Should transform: Entity->translation()
        $translation = $article->translation('fra');

        return $article;
    }
}

?>
-----
<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\TranslationToGetOrCreateTranslationRector\Fixture;

use Cake\ORM\Entity;

class Article extends Entity
{
}

class ArticlesController
{
    public function edit()
    {
        $article = new Article();

        // Should transform: Entity->translation()
        $translation = $article->getOrCreateTranslation('fra');

        return $article;
    }
}

?>
