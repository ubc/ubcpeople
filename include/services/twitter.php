<?php
//3 line xml -> array conversion
function people_twitter(){
	$xml = simplexml_load_string(file_get_contents('http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=superejack'));
	$json = json_encode($xml);
	$array = json_decode($json,TRUE);
	
	echo '<pre style="float:left;">';
	print_r($array);
	echo '</pre>';
}