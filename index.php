<?php

function set_error_reporting() {{{

	if( DEVELOPMENT_ENVIRONMENT == true ) {
		error_reporting( E_ALL );
		ini_set( 'display_errors', 'On' );
	}
	else {
		error_reporting( E_ALL );
		ini_set( 'display_errors', 'Off' );
	}

}}}

function strip_slashes_deep($value) {{{

	$value = is_array( $value ) ? array_map( 'strip_slashes_deep', $value ) : stripslashes( $value );
	return $value;

}}}

function remove_magic_quotes() {{{

	if( get_magic_quotes_gpc() ) {
		$_GET    = strip_slashes_deep( $_GET );
		$_POST   = strip_slashes_deep( $_POST );
		$_COOKIE = strip_slashes_deep( $_COOKIE );
	}

}}}

function unregister_globals() {{{

		if( ini_get( 'register_globals' ) ) {
				$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
				foreach( $array as $value ) {
						foreach( $GLOBALS[$value] as $key => $var ) {
								if( $var === $GLOBALS[$key] ) {
										unset($GLOBALS[$key]);
								}
						}
				}
		}

}}}

// Load all the constants
require_once('lib/Constants.php');

ob_start( "ob_gzhandler" );

date_default_timezone_set( TIMEZONE );
// Validate everything
set_error_reporting();
remove_magic_quotes();
unregister_globals();

// Load the main loader class
require_once('lib/Loader.php');
require_once('lib/Log.php');
require_once('lib/Session.php');

$log = new Log( LOG_LOCAL5, 'mvc' );

//$log->syslog("POST: ".var_export($_POST,TRUE));
//$log->syslog("GET: ".var_export($_GET,TRUE));
//$log->syslog("URI: ".var_export($_SERVER['REQUEST_URI'],TRUE), LOG_INFO);

if( !empty($_GET['url']) && $_GET['url'] != '/' && $_GET['url'] != '/home' ) {
	$url		= strtok( $_GET['url'], '?' );
} 
else {
	$url		= 'index/display';
}

$loader = new Loader( $url, $log );

$page = $loader->load();

if( !$page && !is_array( $page ) ) {
	header( 'Location: '.SITE_URL.'error/display/' );
}

/* vim: set tw=2 sw=2 et */ 
?>
