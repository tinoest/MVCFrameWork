<?php

class baseController
{

	protected $controller;
	protected $action;
	protected $template;
	protected $loader;
	protected $login = FALSE;

	function __construct($loader, $controller, $action) {{{

		$this->controller = $controller;
		$this->action 		= $action;
		$this->loader			= $loader;
		$class						= str_replace( 'Controller', '', $controller );

		$model						= new Model( $class, $loader );
		$this->model = $model->class;
		$this->template = new Template( $class, $action, $loader );
		$this->set( 'siteName', SITE_NAME );
		$this->set( 'logo', SITE_LOGO );

		Session::init();
		if(Session::get('id')) {
			$this->login = TRUE;
		} 

		$data = array();
		$data[]						= 'home';
		//$data[]						= 'blog';
		//$data['examples'] = array ( 'c code' => array ( 'example' ) , 'js code' , 'php code' );
		$data[]						= array('ext' => array('url' => 'https://github.com/tinoest', 'name' => 'github'));
		//$data['sql']			= array ( 'sqlite' , 'postgresql' , 'mysql' );
		//$data['hobbies']	= array ( 'mini' , 'bike' );
		//$data['misc']			= array ( 'system status' ); 
		//$data['misc']			= array ( 'mail', 'system status', 'curriculum vitae' ); 
		$data['misc']			= array('mail', 'system status'); 
		
		if($this->login) {
			$data['data']			= array('graph temperature', 'graph battery', 'graph humidity', 'graph power'); 
		}

		$data[]						= array('ext' => array('url' => SITE_URL.'forum/', 'name' => 'forum'));
		$data[]						= array('ext' => array('url' => SITE_URL.'skydive/', 'name' => 'skydive'));
		//$data[]						= array ( 'ext' => array( 'url' => SITE_URL.'shop/' , 'name' => 'shop'));
		if( $this->login ) {
			$data['admin']	= array('logout');
		}
		else {
			$data['admin']	= array('login');
		}

		$this->set( 'menu', $data );

	}}}

	/**
	 * @param string $name
	 */
	function set($name, $value) {{{

			$this->template->set( $name, $value );

	}}}

	/**
	 * @param string $name
	 */
	function get($name) {{{

			return $this->template->get( $name );

	}}}

	function __destruct() {{{

		$this->template->render();

	}}}

	function login() {{{

		Session::set('id', Session::id());
		$this->login = TRUE;
		$this->loader->log->syslog( "SERVER ".var_export( $_SERVER['HTTP_REFERER'], TRUE ) );
		//if($this->graphRedirect === TRUE) {
			//header( 'Location: '.SITE_URL.'data/graph_power' );
		//}
		//else {
			header( 'Location: '.SITE_URL.'index' );
		//}
		return TRUE;

	}}}

	function logout($data) {{{

		$this->login = FALSE;
		Session::destroy();
		header( 'Location: '.SITE_URL.'admin' );
		die();
		return TRUE;

	}}}

	function get_login_state() {{{

		return $this->login;

	}}}

}

?>
