<?php
class BusterComponent extends Object {

	function startup(&$controller) {
		$this->Controller =& $Controller;
	}
	
	function kill($path) {
			App::import('core', 'Folder');
			$Folder = new Folder();
			$Folder->delete(WWW_ROOT . 'cache' . DS . $path);
	}
}
?>