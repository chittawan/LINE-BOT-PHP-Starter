<?php
$xmlFeed = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml';
		$xml = simplexml_load_file($xmlFeed);

echo $xml->weeklyevents->event->title;
	foreach($xml->children() as $event)
	{
	   echo $event->title;
	}
?>
