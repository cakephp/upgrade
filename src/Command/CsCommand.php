<?php

namespace Cake\Upgrade\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\Exception\StopException;

class CsCommand extends Command {

	/**
	 * The name of this command.
	 *
	 * @var string
	 */
	protected $name = 'cs';

	/**
	 * Any levels always include previous ones.
	 *
	 * @var array
	 */
	protected $levels = [
		'whitespace' => [
			'Spryker.WhiteSpace.EmptyEnclosingLine,Spryker.WhiteSpace.EmptyLines,Spryker.WhiteSpace.FunctionSpacing,Spryker.WhiteSpace.DocBlockSpacing',
		],
		'type-order' => [
			'Spryker.Commenting.DocBlockTypeOrder',
			'Spryker.Commenting.DocBlockVariableNullHintLast',
			'Spryker.Commenting.DocBlockVarNotJustNull',
		],
		'php' => [
			'PSR2R.Classes.SelfAccessor',
		],
	];

	/**
	 * E.g.:
	 * bin/cake cs /path/to/app --level=type-order
	 *
	 * @param \Cake\Console\Arguments $args The command arguments.
	 * @param \Cake\Console\ConsoleIo $io The console io
	 *
	 * @throws \Cake\Console\Exception\StopException
	 * @return int|null|void The exit code or null for success
	 */
	public function execute(Arguments $args, ConsoleIo $io) {
		$path = $args->getArgumentAt(0);
		if ($path) {
			$path = realpath($path);
		}
		if ($path) {
			$path .= DS;
		}

		if (!is_dir($path)) {
			$io->error('Project path not found: ' . $args->getArgumentAt(0));

			throw new StopException();
		}
		if (!file_exists($path . 'composer.json')) {
			$io->error('Composer.json not found in ' . $args->getArgumentAt(0));

			throw new StopException();
		}

		$this->process($path, $args, $io);
	}

	/**
	 * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
	 *
	 * @return \Cake\Console\ConsoleOptionParser The built parser.
	 */
	protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
		$parser = parent::buildOptionParser($parser)
			->setDescription('A tool to help automate cs fixing. Quickly execute groups of sniffer rules. ' .
				'Be sure to have a backup of your application before running these commands.')->addArgument('path', [
				'help' => 'Path to project.',
				'required' => true,
			])->addOption('level', [
				'help' => 'Level to use.',
				'required' => true,
			])->addOption('fix', [
				'help' => 'Fix fixable issues.',
				'short' => 'f',
				'boolean' => true,
				])->addOption('explain', [
				'help' => 'Explain available sniffs.',
				'short' => 'e',
				'boolean' => true,
			]);

		return $parser;
	}

	/**
	 * @param string $path
	 * @param \Cake\Console\Arguments $args
	 * @param \Cake\Console\ConsoleIo $io
	 * @return void
	 */
	protected function process(string $path, Arguments $args, ConsoleIo $io): void {
		$this->prepRulesets();

		$standardXml = realpath(TMP . 'rulesets' . DS . 'code-sniffer' . DS . 'ruleset.xml');
		if (!$standardXml) {
			$io->error('Standard level not found/configured.');
			$this->abort();
		}

		$command = $args->getOption('fix') ? 'phpcbf' : 'phpcs';
		$command = 'vendor/bin/' . $command . ' --standard=' . $standardXml . '';

		if ($args->getOption('explain')) {
			$this->explain($command . ' -e ' . $path, $io);

			return;
		}

		$level = $args->getOption('level');
		$standard = $this->standard($level);

		if ($standard['sniffs']) {
			$command .= ' --sniffs=' . $standard['sniffs'];
		}

		if (is_dir($path . 'src')) {
			$path .= 'src';
		} else {
			$command .= ' --ignore=/vendor/,/tmp/,/logs/,/files/,/tests/';
		}
		$command .= ' ' . $path;

		$io->out('Running `' . $command . '`...');

		exec($command, $output, $returnVar);
		$io->out($output);
		if ($returnVar === 0) {
			$io->success('All good.');
		} else {
			$io->err('Return code: ' . $returnVar);
		}
	}

	/**
	 * @return void
	 */
	protected function prepRulesets(): void {
		$path = TMP . 'rulesets' . DS;
		if (!is_dir($path)) {
			mkdir($path, 0770, true);
		}

		if (!is_dir($path . 'spryker-code-sniffer')) {
			exec('cd ' . $path . ' && git clone https://github.com/spryker/code-sniffer.git spryker-code-sniffer');
		}
		if (!is_dir($path . 'slevomat-code-sniffer')) {
			exec('cd ' . $path . ' && git clone https://github.com/slevomat/coding-standard.git slevomat-code-sniffer');
		}
		/*
		if (!is_dir($path . 'psr2r-code-sniffer')) {
			exec('cd ' . $path . ' && git clone https://github.com/php-fig-rectified/psr2r-sniffer.git psr2r-code-sniffer');
		}
		*/
		if (!is_dir($path . 'code-sniffer')) {
			mkdir($path . 'code-sniffer', 0770, true);
		}
		$ruleset = <<<XML
<?xml version="1.0"?>
<ruleset name="CodeSniffer">
    <description>
        Coding Standard.
    </description>

    <!-- Ignore all (PHP) test files. -->
    <exclude-pattern>*/test_files/*</exclude-pattern>

    <exclude-pattern>\.idea</exclude-pattern>
    <exclude-pattern>\.git</exclude-pattern>
    <exclude-pattern>*\.xml</exclude-pattern>
    <exclude-pattern>*\.css</exclude-pattern>
    <exclude-pattern>*\.js</exclude-pattern>
    <exclude-pattern>*\.yml</exclude-pattern>
    <exclude-pattern>*\.txt</exclude-pattern>
    <exclude-pattern>*\.json</exclude-pattern>

    <config name="installed_paths" value="tmp/rulesets/slevomat-code-sniffer,tmp/rulesets/spryker-code-sniffer,vendor/fig-r/psr2r-sniffer"/>

    <rule ref="SlevomatCodingStandard"/>
    <rule ref="SprykerStrict"/>
    <rule ref="vendor/fig-r/psr2r-sniffer"/>

</ruleset>
XML;

		/*
		<config name="installed_paths" value="../../tmp/rulesets/slevomat-code-sniffer"/>
		<config name="installed_paths" value="../../tmp/rulesets/spryker-code-sniffer"/>
		<config name="installed_paths" value="../../tmp/rulesets/psr2r-code-sniffer"/>

		 */

		file_put_contents($path . 'code-sniffer' . DS . 'ruleset.xml', $ruleset);
	}

	/**
	 * @param string $level
	 *
	 * @return array
	 */
	protected function standard(string $level): array {
		if (!empty($this->levels[$level])) {
			/** @var array<string>|string $level */
			$level = $this->levels[$level];
			if (is_array($level)) {
				$level = implode(',', $level);
			}
		}

		return [
			'sniffs' => $level,
		];
	}

	/**
	 * @param string $command
	 * @param \Cake\Console\ConsoleIo $io
	 * @return void
	 */
	protected function explain(string $command, ConsoleIo $io): void {
		exec($command, $output);

		$io->out($output);

		$io->out('Use --level with a comma separated list of sniffs or use predefined levels.');
	}

}
