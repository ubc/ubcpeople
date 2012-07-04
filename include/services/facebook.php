<?
function ubcpeople_facebook($username){

	//Check if we have any recent cached data before doing any work.
	if(!($data = get_transient('facebook_'.$username) ) ):
		$data = ubcpeople_fb_get_data($username);
	endif;
	?>
	
	<img src="<?php echo $data['picture']; ?>" />
	<a href="http://facebook.com/<?php echo $data['info']['id']; ?>"><?php echo $data['info']['name']; ?></a>
	<p><?php echo 'Current Status: '.$data['feed']['data'][0]['message']; ?></p>
	<?php echo '<br /><br /><pre>'; print_r( $data); echo '</pre>'; ?>
	
	<?php
}


function ubcpeople_fb_get_data($username){
		$data = array();
		
		//Check if we have an access token.
		$fb_access_token = get_post_meta(get_the_ID(), "fb_access_token", true);
		if($fb_access_token):	
		
			$data['info'] = json_decode(file_get_contents('https://graph.facebook.com/'.$username.'?access_token='.$fb_access_token),true);
			//$data['feed'] = json_decode(file_get_contents('https://graph.facebook.com/'.$username.'/feed?access_token='.$fb_access_token),true);
			$data['feed'] = json_decode(file_get_contents('https://graph.facebook.com/fql?q=SELECT+message,time+FROM+status+WHERE+uid='.$data['info']['id'].'+LIMIT+10&access_token='.$fb_access_token),true);
			$data['picture'] = 'https://graph.facebook.com/'.$username.'/picture?type=normal';
			set_transient('facebook_'.$username, $data, 60*60);
			
		else:	//If not, we can't really make the request

			$data['error'] = 'No access token!';
			
		endif;
		return $data;
}


function ubcpeople_get_icon_facebook(){
	return array(
		'url'=>'facebook.png',
		'id'=>'icon-facebook',
		'alt'=>'Facebook',
	);
}