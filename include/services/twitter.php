<?php

function ubcpeople_twitter($username){

	$data = ubcpeople_twitter_get_feed($username);
	//  echo '<pre>';print_r($data);echo '</pre>';
	?>
	
	<div class="social-header">
		<h2><a href="http://twitter.com/' . $username . '">@<?php echo $data[0]['user']['screen_name']; ?></a></h2>
	</div>
	
	<div class="social-body">
		<ul>
		
			<?php 
			foreach($data as $tweet): 
				echo '<li>' . apply_filters('the_content',('https://twitter.com/' . $tweet['user']['screen_name'] . '/status/'. $tweet['id_str'])) . '</li>';
			endforeach; 
			?> 

		</ul>
	</div>
	<?
}



function ubcpeople_twitter_get_feed($username){
	if(!($data = get_transient('twitter_'.$username) ) ):
		$data = json_decode(file_get_contents('https://twitter.com/status/user_timeline/'.$username.'.json'),TRUE);
		set_transient('twitter_'.$username, $data, 60*60);
	endif;
	return $data;
}



function ubcpeople_twitter_get_icon(){
	return array(
		'url'=>'twitter.png',
		'id'=>'icon-twitter',
		'alt'=>'Twitter',
	);
}