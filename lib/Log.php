<?php

/*
 * This is a very simple Linux SYSLOG Class
 * Author: Martyn Brown
 * Version: 0.1
 * Revision History:
 * First intial Release 28-01-2012
 *
 */
class Log
{

public $prefix;

	function Log($facility = LOG_LOCAL5, $ident = __CLASS__, $option = FALSE) {{{ 

		if( empty($option) ) {
			$option = (LOG_PID | LOG_ODELAY);
		}
		$this->prefix = '';

		return openlog( $ident, $option, $facility );

	}}}

	function syslog($msg, $level = FALSE) {{{

			if( empty($level) ) {
				$priority = LOG_INFO;
			}
			else {
				$priority = (int) $level;
			}

			// Remove tab, newline etc to conform to rfc
			//$msg	= preg_replace('/[\t\r\n\f]+/', '', $msg);
			$len  = strlen( $msg );
			$p    = 0; // Linux has a length of 500 characters wrap the string
			while( $p < $len ) {
				$str  = substr( $msg, $p, '480' );
				$p    = $p + '480';
				syslog( $priority, $this->prefix.$str );
			}

	}}}

	function close() {{{

			return closelog();

	}}}

}
