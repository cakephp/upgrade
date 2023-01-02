<?php
declare(strict_types=1);

namespace Cake\Upgrade\Rector;

use Nette\Utils\Strings;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Util\StringUtils;

/**
 * @inspired https://github.com/cakephp/upgrade/blob/756410c8b7d5aff9daec3fa1fe750a3858d422ac/src/Shell/Task/AppUsesTask.php
 */
final class ShortClassNameResolver
{
    /**
     * @var string
     * @see https://regex101.com/r/mbvKJp/1
     */
    public const LIB_NAMESPACE_PART_REGEX = '#\\\\Lib\\\\#';

    /**
     * @var string
     * @see https://regex101.com/r/XvoZIP/1
     */
    private const SLASH_REGEX = '#(/|\.)#';

    /**
     * @var string
     * @see https://regex101.com/r/lq0lQ9/1
     */
    private const PLUGIN_OR_LIB_REGEX = '#(Plugin|Lib)#';

    /**
     * A map of old => new for use statements that are missing
     *
     * @var string[]
     */
    private const RENAME_MAP = [
        'App' => 'Cake\Core\App',
        'AppController' => 'App\Controller\AppController',
        'AppHelper' => 'App\View\Helper\AppHelper',
        'AppModel' => 'App\Model\AppModel',
        'Cache' => 'Cake\Cache\Cache',
        'CakeEventListener' => 'Cake\Event\EventListener',
        'CakeLog' => 'Cake\Log\Log',
        'CakePlugin' => 'Cake\Core\Plugin',
        'CakeTestCase' => 'Cake\TestSuite\TestCase',
        'CakeTestFixture' => 'Cake\TestSuite\Fixture\TestFixture',
        'Component' => 'Cake\Controller\Component',
        'ComponentRegistry' => 'Cake\Controller\ComponentRegistry',
        'Configure' => 'Cake\Core\Configure',
        'ConnectionManager' => 'Cake\Database\ConnectionManager',
        'Controller' => 'Cake\Controller\Controller',
        'Debugger' => 'Cake\Error\Debugger',
        'ExceptionRenderer' => 'Cake\Error\ExceptionRenderer',
        'Helper' => 'Cake\View\Helper',
        'HelperRegistry' => 'Cake\View\HelperRegistry',
        'Inflector' => 'Cake\Utility\Inflector',
        'Model' => 'Cake\Model\Model',
        'ModelBehavior' => 'Cake\Model\Behavior',
        'Object' => 'Cake\Core\Object',
        'Router' => 'Cake\Routing\Router',
        'Shell' => 'Cake\Console\Shell',
        'View' => 'Cake\View\View',
        // Also apply to already renamed ones
        'Log' => 'Cake\Log\Log',
        'Plugin' => 'Cake\Core\Plugin',
        'TestCase' => 'Cake\TestSuite\TestCase',
        'TestFixture' => 'Cake\TestSuite\Fixture\TestFixture',
    ];

    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * This value used to be directory So "/" in path should be "\" in namespace
     */
    public function resolveShortClassName(string $pseudoNamespace, string $shortClass): string
    {
        $pseudoNamespace = $this->normalizeFileSystemSlashes($pseudoNamespace);

        $resolvedShortClass = self::RENAME_MAP[$shortClass] ?? null;

        // A. is known renamed class?
        if ($resolvedShortClass !== null) {
            return $resolvedShortClass;
        }

        // Chop Lib out as locations moves those files to the top level.
        // But only if Lib is not the last folder.
        if (StringUtils::isMatch($pseudoNamespace, self::LIB_NAMESPACE_PART_REGEX)) {
            $pseudoNamespace = Strings::replace($pseudoNamespace, '#\\\\Lib#', '');
        }

        // B. is Cake native class?
        $cakePhpVersion = 'Cake\\' . $pseudoNamespace . '\\' . $shortClass;
        if ($this->reflectionProvider->hasClass($cakePhpVersion)) {
            return $cakePhpVersion;
        }

        // C. is not plugin nor lib custom App class?
        if (
            \str_contains($pseudoNamespace, '\\') && ! StringUtils::isMatch(
                $pseudoNamespace,
                self::PLUGIN_OR_LIB_REGEX
            )
        ) {
            return 'App\\' . $pseudoNamespace . '\\' . $shortClass;
        }

        return $pseudoNamespace . '\\' . $shortClass;
    }

    private function normalizeFileSystemSlashes(string $pseudoNamespace): string
    {
        return Strings::replace($pseudoNamespace, self::SLASH_REGEX, '\\');
    }
}
