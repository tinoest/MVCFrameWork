<?php

class sqlite
{

private $db;

function __construct() {{{

	$this->db = sqlite_open( $this->database, 0666, $sqliteerror );

}}}

function query() {{{

	$data = func_get_args();
	$sql = array_shift( $data );
	$esql	= $this->escape_data( $sql );
	$ret 	= sqlite_query( $this->db, $esql );

	return $ret;

}}}

function query_rows() {{{

	$values = array();
	$data = func_get_args();
	$sql = array_shift( $data );
	$esql	= $this->escape_data( $sql );
	$result = sqlite_query( $this->db, $esql );
	while( $data = sqlite_fetch_array( $result, SQLITE_ASSOC ) ) {
			$values[] = $data;
	}

	return $values;

}}}

function escape_data($data) {{{

		if( is_array( $data ) ) {
						return array_map( "sqlite_escape_string", $data );
		}

		return sqlite_escape_string( $data );

}}}

function __destruct() {{{

	return sqlite_close( $this->db );

}}}

}
?>
