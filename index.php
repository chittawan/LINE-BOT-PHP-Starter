<?php
$date = new DateTime('28-02-2017');
$date->format('d-m-Y H:i:s');
date('Y-m-d', strtotime($date,"+7 hours"));
echo $date;
?>
