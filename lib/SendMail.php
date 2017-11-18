<?php

class SendMail
{

	public	$attachments;
	public	$boundary;
	public	$mailfrom;
	private $username;
	private $password;
	private $log;

	function __construct() {{{

		$args 							= func_get_args();
		$this->log = array_shift( $args );
		$ret = array_shift( $args );
		$user								= $ret[0];
		$password = $ret[1];
		if( count( $ret ) > 2 ) {
			$from							= $ret[2];
		}
		else {
			$from							= $user; 
		}
		$this->attachments  = array();
		$this->boundary = '-----'.md5( time() ).'-----';
		$this->mailfrom	    = '<'.$from.'>';
		$this->username	    = $user;
		$this->password	    = $password;
		$this->crlf = "\r\n";

		$this->log->syslog( "Loaded SendMail" );

	}}}

	/**
	 * @param string|null $expected_response
	 * @param null|string $message
	 */
	function server_parse($message, $socket, $expected_response, &$response = null) {{{

		// Have a message to send
		if( !is_null( $message ) ) {
					fwrite( $socket, $message.$this->crlf );
		}

		// This should always be null
		if( !is_null( $response ) ) {
					$response = null;
		}

		// We expect a response
		if( !is_null( $expected_response ) ) {
			$server_response = '';
			while( substr( $server_response, 3, 1 ) != ' ' ) {
				if( !($server_response = fgets( $socket, 256 )) ) {
					$this->log->syslog( "Couldn't get mail server response code, expected: $expected_response. Please contact the administrator." );
					return FALSE;
				}
				$response .= $server_response;
			}

			if( !(substr( $server_response, 0, 3 ) == $expected_response) ) {
				$this->log->syslog( "Unable to send e-mail. Please contact the administrator with the following error message reported by the SMTP server: ".var_export( $server_response, TRUE ) );        
				return FALSE;
			}
		}

		return TRUE;

	}}}

	/**
	 * @param string $content
	 */
	function smtp_mail($to, $subject, $content) {{{

		$recipients = explode( ',', $to );
		$user				= $this->username;
		$pass				= $this->password;
		$smtp_host	= SITE_SMTP_HOST;
		$smtp_port	= (int) SITE_SMTP_PORT;

		if( ($smtp_port === 465) && (substr( $smtp_host, 0, 6 ) !== "ssl://" && substr( $smtp_host, 0, 6 ) !== "tls://") ) {
			$smtp_host = 'ssl://'.$smtp_host;
		}

		$this->log->syslog( "Sending email to: ".var_export( $to, TRUE ) );

		if( function_exists( 'stream_socket_client' ) ) {
			$socket	= stream_socket_client( $smtp_host.':'.$smtp_port, $errno, $errstr, 15 );
		}
		else {
			$socket = fsockopen( $smtp_host, $smtp_port, $errno, $errstr, 15 ); 
		}

		if( !$socket ) {
			$this->log->syslog( "Could not connect to smtp host '$smtp_host' ($errno) ($errstr)" );
		}

		if( !$this->server_parse( null, $socket, '220' ) ) {
			return FALSE; 
		}

		$helo			= FALSE;
		if( function_exists( 'gethostname' ) && gethostname() !== false ) {
			$helo		= gethostname();
		}
		elseif( function_exists( 'php_uname' ) ) {
			$helo		= php_uname( 'n' );
		}
		elseif( array_key_exists( 'SERVER_NAME', $_SERVER ) && !empty($_SERVER['SERVER_NAME']) ) {
			$helo		= $_SERVER['SERVER_NAME'];
		}

		if( empty($helo) ) { // This is bad practise but everything else failed.
			$helo = 'localhost';
		}

		$message = 'EHLO '.$helo;
		if( !$this->server_parse( $message, $socket, '250', $response ) ) {
			return FALSE; 
		}

		if( $smtp_port === 587 ) {
			if( preg_match( "/250( |-)STARTTLS/mi", $response ) ) {
				$message = 'STARTTLS';
				if( !$this->server_parse( $message, $socket, '220' ) ) {
					return FALSE; 
				}

				if( !@stream_socket_enable_crypto( $socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT ) ) {
					return FALSE; 
				}

				$message = 'EHLO '.$helo;
				if( !$this->server_parse( $message, $socket, '250', $response ) ) {
					return FALSE;
				}
			}
			else {
				// Port 587 requires STARTTLS so can't continue
				return FALSE;
			}
		}

		if( preg_match( "/250( |-)AUTH( |=)(.+)$/mi", $response, $matches ) === FALSE ) {
			$this->log->syslog( "LOGIN information not returned by mail server" );
			return FALSE;
		}

		$logins = explode( ' ', $matches[3] );
		if( in_array( 'LOGIN', $logins ) ) {
			$message = 'AUTH LOGIN';
			if( !$this->server_parse( $message, $socket, '334' ) ) {
				return FALSE; 
			}

			$message = base64_encode( $user );
			if( !$this->server_parse( $message, $socket, '334' ) ) {
				return FALSE; 
			}

			$message = base64_encode( $pass );
			if( !$this->server_parse( $message, $socket, '235' ) ) {
				return FALSE; 
			}

		}
		elseif( in_array( 'PLAIN', $logins ) ) {
			$message = 'AUTH PLAIN';
			if( !$this->server_parse( $message, $socket, '334' ) ) {
				return FALSE; 
			}
				
			$message = base64_encode( chr( 0 ).$user.chr( 0 ).$pass );
			if( !$this->server_parse( $message, $socket, '235' ) ) {
				return FALSE; 
			}

		}
		else {
			$this->log->syslog( "Unsupported AUTH type returned by mail server" );
			return FALSE;
		}


		$message = 'MAIL FROM: '.$this->mailfrom;
		if( !$this->server_parse( $message, $socket, '250' ) ) {
			return FALSE; 
		}

		foreach( $recipients as $email ) {
			$message = 'RCPT TO: <'.$email.'>';
			if( !$this->server_parse( $message, $socket, '250' ) ) {
				return FALSE;
			}
		}

		$message = 'DATA';
		if( !$this->server_parse( $message, $socket, '354' ) ) {
			return FALSE;
		}

		$message	= 'Date: '.date( 'r' );
		$this->server_parse( $message, $socket, null );

		$message	= 'Subject: '.$subject;
		$this->server_parse( $message, $socket, null );
		
		$message	= 'To: <'.implode( '>, <', $recipients ).'>';
		$this->server_parse( $message, $socket, null );
		
		$message	= ''; //Sends the additional \r\n required after headers
		$this->server_parse( $message, $socket, null );
		
		$message	= $content;
		$this->server_parse( $message, $socket, null );

		$message = '.';
		if( !$this->server_parse( $message, $socket, '250' ) ) {
			return FALSE;
		}

		$message = 'QUIT';
		$this->server_parse( $message, $socket, null );
		fclose( $socket );

		$this->log->syslog( "Sent email to: ".var_export( $to, TRUE ) );

		return TRUE;

	}}}

	function send_attachment_mail($to, $subject, $body, $attachmentData, $attachmentName = 'attachment', $attachmentType = 'text/csv') {{{

		$attachment = chunk_split( base64_encode( $attachmentData ) );

		$body = "--$this->boundary\r\n"
			. "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n"
			. "Content-Transfer-Encoding: 7bit\r\n"
			. "\r\n"
			. "$body\r\n"
			. "--$this->boundary\r\n"
			. "Content-Type: $attachmentType\r\n"
			. "Content-Transfer-Encoding: base64\r\n"
			. "Content-Disposition: attachment; filename=\"$attachmentName\"\r\n"
			. "\r\n"
			. "$attachment\r\n"
			. "--$this->boundary--";

		return $this->smtp_mail( $to, $subject, $body );

	}}}

}

?>
