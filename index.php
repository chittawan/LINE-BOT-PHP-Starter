<?php
$mydate = '02-27-2017';
$date = date_create($mydate);
$date->format("d-m-Y");
echo $date;
?>
