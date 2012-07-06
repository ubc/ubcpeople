<?php
add_action('wp_ajax_ubcpeople_update_post', 'ubcpeople_update_post');

include 'include/receive-image.php';
include 'include/services/twitter.php';
include 'include/services/ubc_blog.php';
include 'include/services/ubc_wiki.php';
include 'include/services/wordpress.php';
include 'include/services/facebook.php';


/*
 *	Update post via ajax from front-end
 */
function ubcpeople_update_post(){

	$post_meta = json_decode(stripslashes($_POST['postData']), true);
	//edit the one below in place because of the way profile-cct plugin checks for this data
	$_POST['profile_cct'] = json_decode(stripslashes($_POST['profile_cct']), true);	
	
	$id = $_POST['id']; 

	$post_data = array(
		'ID' => $id,
		'post_modified' => date('Y-m-d H:i:s'),
	);
	
	wp_update_post( $post_data );
	update_post_meta($id, 'people', $post_meta);
}


/**
 * ubcpeople_get_service_function()
 * @param $service_name
 * Given a string referring to an external service as an input, checks its validity and returns a string which is used to call functions for that service, or false on failure
 */
function ubcpeople_get_service_function($service_name){
	$social_options = profile_cct_social_options();
	
	//Make key->value array of slug->name correspond
	$social_array = array();
	foreach($social_options as $service):
		$social_array[] = $service['label'];
	endforeach;
	
	if(in_array($service_name, $social_array)):
		return str_replace( array(' ', '-','.'), '_', $service_name);
	endif;
	return false;
}


/**
 * ubcpeople_display_service_icon()
 * @param string $service
 * Given a string, displays an icon linking to that service in a popup
 */
function ubcpeople_display_service_icon($service){
	$func = ubcpeople_get_service_function($service['option']);
	if($func):
		$icon = call_user_func('ubcpeople_' . $func . '_get_icon');
		echo '<a class="open-social-overlay" id="' . $icon['id'] . '" href="#social-inline-content"><img width="32" height="32" src="' . get_stylesheet_directory_uri() . '/social-icons/png/' . $icon['url'] . '" alt="' . $icon['alt'] . '" /></a>';
	endif;
}


/**
 * ubcpeople_display_service()
 * Calls the function to display the content for a particular service
 *
 */
function ubcpeople_display_service($service, $username){
	$func = ubcpeople_get_service_function($service);
	if($func):
		call_user_func('ubcpeople_' . $func, $username);
	endif;
}