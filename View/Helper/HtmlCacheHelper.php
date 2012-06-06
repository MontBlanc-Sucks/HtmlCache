<?php
/*
 * HtmlCache Plugin for cake 2.0 
 * Copyright (c) 2011 Sam Sherlock
 * http://samsherlock.com
 * http://github.com/samsherlock/static_cache
 *
 * @author        sams <sam.sherlock@gmail.com>
 * @license       MIT
 *
 * based on
 * HtmlCache Plugin for cake 1.2/1.3 
 * Copyright (c) 2009 Matt Curry
 * http://pseudocoder.com
 * http://github.com/mcurry/html_cache
 *
 * @author        mattc <matt@pseudocoder.com>
 * @license       MIT
 *
 */
App::uses('HtmlCacheBaseHelper', 'HtmlCache.View/Helper');


// @todo move this set of helpers into lib and make static cache helper use the lib make the


/**
 * HtmlCacheHelper class
 *
 * @uses          HtmlCacheBaseHelper
 * @package       HtmlCache
 * @subpackage    HtmlCache.View.Helper
 */
class HtmlCacheHelper extends HtmlCacheBaseHelper {
/**
 * construct method
 *
 * @param mixed $options
 * @return void
 * @access private
 */
  function __construct(View $View, $settings = array()) {
    parent::__construct($View, $settings);
  }
}
