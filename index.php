<?php
$date = new DateTime('28-02-2017');
$date->modify('+7 hours');
echo $date->format('d-m-Y H:i:s');
?>
