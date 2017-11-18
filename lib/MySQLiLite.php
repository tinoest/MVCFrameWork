<?php
class MySQLiLite
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
	$sqlCredentials = array_shift( $data );
	$this->host	= $sqlCredentials[0];
	$this->user = $sqlCredentials[1];
	$this->pass	= $sqlCredentials[2];
	$this->db = $sqlCredentials[3];
	if( DEBUG ) {
			$this->log->syslog( "Trying to connect to ".$this->host." User: ".$this->user." Password: ".$this->pass." Database: ".$this->db );
	}

	$this->link = mysqli_init();
	if( !mysqli_real_connect( $this->link, $this->host, $this->user, $this->pass, $this->db ) ) {
		$err = mysqli_connect_error();
		$errno = mysqli_connect_errno(); 
		$this->log->syslog( "Fatal Error NO: ".$errno." Error String ".$err );
	}
	else {
		$this->log->syslog( "Connected to ".$this->host." successfully" );
	}
}}}

function reconnect() {{{

	@mysqli_close( $this->link );
	$this->link = mysqli_init();

	if( !mysqli_real_connect( $this->link, $this->host, $this->user, $this->pass, $this->db ) ) {
		$err   = mysqli_connect_error();
		$errno = mysqli_connect_errno(); 
		$this->log->syslog( "Fatal Error NO: ".$errno." Error String ".$err );
		return FALSE;
	}

	return TRUE;

}}}

function query() {{{

	if( !mysqli_ping( $this->link ) ) {
		if( !$this->reconnect() ) {
			return FALSE;
		}
	}
	$data = func_get_args();
	$sql = array_shift( $data );
	$esql	= $this->escape_data( $sql );
	$this->log->syslog( "Running Query ".$sql );
	$ret 	= mysqli_query( $this->link, $sql );
	if( !$ret ) {
		$err	 	= mysqli_error( $this->link );
		$errno	= mysqli_errno( $this->link );
		$this->log->syslog( "Fatal Error NO: ".$errno." Error String ".$err );
		return FALSE;
	}

	return $ret;

}}}

function query_rows() {{{

	if( !mysqli_ping( $this->link ) ) {
		if( !$this->reconnect() ) {
			return FALSE;
		}
	}
	$values = array();
	$data 	= func_get_args();
	$sql = array_shift( $data );
	$esql		= $this->escape_data( $sql );
	$this->log->syslog( "Running Query: ".$sql );
	$result = mysqli_query( $this->link, $sql );
	if( !$result ) {
		$err	 	= mysqli_error( $this->link );
		$errno	= mysqli_errno( $this->link );
		$this->log->syslog( "Fatal Error NO: ".$errno." Error String ".$err );
		return FALSE;
	}
	while( $data = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {
			$values[] = $data;
	}
	$this->log->syslog( "Returned results: ".var_export( $values, TRUE ) );
	mysqli_free_result( $result );

	return $values;

}}}

function escape_array($data) {{{
	
	return mysqli_real_escape_string( $this->link, $data );

}}}

function escape_data($data) {{{

		if( is_array( $data ) ) {
						return array_map( "escape_array", $data );
		}

		return mysqli_real_escape_string( $this->link, $data );

}}}

function __destruct() {{{

	return mysqli_close( $this->link );

}}}

}
?>
