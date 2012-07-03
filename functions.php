<?php
add_action('wp_ajax_ubcpeople_update_post', 'ubcpeople_update_post');

include 'include/receive-image.php';
include 'include/services/twitter.php';
include 'include/services/ubc_blog.php';
include 'include/services/ubc_wiki.php';

/**
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

function ubcpeople_display_service_icon($service){
	switch($service['option']){
		case 'Twitter':
			echo '<a class="open-social-overlay" id="icon-twitter" href="#social-inline-content"><img width="32" height="32" src="' . get_stylesheet_directory_uri() . '/social-icons/png/twitter.png" alt="Twitter" /></a>';
		break;
		case 'Facebook':
			echo '<a class="open-social-overlay" id="icon-facebook" href="#social-inline-content"><img width="32" height="32" src="' . get_stylesheet_directory_uri() . '/social-icons/png/facebook.png" alt="Facebook" /></a>';
		break;
		case 'UBC Blog':
			echo '<a class="open-social-overlay" id="icon-ubc-blogs" href="#social-inline-content"><img width="32" height="32" src="' . get_stylesheet_directory_uri() . '/social-icons/png/wordpress.png" alt="UBC Blog" /></a>';
		break;
		case 'UBC Wiki':
			echo '<a class="open-social-overlay" id="icon-ubc-wiki" href="#social-inline-content"><img width="32" height="32" src="' . get_stylesheet_directory_uri() . '/social-icons/png/friendster.png" alt="UBC Wiki" /></a>';
		break;
		default:
		
		break;
	}
}

function ubcpeople_display_service($service, $username){
	switch($service){
		case 'Twitter':
			ubcpeople_twitter($username);
		break;
		case 'UBC Blog':
			ubcpeople_ubc_blog($username);
		break;
		case 'UBC Wiki':
			ubcpeople_ubc_wiki($username);
		break;
		case 'Facebook':
			//ubcpeople_ubc_wiki($username);
		break;
	}
}