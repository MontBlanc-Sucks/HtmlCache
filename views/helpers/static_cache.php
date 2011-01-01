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
App::import('Helper', 'StaticCache.StaticCacheBase');

/**
 * HtmlCacheHelper class
 *
 * @uses          HtmlCacheBaseHelper
 * @package       html_cache
 * @subpackage    html_cache.views.helpers
 */
class StaticCacheHelper extends StaticCacheBaseHelper {
/**
 * construct method
 *
 * @param mixed $options
 * @return void
 * @access private
 */
  function __construct() {
    parent::__construct();
  }
}