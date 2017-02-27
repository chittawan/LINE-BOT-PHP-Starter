<?php
if(!file_exists("text.txt")){
   $myfile = fopen("text.txt", "w") or die("Unable to open file!");
   fwrite($myfile, 'false');
   fclose($myfile);
}
$myfile = fopen("text.txt", "r") or die("Unable to open file!");
$shortup = (bool)fgets($myfile);
fclose($myfile);

echo $shortup
?>
