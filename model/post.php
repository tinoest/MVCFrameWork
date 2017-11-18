<?php

class post extends baseModel
{

	function __construct($loader) {{{
		parent::__construct( $loader );
		
		$this->sql = $this->loader->add_lib( SQL_CLASS, SQL_IP, SQL_USER, SQL_PASS, SQL_DB );
		return TRUE;

	}}}

	function submit_post($data) {{{

		$sql = "INSERT INTO posts ( title , content , author_id , enabled ) VALUES ( '{$data['title']}' , '{$data['content']}' , '1' , '1' )";
		return $this->sql->query( $sql );

	}}}

	function check_login($data) {{{
		
		$sql = "SELECT id FROM author WHERE name = '{$data['username']}' AND password = '{$data['password']}'";
		return $this->sql->query_rows( $sql );
	
	}}}
}

?>
