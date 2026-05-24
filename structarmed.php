<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;
use Boundwize\StructArmed\Preset\Presets\Psr4Preset;

return Architecture::define()
    ->skip([
        Psr4Preset::CLASSES_MUST_MATCH_COMPOSER => [
            __DIR__ . '/tests/TestCase/Rector/Namespace_/AppUsesStaticCallToUseStatementRector/Source',
        ],
    ])
    ->withPreset(Preset::PSR4());
