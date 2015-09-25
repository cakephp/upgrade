<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Upgrade\Shell\Task;

use Cake\Console\Shell;
use Cake\Upgrade\Shell\Task\BaseTask;

/**
 * Create and setup missing files and folders via app repo.
 */
class SkeletonTask extends BaseTask
{

    use ChangeTrait;

    public $tasks = ['Stage'];

    /**
     * Add missing files and folders in the root app dir.
     *
     * @param mixed $path
     * @return bool
     */
    protected function _process($path)
    {
        $path = dirname($path) . DS;

        $dirs = ['logs', 'bin', 'config', 'webroot', 'tests'];
        foreach ($dirs as $dir) {
            if (!is_dir($path . $dir) && empty($this->params['dry-run'])) {
                mkdir($path . DS . $dir, 0770, true);
            }
        }

        if (!is_file($path . 'logs' . DS . 'empty') && empty($this->params['dry-run'])) {
            touch($path . 'logs' . DS . 'empty');
        }

        $sourcePath = ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'app' . DS;
        $files = [
            'bin' . DS . 'cake',
            'bin' . DS . 'cake.bat',
            'bin' . DS . 'cake.php',
            'phpunit.xml.dist',
            'index.php',
            'webroot' . DS . 'index.php',
            'config' . DS . 'bootstrap.php',
            'config' . DS . 'bootstrap_cli.php',
            'config' . DS . 'paths.php',
            'config' . DS . 'routes.php',
            'tests' . DS . 'bootstrap.php',
            'src' . DS . 'Template' . DS . 'Error' . DS . 'error400.ctp',
            'src' . DS . 'Template' . DS . 'Error' . DS . 'error500.ctp',
            'src' . DS . 'Template' . DS . 'Layout' . DS . 'error.ctp',
            'src' . DS . 'Controller' . DS . 'PagesController.php',
        ];
        $ret = 0;
        foreach ($files as $file) {
            $ret |= $this->_addFile($file, $sourcePath, $path);
        }
        $ret |= $this->_addFile('config' . DS . 'app.default.php', $sourcePath, $path, 'config' . DS . 'app.default.php');
        return (bool)$ret;
    }

    /**
     * _addFile()
     *
     * @param string $file
     * @param string $sourcePath
     * @param string $targetPath
     * @return bool
     */
    protected function _addFile($file, $sourcePath, $targetPath, $targetFile = null)
    {
        $result = false;
        if (!is_file($targetPath . $file) || !empty($this->params['overwrite'])) {
            $result = true;
            if (empty($this->params['dry-run'])) {
                if ($targetFile === null) {
                    $targetFile = $file;
                }
                $targetPathName = $targetPath . dirname($targetFile);
                if (!is_dir($targetPathName)) {
                    mkdir($targetPathName, 0755, true);
                }
                $result = copy($sourcePath . $file, $targetPath . $targetFile);
            }
            $this->out('Adding ' . $file, 1, Shell::VERBOSE);
        }
        return $result;
    }

    /**
     * _shouldProcess
     *
     * Is the current path within the scope of this task?
     *
     * @param string $path
     * @return bool
     */
    protected function _shouldProcess($path)
    {
        if (basename($path) === 'composer.json' && empty($this->params['plugin'])) {
            return true;
        }
        return false;
    }

    /**
     * Get the option parser for this shell.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        return parent::getOptionParser()
            ->addOptions([
                'overwrite' => [
                    'short' => 'o',
                    'boolean' => true,
                    'help' => 'Overwrite files even if they already exist.'
                ]
            ]);
    }
}
