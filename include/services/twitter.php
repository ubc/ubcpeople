<?php
function ubcpeople_twitter($username){

	if(!($data = get_transient('twitter_'.$username) ) ):
		
		$data = json_decode(file_get_contents('https://twitter.com/status/user_timeline/'.$username.'.json'),TRUE);
		
		set_transient('twitter_'.$username, $data, 60*60);
	endif;
	

	echo '<h2><a href="http://twitter.com/' . $username . '">Twitter/' . $data[0]['user']['screen_name'] . '</a></h2>';
	echo '<ul>';
	foreach($data as $tweet):
		echo '<li>' . $tweet['text'].' - Posted ' . $tweet['created_at'] . '</li>';
	endforeach; 
	echo '</ul>';
	//echo '<pre style="float:left;">';	print_r($data[0]);echo '</pre>';

}

function ubcpeople_get_icon_twitter(){
	return array(
		'url'=>'twitter.png',
		'id'=>'icon-twitter',
		'alt'=>'Twitter',
	);
}