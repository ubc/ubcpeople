<?php
add_action('ubcpeople_admin', 'ubcpeople_twitter_init');

function ubcpeople_twitter($person_id, $service_username){

	$data = ubcpeople_twitter_get_feed($service_username);
	//  echo '<pre>';print_r($data);echo '</pre>';
	?>
	
	<div class="social-header">
		<h2><a href="http://twitter.com/' . $username . '">@<?php echo $data[0]['user']['screen_name']; ?></a></h2>
	</div>
	
	<div class="social-body">
		<ul>
		
			<?php 
			
			if(!($output = get_transient('tweets_'.$service_username) ) ):
				$output="";
				$count = 0;
				foreach($data as $tweet): 
					//if possible then we should cache th finished html of the list
					$output.= '<li>' . apply_filters('the_content',('https://twitter.com/' . $tweet['user']['screen_name'] . '/status/'. $tweet['id_str'])) . '</li>';
					$count++;
					if($count==3)break;
				endforeach; 
				set_transient('tweets_'.$service_username, $output, 60*60); 
			endif; 
			
			echo $output;
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



/**
 *	Output the HTML for the add Twitter window
 */
function ubcpeople_twitter_add(){
	
	?>
	<div style="display:none;">
		<div id="add-service-twitter" class="add-service">
			<h2>Add Twitter</h2>
			<form class="add-service-form" method="get" action="">
				<p>Twitter Username<br /> 	
					<input type="text" id="service-username" name="service-username" />
					<input type="hidden" name="add-service" value="twitter" />
					<input type="hidden" name="person" value="<?php echo $_REQUEST['person']; ?>" />
					
				</p>
				
				<p><button class="submit-add-social" type="button">Add</button>
					<span class="small">Any changes you have made will be saved.</span>
				</p>
			</form>
			
		</div>
	</div>
	<?
}


function ubcpeople_twitter_init(){
	ubcpeople_twitter_add();
	
}