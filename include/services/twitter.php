<?php
//3 line xml -> array conversion
function people_twitter(){
	$xml = simplexml_load_string(file_get_contents('http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=superejack'));
	$json = json_encode($xml);
	$data = json_decode($json,TRUE);
	
	echo $data['channel']['title'].'<br />';
	$items = $data['channel']['item'];
	
	for($i=0;$i<10;$i++):
		echo $items[$i]['description'].'<br />';
	endfor;
	
	echo '<pre style="float:left;">';
	print_r($data);
	echo '</pre>';
}