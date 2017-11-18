<?php

class PostgreSQL
{

private $link;
private $host;
private $user;
private $pass;
private $db;
private $log;

// Takes host , user , pass , database
function __construct() {{{

	$data = func_get_args();
	$this->log = array_shift( $data );
	$params = array_shift( $data );
	$this->host	= $params[0];
	$this->user = $params[1];
	$this->pass	= $params[2];
	$this->db = $params[3];

	if( (is_resource( $this->link ) === FALSE) || (strpos( get_resource_type( $this->link ), 'pgsql' ) === FALSE) ) {
		$conn_str = sprintf( "host=%s dbname=%s user='%s' password='%s'", $this->host, $this->db, $this->user, $this->pass );
		$this->link = pg_connect( $conn_str ); 	
		if( !is_resource( $this->link ) ) {
			$err = pg_last_error(); 
			$this->log->syslog( "Fatal Error ".$err );
		}
	}

}}}

function reconnect() {{{

	@pg_close( $this->link );

	$conn_str = sprintf( "host=%s dbname=%s user='%s' password='%s'", $this->host, $this->db, $this->user, $this->pass );
	$this->link = pg_connect( $conn_str );	
	if( !$this->link ) {
		$err = pg_last_error(); 
		$this->log->syslog( "Fatal Error ".$err );
		return FALSE;
	}

	return TRUE;

}}}

function query() {{{

	if( !pg_ping( $this->link ) ) {
		if( !$this->reconnect() ) {
			return FALSE;
		}
	}
	$data 	= func_get_args();
	$sql = array_shift( $data );
	$esql		= $this->escape_data( $sql );
	$ret 		= pg_query( $this->link, $esql );
	if( !$ret ) {
		$err = pg_last_error( $this->link );
		$this->log->syslog( "Fatal Error ".$err );
		return FALSE;
	}

	return $ret;

}}}

function query_rows() {{{

	if( !pg_ping( $this->link ) ) {
		if( !$this->reconnect() ) {
			return FALSE;
		}
	}
	$values = array();
	$data = func_get_args();
	$sql = array_shift( $data );
	//$esql		= $this->escape_data($sql);
	$result = pg_query( $this->link, $sql );
	if( !$result ) {
		$err = pg_last_error( $this->link );
		$this->log->syslog( "Fatal Error ".$err );
		return FALSE;
	}
	while( $data = pg_fetch_array( $result, NULL, PGSQL_ASSOC ) ) {
			$values[] = $data;
	}
	pg_free_result( $result );

	return $values;

}}}

function escape_array($data) {{{
	
	return pg_escape_string( $this->link, $data );

}}}

function escape_data($data) {{{

		if( is_array( $data ) ) {
						return array_map( "escape_array", $data );
		}

		return pg_escape_string( $this->link, $data );

}}}

function __destruct() {{{

	return pg_close( $this->link );

}}}

}
?>
