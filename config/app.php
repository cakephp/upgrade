<?php
return [
/**
 * Debug Level:
 *
 * Production Mode:
 * 0: No error messages, errors, or warnings shown. Flash messages redirect.
 *
 * Development Mode:
 * 1: Errors and warnings shown, model caches refreshed, flash messages halted.
 * 2: As in 1, but also with full debug messages and SQL output.
 *
 */
	'debug' => 2,

/**
 * Configure basic information about the application.
 *
 * - namespace - The namespace to find app classes under.
 * - encoding - The encoding used for HTML + database connections.
 * - base - The base directory the app resides in. If false this
 *   will be auto detected.
 * - dir - Name of app directory.
 */
	'App' => [
		'namespace' => 'Cake\Upgrade',
		'encoding' => 'UTF-8',
		'base' => false,
		'dir' => 'src'
	],

/**
 * Configure the cache adapters.
 */
	'Cache' => [
		'default' => [
			'engine' => 'File',
		],

	/**
	 * Configure the cache used for general framework caching.  Path information,
	 * object listings, and translation cache files are stored with this configuration.
	 */
		'_cake_core_' => [
			'className' => 'File',
			'prefix' => 'myapp_cake_core_',
			'path' => CACHE . 'persistent/',
			'serialize' => true,
			'duration' => '+10 seconds',
		],

	/**
	 * Configure the cache for model and datasource caches.  This cache configuration
	 * is used to store schema descriptions, and table listings in connections.
	 */
		'_cake_model_' => [
			'className' => 'File',
			'prefix' => 'my_app_cake_model_',
			'path' => CACHE . 'models/',
			'serialize' => 'File',
			'duration' => '+10 seconds',
		],
	],

/**
 * Configure the Error and Exception handlers used by your application.
 *
 * By default errors are displayed using Debugger, when debug > 0 and logged by
 * Cake\Log\Log when debug = 0.
 *
 * In CLI environments exceptions will be printed to stderr with a backtrace.
 * In web environments an HTML page will be displayed for the exception.
 * While debug > 0, framework errors like Missing Controller will be displayed.
 * When debug = 0, framework errors will be coerced into generic HTTP errors.
 *
 * Options:
 *
 * - `errorLevel` - int - The level of errors you are interested in capturing.
 * - `trace` - boolean - Whether or not backtraces should be included in
 *   logged errors/exceptions.
 * - `log` - boolean - Whether or not you want exceptions logged.
 * - `exceptionRenderer` - string - The class responsible for rendering
 *   uncaught exceptions.  If you choose a custom class you should place
 *   the file for that class in app/Lib/Error. This class needs to implement a render method.
 * - `skipLog` - array - List of exceptions to skip for logging. Exceptions that
 *   extend one of the listed exceptions will also be skipped for logging.
 *   Example: `'skipLog' => array('Cake\Error\NotFoundException', 'Cake\Error\UnauthorizedException')`
 */
	'Error' => [
		'errorLevel' => E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED,
		'exceptionRenderer' => 'Cake\Error\ExceptionRenderer',
		'skipLog' => [],
		'log' => true,
		'trace' => true,
	],

/**
 * Configures logging options
 */
	'Log' => [
		'debug' => [
			'className' => 'Cake\\Log\\Engine\\FileLog',
			'file' => 'debug',
			'levels' => ['notice', 'info', 'debug'],
		],
		'error' => [
			'className' => 'Cake\\Log\\Engine\\FileLog',
			'file' => 'error',
			'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
		],
	],

	'Session' => [
		'defaults' => 'php',
	],

    /**
     * Email configuration.
     *
     * By defining transports separately from delivery profiles you can easily
     * re-use transport configuration across multiple profiles.
     *
     * You can specify multiple configurations for production, development and
     * testing.
     *
     * Each transport needs a `className`. Valid options are as follows:
     *
     *  Mail   - Send using PHP mail function
     *  Smtp   - Send using SMTP
     *  Debug  - Do not send the email, just return the result
     *
     * You can add custom transports (or override existing transports) by adding the
     * appropriate file to src/Mailer/Transport. Transports should be named
     * 'YourTransport.php', where 'Your' is the name of the transport.
     */
    'EmailTransport' => [
        'default' => [
            'className' => 'Cake\Mailer\Transport\MailTransport',
            /*
             * The following keys are used in SMTP transports:
             */
            'host' => 'localhost',
            'port' => 25,
            'timeout' => 30,
            'username' => null,
            'password' => null,
            'client' => null,
            'tls' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],

];
