<?php 
$html = '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />';
if( DEVELOPMENT_ENVIRONMENT == true ) {
	$html	.= '<link rel="stylesheet" type="text/css" href="'.SITE_URL.'mvc/css/core.css" />';
	$html	.= '<link rel="stylesheet" type="text/css" href="'.SITE_URL.'mvc/css/contact.css" />';
	$html	.= '<link rel="stylesheet" type="text/css" href="'.SITE_URL.'mvc/css/responsive-menu.css" />';
} 
else {
	$html	.= '<link rel="stylesheet" type="text/css" href="'.SITE_URL.'mvc/css/min/core.min.css?'.filemtime( ROOTDIR.'/css/min/core.min.css' ).'" />';
}
$html	.= '<title>'.$siteName.'</title>
</head>
<body>';
$html	.= '<div class="site-header"><div class="logo"><img src="'.$logo.'" alt="mini image"><div class="site-name">'.$siteName.'</div></div></div>';
//$html	.= '<div class="logo"></div>';
$html .= '<div class="nav">'.create_menu( $menu ).'</div>';

?>


