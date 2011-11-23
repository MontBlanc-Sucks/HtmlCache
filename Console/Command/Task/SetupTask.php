<?php
App::uses('Shell', 'Console');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('StaticCacheCfg', 'StaticCache.Lib');

class SetupTask extends Shell {
	
	protected $_Config;
        
        public function init() {
            
        }
}