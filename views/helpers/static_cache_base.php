<?php
/*
 * HtmlCache Plugin
 * Copyright (c) 2009 Matt Curry
 * http://pseudocoder.com
 * http://github.com/mcurry/html_cache
 *
 * @author        mattc <matt@pseudocoder.com>
 * @license       MIT
 *
 */

class StaticCacheBaseHelper extends Helper {

/**
 * options property
 *
 * @var array
 * @access public
 */
	public $options = array(
		'test_mode' => false,
    'host' => null,
		'domain' => false
	);

/**
 * helpers property
 *
 * @var array
 * @access public
 */
	public $helpers = array('Session');

/**
 * path property
 *
 * @var string ''
 * @access public
 */
	public $path = '';

/**
 * isFlash property
 *
 * @var bool false
 * @access public
 */
	public $isFlash = false;

/**
 * parsed ini file values.
 *
 * @var array
 */
	protected $_iniFile;

/**
 * Contains the build timestamp from the file.
 *
 * @var string
 */
	protected $_buildTimestamp;

/**
 * Constructor - finds and parses the ini file the plugin uses.
 *
 * @return void
 */
	public function __construct($options = array()) {
		if (!empty($options['iniFile'])) {
			$iniFile = $options['iniFile'];
		} else {
			$iniFile = CONFIGS . 'static_cache.ini';
		}
		if (!file_exists($iniFile)) {
			$iniFile = App::pluginPath('StaticCache') . 'config' . DS . 'config.ini';
		}
		$this->_iniFile = parse_ini_file($iniFile, true);
		//die(debug($this->_iniFile));
	}

/**
 * Modify the runtime configuration of the helper.
 * Used as a get/set for the ini file values.
 * 
 * @param string $name The dot separated config value to change ie. Css.searchPaths
 * @param mixed $value The value to set the config to.
 * @return mixed Either the value being read or null.  Null also is returned when reading things that don't exist.
 */
	public function config($name, $value = null) {
		if (strpos($name, '.') === false) {
			return null;
		}
		list($section, $key) = explode('.', $name);
		if ($value === null) {
			return isset($this->_iniFile[$section][$key]) ? $this->_iniFile[$section][$key] : null;
		}
		$this->_iniFile[$section][$key] = $value;
	}

/**
 * Set options, merge with existing options.
 *
 * @return void
 */
	public function options($options) {
		$this->options = Set::merge($this->options, $options);
	}

/**
 * beforeRender method
 *
 * @return void
 * @access public
 */
	public function beforeRender() {
		if($this->Session->read('Message')) {
			$this->isFlash = true;
		}
	}

/**
 * afterLayout method
 *
 * @return void
 * @access public
 */
	public function afterLayout() {
		if(!$this->_isCachable()) {
			return;
		}

		$view =& ClassRegistry::getObject('view');

		//handle 404s
		if ($view->name == 'CakeError') {
			$path = $this->params['url']['url'];
		} else {
			$path = $this->here;
		}

		$path = implode(DS, array_filter(explode('/', $path)));
		if($path !== '') {
			$path = DS . ltrim($path, DS);
		}
    
    $host = '';
    if($this->options['domain']) {
      if (!empty($_SERVER['HTTP_HOST'])) {
        $host = DS . $_SERVER['HTTP_HOST'];
      } elseif ($this->options['host']) {
        $host = DS . $this->options['host'];
      }
    }
    
    //die(debug($this->options));
    
		$path = APP . 'webroot' . DS . 'cache' . $host . DS . $this->params['url']['ext'] .  $path . DS . 'index.' . $this->params['url']['ext'];
		
		//die($path);
		
		$file = new File($path, true);
		$file->write($view->output);
	}

/**
 * isCachable method
 *
 * @return void
 * @access protected
 */
	protected function _isCachable() {
		return true;
		if (!$this->options['test_mode'] && Configure::read('debug') > 0) {
			return false;
		}

		if($this->isFlash) {
			return false;
		}

		if(!empty($this->data)) {
			return false;
		}

		return true;
	}
}