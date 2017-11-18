<?php

class indexController extends baseController
{

	function __construct($loader, $controller, $action) {{{
		parent::__construct( $loader, $controller, $action );
	}}}
	
	function display($data) {{{

		// This should always be empty for this action
		if( !empty($action) ) {
			return FALSE;
		}


		$content = '<br /><br /><br /><br />Welcome to my website<br /><br /> This is currently a work in progress about the things that I enjoy and like to do. Various sections will be added and or expanded as I slowly build new sections of this website. If you have any questions please send me an email via the built in <a href="'.SITE_URL.'misc/mail">Mail module</a>. <br /> <br />	Thanks for looking,<br /><br />	tino';

		$this->set( 'content', $content );

		return TRUE;

	}}}

}




?>
