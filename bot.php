<?php
$access_token = 'WrOvUObu3f++FH65SpmKQqkzd31q1HsVgv29G2EYPkye7NdGMp+I0/SeQHXIcjeI27CimIle69IF2uIjxynh4e4Yw2cQkULGEsJiBgvaqQ8agK/PEY/JYc2FT05jWFqTfPX3XCQmFsIZ+M6d9NGB3AdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
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

			//if (stripos($text, "หอย") !== false) {
	
				//if ($userId == $SSCGroupId || $userId == $TestGroupId || $userId == $PaeUserId) {
					$messages = GetReplyMessage($text,$userId);

				//} 
				//elseif (stripos($text, "userid") !== false) {
				//	$messages = [[
				//		'type' => 'text',
				//		'text' => $userId
				//	]];
				//}
				
				if (!is_null($messages)) {
				
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
				
			//}
		}
	}
}
function GetReplyMessage($text,$userId) {
	$serviceUrl = 'http://vsmsdev.apps.thaibev.com/linebot/linebotWCF';
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
	}  else if (stripos($text, "จน") !== false) {		
		$messages = [[
			'type' => 'text',
			'text' => 'พรุ่งนี้รวยๆ'
		]];
	} 
	return $messages;
}
echo "OK";
