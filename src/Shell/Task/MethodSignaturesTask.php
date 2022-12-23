<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Upgrade\Shell\Task;

use Cake\Upgrade\Shell\Task\BaseTask;

/**
 * Update method signatures task.
 *
 * Handles updating method signatures that have been changed.
 *
 */
class MethodSignaturesTask extends BaseTask
{

    use ChangeTrait;

    public $tasks = ['Stage'];

    /**
     * Processes a path.
     *
     * @param string $path
     * @return void
     */
    protected function _process($path)
    {
        $controllerPatterns = [
            [
                'beforeFilter(Event $event) callback',
                '#\bfunction beforeFilter\(\)#i',
                'function beforeFilter(Event $event)',
            ],
            [
                'parent::beforeFilter(Event $event) call',
                '#\bparent::beforeFilter\(\)#i',
                'parent::beforeFilter($event)',
            ],
            [
                'beforeRender(Event $event) callback',
                '#\bfunction beforeRender\(\)#i',
                'function beforeRender(Event $event)',
            ],
            [
                'parent::beforeRender(Event $event) call',
                '#\bparent::beforeRender\(\)#i',
                'parent::beforeRender($event)',
            ],
            [
                'afterFilter(Event $event) callback',
                '#\bfunction afterFilter\(\)#i',
                'function afterFilter(Event $event)',
            ],
            [
                'parent::afterFilter(Event $event) call',
                '#\bparent::afterFilter\(\)#i',
                'parent::afterFilter($event)',
            ],
            [
                'constructClasses() method',
                '#\bfunction constructClasses\(\)#i',
                'function initialize()',
            ],
        ];
        $componentPatterns = [
            [
                'beforeRedirect(Event $event, $url, Response $response) callback',
                '#\bfunction beforeRedirect\(Controller $controller,#i',
                'function beforeRedirect(Event $event,',
            ],
            [
                'initialize(Event $event) callback',
                '#\bfunction initialize\(Controller $controller\)#i',
                'function initialize(Event $event)',
            ],
            [
                'startup(Event $event) callback',
                '#\bfunction startup\(Controller $controller\)#i',
                'function startup(Event $event)',
            ],
            [
                'beforeRender(Event $event) callback',
                '#\bfunction beforeRender\(Controller $controller\)#i',
                'function beforeRender(Event $event)',
            ],
            [
                'shutdown(Event $event) callback',
                '#\bfunction shutdown\(Controller $controller\)#i',
                'function shutdown(Event $event)',
            ],
        ];
        $helperPatterns = [
            [
                'beforeRenderFile(Event $event, $viewFile) callback',
                '#\bfunction beforeRenderFile\($viewFile\)#i',
                'function beforeRenderFile(Event $event, $viewFile)',
            ],
            [
                'afterRenderFile(Event $event, $viewFile, $content) callback',
                '#\bfunction afterRenderFile\($viewFile, $content\)#i',
                'function afterRenderFile(Event $event, $viewFile, $content)',
            ],
            [
                'beforeRender(Event $event, $viewFile) callback',
                '#\bfunction beforeRender\($viewFile\)#i',
                'function beforeRender(Event $event, $viewFile)',
            ],
            [
                'afterRender(Event $event, $viewFile) callback',
                '#\bfunction afterRender\($viewFile\)#i',
                'function afterRender(Event $event, $viewFile)',
            ],
            [
                'beforeLayout(Event $event, $layoutFile) callback',
                '#\bfunction beforeLayout\($layoutFile\)#i',
                'function beforeLayout(Event $event, $layoutFile)',
            ],
            [
                'afterLayout(Event $event, $layoutFile) callback',
                '#\bfunction afterLayout\($layoutFile\)#i',
                'function afterLayout(Event $event, $layoutFile)',
            ],
        ];
        $modelPatterns = [
            [
                'beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) callback',
                '#\bfunction beforeValidate\(array $options\s*=\s*array\(\)\)#i',
                'function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)',
            ],
            [
                'beforeSave(Event $event, Entity $entity, ArrayObject $options) callback',
                '#\bfunction beforeSave\(array $options\s*=\s*array\(\)\)#i',
                'function beforeSave(Event $event, Entity $entity, ArrayObject $options)',
            ],
            [
                'beforeDelete(Event $event, Entity $entity, ArrayObject $options) callback',
                '#\bfunction beforeDelete\($cascade\s*=\s*true\)#i',
                'function beforeDelete(Event $event, Entity $entity, ArrayObject $options)',
            ],
            [
                'afterRules(Event $event, Entity $entity, ArrayObject $options) callback',
                '#\bfunction afterValidate\(\)#i',
                'function afterRules(Event $event, Entity $entity, ArrayObject $options)',
            ],
            [
                'afterSave(Event $event, Entity $entity, ArrayObject $options) callback',
                '#\bfunction afterSave\($created,\s*$options\s*=\s*array\(\)\)#i',
                'function afterSave(Event $event, Entity $entity, ArrayObject $options)',
            ],
            [
                'afterDelete(Event $event, Entity $entity, ArrayObject $options) callback',
                '#\bfunction afterDelete\(\)#i',
                'function afterDelete(Event $event, Entity $entity, ArrayObject $options)',
            ],
        ];
        $behaviorPatterns = [
            [
                'beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) callback',
                '#\bfunction beforeValidate\(Model $Model,\s*$options\s*=\s*array\(\)\)#i',
                'function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)',
            ],
            [
                'beforeSave(Event $event, Entity $entity, ArrayObject $options) callback',
                '#\bfunction beforeSave\(Model $Model,\s*$options\s*=\s*array\(\)\)#i',
                'function beforeSave(Event $event, Entity $entity, ArrayObject $options)',
            ],
            [
                'beforeDelete(Event $event, Entity $entity, ArrayObject $options) callback',
                '#\bfunction beforeDelete\(Model $Model,\s*$cascade\s*=\s*true\)#i',
                'function beforeDelete(Event $event, Entity $entity, ArrayObject $options)',
            ],
            [
                'afterRules(Event $event, Entity $entity, ArrayObject $options) callback',
                '#\bfunction afterValidate\(Model $Model\)#i',
                'function afterRules(Event $event, Entity $entity, ArrayObject $options)',
            ],
            [
                'afterSave(Event $event, Entity $entity, ArrayObject $options) callback',
                '#\bfunction afterSave\(Model $Model,\s*$created,\s*$options\s*=\s*array\(\)\)#i',
                'function afterSave(Event $event, Entity $entity, ArrayObject $options)',
            ],
            [
                'afterDelete(Event $event, Entity $entity, ArrayObject $options) callback',
                '#\bfunction afterDelete\(Model $Model\)#i',
                'function afterDelete(Event $event, Entity $entity, ArrayObject $options)',
            ],
        ];

        $patterns = [];
        if (strpos($path, DS . 'View' . DS) !== false) {
            $patterns = $helperPatterns;
        } elseif (strpos($path, DS . 'Controller' . DS . 'Component' . DS) !== false) {
            $patterns = $componentPatterns;
        } elseif (strpos($path, DS . 'Controller' . DS) !== false) {
            $patterns = $controllerPatterns;
        } elseif (strpos($path, DS . 'Model' . DS) !== false) {
            $patterns = array_merge($modelPatterns, $behaviorPatterns);
        }

        $original = $contents = $this->Stage->source($path);
        $contents = $this->_updateContents($contents, $patterns);

        return $this->Stage->change($path, $original, $contents);
    }

    /**
     * _shouldProcess
     *
     * Default to PHP files only
     *
     * @param string $path
     * @return bool
     */
    protected function _shouldProcess($path)
    {
        return (substr($path, -4) === '.php');
    }
}
