<?php

class adminController extends baseController
{

	function __construct($loader, $controller, $action) {{{
	
		parent::__construct( $loader, $controller, $action );


	}}}

	function display($data) {{{

		return TRUE;

	}}}

	function login( ) {{{

		//if(strpos($_SERVER['HTTP_REFERER'],'graph_power') !== false) {
			//$graphRedirect = TRUE;
		//}

		if( $this->login ) {
			return TRUE;
		}

		$username = $this->loader->post( 'username' );
		$password = $this->loader->post( 'password' );

		if( !is_null( $username ) && !is_null( $password ) ) {
			if( $this->model->login_check( $username, $password ) ) {
				parent::login();
			}
		}

		return TRUE;

	}}}

}


?>
