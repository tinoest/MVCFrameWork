<?php

	/**
	 * @param string $root
	 * @param string $menu
	 */
	function sub_menu($root, $tmp, &$menu, $sub = FALSE, $parent = FALSE) {{{

		if( $sub !== FALSE ) {
				if( $parent !== FALSE ) {
					// Handle it here
				}
				$menu .= '<ul class="hidden"><a href="'.SITE_URL.str_replace( ' ', '_', $sub ).'">'.ucwords( $sub ).'</a>'.PHP_EOL;
		}
		$menu .= '<ul class="hidden">'.PHP_EOL;

		foreach( $tmp as $kk => $vv ) {
			
			if( is_array( $vv ) ) {
				sub_menu( $kk, $vv, $menu, $kk, $root );
			}
			else {
				$menu .= '<li>'.PHP_EOL;
				$menu .= '<a href="'.SITE_URL.str_replace( ' ', '_', $root ).'/'.str_replace( ' ', '_', $vv ).'">'.ucwords( $vv ).'</a>'.PHP_EOL;
			}
		}
		$menu .= '</li>'.PHP_EOL;
		$menu .= '</ul>'.PHP_EOL;

	}}}

	function create_menu($data) {{{

		$menu		 = '<label for="show-menu" class="show-menu">Show Menu</label>'.PHP_EOL;
		$menu		.= '<input type="checkbox" id="show-menu" role="button">'.PHP_EOL;
		$menu		.= '<ul id="menu">'.PHP_EOL;
		foreach( $data as $k => $v ) {
			if( !is_numeric( $k ) ) {
				$menu .= '<li><a href="'.SITE_URL.$k.'">'.ucwords( $k ).'</a>'.PHP_EOL;
				if( is_array( $v ) ) {
						sub_menu( $k, $v, $menu );
				}
				else {
					$menu .= '<li><a href="'.SITE_URL.$k.'/'.$v.'">'.ucwords( $v ).'</a>'.PHP_EOL;
				}
			}
			else {
				if( is_array( $v ) && array_key_exists( 'ext', $v ) ) {
					$menu .= '<li><a href="'.$v['ext']['url'].'">'.ucwords( $v['ext']['name'] ).'</a>'.PHP_EOL;												
				}
				else {
					$menu .= '<li><a href="'.SITE_URL.$v.'">'.ucwords( $v ).'</a>'.PHP_EOL;
				}
			}
		}
		$menu .= '</li>'.PHP_EOL;
		$menu .= '</ul>'.PHP_EOL;

		return $menu;

	}}}

	function download_file($fullPath) {{{

		// Must be fresh start
		if( headers_sent() ) {
					die('Headers Sent');
		}

		// Required for some browsers
		if( ini_get( 'zlib.output_compression' ) ) {
					ini_set( 'zlib.output_compression', 'Off' );
		}

		// File Exists?
	//  if( file_exists($fullPath) ){

			// Parse Info / Get Extension
			$fsize = filesize( $fullPath );
			$path_parts = pathinfo( $fullPath );
			$ext = strtolower( $path_parts["extension"] );

			// Determine Content Type
			switch( $ext ) {
				case "pdf": 
					$ctype = "application/pdf"; 
				break;
				case "exe": 
					$ctype = "application/octet-stream"; 
				break;
				case "zip": 
					$ctype = "application/zip"; 
				break;
				case "doc": 
					$ctype = "application/msword"; 
				break;
				case "xls": 
					$ctype = "application/vnd.ms-excel"; 
				break;
				case "ppt": 
					$ctype = "application/vnd.ms-powerpoint"; 
				break;
				case "gif": 
					$ctype = "image/gif"; 
				break;
				case "png": 
					$ctype = "image/png"; 
				break;
				case "jpeg":
				case "jpg": 
					$ctype = "image/jpg"; 
				break;
				default: 
					$ctype = "application/force-download";
				break;
			}

			header( "Pragma: public" ); // required
			header( "Expires: 0" );
			header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
			header( "Cache-Control: private", false ); // required for certain browsers
			header( "Content-Type: $ctype" );
			header( "Content-Disposition: attachment; filename=\"".basename( $fullPath )."\";" );
			header( "Content-Transfer-Encoding: binary" );
			header( "Content-Length: ".$fsize );
			ob_clean();
			flush();
			readfile( $fullPath );
	}}}

?>
