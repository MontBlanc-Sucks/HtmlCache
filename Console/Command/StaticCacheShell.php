<?php
App::uses('Shell', 'Console');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('StaticCacheCfg', 'StaticCache.Lib');

/**
 * StaticCache shell.
 *
 * @package       StaticCache
 * @subpackage    StaticCache.Console.Command
 */
class StaticCacheShell extends Shell {
 

	public $tasks = array('StaticCache.Setup', 'StaticCache.Config', 'StaticCache.Clear');
        private $_config  = null;

/**
 * Override startup
 *
 * @return void
 */
	public function startup() {
                $config = (!empty($this->params['config'])) ? $this->params['config'] : null;
                $this->_config = new StaticCacheCfg($config);
		$this->_welcome();
                print_r($this->_config->read());
                print_r($this->_config->available());
                print_r(Configure::read('StaticCache.*'));die();
	}

/**
 * get the option parser.
 *
 * @return void
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(
			'Static Cache.' . 
			'')
			->addSubcommand('status', array(
				'help' => __('Displays a status of StaticCache.')))
			->addSubcommand('setup', array(
				'help' => __('create cache and update htaccess optionally')))
			->addSubcommand('clear', array(
				'help' => __('Clear cache.')))
			->addSubcommand('config', array(
				'help' => __('Display or write config (if write the file will be in APP/Config/static_cache.ini use -c to set new name).')));		
		
	}

/**
 * Override main
 *
 * @return void
 */
	public function main() {
            $this->out('[I]nitialize Setup StaticCache (or re-initialize)');
            $this->out('[S]tatus check the status of StaticCache');
            $this->out('[A]lter configuration of StaticCache');
            $this->out('[C]lear');
            
            $action = $this->in(
            __('What would you like to do?', true),
            array('I', 'S', 'A', 'C', 'H', 'Q'),
            'q'
            );
            
            $this->out();
            
            switch (strtoupper($action)) {
            case 'I':
                $this->Config->init();
            break;
            case 'S':
                $this->Config->check();
            break;
            case 'C':
                $this->Clear->rm();
            break;
            case 'H':
                $this->help();
            break;
            case 'Q':
                $this->_stop();
            }
            $this->main();
        }

/**
 * Run the migrations
 *
 * @return void
 */
	public function status() {
            $this->Config->check();
        }

/**
 * Run the migrations
 *
 * @return void
 */
	public function clear() {
            $this->Clear->rm();
        }

/**
 * Run the migrations
 *
 * @return void
 */
	public function setup() {
            $this->Setup->init();
        }

/**
 * Clear the console
 *
 * @return void
 */
	protected function _clear() {
		$this->Dispatch->clear();
	}
    
}