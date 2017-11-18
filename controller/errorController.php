<?php

class errorController extends baseController
{

	function __construct($loader, $controller, $action) {{{
		parent::__construct( $loader, $controller, $action );


	}}}
	
	function display($data) {{{

		// This should always be empty for this action
		if( !empty($action) ) {
			return FALSE;
		}

		$this->set( 'content', 'Sorry : the page you were looking for can not be found' );

		return TRUE;

	}}}

}




?>
