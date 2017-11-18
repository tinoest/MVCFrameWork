<?php
/*
	A very simple wrapper class around the SQL libriries , adds a simple abstraction layer so any can be utilised.



*/
class SQL
{

protected $sqlClass;
protected $link;

// Takes host , user , pass , database
function __construct() {{{

	$data = func_get_args();
	$this->log = array_shift( $data );
	$this->loader = array_shift( $data );
	$this->sqlClass = array_shift( $data );
	$this->link = $this->loader->add_lib( $this->sqlClass, $data );

}}}

function query() {{{

	return $this->link->query( func_get_args() );

}}}

function query_rows() {{{

	return $this->link->query_rows( func_get_args() );

}}}

function __destruct() {{{

	return $this->link->__destruct());

}}}

}
?>
