<?php

class blogController extends baseController
{

		function __construct($loader, $controller, $action) {{{
		parent::__construct( $loader, $controller, $action );


	}}}
	
	function display($data) {{{

		// This should always be empty for this action
		if( !empty($action) ) {
			return FALSE;
		}

		return TRUE;

		$posts = $this->model->display_posts();

		$content 		= '';
		foreach( $posts as $post ) {
			$content .= '<div class="main-container">';
			$content .= '<div class="title-container">';
			$content .= '<div class="title-name">'.$post['title'].'</div>';
			$content .= '<div class="title-author">Author: '.$post['name'].'</div>';
			$content .= '</div>';
			$content .= '<div class="content-container">';
			$content .= $post['content'];
			$content .= '</div>';
			$content .= '</div>';
		}

		$this->set( 'content', $content );

		return TRUE;

	}}}

}




?>
