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

use Cake\Utility\String;

/**
 * Handles custom stuff
 */
class CustomTask extends BaseTask {

	use ChangeTrait;

	public $tasks = ['Stage'];

	/**
	 * Processes a path.
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _process($path) {
		$configPatterns = [
			[
				'<?php $config = [ to return $config = [',
				'/\<\?php(\s+\s*)\$config = \[/',
				'<?php\1return [',
			],
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
				'->behaviors()->has(\1',
			],
			[
				'->Behaviors->load( to ->addBehavior(',
				'/-\>Behaviors-\>load\(/',
				'->addBehavior(',
			],
			[
				'->Behaviors->unload( to ->removeBehavior(',
				'/-\>Behaviors-\>unload\(/',
				'->removeBehavior(',
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
				'Geo.GoogleMapV3',
			],
			[
				'Tools.Geocoder to Geo.Geocoder',
				'/\bTools.Geocoder\b/',
				'Geo.Geocoder',
			],
			[
				'Tools.Ajax to Ajax.Ajax',
				'/\bTools.Ajax\b/',
				'Ajax.Ajax',
			],
			[
				'Tools.AuthExt to Auth',
				'/\bTools.AuthExt\b/',
				'Auth',
			],
			[
				'Tools.Tiny to TinyAuth.Tiny',
				'/\bTools.Tiny\b/',
				'TinyAuth.Tiny',
			],
			[
				'$this->Common->flash() to $this->Flash->render()',
				'/\$this-\>Common-\>flash\(/',
				'$this->Flash->render(',
			],
			[
				'$this->Datetime-> to $this->Time->',
				'/\$this-\>Datetime-\>(.+)\(/',
				'$this->Time->\1(',
			],
			[
				'$this->Numeric-> to $this->Number->',
				'/\$this-\>Numeric-\>(.+)\(/',
				'$this->Number->\1(',
			],
			[
				'Tools\Mailer\Email',
				'/\buse Tools\\\\Lib\\\\EmailLib;/',
				'use Tools\Mailer\Email;',
			],
			[
				'new EmailLib() to new Email()',
				'/\bnew EmailLib\(/',
				'new Email(',
			],
			[
				'Tools\Controller\Controller',
				'/\buse Tools\\\\Controller\\\\MyController;/',
				'use Tools\Controller\Controller;',
			],
			[
				'extends MyController to extends Controller',
				'/\bextends MyController\b/',
				'extends Controller',
			],
			[
				'extends ControllerTestCase to extends IntegrationTestCase',
				'/\bextends ControllerTestCase\b/',
				'extends IntegrationTestCase',
			],
			[
				'$this->testAction( to $this->get(',
				'/\$this-\>testAction\(/',
				'$this->get(',
			],
			[
				'return $this->flash(..., ...) to $this->Flash->message(\1); return $this->redirect(\2);',
				'/\breturn \$this-\>flash\((.+),\s*(.+)\);/',
				'$this->Flash->message(\1); return $this->redirect(\2);',
			],
			[
				'$this->Session->setFlash() to $this->Flash->message();',
				'/\$this-\>Session-\>setFlash\(/',
				'$this->Flash->message(',
			],
			# old ones to new sugar
			[
				'$this->Flash->message(..., type) ... $this->Flash->type(...)',
				'/-\>Flash-\>message\((.+),\s*\'(error|warning|success|info)\'\)/',
				'->Flash->\2(\1)',
			],
			[
				'$this->Flash->message(..., type) ... $this->Flash->type(...)',
				'/-\>Common-\>flashMessage\((.+),\s*\'(error|warning|success|info)\'\)/',
				'->Flash->\2(\1)',
			],
			// Tools flash
			[
				'$this->Flash->message(...)',
				'/-\>Common-\>flashMessage\(__\(\'Invalid (.*)\'\)\)/i',
				'->Flash->error(__(\'Invalid \1\'))',
			],
			[
				'$this->Flash->message(...)',
				'/-\>Common-\>flashMessage\(__\(\'(.*) has been saved\'\)\)/',
				'->Flash->success(__(\'\1 has been saved\'))',
			],
			[
				'$this->Flash->message(...)',
				'/-\>Common-\>flashMessage\(__\(\'(.*) could not be saved(.*)\'\)\)/',
				'->Flash->error(__(\'\1 could not be saved\2\'))',
			],
			# old ones to new sugar
			[
				'$this->Flash->message(..., type) ... $this->Flash->type(...)',
				'/-\>Flash-\>message\((.+),\s*\'(error|warning|success|info)\'\)/',
				'->Flash->\2(\1)',
			],
			# tmp to qickly find unmatching ones
			[
				'$this->Flash->message(...)',
				'/-\>Common-\>flashMessage\(__\(\'(.*)\'\)\)/',
				'->Flash->xxxxx(__(\'\1\'))',
			],
			[
				'use App\\Utility\\Folder to use Cake\\Filesystem\\Folder',
				'/\bApp\\\\Utility\\\\Folder\b/',
				'Cake\\Filesystem\\Folder',
			],
			[
				'use App\\Utility\\File to use Cake\\Filesystem\\File',
				'/\bApp\\\\Utility\\\\File\b/',
				'Cake\\Filesystem\\File',
			],
			[
				'use Cake\\Utility\\Folder to use Cake\\Filesystem\\Folder',
				'/\bCake\\\\Utility\\\\Folder\b/',
				'Cake\\Filesystem\\Folder',
			],
			[
				'use Cake\\Utility\\File to use Cake\\Filesystem\\File',
				'/\bCake\\\\Utility\\\\File\b/',
				'Cake\\Filesystem\\File',
			],
			[
				'Auth::...() to $this->AuthUser->...()',
				'/\bAuth\:\:(\w+)\(/',
				'$this->AuthUser->\1(',
			],
			[
				'$this->Html->defaultLink() to $this->Html->resetLink()',
				'/\$this-\>Html-\>defaultLink\(/',
				'$this->Html->resetLink(',
			],
			[
				'$this->Html->defaultUrl() to $this->Url->reset()',
				'/\$this-\>Html-\>defaultUrl\(/',
				'$this->Url->reset(',
			],
			[
				'$this->Html->completeUrl() to $this->Url->complete()',
				'/\$this-\>Html-\>completeUrl\(/',
				'$this->Url->complete(',
			],
			[
				'$this->Auth->login() to $this->Auth->identify()',
				'/\$this-\>Auth-\>login\(\)/',
				'$this->Auth->identify()',
			],
			[
				'ClassRegistry::init( to TableRegistry::get(',
				'/\bClassRegistry\:\:init\(/', //TODO: pluralize model?
				'TableRegistry::get(',
			],
			[
				'php_sapi_name() to PHP_SAPI',
				'/\bphp_sapi_name\(\)/',
				'PHP_SAPI',
			],
			// CakeDC Search
			[
				'->parseCriteria(...) to ->find(\'searchable\', ...)',
				'/-\>parseCriteria\(/',
				'->find(\'searchable\', ',
			],
			// Tools
			[
				'->loadComponent(array(...)) to ->loadComponent(...)',
				'/-\>Common-\>loadComponent\(array\(\'(.+?)\'\)\)/',
				'->Common->loadComponent(\'\1\')',
			],
			[
					'public $order = [\'X.name\' => \'ASC\'];',
					'/public \$order\s*=\s*\[\'(\w+)\.(\w+)\'\s*=\>/',
					'public $order = [\'\2\' =>',
			],
			[
					'counter removal',
					'/echo \$this-\>Paginator-\>counter\(.+?\);/sm',
					'',
			],
			[
					'paginator fix',
					'/\<div class="paging"\>.+?\<\/div\>/sm',
					'<div class="pagination-container">
	<?php echo $this->element(\'Tools.pagination\'); ?>
</div>',
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
