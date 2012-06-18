<?php
function people_twitter(){
	if(!($data = get_transient('twitter') ) ):
		$data = json_decode(file_get_contents('https://twitter.com/status/user_timeline/superejack.json'),TRUE);
		set_transient('twitter', $data, 60*60);
	endif;
	

	echo '<a href="http://twitter.com/' . $data[0]['user']['screen_name'] . '">Twitter/' . $data[0]['user']['screen_name'] . '</a><br />';
	echo '<ul>';
	foreach($data as $tweet):
		echo '<li>' . $tweet['text'].' - Posted ' . $tweet['created_at'] . '</li>';
	endforeach; 
	echo '</ul>';
	//echo '<pre style="float:left;">';	print_r($data[0]);echo '</pre>';

}