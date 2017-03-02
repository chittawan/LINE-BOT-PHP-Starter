<?php
$groupId = 'Uce91bbcb4d5185a7c0ab1ebfdbd13539';
$userId = 'E';
$answer = 'NO';
$myArray = array([
                  userId => 'A',
                  answer => 'Yes'
                  ], [
                  userId => 'B',
                  answer => 'Yes'
                  ], [
                  userId => 'C',
                  answer => 'NO'
                  ]);

$isExists = false;
foreach($myArray as $item)
{
    if($item->userId == $userId)
    {
        $isExists = true;
        $item->answer = $answer;
    }
}
if(!$isExists){
  array_push($a,[
                  userId => $userId,
                  answer => $answer
                  ]);
}


$myFileName = $groupId . ".txt";
$json = json_encode($myArray, true);
if(!file_exists($myFileName)){
   $myfile = fopen($myFileName, "w") or die("Unable to open file!");
   fwrite($myfile, $json);
   fclose($myfile);
}
echo $json;
?>
