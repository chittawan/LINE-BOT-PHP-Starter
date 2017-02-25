<?php
$xmlFeed = 'https://cdn-nfs.forexfactory.net/ff_calendar_thisweek.xml';
		$xml = simplexml_load_file($xmlFeed);
print_r($xml);
#_dump($xml)
#		foreach($xml->weeklyevents->event as $key => $value)
#		{
#			echo $value;
#		}
?>
