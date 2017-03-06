<?php
$groupId = 'Uce91bbcb4d5185a7c0ab1ebfdbd13539';
$userId = 'E';
function addWordFile($myUserId,$myAsk,$myAnswer){
	echo '2';
	$myFileName = 'word_' . $myUserId . '.txt';
	echo $myFileName;
	if(!file_exists($myFileName)){
	   clearQuestionFile($myFileName);
		echo '2';
	}
	echo '4';
	if(file_exists($myFileName)){
		echo '5';
		$myfile = fopen($myFileName, "r") or die("Unable to open file!");		
	 	$myArray = json_decode(fgets($myfile));		
		
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
function clearQuestionFile($fileName){ 
	$myfile = fopen($fileName, "w") or die("Unable to open file!");
	fwrite($myfile, json_encode(array()));
	fclose($myfile);
	
}
echo '1';
$result =  addWordFile('Uce91bbcb4d5185a7c0ab1ebfdbd13539',"Hello",'Hi');
echo $result;
?>
