<?php
add_action('ubcpeople_admin', 'ubcpeople_facebook_init');

/**
 *	Display the content of the Facebook social overlay. To be called from main plugin file
 *	@param int $person_id
 *	@param string $service_username
 */
function ubcpeople_facebook($person_id, $service_username){ 

	//Check if we have any recent cached data before doing any work.
	if(!($data = get_transient('facebook_'.$service_username) ) ):
		$data = ubcpeople_fb_get_data($person_id, $service_username);
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


function ubcpeople_fb_get_data($person_id, $service_username){
		$data = array();

		//Check if we have an access token.
		$fb_access_token = get_user_meta($person_id, "fb_access_token", true);
		if($fb_access_token):	
		
			$data['info'] = json_decode(file_get_contents('https://graph.facebook.com/'.$service_username.'?access_token='.$fb_access_token),true);
			$data['feed'] = json_decode(file_get_contents('https://graph.facebook.com/fql?q=SELECT+message,time+FROM+status+WHERE+uid='.$data['info']['id'].'+LIMIT+10&access_token='.$fb_access_token),true);
			$data['picture'] = 'https://graph.facebook.com/'.$service_username.'/picture?type=normal';
			set_transient('facebook_'.$service_username, $data, 60*60);
			
		else:	//If not, we can't really make the request

			$data['error'] = 'No access token!';
			
		endif;
		return $data;
}


function ubcpeople_facebook_get_access_code($url, $username){


	$user = get_user_by('login', $username);
	
	if(isset($_GET['code']) && $_GET['app'] == 'facebook'):
	
		$options = get_option("people_settings");
		
		$client 		= new OAuth2\Client($options['fb_key'], $options['fb_secret']);
		$redirect_uri 	= $url;
		$token_endpoint = 'https://graph.facebook.com/oauth/access_token';
	
	
		$params = array( 'code' => $_GET['code'], 'redirect_uri' => $redirect_uri );
		$response = $client->getAccessToken($token_endpoint, 'authorization_code', $params);
		print_r($response['result']);
		parse_str($response['result'], $info);
		$client->setAccessToken($info['access_token']);
		$fb_user_details = $client->fetch('https://graph.facebook.com/me');
		print_r( $fb_user_details );
		die();
		//store the access token
		update_user_meta($user->ID, 'fb_access_token', $info['access_token']);
		
		//Grab the user details so we have their ID/username
		$fb_user_details = json_decode(file_get_contents('https://graph.facebook.com/me?access_token='. $parsed_data['access_token']),true);
		
		ubcpeople_add_service($username, 'facebook', $fb_user_details['id']);
		
		//temporary solution
		echo '<meta http-equiv="refresh" content="0;url='.ubcpeople_get_person_url().'" />';
		exit;
	endif;
}



function ubcpeople_facebook_get_parameters(){
	return array(
		'icon-url'=>'facebook.png',
		'icon-id'=>'icon-facebook',
		'icon-alt'=>'Facebook',
		'category'=>'external',
	);
}


/**
 *	Output the HTML for the add Facebook window
 */
function ubcpeople_facebook_add(){
	$current_url = 'http://'.$_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI']; //temp
	$options = get_option("people_settings");
	?>
	<div style="display:none;">
		<div id="add-service-facebook" class="add-service">
			<h2>Facebook</h2>
			<p>You will be redirected to Facebook to authenticate. </p>
			<p><a class="submit-add-social" href="https://www.facebook.com/dialog/oauth/?client_id=<?php echo $options['fb_key']; ?>&redirect_uri=<?php echo urlencode($current_url . '&app=facebook'); ?>&state=todocsrf&scope=user_status">Authenticate</a>
			<span class="small">Any changes you have made will be saved.</span>
			</p>
		</div>
	</div>
	<?php
}

function ubcpeople_facebook_init(){
	$current_url = 'http://'.$_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI']; //temp
	
	ubcpeople_facebook_add();
	ubcpeople_facebook_get_access_code($current_url, $_REQUEST['person']);
	
}