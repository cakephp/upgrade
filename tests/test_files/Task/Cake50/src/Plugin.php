<?php

namespace SomePlugin;

use Cake\Core\BasePlugin;
use Cake\Routing\RouteBuilder;

class Plugin extends BasePlugin {

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

	public function routes(RouteBuilder $routes): void {
		$routes->prefix('Admin', function (RouteBuilder $routes) {
			$routes->plugin('Expose', function (RouteBuilder $routes) {
			});
		});
	}
}
