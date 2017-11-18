<?php 
$html	.= '<div class="content">';
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
$html .= '</div>';
?>
