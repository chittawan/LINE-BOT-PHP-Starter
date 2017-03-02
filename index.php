<?php
$keyword = array([
                  userId => 'A',
                  answer => 'Yes'
                  ], [
                  userId => 'B',
                  answer => 'Yes'
                  ], [
                  userId => 'C',
                  answer => 'NO'
                  ]);
$myUserId = 'Uce91bbcb4d5185a7c0ab1ebfdbd13539';
$myFileName = $myUserId . ".txt";
$json = json_encode($keyword, true);
if(!file_exists($myFileName)){
   $myfile = fopen($myFileName, "w") or die("Unable to open file!");
   fwrite($myfile, $json);
   fclose($myfile);
}
echo $json;
?>
