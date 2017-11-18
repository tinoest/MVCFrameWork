<?php 

require_once "/mnt/www/mvc/lib/xml.php"; 

$xml_data = '
<data>
<post>
	<dt-created>2012-12-31</dt-created>
	<title>Post Title</title>
	<content>This is some content for the post</content>
</post>
<post>
	<dt-created>2013-01-01</dt-created>
	<title>Post Title</title>
	<content>This is some content for the post</content>
</post>
<post>
	<dt-created>2013-01-02</dt-created>
	<title>Post Title</title>
	<content>This is some content for the post</content>
</post>
<post>
	<dt-created>2013-01-03</dt-created>
	<title>Post Title</title>
	<content>This is some content for the post</content>
</post>
<tags>
<content>Something</content>
</tags>
<tags>
<content>Something</content>
</tags>
</data>
';

//Creating Instance of the Class 
$xmlObj    = new xml2array( $xml_data ); 
//Creating Array 
$arrayData = $xmlObj->create_array(); 

print_r( $arrayData ); 
?>
