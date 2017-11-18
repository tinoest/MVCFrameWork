<?php

class miscController extends baseController
{


	function __construct($loader, $controller, $action) {{{
		parent::__construct( $loader, $controller, $action );


	}}}

	function display($data) {{{

		$data	= $this->get( 'menu' );
		$menu	= $data['misc'];

		$content = '<ul>';
		foreach( $menu as $k => $v ) {
			$content	.= '<li><a href="'.SITE_URL.'misc/'.str_replace( ' ', '_', $v ).'">'.ucfirst( $v ).'</a></li>';
		}
		$content		.= '</ul>';
		$this->set( 'content', $content );
		return TRUE;

	}}}

	function mail($data) {{{

		$this->set( 'content', 'Send me an email' );
		return TRUE;

	}}}

	function send_email() {{{

		foreach( array('contactname', 'email', 'message') as $value ) {
			$mail[$value] = $this->loader->post( $value );
			if( is_null( $mail[$value] ) ) {
				$this->set( 'content', "Mail Sending Failed" );
				return TRUE;
			}
		}

		if( $this->model->send_email( $mail ) ) {
			$this->set( 'content', "Mail Sent Successfully" );
		}
		else {
			$this->set( 'content', "Mail Sending Failed" );
		}

		return TRUE;

	}}}

	function system_status() {{{


		$content 	= '<pre></br><b>PHP Version:</b></br></br>'.phpversion();
		$conn     = pg_connect( "dbname=".SQL_DB." user=".SQL_USER." password=".SQL_PASS." host=".SQL_IP );
		if( $conn ) {
			$version = pg_version( $conn );
			$content .= '</br></br><b>postgreSQL Version:</b></br></br>'.$version['server'];
		}	
		$data			= shell_exec( "uptime" );
		$content 	.= '</br></br><b>Uptime:</b></br></br>'.$data;
		$data			= shell_exec( "uname -a" );
		$content 	.= '</br></br><b>System Information:</b></br></br>'.$data;
		$data			= shell_exec( "free -m" );
		//$data			= shell_exec("vm_stat");
		$content .= '</br></br><b>Memory Usage (MB):</b></br></br>'.$data;
		//$data			= shell_exec("df -h");
		//$content	.= '</br></br><b>Disk Usage:</b></br></br>'.$data;
		$data = shell_exec( "cat /proc/cpuinfo | grep \"model name\|processor\"" );
		$content .= '</br></br><b>CPU Information:</b></br></br>'.$data;
		$content .= '</pre>';

		$this->set( 'content', $content );
		return TRUE;

	}}}

	function curriculum_vitae() {{{

		//$content	= file_get_contents('/var/www/html/mvc/CV.pdf');
		//$this->set('content' , $content);
		return TRUE;
	}}}

}
?>
