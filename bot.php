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
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			$messages = GetReplyMessage($text,$userId);
				
				
			if (!is_null($messages) && (!$shortup) ) {

				// Make a POST Request to Messaging API to reply to sender

				$url = 'https://api.line.me/v2/bot/message/reply';
				$data = [
					'replyToken' => $replyToken,
					'messages' => $messages,
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
			}
		}
	}
}
function GetReplyMessage($text,$myUserId) {
	$serviceUrl = 'http://vsmsdev.apps.thaibev.com/linebot/linebotWCF';
	
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
		//$messages = [[
		//  'type'=> 'sticker',
		//  'packageId'=> '1',
		//  'stickerId'=> '3'
		//]];
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
			'text' => 'เมอยู่บ้าน ม่านปิดอยู่'
		]];
	} else if (stripos($text, "เย") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'เยที่่ไหน ใครรู้บ้าง'
		]];
	} else if (stripos($text, "ใคร") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'กุไงจะใครละ'
		]];
	} else if (stripos($text, "ไบ้หวยหน่อย") !== false) {	
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
	} 
	
	if (stripos($text, "Cfx Myinfo") !== false) {	
		$messages = [[
			'type' => 'text',
			'text' => $myUserId
		]];
		
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
		
	} else if (stripos($text, "Cfx x2") !== false) {	
		$messages = [[
				 "type"=> "template",
				  "altText"=> "this is a carousel template",
				  "template"=> [
				      "type"=> "carousel",
				      "columns"=> array(
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
		
	} else if (stripos($text, "Cfx xy") !== false) {	
		$messages = [[
				  "type"=> "template",
				  "altText"=> "this is a buttons template",
				  "template"=> [
				      "type"=> "buttons",
				      "thumbnailImageUrl"=> "https://fathomless-anchorage-14853.herokuapp.com/YDSY4925.JPG",
				      "title"=> "Menu",
				      "text"=> "Please select",
				      "actions"=> array(
					  [
					    "type"=> "message",
					    "label"=> "Yes",
					    "text"=> "yes"
					  ],
					  [
					    "type"=> "message",
					    "label"=> "No",
					    "text"=> "no"
					  ],[
					    "type"=> "uri",
					    "label"=> "View detail",
					    "uri"=> "https://www.dropbox.com/sh/i7jfxjntjldsglo/AAAufjP0Q8jg5SHJl6yJZUv6a?dl=0"
					  ]
				      )
				  ]

		]];
		
	} else if (stripos($text, "Cfx ask") !== false) {
		$splitStr = explode('#',$text);
		if(count($splitStr) >= 4){
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
						    "text"=> $splitStr[2]
						  ],
						  [
						    "type"=> "message",
						    "label"=> $splitStr[3],
						    "text"=> $splitStr[3]
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
echo "OK";
