<?php

class postController extends baseController
{

		function __construct($loader, $controller, $action) {{{
		parent::__construct( $loader, $controller, $action );


	}}}
	
	function display($data) {{{

		// This should always be empty for this action
		if( !empty($action) ) {
			return FALSE;
		}

		$content = '';

		$this->set( 'content', $content );

		return TRUE;

	}}}

	function create($data = array()) {{{

		if( $this->login === FALSE ) {
			$content = '<div class="login">
			<form id=\'login\' action=\'login\' method=\'post\' accept-charset=\'UTF-8\'>
				<fieldset >
					<legend>Login</legend>
					<input type=\'hidden\' name=\'submitted\' id=\'submitted\' value=\'1\'/>
					<label for=\'username\' >UserName:</label>
					<input type=\'text\' name=\'username\' id=\'username\'  maxlength="50" />
					<label for=\'password\' >Password:</label>
					<input type=\'password\' name=\'password\' id=\'password\' maxlength="50" />
					<input type=\'submit\' name=\'Submit\' value=\'Submit\' />
 				</fieldset>
			</form>
			</div>';
		}
		else {
			$content = '<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
//<![CDATA[
        bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
  //]]>
  </script>
  	<h4>
    	Create Post
  	</h4>
		<form method="POST" action="submit">
		Title: <input id="post-title" type="text" name="post-title"></input></br>
  	<textarea id="post-content" name="post-content" value=""></textarea>
		<input type="submit" id="post-content-submit" value="submit"/>
	</form>';
		}
		$this->set( 'content', $content );

		return TRUE;

	}}}

	function edit() {{{

		$content = 'edit post';

		$this->set( 'content', $content );

		return TRUE;

	}}}

	function remove() {{{

		$content = 'remove post';

		$this->set( 'content', $content );

		return TRUE;

	}}}

function submit($data) {{{

	var_dump( $_POST );
	$content = array();
	$content['title'] = $_POST['post-title'];
	$content['content'] = $_POST['post-content'];
	$this->model->submit_post( $content );

	return TRUE;
}}}

		function login() {{{
			
			$data = array();
			$data['username'] = $_POST['username'];
			$data['password']	= $_POST['password'];
			$ret = $this->model->check_login( $data );
			if( is_array( $ret ) && count( $ret ) > 0 ) {
				$_SESSION['login'] = TRUE;
			}

			header( 'Location: '.SITE_URL.'post/create' );
			return TRUE;

		}}}
}




?>
