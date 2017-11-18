<?php

class admin extends baseModel
{

	function __construct($loader) {{{
		parent::__construct( $loader );

		$this->sql = $this->loader->add_lib( SQL_CLASS, SQL_IP, SQL_USER, SQL_PASS, SQL_DB );
		return TRUE;

	}}}

	function login_check($username, $password) {{{

		$sql = "SELECT password_hash FROM users WHERE username = '$username' AND enabled = TRUE;";
		$this->loader->log->syslog( $sql );
		$data = $this->sql->query_rows( $sql );	
		if( is_array( $data ) && count( $data ) ) {
			if( password_verify( $password, $data[0]['password_hash'] ) ) {
				return TRUE;
			}
		}

		return FALSE;

	}}}

}

?>
