<?php

class blog extends baseModel
{

	function __construct($loader) {{{
		parent::__construct( $loader );
		
		$this->sql = $this->loader->add_lib( SQL_CLASS, SQL_IP, SQL_USER, SQL_PASS, SQL_DB );
		return TRUE;

	}}}

	function display_posts() {{{

		$sql = "SELECT p.title AS title , p.content AS content , p.dt_posted AS dt_posted , a.name AS name FROM posts AS p INNER JOIN author AS a ON a.id = p.author_id WHERE enabled = 1 ORDER BY dt_posted DESC";

		return $this->sql->query_rows( $sql );

	}}}

}

?>
