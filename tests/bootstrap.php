<?php
error_reporting(-1);
date_default_timezone_set('UTC');

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../vendor/cakephp/cakephp/src/basics.php';

use Cake\Core\Configure;
Configure::write('debug', 2);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('APP_DIR', 'src');
define('CAKE_CORE_INCLUDE_PATH', ROOT);
