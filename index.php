<?php
$feedUrl = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml';
		$feedUrl = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml';
		$xml = simplexml_load_file($feedUrl);
		$txt = '';
		$myDate = '';
		#echo $xml->weeklyevents->event->title;
			foreach($xml->children() as $event)
			{	 
			   if($myDate != $event->date){
				$myDate = $event->date;
				$txt .= ($event->date) . "</br>";   
			   }
			   $myImpact = $event->impact;
				#echo $impact;
			   if($myImpact == 'High'){
				$txt .= ($event->title) . "</br>";
			   }
			}
echo $txt
?>
