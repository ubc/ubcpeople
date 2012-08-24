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
	endif;
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
	$current_url = 'http://'.$_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI']; //temp
	$options = get_option("people_settings");
	?>
	<div style="display:none;">
		<div id="add-service-linkedin" class="add-service">
			<h2>LinkedIn</h2>
			<p>You will be redirected to LinkedIn to authenticate. </p>
			<p><a class="submit-add-social" href="https://www.linkedin.com/uas/oauth/requestToken/?oauth_consumer_key=<?php echo $options['linkedin_key']; ?>&oauth_callback=<?php echo urlencode($current_url . '&app=linkedin'); ?>">Authenticate</a>			
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