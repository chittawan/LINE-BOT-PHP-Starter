<?php
$xmlFeed = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml';
		$xml = simplexml_load_file($xmlFeed);

	_dump($xml);
#echo $sxml->weeklyevents->event;
	foreach($xml->children() as  $event)
	{
	   echo $event->title;
	}
?>
