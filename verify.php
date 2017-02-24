<?php
$access_token = 'WrOvUObu3f++FH65SpmKQqkzd31q1HsVgv29G2EYPkye7NdGMp+I0/SeQHXIcjeI27CimIle69IF2uIjxynh4e4Yw2cQkULGEsJiBgvaqQ8agK/PEY/JYc2FT05jWFqTfPX3XCQmFsIZ+M6d9NGB3AdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;