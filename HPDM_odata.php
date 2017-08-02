<?php
/*
include_once( "./xmltools/XMLTools.php" );
$xmltools = new XMLTools();
*/
function download_page($path){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$path);
	curl_setopt($ch, CURLOPT_FAILONERROR,1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$retValue = curl_exec($ch);			 
	curl_close($ch);
	return $retValue;
}

/*
Actual Query: http://sol-devsvr-03/HPDM/HPDMData.svc/Payors?$filter=PCN_ eq 'ADV'
*/
$sXML = download_page('http://www.nfl.com/rss/rsslanding?searchString=team&abbr=GB');

/*
Can change query to be used with any other Object in HPDM
$sXML = download_page('http://sol-devsvr-03/HPDM/HPDMData.svc/Manfacturers');

*/
$xml = simplexml_load_string($sXML, 'SimpleXMLElement', LIBXML_NOCDATA);

/*
foreach ($xml->entry as $Objs) {
$obj = $Objs[0]->content->children('m', true)->properties->children('d', true);
echo '<pre>';
print_r($obj);
echo '</pre>';

}
*/
/*
$data = $xmltools->XML2Array( $sXML );
print_r( $data );
*/
foreach( $xml->entry as $entry ){
	echo $entry->summary . "<br>";
}//foreach


