<?php

namespace Cake\Upgrade\Test\TestCase\Rector\MethodCall\TranslationToGetOrCreateTranslationRector\Fixture;

class SomeTranslationService
{
    public function translation(string $lang): ?string
    {
        return null;
    }
}

class SomeController
{
    public function index()
    {
        $service = new SomeTranslationService();

        // Should NOT transform: not an Entity subclass
        $result = $service->translation('fra');

        return $result;
    }
}

?>
