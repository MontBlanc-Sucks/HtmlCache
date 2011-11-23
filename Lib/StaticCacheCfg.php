<?php

App::uses('IniReader', 'Configure');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::core('CakePlugin');

class StaticCacheCfg {

    protected $_Config = null;  
    protected $_Current = null; 
    protected $_IniName = 'config';    
    protected $_IniDir = 'config';    
    
    public function __construct($config = null) {
        $this->_IniDir = App::pluginPath('StaticCache') . 'Config' . DS;
        if(!is_null($config) && file_exists(APP . 'Config' . DS . $config . '.ini')) {
            $this->_IniDir = APP . 'Config' . DS;
            $this->_IniName = $config . '.ini';
        }
        $this->_Config = new IniReader($this->_IniDir);
        
        return $this;
    }
    
    public function save($data = array()) {
        
    }
    
    public function read() {
        $this->_Config->read($this->_IniName . '.ini');
    }
    
    private function _write() {
        
    }
    
    public function available() {
        $Folder = new Folder();
        $files = array();
        $Folder->cd(App::pluginPath('StaticCache') . 'Config');
        $files = $Folder->find('.*\.ini', true);
        $Folder->cd(APP . 'Config');
        $files = array_merge($files, $Folder->find('.*\.ini', true));
        //print_r($files); die();
        for($i = 0; $i < count($files); $i++) {
            //print "\n" . str_replace('.ini', '', $files[$i]);
            if(CakePlugin::loaded(Inflector::camelize(str_replace('.ini', '', $files[$i])))) {
                unset($files[$i]);
            }
        }    
        
        return $files;
    }
}