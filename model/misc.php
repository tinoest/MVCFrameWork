<?php

class misc extends baseModel
{

	private $mail;

	function __construct($loader) {{{
		parent::__construct($loader);
		

		$this->mail = $this->loader->add_lib('SendMail' , SMTP_USERNAME , SMTP_PASSWORD );


		return TRUE;

	}}}

	function send_email ( $mail ) {{{

		$to 			= ADMIN_EMAIL;
		$subject	= "Mail from ".SITE_NAME;
		$msg			= $mail['contactname'].' '.$mail['email']."\n";
		$msg			.= $mail['message'];
		// smtp_mail($to, $subject, $message, $headers = '')
		
		return $this->mail->smtp_mail ( $to , $subject , $msg );

	}}}


}

?>
