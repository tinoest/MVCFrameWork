<?php
class Model
{

	public $class;

	function __construct($model, $loader) {{{

		if( file_exists( ROOTDIR.'/model/'.$model.'.php' ) ) {
			//$loader->log->syslog("Loading Model: $model");
			require_once(ROOTDIR.'/model/'.$model.'.php');
			$this->class = new $model( $loader );
			return TRUE;
		}
		else {
			$loader->log->syslog( "Failed to Load Model: $model" );
			return FALSE;
		}

	}}}
  
}
?>
