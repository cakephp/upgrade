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
    protected $bootstrapEnabled = true;

    /**
     * Console middleware
     *
     * @var bool
     */
    protected $consoleEnabled = true;

    /**
     * Enable middleware
     *
     * @var bool
     */
    protected $middlewareEnabled = true;

    /**
     * Register container services
     *
     * @var bool
     */
    protected $servicesEnabled = true;

    /**
     * Load routes or not
     *
     * @var bool
     */
    protected $routesEnabled = true;

    /**
     * The path to this plugin.
     *
     * @var string|null
     */
    protected $path = null;

    /**
     * The class path for this plugin.
     *
     * @var string|null
     */
    protected $classPath = null;

    /**
     * The config path for this plugin.
     *
     * @var string|null
     */
    protected $configPath = null;

    /**
     * The templates path for this plugin.
     *
     * @var string|null
     */
    protected $templatePath = null;

    /**
     * The name of this plugin
     *
     * @var string|null
     */
    protected $name = null;
}
