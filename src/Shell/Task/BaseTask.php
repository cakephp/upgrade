<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 3.0.0
 * @license       MIT License (https://www.opensource.org/licenses/mit-license.php)
 */
namespace Cake\Upgrade\Shell\Task;

use Cake\Console\Shell;

/**
 * Base Task that defines some common methods for the upgrade shell tools.
 */
class BaseTask extends Shell
{

    /**
     * Get the option parser for this shell.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        return parent::getOptionParser()
            ->addArgument('path', [
                'help' => 'Path to code to upgrade',
                'required' => true
            ])
            ->addOptions([
                'plugin' => [
                    'short' => 'p',
                    'help' => 'The plugin to update. Only the specified plugin will be updated.'
                ],
                'dry-run' => [
                    'short' => 'd',
                    'help' => 'Dry run the update, no files will actually be modified.',
                    'boolean' => true
                ],
                'git' => [
                    'help' => 'Perform git operations. eg. git mv instead of just moving files.',
                    'boolean' => true
                ],
                'namespace' => [
                    'help' => 'Set the base namespace you want to use. Defaults to App or the plugin name.',
                    'default' => '',
                ],
                'exclude' => [
                    'help' => 'Comma separated list of top level diretories to exclude.',
                    'default' => '',
                ]
            ]);
    }
}
