<?php
class Template
{

	protected $variables = array();
	protected $controller;
	protected $action;
	public		$loader;

	function __construct($controller, $action, $loader) {{{

		$this->controller = $controller;
		$this->action 		= ucfirst( $action );
		$this->loader			= $loader;

	}}}

	function set($name, $value) {{{

		$this->variables[$name] = $value;

	}}}

	function get($name) {{{

		return $this->variables[$name];

	}}}

	function render() {{{

		extract( $this->variables );

		$this->loader->log->syslog( "Trying to load view: ".$this->controller." Action ".$this->action );

		// If specific view doesn't exist load standard in all instances
		if( file_exists( ROOTDIR.'/views/'.$this->controller.'header.php' ) ) {
			require_once(ROOTDIR.'/views/'.$this->controller.'header.php');
		}
		else {
			require_once(ROOTDIR.'/views/header.php');
		}

		if( file_exists( ROOTDIR.'/views/'.$this->controller.$this->action.'.php' ) ) {
			require_once(ROOTDIR.'/views/'.$this->controller.$this->action.'.php');		 
		}
		else {
			require_once(ROOTDIR.'/views/base.php');
		}

		if( file_exists( ROOTDIR.'/views/'.$this->controller.'footer.php' ) ) {
			require_once(ROOTDIR.'/views/'.$this->controller.'footer.php');
		}
		else {
			require_once(ROOTDIR.'/views/footer.php');
		}

	}}}

}
?>
