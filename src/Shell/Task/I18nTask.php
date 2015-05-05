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

use Cake\Upgrade\Shell\Task\BaseTask;

/**
 * I18n Task.
 *
 * Updates __() calls.
 */
class I18nTask extends BaseTask
{

    use ChangeTrait;

    public $tasks = ['Stage'];

/**
 * Converts placeholders from 2.x to 3.x syntax.
 *
 * @return void
 */
    protected function _process($path)
    {
        $original = $contents = $this->Stage->source($path);

        $contents = $this->_adjustI18n($contents);
        return $this->Stage->change($path, $original, $contents);
    }

/**
 * Adjusts __() to use {n} instead of %s.
 *
 * @param string $contents
 * @return string
 */
    protected function _adjustI18n($contents)
    {
        // Basic functions
        $pattern = '#__(n|c)?\((\'|")(.*?)(?<!\\\\)\2,#';

        $replacement = function ($matches) {
            $string = $matches[3];
            $count = 0;

            $c = 1;
            while ($c) {
                $repString = '{' . $count . '}';
                $string = preg_replace('/%[sdefc]/', $repString, $string, 1, $c);
                $count++;
            }
            return '__' . $matches[1] . '(' . $matches[2] . $string . $matches[2] . ',';
        };

        $contents = preg_replace_callback($pattern, $replacement, $contents, -1, $count);

        // Domain functions
        $pattern = '#__(|d|dc|dn|dcn)?\((\'|")(.*?)(?<!\\\\)\2,\s*(\'|")(.*?)(?<!\\\\)\4,#';

        $replacement = function ($matches) {
            $string = $matches[5];
            $count = 0;

            $c = 1;
            while ($c) {
                $repString = '{' . $count . '}';
                $string = preg_replace('/%[sdefc]/', $repString, $string, 1, $c);
                $count++;
            }
            return '__' . $matches[1] . '(' . $matches[2] . $matches[3] . $matches[2] . ', ' .
                $matches[4] . $string . $matches[4] . ',';
        };

        $contents = preg_replace_callback($pattern, $replacement, $contents, -1, $count);

        return $contents;
    }

/**
 * _shouldProcess
 *
 * Bail for invalid files (php/ctp files only)
 *
 * @param string $path
 * @return bool
 */
    protected function _shouldProcess($path)
    {
        $ending = substr($path, -4);
        return $ending === '.php' || $ending === '.ctp';
    }
}
