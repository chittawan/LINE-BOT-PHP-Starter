<?php
$payload = file_get_contents('php://input'); // get data from Omise

$event = json_decode($payload); // change json text to php object
print_r($payload); // show data from Omise

if ($event->key == "charge.create") { // if event is `charge.create`
    if ($event->data->status == "successful") { // if status is `successful`
        // add some logic here
        addWordFile("test", $payload);
    }
}

function addWordFile($myUserId,$myText){
	
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
?>
