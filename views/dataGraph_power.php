<?php 
$html .= '<div class="content">';
if( isset($loggedin) && $loggedin == TRUE ) {
	if( $enabled == FALSE ) {
		$html .= '<div id="power">Power Use Now: '.$powerNow.'W</div>';
	}
	if( $enabled == TRUE ) {
		$html .= '<div id="power">Power Use <br /> '.$date.': '.$powerToday.'W</div>';
	}
	$html	.= '<div id="chart"><canvas id="chartData"></canvas></div>';
	$html .= '<div class="buttons">';
	$html	.= '<span class="bbtn">Previous</span>';
	if( $enabled == TRUE ) {
		$html .= '<span class="fbtn">Next</span>';
	}
	else {
		$html .= '<span class="fbtn" style="visibility: hidden;">Next</span>';
	}
	$html .= '<div class="buttons-center">';
	$html .= '<span class="line">Line</span>';
	$html .= '<span class="bar">Bar</span>';
	$html .= '<span class="exportcsv">Export CSV</span>';
	$html .= '<span class="exportpng">Export PNG</span>';
	$html .= '</div>';
	$html .= '</div>';	
}
else {
	//$html .= '<a href="'.SITE_URL.'admin/login">Login</a>';
	//$html .= '</div>';	
	header( 'Location: '.SITE_URL.'admin/login' );
	die();
	//exit();
}
$html .= '</div>';
?>
