
<?php
$access_token = 'ycQkLcqiCGJGv0nZSneIbS26Ya2mqNM//ThR6p/v6mJi7rqf7b8FlHxT0lIPVzRSd1u4PXubLillrRsVYo1rCej7ziWSNrNXnHS+grMiGXht1I8xEWLJ3EI+vwxIAKLKDvF4yMouyBSsMiZ45ZGIGwdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
$userId = '';
if(!file_exists("text.txt")){
   $myfile = fopen("text.txt", "w") or die("Unable to open file!");
   fwrite($myfile, 0);
   fclose($myfile);
}
$myfile = fopen("text.txt", "r") or die("Unable to open file!");
$shortup = (bool)fgets($myfile);
fclose($myfile);
#$shortup = (bool)$_COOKIE[$cookie_name];
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get User 
			if ($event['source']['type'] == 'user') {
				$userId = $event['source']['userId'];			
			}
			elseif ($event['source']['type'] == 'group') {
				$userId = $event['source']['groupId'];			
			}
			elseif ($event['source']['type'] == 'room') {
				$userId = $event['source']['roomId'];			
			}
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			$messages = GetReplyMessage($text,$userId);
				
				
			if (!is_null($messages)) {// && (!$shortup) 
				// Make a POST Request to Messaging API to reply to sender
				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
					'replyToken' => $replyToken,
					'messages' => $messages
				];
				$post = json_encode($data);
				$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);
				echo $result . "\r\n";
				/*
				$url = 'https://api.line.me/v2/bot/group/' . $userId . '/leave';
				if ($event['source']['type'] == 'user') {
				$userId = $event['source']['userId'];			
				}
				elseif ($event['source']['type'] == 'group') {
					$userId = $event['source']['groupId'];	
					$data = [
						'replyToken' => $replyToken,
						'groupId' => $userId
					];
				}
				elseif ($event['source']['type'] == 'room') {
					$userId = $event['source']['roomId'];	
					$data = [
						'replyToken' => $replyToken,
						'roomId' => $userId
					];
				}
				
				$post = json_encode($data);
				$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);
				echo $result . "\r\n";
				*/
			}
		}
	}
}
function clearQuestionFile($fileName){ 
	$myfile = fopen($fileName, "w") or die("Unable to open file!");
	fwrite($myfile, json_encode(array()));
	fclose($myfile);
	
}
function answerQuestionFile($myFileName,$myUserId,$myAnswer){
	if(file_exists($myFileName)){
		$myfile = fopen($myFileName, "r") or die("Unable to open file!");		
	 	$myArray = json_decode(fgets($myfile));		
		
		$isExists = false;
		$total = 1;
		foreach($myArray as $item)
		{
		    if($item->userId == $myUserId && $item->answer == $myAnswer)
		    {
			$isExists = true;
			$item->answer = $myAnswer;
			$item->total = $item->total +1;
			$total = $item->total;
		    }
		}
		if(!$isExists){
		  array_push($myArray,[
				  userId => $myUserId,
				  answer => $myAnswer,
			  	  total => 1
				  ]);
		}
		
		
		$json = json_encode($myArray, true);
		if(file_exists($myFileName)){
			$myfile = fopen($myFileName, "w") or die("Unable to open file!");
			fwrite($myfile, $json);
			fclose($myfile);
			return $myAnswer . ' (' . $total . ')';
		}
	}
}
function addWordFile($myUserId,$myAsk,$myAnswer){
	
	$myFileName = 'word_' . $myUserId . '.txt';
	if(!file_exists($myFileName)){
	   clearQuestionFile($myFileName);
	}
	if(file_exists($myFileName)){
		$myfile = fopen($myFileName, "r") or die("Unable to open file!");		
	 	$myArray = json_decode(fgets($myfile));		
		if(empty($myArray)) $myArray = array();
		fclose($myfile);
		
		$isExists = false;
		foreach($myArray as $item)
		{
		    if($item->ask == $myAsk)
		    {
			$isExists = true;
			$item->answer = $myAnswer;
		    }
		}
		if(!$isExists){
		  array_push($myArray,[
			ask => $myAsk,
			answer => $myAnswer
			]);
		}
		$json = json_encode($myArray, true);
		if(file_exists($myFileName)){
			$myfile = fopen($myFileName, "w") or die("Unable to open file!");
			fwrite($myfile, $json);
			fclose($myfile);
			return 'OK.';
		}
	}
	return 'Fail';
}
function findWordFile($myUserId,$myAsk){
	$myFileName = 'word_' . $myUserId . '.txt';
	if(file_exists($myFileName)){
		$myfile = fopen($myFileName, "r") or die("Unable to open file!");		
	 	$myArray = json_decode(fgets($myfile));		
		$result = '';
		
		$isExists = false;
		$total = 1;
		foreach($myArray as $item)
		{
		    if(stripos($myAsk, $item->ask) !== false)
		    {
			$isExists = true;
			$result = $item->answer;
		    }
		}
		return $result;
	}
}
function GetReplyMessage($text,$myUserId) {
	$serviceUrl = 'http://tomsvpn.apps.thaibev.com/TOMsAPIBotLine/CheckService.svc';
	if(stripos($text, "หุบปาก") !== false){
		$myfile = fopen("text.txt", "w") or die("Unable to open file!");
		fwrite($myfile, 1);
		fclose($myfile);
	} else if(stripos($text, "อ้าปาก")!== false){
		$myfile = fopen("text.txt", "w") or die("Unable to open file!");
		fwrite($myfile, 0);
		fclose($myfile);
	}	
	   
	// Build message to reply back
	if (stripos($text, "ดี") !== false) {
		$messages = [[
			'type' => 'text',
			'text' => 'ดี'
		]];
	} else if (stripos($text, "บ้า") !== false) {
		$messages = [[
		  'type'=> 'sticker',
		  'packageId'=> '1',
		  'stickerId'=> '3'
		]];
	} else if (stripos($text, "555+") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'ฮาๆ'
		]];
	} else if (stripos($text, "ฮาๆ") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => '555+'
		]];
	} else if (stripos($text, "เออ") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'เออ'
		]];
	}  else if (stripos($text, "ว่าไง") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'ลืมแล้ว'
		]];
	}  else if (stripos($text, "เล่นอะไรกัน") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'อะไรก็ได้'
		]];
	} else if (stripos($text, "ไปเล่นตรงนู๊น") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'เค้าไล่กุมาเล่นตรงนี้'
		]];
	} else if (stripos($text, "กำ") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'กำราย'
		]];
	} else if (stripos($text, "คุณคือใคร") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'ผมเป็นบอท'
		]];
	} else if (stripos($text, "ไง") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'ชิวๆ'
		]];
	} else if (stripos($text, "จน") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'พรุ่งนี้รวยๆ'
		]];
	} else if (stripos($text, "หอย") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'หาเองเองดิ'
		]];
	} else if (stripos($text, "รวย") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'เอามาแบ่งบ้าง'
		]];
	} else if (stripos($text, "ต่อ") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'ต่อไหน ใครรู้บ้าง'
		]];
	} else if (stripos($text, "เม") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'เมอยู่บ้าน'
		]];
	} else if (stripos($text, "เย") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'ที่่ไหน'
		]];
	} else if (stripos($text, "ใคร") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'กุไงจะใครละ'
		]];
	} else 
	if (stripos($text, "ไบ้หวยหน่อย") !== false) {	
		$digits = 3;
                $randNumber = rand(pow(10, $digits-1), pow(10, $digits)-1);
		$messages = [[
			'type' => 'text',
			'text' => $randNumber
		]];
	} else if (stripos($text, "2 ตัว") !== false) {	
		$digits = 2;
                $randNumber = rand(pow(10, $digits-1), pow(10, $digits)-1);
		$messages = [[
			'type' => 'text',
			'text' => $randNumber
		]];				
	} else if (stripos($text, "Cfx") === false) {		
		$result = findWordFile($myUserId,$text);
		$messages = [[
			'type' => 'text',
			'text' => $result
		]];
	} 
	
	if ($text === 'Itoms') {	
			$myUrl = $serviceUrl . '/SayHello';
			$response = GetWebService($myUrl);
			$str = $response;
		$messages = [[
			'type' => 'text',
			'text' => $str
		]];
	} 
	else if (stripos($text, "Itomstest") !== false) {	
		$splitStr = explode('#',$text);
		$str = 'Fail';
		if(count($splitStr) >= 2){	
			$myUrl = $serviceUrl . '/exec2' . '?param1=' . 'a' . '?param2=' . 'b';
			$response = GetWebService($myUrl);
			$str = $response;
		}
		$messages = [[
			'type' => 'text',
			'text' => $str
		]];
	} 	
	return $messages;
}
function GetWebService2($url) {
	$headers = array('Content-Type: application/json');
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	
	return $result;
}
function GetWebService($url) {
	$headers = array('Content-Type: application/json');
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	
	$jsonResult = json_decode($result);
	
	return $jsonResult;
}
echo "OK";
