<?php
$access_token = 'WrOvUObu3f++FH65SpmKQqkzd31q1HsVgv29G2EYPkye7NdGMp+I0/SeQHXIcjeI27CimIle69IF2uIjxynh4e4Yw2cQkULGEsJiBgvaqQ8agK/PEY/JYc2FT05jWFqTfPX3XCQmFsIZ+M6d9NGB3AdB04t89/1O/w1cDnyilFU=';
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
	//$serviceUrl = 'http://webgis1.apps.thaibev.com/CheckService/CheckService.svc/ReadCheck';
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
	/*if (stripos($text, "ดี") !== false) {
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
	} else */
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
	
	if (stripos($text, "toms checkTO") !== false) {	
		$splitStr = explode('#',$text);
		$str = 'Fail';
		if(count($splitStr) >= 2){	
			$serviceUrl = 'http://webgis1.apps.thaibev.com/checkService/CheckService.svc/CheckTOId/' . $splitStr[1];
			$response = GetWebService($serviceUrl);
			$str = $response;
		}
		$messages = [[
			'type' => 'text',
			'text' => $str
		]];
	} 
	
	if (stripos($text, "Cfx Myinfo") !== false) {	
		$messages = [[
			'type' => 'text',
			'text' => str_replace($myUserId,"\n","\n")
		]];
		
	} else if (stripos($text, "Cfx serv") !== false) {	
		$serviceUrl = 'http://webgis1.apps.thaibev.com/CheckService/CheckService.svc/ReadCheck';
		$response = GetWebService($serviceUrl);
		$result = json_decode($response);
		$str = '';
		foreach($result as $data){
			foreach($data as $item){
				$str .=  '[' . ($item->DiffTime) . '] ' . ($item->CheckId) . ' ' . ($item->CheckName) . ' ' . ($item->DrawDown) . '% ' . ($item->Lots) . ' ' . ($item->Serv) . "\n";
			}
		}
		$messages = [[
			'type' => 'text',
			'text' => $str
		]];
		
	} else if (stripos($text, "Cfx check") !== false) {	
		$splitStr = explode('#',$text);
		$str = 'Fail';
		if(count($splitStr) >= 2){	
			$serviceUrl = 'http://webgis1.apps.thaibev.com/CheckService/CheckService.svc/ReadCheckById/' . $splitStr[1];
			$response = GetWebService($serviceUrl);
			$result = json_decode($response);
			$str = '';
			foreach($result as $data){
				foreach($data as $item){
					$status = (15 > $item->DiffTime) ? "OK" : "Fail";
					$str .=  'ID : ' . ($item->CheckId) . "\nName : " . ($item->CheckName) . "\nLots : " . ($item->Lots) . "\nDrawdown : " . ($item->Drawdown) . "%\nStatus : " . $status;
				}
			}
		}
		$messages = [[
			'type' => 'text',
			'text' => $str
		]];
		
	} else if (stripos($text, "Cfx add") !== false) {	
		$splitStr = explode('#',$text);
		if(count($splitStr) >= 3){
			$result = addWordFile($myUserId, $splitStr[1], $splitStr[2]);
			$messages = [[
				'type' => 'text',
				'text' => $result
			]];
		} else {
			$messages = [[
				'type' => 'text',
				'text' => 'Fail'
			]];
		}
		
	} else if (stripos($text, "Cfx wmi") !== false) {	
		$url = 'https://api.line.me/v2/bot/profile/' . $myUserId;
		$data = [
			'replyToken' => $replyToken,
			'userId' => $myUserId
		];
		$post = json_encode($data);
		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$response = curl_exec($ch);
		curl_close($ch);
		if ($response->isSucceeded()) {
		    	$profile = $response->getJSONDecodedBody();
			$messages = [[
				'type' => 'text',
				'text' => $profile['displayName']
			]];
		}		
		
	} else if (stripos($text, "Cfx Acc") !== false) {	
		$messages = [[
			'type' => 'text',
			'text' => "ค่า server โอนมาที่ \n 718-258-018-4 \n กสิกร \n วิทยา จงอุดมพร"
		]];
		
	} else if (stripos($text, "Cfx toms2") !== false) {	
		$messages = [[
				  "type"=> "template",
				  "altText"=> "TOMS2 Demo",
				  "template"=> [
				      "type"=> "buttons",
				      "thumbnailImageUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/login.JPG",
				      "title"=> "TOMS2 DEMO",
				      "text"=> "Go to Toms2 demo",
				      "actions"=> array([
					    "type"=> "uri",
					    "label"=> "View detail",
					    "uri"=> "http://tomsdev.apps.thaibev.com/Toms2"
					  ]
				      )
				  ]

		]];
		
	} else if (stripos($text, "Cfx wd") !== false) {	
		$messages = [[
				 "type"=> "template",
				  "altText"=> "this is a carousel template",
				  "template"=> [
				      "type"=> "buttons",
				      "thumbnailImageUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/WeddingPraeFilm.jpg",
					    "title"=> "Wedding",
					    "text"=> "รายละเอียด",
					    "actions"=> array(
						[
						    "type"=> "uri",
						    "label"=> "การ์ดเชิญ",
						    "uri"=> "https://fathomless-anchorage-14853.herokuapp.com/Card.jpg",
						],
						[
						    "type"=> "uri",
						    "label"=> "สถานที่จัดงาน",
						    "uri"=> "https://www.google.com/maps/place/wedding+and+conference+venue/@13.8753719,100.5806225,15.75z/data=!4m12!1m6!3m5!1s0x30e282d3bfc13d97:0xe03669df900ca167!2swedding+and+conference+venue!8m2!3d13.8739451!4d100.581968!3m4!1s0x30e282d3bfc13d97:0xe03669df900ca167!8m2!3d13.8739451!4d100.581968",
						],
						[
						    "type"=> "uri",
						    "label"=> "รับชม Teaser",
						    "uri"=> "https://drive.google.com/open?id=1gKsTQTQI_2DIMJHQlS3CU-du7Wtql2cF"
						]
					    )
				  ]
		]];
		
	}else if (stripos($text, "Cfx x2") !== false) {	
		$messages = [[
				 "type"=> "template",
				  "altText"=> "this is a carousel template",
				  "template"=> [
				      "type"=> "carousel",
				      "columns"=> array(
					      [
					    "thumbnailImageUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/Party.jpg",
					    "title"=> "this is menu",
					    "text"=> "description",
					    "actions"=> array(
						[
						    "type"=> "message",
						    "label"=> "ฉันจะไป",
						    "text"=> "ฉันจะไป"
						],
						[
						    "type"=> "message",
						    "label"=> "ฉันไป",
						    "text"=> "ฉันไป"
						],
						[
						    "type"=> "uri",
						    "label"=> "View detail",
						    "uri"=> "https://www.google.co.th/maps/place/wedding+and+conference+venue/@13.8739451,100.581968,15z/data=!4m5!3m4!1s0x0:0xe03669df900ca167!8m2!3d13.8739451!4d100.581968",
						]
					    )
					  ],
					  [
					    "thumbnailImageUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/x1.jpg",
					    "title"=> "this is menu",
					    "text"=> "description",
					    "actions"=> array(
						[
						    "type"=> "postback",
						    "label"=> "Buy",
						    "data"=> "action=buy&itemid=111"
						],
						[
						    "type"=> "postback",
						    "label"=> "Add to cart",
						    "data"=> "action=add&itemid=111"
						],
						[
						    "type"=> "uri",
						    "label"=> "View detail",
						    "uri"=> "https://fathomless-anchorage-14853.herokuapp.com/x1.jpg",
						]
					    )
					  ],
					  [
					    "thumbnailImageUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/x2.jpg",
					    "title"=> "this is menu",
					    "text"=> "description",
					    "actions"=> array(
						[
						    "type"=> "postback",
						    "label"=> "Buy",
						    "data"=> "action=buy&itemid=222"
						],
						[
						    "type"=> "postback",
						    "label"=> "Add to cart",
						    "data"=> "action=add&itemid=222"
						],
						[
						    "type"=> "uri",
						    "label"=> "View detail",
						    "uri"=> "https://fathomless-anchorage-14853.herokuapp.com/x2.jpg",
						]
					    )
					  ]
				      )
				  ]

		]];
		
	} else if (stripos($text, "Cfx hbd") !== false) {	
		$messages = [[
				  "type"=> "template",
				  "altText"=> "this is a buttons template",
				  "template"=> [
				      "type"=> "buttons",
				      "thumbnailImageUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/hbd_1.jpg",
				      "title"=> "Menu",
				      "text"=> "Please select",
				      "actions"=> array(
					  [
					    "type"=> "message",
					    "label"=> "Happy 1",
					    "text"=> "ขอให้มีแต่ความสุขนะ"
					  ],
					  [
					    "type"=> "message",
					    "label"=> "Happy 2",
					    "text"=> "ขอให้ร่ำรวยเงินทองไหลมาเทมา"
					  ],[
					    "type"=> "message",
					    "label"=> "Happy 3",
					    "text"=> "ขอให้สุขภาพแข็งแรง ไม่มีโรคภัย"
					  ]
				      )
				  ]

		]];
		
	} else if (stripos($text, "Cfx rov") !== false) {	
		$messages = [[
				  "type"=> "template",
				  "altText"=> "this is a buttons template",
				  "template"=> [
				      "type"=> "buttons",
				      "thumbnailImageUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/rov-flash.jpg",
				      "title"=> "Menu",
				      "text"=> "Please select",
				      "actions"=> array(
					  [
					    "type"=> "message",
					    "label"=> "กุเล่นด้วย",
					    "text"=> "กุเล่นด้วย"
					  ],
					  [
					    "type"=> "message",
					    "label"=> "ยังไม่เล่น",
					    "text"=> "ยังไม่เล่น"
					  ],[
					    "type"=> "message",
					    "label"=> "พอก่อน",
					    "text"=> "หัวร้อนแล้ว พอก่อน"
					  ],[
					    "type"=> "message",
					    "label"=> "ขอ 5 นาที",
					    "text"=> "ขอ 5 นาที"
					  ]
				      )
				  ]

		]];
		
	} else if (stripos($text, "Cfx mp4") !== false) {	
		$messages = [[
				  "type"=> "template",
				   "type"=> "video",
				   "originalContentUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/playWd.mp4",
				   "previewImageUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/preview.jpg"

		]];
		
	} else if (stripos($text, "Cfx wd") !== false) {	
		$messages = [[
				  "type"=> "template",
				  "altText"=> "Wedding",
				  "template"=> [
				      "type"=> "buttons",
				      "thumbnailImageUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/Vit.jpg",
				      "title"=> "Wedding",
				      "text"=> "งานแต่ง วิทยา วันที่ 24 สิงหาคม 2560 จ.สิงค์บุรี",
				      "actions"=> array(
					  [
					    "type"=> "message",
					    "label"=> "ฉันจะไป",
					    "text"=> "ฉันจะไป"
					  ],
					  [
					    "type"=> "message",
					    "label"=> "ฉันขอคิดดูก่อน",
					    "text"=> "ขอคิดดูก่อน"
					  ],[
					    "type"=> "uri",
					    "label"=> "รายะเอียด",
					    "uri"=> "https://fathomless-anchorage-14853.herokuapp.com/Vit.jpg"
					  ]
				      )
				  ]

		]];
		
	} else if (stripos($text, "Cfx answer") !== false) {
		$splitStr = explode('#',$text);
		if(count($splitStr) >= 2){			
			$fileName = 'question.txt';
			$answer = $splitStr[1];			
			$result = answerQuestionFile($fileName,$myUserId,$answer);
			$messages = [[
				'type' => 'text',
				'text' => $result
			]];
		} else {
			$messages = [[
				'type' => 'text',
				'text' => "ผมไม่เข้าใจ"
			]];
		}
		
	}  else if (stripos($text, "Cfx ask") !== false) {
		$splitStr = explode('#',$text);
		if(count($splitStr) >= 4){
			$fileName = 'question.txt';
			$key = 'Cfx answer#';
			clearQuestionFile($fileName);
			$messages = [[
					  "type"=> "template",
					  "altText"=> $splitStr[1],
					  "template"=> [
					      "type"=> "confirm",
					      "text"=> $splitStr[1],
					      "actions"=> array(
						  [
						    "type"=> "message",
						    "label"=> $splitStr[2],
						    "text"=> $key . $splitStr[2]
						  ],
						  [
						    "type"=> "message",
						    "label"=> $splitStr[3],
						    "text"=> $key . $splitStr[3]
						  ]
					      )
					  ]

			]];
		} else {
			$messages = [[
			'type' => 'text',
			'text' => "ผมไม่เข้าใจ"
		]];
		}
		
	} else if (stripos($text, "Cfx saimai") !== false) {	
		$splitStr = explode('#',$text);
		if(count($splitStr) >= 2) {
			$userId = "C6614ebe54e49c320307197b657d07202";
			$messages = [[
				'type' => 'text',
				'text' => $splitStr[1]
			]];
		}
	} else if (stripos($text, "cfx Fac") !== false) {	
		$feedUrl = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml?v=1';
		$xml = simplexml_load_file($feedUrl);
		$txt = '';
		$myDate = '';
		$myOldDate = DateTime::createFromFormat('d-m-Y', '01-01-2017');
		#echo $xml->weeklyevents->event->title;
			foreach($xml->children() as $event)
			{	 
			  $myDate = (string)$event->date;
		          $myTime = (string)$event->time;
			  $strTime = $myDate . ' ' . $myTime;
			  $date =  DateTime::createFromFormat('d-m-Y H:ia', $strTime);			  
			  $date->modify('+7 hours');		
			  
			  if((string)$date->format('d-m-Y') != (string)$myOldDate->format('d-m-Y')){
				#echo $date->format('d-m-Y H:i:s');
				#$myDate = $date->format('d-m-Y');
				$txt .= (string)$date->format('d-m-Y') . "\n";
				$myOldDate = $date;
			   }
			   if($event->impact == 'High'){
				$txt .= ($event->country) . ' ' . (string)($date->format('H:ia')) . ' ' . ($event->title) . "\n";
			   }
			}
		           
		$messages = [[
			'type' => 'text',
			'text' => $txt
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
