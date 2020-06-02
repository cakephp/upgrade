<?php
declare(strict_types=1);

/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */

error_reporting(-1);
date_default_timezone_set('UTC');

require dirname(__DIR__) . '/vendor/autoload.php';

require dirname(__DIR__) . '/config/bootstrap.php';

use Cake\Core\Configure;

Configure::write('App.namespace', 'App');
