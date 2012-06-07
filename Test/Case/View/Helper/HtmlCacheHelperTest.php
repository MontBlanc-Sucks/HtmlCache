<?php
App::uses('HtmlCacheBaseHelper', 'HtmlCache.View/Helper');
App::uses('HtmlCacheHelper', 'HtmlCache.View/Helper');
App::uses('View', 'View');
App::uses('Folder', 'Utility');

class HtmlCacheHelperTest extends CakeTestCase {
	public $www_root = null;

	public function setUp() {
		$this->www_root = APP . 'Plugin' . DS . 'HtmlCache' . DS . 'Test' . DS . 'test_app' . DS . 'webroot' . DS;
		$controller = null;
		$this->View = new View($controller);
		$this->View->HtmlCache = new HtmlCacheHelper($this->View);
		$this->View->HtmlCache->options(array('test_mode' => true, 'www_root' => $this->www_root));
		$this->View->HtmlCache->here = '/posts';
	}

	public function tearDown() {
		$Folder = new Folder();
		$Folder->delete($this->www_root . 'cache');
	}

	public function testInstances() {
		$this->assertTrue(is_a($this->View, 'View'));
	}

	public function testOption() {
		$options = array('test_mode' => true, 'www_root' => $this->www_root);
		$this->View->HtmlCache->options($options);
		$results = $this->View->HtmlCache->options;
		$expected = array(
			'host' => null,
			'domain' => false,
			'cache_dir' => 'cache',
			'test_mode' => true,
			'www_root' => $this->www_root,
		);
		$this->assertEquals($expected, $results);
	}

	public function testWriteCache() {
		$expected = <<<END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en">
<head>
    <title>HtmlCacheHelper Test Title</title>
</head>
<body>
    HtmlCacheHelper Test Body
</body>
</html>
END;

		$this->View->output = $expected;
		$this->View->HtmlCache->afterLayout();

		$path = $this->www_root . 'cache' . DS . 'posts' . DS . 'index.html';
		$this->assertTrue(file_exists($path));
		$cached = file_get_contents($path);
		$this->assertEquals($expected, $cached);
	}
}
