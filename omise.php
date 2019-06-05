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
		$myfile = fopen($myFileName, "w") or die("Unable to open file!");
		fwrite($myfile, $myText);
		fclose($myfile);
		return 'OK.';
	}
	return 'Fail';
}

function clearQuestionFile($fileName){ 
	$myfile = fopen($fileName, "w") or die("Unable to open file!");
	fwrite($myfile, json_encode(array()));
	fclose($myfile);
	
}
?>
