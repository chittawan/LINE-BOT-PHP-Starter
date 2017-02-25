<?php
$feedUrl = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml';
		$feedUrl = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml';
		$xml = simplexml_load_file($feedUrl);
		$txt = '';
		#echo $xml->weeklyevents->event->title;
			foreach($xml->children() as $event)
			{	 
			   $impact = $event->impact;
				#echo $impact;
			   if($impact == 'High'){
				$txt .= ($event->title) . "\n";
			   }
			}
echo $txt
?>
