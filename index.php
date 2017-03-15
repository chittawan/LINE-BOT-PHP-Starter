<?php
$groupId = 'Uce91bbcb4d5185a7c0ab1ebfdbd13539';
$userId = 'E';

function GetWebService($url) {
	$headers = array('Content-Type: application/json');

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	
	$jsonResult = json_decode($result, true);
	
	return $result;
}

$serviceUrl = 'http://webgis1.apps.thaibev.com/CheckService/CheckService.svc/ReadCheck';
$response = GetWebService($serviceUrl);
$result = json_decode($response);
echo $result;
$str = '';
foreach (($result->data) as $item){
	echo $item;
	$str .= ($item->CheckId) . " " . ($item->CheckName) . " " . ($item->DiffTime) . "\r\n";
}

echo $str;
//echo "OK";
?>
