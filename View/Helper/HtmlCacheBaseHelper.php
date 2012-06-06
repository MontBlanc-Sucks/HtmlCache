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

		$path = $this->options['www_root'] . 'cache' . $host . $path;
		if ((empty($this->request->params['ext']) || $this->request->params['ext'] === 'html') && !preg_match('@.html?$@', $path)) {
			$path .= DS . 'index.html';
		}

		
		$file = new File($path, true);
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
