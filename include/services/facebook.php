<?php
function ubcpeople_facebook($username){ 

	//Check if we have any recent cached data before doing any work.
	if(!($data = get_transient('facebook_'.$username) ) ):
		$data = ubcpeople_fb_get_data($username);
	endif;
	?>

	<div class="social-header">
		<img src="<?php echo $data['picture']; ?>" style="float:right;" />
		<h2><a href="http://facebook.com/<?php echo $data['info']['id']; ?>"><?php echo $data['info']['name']; ?> on Facebook</a></h2>
	</div>
	
	<div class="social-body">
		<p>
			Current Status: <?php echo $data['feed']['data'][0]['message']; ?>
		</p>
	</div>
	
	<?php 
}



function ubcpeople_fb_get_data($username){
		$data = array();
		
		//Check if we have an access token.
		$fb_access_token = get_post_meta(get_the_ID(), "fb_access_token", true);
		if($fb_access_token):	
		
			$data['info'] = json_decode(file_get_contents('https://graph.facebook.com/'.$username.'?access_token='.$fb_access_token),true);
			$data['feed'] = json_decode(file_get_contents('https://graph.facebook.com/fql?q=SELECT+message,time+FROM+status+WHERE+uid='.$data['info']['id'].'+LIMIT+10&access_token='.$fb_access_token),true);
			$data['picture'] = 'https://graph.facebook.com/'.$username.'/picture?type=normal';
			set_transient('facebook_'.$username, $data, 60*60);
			
		else:	//If not, we can't really make the request

			$data['error'] = 'No access token!';
			
		endif;
		return $data;
}

function ubcpeople_facebook_get_access_code($post_id, $post_permalink){
	if($_GET['code']):
		$response =  file_get_contents('https://graph.facebook.com/oauth/access_token?client_id=391752004205525&redirect_uri=' . $post_permalink . '&client_secret=fcafa1b443fc3194707773c0efb37add&code=' . $_GET['code']);
		$parsed_data = array();
		parse_str($response, $parsed_data);
		update_post_meta($post_id, 'fb_access_token', $parsed_data['access_token']);
	endif;
}



function ubcpeople_facebook_get_icon(){
	return array(
		'url'=>'facebook.png',
		'id'=>'icon-facebook',
		'alt'=>'Facebook',
	);
}

function ubcpeople_facebook_init(){
	?>
	
	<?php
}