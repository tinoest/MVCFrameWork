<?php

class index extends baseModel
{

	function __construct($loader) {{{
		parent::__construct( $loader );
		
		// $this->sql = $this->loader->add_lib( SQL_CLASS , SQL_IP , SQL_USER, SQL_PASS , SQL_DB  );
		return TRUE;

	}}}

	function display_posts() {{{

		$sql = "SELECT title , content , author_id FROM posts WHERE enabled = '1'";
		$ret = $this->sql->query( $sql );
		

	}}}

}

?>
