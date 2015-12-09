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
App::uses('SessionHelper', 'View/Helper');
App::uses('AppHelper', 'View/Helper');
App::uses('File', 'Utility');
class HtmlCacheBaseHelper extends AppHelper {

/**
 * options property
 *
 * @var array
 * @access public
 */
	public $options = array(
		'test_mode' => false,
		'host' => null,
		'domain' => false,
		'www_root' => null, // override in constructor
		'cache_dir' => 'cache',
		'filename' => 'index.html',
		'file_path' => null, // use if you need custom path
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
 * isFlash property
 *
 * @var bool false
 * @access public
 */
	public $forceDisable = false;

/**
 * Contains the build timestamp from the file.
 *
 * @var string
 */
	protected $_buildTimestamp;
	
	public function __construct(View $View) {
		$defaults = array(
			'www_root' => APP . WEBROOT_DIR,
		);
		$this->options($defaults);
		parent::__construct($View);
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
 * Delete file
 *
 * @var (optional) mixed file path string
 * @var (optional) boolean cache directory if true
 * @return mixed deleted file path
 */
	public function deleteCache($path = false, $isCacheDir = false) {
		$dir = $this->options['www_root'];
		if (empty($isCacheDir)) {
			$dir .= $this->options['cache_dir'];
		} elseif (empty($path)) {
			return false;
		}
		$path = $dir . (!empty($path) ? DS . ltrim($path, DS) : '');
		$File = new File($path);
		$File->delete();
		return $path;
	}

/**
 * beforeRender method
 *
 * @return void
 * @access public
 */
	public function beforeRender($viewFile) {
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
	public function afterLayout($layoutFile) {
		if(!$this->_isCachable()) {
			return;
		}

		//handle error pages not just 404
		if ($this->_View->name == 'CakeError') {
			$path = $this->request->params['url'];
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

		if (empty($this->options['file_path'])) {
			$path = $this->options['www_root'] . DS . $this->options['cache_dir'] . $host . $path;
			if ((empty($this->request->params['ext']) || $this->request->params['ext'] === 'html') && !preg_match('@.html?$@', $path)) {
				$path .= DS . $this->options['filename'];
			}
			$this->options['file_path'] = $path;
		}

		$file = new File($this->options['file_path'], true);
		$file->write($this->_View->output);
	}

/**
 * isCachable method
 *
 * @return void
 * @access protected
 */
	protected function _isCachable() {
		if (empty($this->options['test_mode']) && Configure::read('debug') > 0) {
			return false;
		}
		
		if ($this->forceDisable) {
			return false;
		}

		if($this->isFlash) {
			return false;
		}

		if(!empty($this->request->data)) {
			return false;
		}

		return true;
	}
}
