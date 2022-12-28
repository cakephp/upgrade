<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link http://cakephp.org CakePHP(tm) Project
 * @since CakePHP(tm) v 0.10.8.2117
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Configure paths required to find CakePHP + general filepath
 * constants
 */
require __DIR__ . '/paths.php';

// Use composer to load the autoloader.
require ROOT . DS . 'vendor' . DS . 'autoload.php';

/**
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Log\Log;

/**
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
	Configure::config('default', new PhpConfig());
	Configure::load('app', 'default', false);

	// Load an environment local configuration file.
	// You can use this file to provide local overrides to your
	// shared configuration.
	// Configure::load('app.local', 'default');
} catch (Exception $e) {
	exit('Unable to load config/app.php. Create it by copying config/app.default.php to config/app.php.');
}

Configure::load('app_custom', 'default');

	/**
	 * Uncomment this line and correct your server timezone to fix
	 * any date & time related errors.
	 */
	//date_default_timezone_set('UTC');

	/**
	 * Configure the mbstring extension to use the correct encoding.
	 */
mb_internal_encoding(Configure::read('App.encoding'));

	/**
	 * Register application error and exception handlers.
	 */
(new ErrorTrap(Configure::read('Error')))->register();
(new ExceptionTrap(Configure::read('Error')))->register();

	/**
	 * Set the full base url.
	 * This URL is used as the base of all absolute links.
	 *
	 * If you define fullBaseUrl in your config file you can remove this.
	 */
if (!Configure::read('App.fullBaseUrl')) {
	$s = null;
	if (env('HTTPS')) {
		$s = 's';
	}

	$httpHost = env('HTTP_HOST');
	if (isset($httpHost)) {
		Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
	}
	unset($httpHost, $s);
}

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
Log::setConfig(Configure::consume('Log'));
