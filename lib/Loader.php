<?php
define( 'POST', 'post' );
define( 'GET', 'get' );

class Loader
{

	private $controller;
	private $action;
	private	$queryString;
	private $bodyParams;
	private $requestType;
	public $log;

	function __construct($url, $log) {{{

		$this->bodyParams = null;
		$this->log	= &$log;
		$urlArray 	= array();
		$urlArray		= explode( "/", $url );
		if( substr( $url, 0, 1 ) == '/' ) {
			array_shift( $urlArray );
		}
		// Remove any empty values
		for( $i = 0; $i < sizeof( $urlArray ); $i++ ) {
			if( empty($urlArray[$i]) ) {
				unset($urlArray[$i]);
			}
		}
		// Reindex the array
		$urlArray = array_values( $urlArray );

		$this->log->syslog( "URL array: ".var_export( $urlArray, TRUE ) );
		$this->log->syslog( "URL : ".var_export( $url, TRUE ) );

		$this->controller = array_shift( $urlArray ).'Controller';
		if( count( $urlArray ) ) {
			$this->action = array_shift( $urlArray );
		}
		else {
			$this->action	= 'display';
		}

		if( count( $urlArray ) == 1 ) {
			$this->queryString	= array_shift( $urlArray );
		}
		elseif( count( $urlArray ) >= 2 ) {
			$this->queryString	= $urlArray;
		}

	}}}

	function load() {{{

		require_once(ROOTDIR.'/controller/baseController.php');
		require_once(ROOTDIR.'/model/baseModel.php');
		require_once(ROOTDIR.'/lib/Template.php');
		require_once(ROOTDIR.'/lib/Model.php');
		require_once(ROOTDIR.'/lib/helper.php');

		if( !is_file( ROOTDIR.'/controller/'.$this->controller.'.php' ) ) {
			$this->log->syslog( "Controller $this->controller not found" );
			return FALSE;
		}

		require_once(ROOTDIR.'/controller/'.$this->controller.'.php');
		
		$dispatch = new $this->controller( $this, $this->controller, $this->action );
		if( method_exists( $this->controller, $this->action ) ) {
			$this->log->syslog( "Action $this->action found in $this->controller" );
			return call_user_func_array( array($dispatch, $this->action), array($this->queryString) );
		}
		else {
			$this->log->syslog( "Action not found $this->action in $this->controller" );
			return FALSE;
		}

	}}}

	function add_lib() {{{

			$args 	= func_get_args();
			$class	= array_shift( $args );
			if( !class_exists( $class ) ) {
				if( file_exists( ROOTDIR.'/lib/'.$class.'.php' ) ) {
					require_once(ROOTDIR.'/lib/'.$class.'.php');
					return new $class( $this->log, $args );
				}
			}

			$this->log->syslog( "Failed to find Class: ".var_export( $class, TRUE ) );

			return FALSE;

	}}}

	function post($name, $defaultValue = null) {{{

		$this->requestType = POST;
		return $this->get_body_param( $name, $defaultValue );

	}}}

	function get($name, $defaultValue = null) {{{

		$this->requestType = GET;
		return $this->get_body_param( $name, $defaultValue );

	}}}

	function get_body_params() {{{

		if( is_null( $this->bodyParams ) ) {
			$this->bodyParams[POST] = $_POST;
			$this->bodyParams[GET] = $_GET;
		}

		return $this->bodyParams[$this->requestType];

	}}}

 function get_body_param($name, $defaultValue = null) {{{

		$params = $this->get_body_params();

		if( array_key_exists( $name, $params ) && !empty($params[$name]) ) {
			return $params[$name];
		}
		else {
			return $defaultValue;
		}

	}}}

	}
?>
