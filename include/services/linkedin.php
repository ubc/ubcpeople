<?php
add_action('ubcpeople_admin', 'ubcpeople_linkedin_init');
add_action('ubcpeople_add_service_form', 'ubcpeople_linkedin_add');
/**
 *	Display the content of the Linkedin social overlay. To be called from main plugin file
 *	@param int $person_id
 *	@param string $service_username
 */
function ubcpeople_linkedin($person_id, $service_username){ 

	//Check if we have any recent cached data before doing any work.
	if( 1 || !( $data = get_transient('linkedin_' . $service_username) ) ):
		$data = ubcpeople_linkedin_get_data( $person_id, $service_username );
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


function ubcpeople_linkedin_get_data($person_id, $service_username){
		$data = array();

		//Check if we have an access token.
		$linkedin_access_token = get_user_meta($person_id, "linkedin_access_token", true);
		if($linkedin_access_token):	
		
			//$data['info'] = json_decode(file_get_contents('https://graph.facebook.com/'.$service_username.'?access_token='.$fb_access_token),true);
			//$data['feed'] = json_decode(file_get_contents('https://graph.facebook.com/fql?q=SELECT+message,time+FROM+status+WHERE+uid='.$data['info']['id'].'+LIMIT+10&access_token='.$fb_access_token),true);
			//$data['picture'] = 'https://graph.facebook.com/'.$service_username.'/picture?type=normal';
			set_transient('linkedin_'.$service_username, $data, 60*60);
			
		else:	//If not, we can't really make the request

			$data['error'] = 'No access token!';
			
		endif;
		return $data;
}


function ubcpeople_linkedin_get_access_code($url, $username){

	if( isset($_GET['act']) && $_GET['act'] == 'add-linkedin' ):
	
		//Information the OAuth2 script needs
		
		
		$user = get_user_by('login', $username);
		
		$options = get_option("people_settings");
		$client = new OAuth2\Client($options['linkedin_key'], $options['linkedin_secret']);
		
		$redirect_url = 'http://'.$_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI'];		//Where the User will be redirected after they come back from facebook.com
		$authorization_endpoint = 'https://www.linkedin.com/uas/oauth/authenticate';
		
		$token_endpoint = 'https://api.linkedin.com/uas/oauth/accessToken';
		
		$auth_url = $client->getAuthenticationUrl ($authorization_endpoint, $redirect_url, array('scope'=>'user_status', 'act'=>'add-linkedin'));	
		
		$request_token_url = 'https://api.linkedin.com/uas/oauth/requestToken';	
		
		if (!isset($_GET['code']))
		{
			//STEP 1:
			//redirect the user to the facebook auth page.
			$auth_url = $client->getAuthenticationUrl($authorization_endpoint, $redirect_url);
			header('Location: ' . $auth_url);
			die('Redirect');
		}
		else
		{
			//STEP 2:
			//Get access token from Linkedin server
			$params = array('code' => $_GET['code'], 'redirect_uri' => $redirect_url);
			$response = $client->getAccessToken($token_endpoint, 'authorization_code', $params);
			parse_str($response['result'], $info);
			$client->setAccessToken($info['access_token']);
			
			//Retrieve users linkedin information
			$response = $client->fetch('http://api.linkedin.com/v1/people/~');
			print_r($response);
			//Store the user's access token and linkedin user ID
			//update_user_meta($user->ID, 'linkedin_access_token', $info['access_token']);
			//ubcpeople_add_service($username, 'linkedin', $response['result']['id']);
		}
		
	endif;

/*

	$user = get_user_by('login', $username);
	return;
	if(isset($_GET['code']) && $_GET['app'] == 'linkedin'):
		$options = get_option("people_settings");
		//Exchange the code for the access token
		$response =  file_get_contents('https://api.linkedin.com/uas/oauth/accessToken?client_id=' . $options['linkedin_key'] . '&redirect_uri=' . $url . '&client_secret=' . $options['linkedin_secret'] . '&code=' . $_GET['code']);
		$parsed_data = array();
		parse_str($response, $parsed_data);
		
		//store the access token
		update_user_meta($user->ID, 'linkedin_access_token', $parsed_data['access_token']);
		
		//Grab the user details so we have their ID/username
		$linkedin_user_details = json_decode(file_get_contents('http://api.linkedin.com/v1/people/~?access_token='. $parsed_data['access_token']),true);
		print_r($linkedin_user_details);
		die();
		ubcpeople_add_service($username, 'linkedin', $linkedin_user_details['id']);
		
		//temporary solution
		echo '<meta http-equiv="refresh" content="0;url='.ubcpeople_get_person_url().'" />';
		exit;
	endif;*/
}



function ubcpeople_linkedin_get_parameters(){
	return array(
		'icon-url'=>'linkedin.png',
		'icon-id'=>'icon-linkedin',
		'icon-alt'=>'LinkedIn',
		'category'=>'external',
	);
}


/**
 *	Output the HTML for the add Linkedin window
 */
function ubcpeople_linkedin_add(){
	global $usermeta;
	?>
	<div style="display:none;">
		<div id="add-service-linkedin" class="add-service">
			<h2>LinkedIn</h2>
			<p>You will be redirected to LinkedIn to authenticate. </p>
			<p><a class="submit-add-social" href="<?php echo ubcpeople_get_person_url($usermeta['login'], array('act'=>'add-linkedin') ); ?>">Authenticate</a>
			<span class="small">Any changes you have made will be saved.</span>
			</p>
		</div>
	</div>
	<?php
}

function ubcpeople_linkedin_init(){
	$current_url = 'http://'.$_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI']; //temp
	ubcpeople_linkedin_get_access_code($current_url, $_REQUEST['person']);
	
}