<?php 
$html	.= '<div class="content">';
$html .= '<div id="chart" style="height: 400px; margin: 0 auto"></div>';
$html	.= '<div id="bbtn"><button type="button" onclick="myBackFunction()">Back</button></div>';
if( $enabled == TRUE ) {
	$html .= '<div id="fbtn"><button type="button" onclick="myForwardFunction()">Forward</button></div>';
}
$html .= '</div>/'
?>
