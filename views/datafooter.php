<?php 
$html .= '
<script>

$( ".exportcsv" ).click(function() {
	graphData(false,true);
});

$( ".exportpng" ).click(function() {
	exportCanvasAsPNG("chartData","export.png");
});

$( ".bar" ).click(function() {
	graphData(true,false);
});

$( ".line" ).click(function() {
	graphData(false,false);
});

$(".bbtn").click(function(){
				var pageURL		= window.location.search.substring(1);
				var pagePath	= window.location.pathname;
				pagePath			= pagePath.replace(/\/$/, "");
				if(pageURL.length === 0) {
					date				= new Date();
				} else {
					var date		= Date.parse(pageURL);
				}
				var dt = new Date(date - 1000*60*60*24*1);
				var day 	= dt.getDate();
				var month = dt.getMonth() + 1;
				var year 	= dt.getFullYear();
				if (month < 10) {
					month = "0" + month;
				}
				if (day < 10) {
					day = "0" + day;
				}

				var dtFormat =  year + "-" + month + "-" + day;
				window.location.href = \'//tinoest.co.uk\' + pagePath + \'/?\' + dtFormat;
});

$(".fbtn").click(function(){
				var pageURL 	= window.location.search.substring(1);
				var pagePath	= window.location.pathname;
				pagePath			= pagePath.replace(/\/$/, "");
				if(pageURL.length === 0) {
					date				= new Date();
				} else {
					var date 		= Date.parse(pageURL);
				}
				var dt = new Date(date + 1000*60*60*24*1);
				var day 	= dt.getDate();
				var month = dt.getMonth() + 1;
				var year 	= dt.getFullYear();
				if (month < 10) {
					month = "0" + month;
				}
				if (day < 10) {
					day = "0" + day;
				}
				var dtFormat =  year + "-" + month + "-" + day;
				window.location.href = \'//tinoest.co.uk\' + pagePath + \'/?\' + dtFormat;
});
</script>';
$html .= '
<footer class="site-footer">
<p>Site Designed by: tinoest '.date( 'Y', strtotime( 'now' ) ).'</p>
</footer>
</body>
</html>';
echo ($html);
?>
