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
$html .= '<title>'.$siteName.'</title>';
$html .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" type="text/javascript"></script>';
$html .= '<script src="//tinoest.co.uk/mvc/js/Chart.js?'.filemtime( ROOTDIR.'/js/Chart.js' ).'"></script>';
$html .= '</head>';
$html .= '<body>';
$html	.= '<div class="site-header"><div class="logo"><img src="'.$logo.'" alt="mini image"><div class="site-name">'.$siteName.'</div></div></div>';
$html .= '<div class="nav">'.create_menu( $menu ).'</div>';

$html .= '<script type="text/javascript">
$(document).ready(function() 
{
	graphData(false,false);
});

function resetCanvas() {
	$(\'#chartData\').remove(); 
	$(\'#chart\').append(\'<canvas id="chartData"><canvas>\');

}

function graphData(barChart,csvDownload) 
{
		var pageURL     = window.location.search.substring(1);
		var pagePath    = window.location.pathname;
		var parts				= pagePath.split("/");
		var chartType		= \'line\';
		if(barChart) { 
			chartType = \'bar\';
		}
		else {
			chartType = \'line\';
		}

		$.getJSON(\'//tinoest.co.uk/data/get_data/\'+parts[2]+\'/\'+pageURL, function(result) {
			if(csvDownload) {
				exportJSONAsCSV(result,\'download\');
			} 
			else {
				resetCanvas();
				var temp = document.getElementById(\'chartData\').getContext(\'2d\');
				if(result[1].length == 2) {
					var myChart = new Chart(temp, {
						type: chartType,
						data: {
							labels: result[0],
							datasets: [{
								fill: false,
								backgroundColor: "#E7E9ED",
								borderColor: "#E7E9ED",
								label: result[2][0],
								data: result[1][0]
							},
							{
								fill: false,
								backgroundColor: "#36A2EB",
								borderColor: "#36A2EB",
								label: result[2][1],
								data: result[1][1]
							}]
						},
						options: {
							scales: {
								xAxes: [{
									ticks: {
										maxTicksLimit: 24 
									}
								}],
								yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							}
						}
				});
			}
			else {
				var myChart = new Chart(temp, {
					type: chartType,
					data: {
						labels: result[0],
						datasets: [{
							fill: false,
							borderColor: "#4BC0C0",
							backgroundColor: "#4BC0C0",
							label: result[2][0],
							data: result[1][0]
						}]
					},
					options: {
						scales: {
							xAxes: [{
								ticks: {
									maxTicksLimit: 24 
								}
							}],
							yAxes: [{
								ticks: {
									beginAtZero:true
								}
							}]
						}
					}
				});
			}
		}
	});
	
}

function formatNumber(number, decimalsLength, decimalSeparator, thousandSeparator) {
	var n = number,
			decimalsLength = isNaN(decimalsLength = Math.abs(decimalsLength)) ? 2 : decimalsLength,
			decimalSeparator = decimalSeparator == undefined ? "," : decimalSeparator,
			thousandSeparator = thousandSeparator == undefined ? "." : thousandSeparator,
			sign = n < 0 ? "-" : "",
			i = parseInt(n = Math.abs(+n || 0).toFixed(decimalsLength)) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;

	return sign +
		(j ? i.substr(0, j) + thousandSeparator : "") +
		i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousandSeparator) +
		(decimalsLength ? decimalSeparator + Math.abs(n - i).toFixed(decimalsLength).slice(2) : "");
}

function exportJSONAsCSV(JSONData, ReportTitle) {

    var arrData = typeof JSONData != \'object\' ? JSON.parse(JSONData) : JSONData;
    var CSV			= \'\';    

    for (var i = 0; i < arrData.length; i++) {
        var row = "";
				for (var index in arrData[i]) {
					if(arrData[i][index] instanceof Array) {
						var j = 0;
						for (j in arrData[i][index]) {
							//console.log(j);
							//console.log(arrData[i][index][j]);
							row += \'"\' + arrData[i][index][j] + \'",\';
						}
						CSV += row + \'\r\n\';
					} 
					else {
						row += \'"\' + arrData[i][index] + \'",\';
					}
				}

        row.slice(0, row.length - 1);
        CSV += row + \'\r\n\';
    }

    if (CSV == \'\') {        
        alert("Invalid data");
        return;
    }   
    
    //Generate a file name
    var fileName	= "";
    fileName			+= ReportTitle.replace(/ /g,"_");   
    var uri				= \'data:text/csv;charset=utf-8,\' + escape(CSV);
    var link			= document.createElement("a");    
    link.href			= uri;
    link.style		= "visibility:hidden";
    link.download = fileName + ".csv";
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportCanvasAsPNG(id, fileName) {

	var canvasElement		= document.getElementById(id);
	var MIME_TYPE				= "image/png";
	var imgURL					= canvasElement.toDataURL(MIME_TYPE);
	var dlLink					= document.createElement(\'a\');
	dlLink.download			= fileName;
	dlLink.href					= imgURL;
	dlLink.dataset.downloadurl = [MIME_TYPE, dlLink.download, dlLink.href].join(\':\');

	document.body.appendChild(dlLink);
	dlLink.click();
	document.body.removeChild(dlLink);

}

</script>';
?>

