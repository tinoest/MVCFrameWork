<?php

class examplesController extends baseController
{

		function __construct($loader, $controller, $action) {{{
		parent::__construct( $loader, $controller, $action );


	}}}

	function display($data) {{{

		$this->set( 'content', 'Stuff about my hobbies goes here' );
		return TRUE;


	}}}

	function js_code($data) {{{

		$this->set( 'content', 'JS related stuff goes here' );
		return TRUE;

	}}}

	function php_code($data) {{{

		$this->set( 'content', 'PHP related stuff goes here' );
		return TRUE;

	}}}

}




?>
