<?php

class sqlController extends baseController
{

		function __construct($loader, $controller, $action) {{{
		parent::__construct( $loader, $controller, $action );


	}}}

	function display($data) {{{

		$data	= $this->get( 'menu' );
		$menu	= $data['sql'];

		$content = '<ul>';
		foreach( $menu as $k => $v ) {
			$content	.= '<li><a href="'.SITE_URL.'sql/'.$v.'">'.ucfirst( $v ).'</a></li>';
		}
		$content		.= '</ul>';
		$this->set( 'content', $content );
		return TRUE;


	}}}

	function sqlite($data) {{{

		$code = file_get_contents( LIBDIR.'/SQLite.php' );
		$content 	= '<pre>'.highlight_string( $code, TRUE ).'</pre>';
		$content	.= 'You can download this class from the following location : <a href="'.SITE_URL.'dl/SQLite.tar.gz" target="_blank">Download Link</a>';
		$this->set( 'content', $content );
		return TRUE;

	}}}

	function postgresql($data) {{{

		$code = file_get_contents( LIBDIR.'/PostgreSQL.php' );
		$content 	= '<pre>'.highlight_string( $code, TRUE ).'</pre>';
		$content	.= 'You can download this class from the following location : <a href="'.SITE_URL.'dl/PostgreSQL.tar.gz" target="_blank">Download Link</a>';
		$this->set( 'content', $content );
		return TRUE;

	}}}

	function mysql($data) {{{

		$code = file_get_contents( LIBDIR.'/MySQLiLite.php' );
		$content 	= '<pre>'.highlight_string( $code, TRUE ).'</pre>';
		$content	.= 'You can download this class from the following location : <a href="'.SITE_URL.'dl/MySQLi.tar.gz" target="_blank">Download Link</a>';
		$this->set( 'content', $content );
//		$this->set('download', 'MySQI.php');
		return TRUE;

	}}}

	function download($data) {{{
		
		
		return TRUE;

	}}}

}




?>
