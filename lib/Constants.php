<?php

// Set up values all keys are strtoupper , values are as typed
$defaults = array( 
	'rootdir' 								=> '/mvc',
	'libdir'									=> '/lib',
	'development_environment'	=> FALSE,
	'site_name' 							=> 'siteName',
	'site_url'								=> 'siteURL',
	'site_logo'								=> 'siteLogo',
	'admin_email'							=> 'user@gmail.com',
	'sql_user'								=> 'SQLuser',
	'sql_pass'								=> 'SQLuserPass',
	'sql_db'									=> 'databaseName',
	'sql_ip'									=> 'localhost',
	'sql_class'								=> 'PostgreSQL',
	'debug'										=> FALSE,
	'timezone'								=> 'Europe/London',
);



foreach( $defaults as $k => $v ) { 
	$key = strtoupper( $k );
	if( !defined( $key ) )
		define( $key, $v );
}


?>
