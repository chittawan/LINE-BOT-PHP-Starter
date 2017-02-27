<?php
$date = new DateTime('28-02-2017');
$date->format('d-m-Y H:i:s');
$date->modify('+7 hours');
echo $date;
?>
