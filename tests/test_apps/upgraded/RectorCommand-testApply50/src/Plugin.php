<?php
declare(strict_types=1);

namespace MyPlugin;

use Cake\Core\BasePlugin;

class Plugin extends BasePlugin
{
    /**
     * Do bootstrapping or not
     *
     * @var bool
     */
    protected bool $bootstrapEnabled = true;

    /**
     * Console middleware
     *
     * @var bool
     */
    protected bool $consoleEnabled = true;

    /**
     * Enable middleware
     *
     * @var bool
     */
    protected bool $middlewareEnabled = true;

    /**
     * Register container services
     *
     * @var bool
     */
    protected bool $servicesEnabled = true;

    /**
     * Load routes or not
     *
     * @var bool
     */
    protected bool $routesEnabled = true;

    /**
     * The path to this plugin.
     *
     * @var string|null
     */
    protected ?string $path = null;

    /**
     * The class path for this plugin.
     *
     * @var string|null
     */
    protected ?string $classPath = null;

    /**
     * The config path for this plugin.
     *
     * @var string|null
     */
    protected ?string $configPath = null;

    /**
     * The templates path for this plugin.
     *
     * @var string|null
     */
    protected ?string $templatePath = null;

    /**
     * The name of this plugin
     *
     * @var string|null
     */
    protected ?string $name = null;
}
