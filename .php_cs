<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
	->in(__DIR__)
	->exclude('tests/test_files')
	->exclude('bin')
	->exclude('config')
	->exclude('tmp')
	->exclude('vendor')
;

return require_once('vendor/fig-r/psr-2-r/.php_cs_psr2r');
