<?php
$feedUrl = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml?v=1';
		$xml = simplexml_load_file($feedUrl);
		$txt = '';
		$myDate = '';
		$myOldDate = DateTime::createFromFormat('d-m-Y', '01-01-2017')
		#echo $xml->weeklyevents->event->title;
			foreach($xml->children() as $event)
			{	 
			  $myDate = (string)$event->date;
		          $myTime = (string)$event->time;
			  $strTime = $myDate . ' ' . $myTime;
			  $date =  DateTime::createFromFormat('d-m-Y', $myDate);
			  $date->modify('+7 hour');		
			  echo $date->format('d-m-Y');
			   if(strtotime($date) <> strtotime($myOldDate)){
				#echo $date->format('d-m-Y H:i:s');
				#$myDate = $date->format('d-m-Y');
				$txt .= $date->format('d-m-Y') . "\n";
				$myOldDate = $date;
			   }
				#echo $impact;
				$x = "xxx";
				echo $x;
			   if($event->impact == 'High'){
				#$txt .= ($event->country) . ' ' . (string)($myOldDate->format('H:ia')) . ' ' . ($event->title) . "\n";
			   }
			}

echo $txt;
?>
