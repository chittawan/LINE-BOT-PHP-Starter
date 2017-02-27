<?php
$feedUrl = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml?v=1';
		$xml = simplexml_load_file($feedUrl);
		$txt = '';
		$myDate = '';
		$myOldDate = DateTime::createFromFormat('d-m-Y', '01-01-2017');
		#echo $xml->weeklyevents->event->title;
			foreach($xml->children() as $event)
			{	 
			  $myDate = (string)$event->date;
		          $myTime = (string)$event->time;
			  $strTime = $myDate . ' ' . $myTime;
			  $date =  DateTime::createFromFormat('d-m-Y H:ia', $strTime);			  
			  $date->modify('+7 hours');		
			  
			  if((string)$date->format('d-m-Y') != (string)$myOldDate->format('d-m-Y')){
				#echo $date->format('d-m-Y H:i:s');
				#$myDate = $date->format('d-m-Y');
				$txt .= (string)$date->format('d-m-Y') . "</br>";
				$myOldDate = $date;
			   }
			   if($event->impact == 'High'){
				$txt .= ($event->country) . ' ' . (string)($date->format('H:ia')) . ' ' . ($event->title) . "</br>";
			   }
			}

echo $txt;
?>
