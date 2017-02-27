<?php
$date = new DateTime('28-02-2017');
$date->modify('+7 hours');
echo $date->format('d-m-Y H:i:s');

$feedUrl = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml?v=1';
		$xml = simplexml_load_file($feedUrl);
		$txt = '';
		$myDate = '';
		$myOldDate = '01-01-2017';
		#echo $xml->weeklyevents->event->title;
			foreach($xml->children() as $event)
			{	 
			  $myDate = (string)$event->date;
		          $myTime = (string)$event->time;
			  echo	$myDate . ' ' . $myTime;
			  $date = new DateTime($myDate . ' ' . $myTime);
			  $date = $date->modify('+7 hours');
				echo $date->format('d-m-Y H:ia');
			   if($date->format('d-m-Y') != $myOldDate->format('d-m-Y')){
							echo 'xxx';
				#echo $date->format('d-m-Y H:i:s');
				#$myDate = $date->format('d-m-Y');
				$txt .= $date->format('d-m-Y') . "\n";
				$myOldDate = $date;
			   }
				#echo $impact;
			   if($event->impact == 'High'){
				$txt .= ($event->country) . ' ' . ($myOldDate->format('H:ia')) . ' ' . ($event->title) . "\n";
			   }
			}

echo $txt;
?>
