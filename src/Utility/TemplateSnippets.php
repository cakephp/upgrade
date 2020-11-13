<?php

namespace Cake\Upgrade\Utility;

class TemplateSnippets {

	/**
	 * @return array
	 */
	public function snippets(): array {
		$list = [
			[
				'->_getTemplateFileName()',
				'#-\>_getViewFileName\(#i',
				'->_getTemplateFileName(',
			],
		];

		return $list;
	}

}
