<?php 
$html	.= '<div class="content">
	<div id="contact">
		<h2>'.$content.'</h2>
			<form action="send_email" method="post" id="contactform">
				<fieldset>
					<label for="name">Name:</label>
					<input type="text" name="contactname" value="" id="contactname" value="" required />
					<label for="email">Email:</label>
					<input type="email" name="email" value="" id="email" value="" required />
					<label for="message">Message:</label>
					<textarea type="text" name="message" value="" id="message" required ></textarea>
				<input type="submit" value="Submit" name="submit"/>
			</fieldset>
		</form>
	</div>
</div>';
/*
$html	.= '<div class="content">
<div id="contact">
<form action="send_email" method="post">
Your Name:
<input type="text" name="name">
Email Address:
<input type="text" name="email">

Message:
<textarea name="message"></textarea>

<input type="submit" value="Submit">
</form>   

</div>
</div>';
*/

?>
