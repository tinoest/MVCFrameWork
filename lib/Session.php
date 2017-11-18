<?php

class Session
{

	public static function init() {{{
		
		if(session_status() == PHP_SESSION_NONE) {
			session_start();
			setcookie( session_name(), session_id(), 0, '/', 'tinoest.co.uk', TRUE, TRUE );
		}

	}}}

	public static function set($key, $value) {{{

		$_SESSION[$key] = $value;
	
	}}}

	public static function get($key) {{{

		if(array_key_exists($key, $_SESSION)) {
			return $_SESSION[$key];
		}

		return null;

	}}}

	public static function id() {{{

		return session_id();

	}}}

	public static function destroy() {{{

		session_destroy();
		$_SESSION = array();

	}}}

}
