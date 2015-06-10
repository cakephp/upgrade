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
use Cake\Utility\Inflector;
use Cake\Utility\String;
/**
 * Handles custom stuff
 *
 */
class CustomTask extends BaseTask {

	use ChangeTrait;

	public $tasks = ['Stage'];

/**
 * Processes a path.
 *
 * @param string $path
 * @return void
 */
	protected function _process($path) {
		$configPatterns = [
			[
				'<?php $config = [ to return $config = [',
				'/\<\?php(\s+\s*)\$config = \[/',
				'<?php\1return ['
			]
		];
		if (strpos($path, DS . 'config' . DS) !== false) {
			$original = $contents = $this->Stage->source($path);
			$contents = $this->_updateContents($contents, $configPatterns);

			return $this->Stage->change($path, $original, $contents);
		}

		$patterns = [
			[
				'->_table->behaviors()->loaded( to has(',
				'/-\>behaviors\(\)-\>loaded\(([^\)]+)/',
				'->behaviors()->has(\1'
			],
			[
				'validateIdentical compare fields',
				'/\'validateIdentical\', \'(.+?)\'\$/i',
				'\'validateIdentical\', [\'compare\' => \'\1\']',
			],
			[
				'throw new FooException( to throw new \\Exception(',
				'/\bthrow new (?!(MethodNotAllowed|Forbidden|NotFound))*Exception\(/i',
				'throw new \Exception(',
			],
			[
				'new DateTime(',
				'/\bnew DateTime\(/i',
				'new \DateTime(',
			],
			[
				'<br> to <br/>',
				'/\<br\s*\>/i',
				'<br/>',
			],
			[
				'<br /> to <br/>',
				'/\<br\s+\/\>/i',
				'<br/>',
			],
			[
				'Tools.GoogleMapV3 to Geo.GoogleMapV3',
				'/\bTools.GoogleMapV3\b/',
				'Geo.GoogleMapV3'
			],
			[
				'Tools.Geocoder to Geo.Geocoder',
				'/\bTools.Geocoder\b/',
				'Geo.Geocoder'
			],
			[
				'Tools.Ajax to Ajax.Ajax',
				'/\bTools.Ajax\b/',
				'Ajax.Ajax'
			],
			[
				'Tools.Tiny to TinyAuth.Tiny',
				'/\bTools.Tiny\b/',
				'TinyAuth.Tiny'
			],
			[
				'$this->Common->flash() to $this->Flash->render()',
				'/\$this-\>Common-\>flash\(/',
				'$this->Flash->render('
			],
			[
				'$this->Datetime-> to $this->Time->',
				'/\$this-\>Datetime-\>(.+)\(/',
				'$this->Time->\1('
			],
			[
				'$this->Numeric-> to $this->Number->',
				'/\$this-\>Numeric-\>(.+)\(/',
				'$this->Number->\1('
			],
			[
				'Tools\Network\Email\Email',
				'/\buse Tools\\\\Lib\\\\EmailLib;/',
				'use Tools\Network\Email\Email;'
			],
			[
				'new EmailLib() to new Email()',
				'/\bnew EmailLib\(/',
				'new Email('
			],
			[
				'Tools\Controller\Controller',
				'/\buse Tools\\\\Controller\\\\MyController;/',
				'use Tools\Controller\Controller;'
			],
			[
				'extends MyController to extends Controller',
				'/\bextends MyController\b/',
				'extends Controller'
			],
			[
				'extends ControllerTestCase to extends IntegrationTestCase',
				'/\bextends ControllerTestCase\b/',
				'extends IntegrationTestCase'
			],
			[
				'$this->testAction( to $this->get(',
				'/\$this-\>testAction\(/',
				'$this->get('
			],
			[
				'return $this->flash(..., ...) to $this->Flash->message(\1); return $this->redirect(\2);',
				'/\breturn \$this-\>flash\((.+),\s*(.+)\);/',
				'$this->Flash->message(\1); return $this->redirect(\2);'
			],
			[
				'$this->Session->setFlash() to $this->Flash->message();',
				'/\$this-\>Session-\>setFlash\(/',
				'$this->Flash->message('
			],
			# old ones to new sugar
			[
				'$this->Flash->message(..., type) ... $this->Flash->type(...)',
				'/-\>Flash-\>message\((.+),\s*\'(error|warning|success|info)\'\)/',
				'->Flash->\2(\1)'
			],
			[
				'use Cake\\Utility\\Folder to use Cake\\Filesystem\\Folder',
				'/\bCake\\\\Utility\\\\Folder\b/',
				'Cake\\Filesystem\\Folder'
			],
			[
				'use Cake\\Utility\\File to use Cake\\Filesystem\\File',
				'/\bCake\\\\Utility\\\\File\b/',
				'Cake\\Filesystem\\File'
			],
			[
				'Auth::...() to $this->AuthUser->...()',
				'/\bAuth\:\:(\w+)\(/',
				'$this->AuthUser->\1('
			],
			[
				'$this->Html->defaultLink() to $this->Html->resetLink()',
				'/\$this-\>Html-\>defaultLink\(/',
				'$this->Html->resetLink('
			],
			[
				'$this->Html->defaultUrl() to $this->Url->reset()',
				'/\$this-\>Html-\>defaultUrl\(/',
				'$this->Url->reset('
			],
			[
				'$this->Html->completeUrl() to $this->Url->complete()',
				'/\$this-\>Html-\>completeUrl\(/',
				'$this->Url->complete('
			],
			[
				'$this->Auth->login() to $this->Auth->identify()',
				'/\$this-\>Auth-\>login\(\)/',
				'$this->Auth->identify()'
			],
			[
				'ClassRegistry::init( to TableRegistry::get(',
				'/\bClassRegistry\:\:init\(/', //TODO: pluralize model?
				'TableRegistry::get('
			],
		];

		$original = $contents = $this->Stage->source($path);

		$contents = $this->_updateContents($contents, $patterns);
		$contents = $this->_replaceCustom($contents, $path);

		return $this->Stage->change($path, $original, $contents);
	}

/**
 * Custom stuff
 *
 * @param string $contents
 * @param string $path
 * @return string
 */
	protected function _replaceCustom($contents, $path) {
		return $contents;

		$pattern = '//i';
		$replacement = function ($matches) {
			$entity = lcfirst($matches[1]);
			return '$this->Form->create($' . $entity . ')';
		};
		$contents = preg_replace_callback($pattern, $replacement, $contents);

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
	protected function _shouldProcess($path) {
		$ending = substr($path, -4);
		return $ending === '.php' || $ending === '.ctp';
	}

}
