<?php
/*
Plugin Name: People
Plugin URI: http://ctlt.ubc.ca
Description: Under development.
Version: 0.1
Author: ejackisch, ctltdev
Author URI: http://ctlt.ubc.ca
*/

add_action( 'wp_ajax_ubcpeople_update_post', 'ubcpeople_update_post' );

add_action( 'edit_user_profile', 'ubcpeople_add_extra_profile_fields' );
add_action( 'show_user_profile', 'ubcpeople_add_extra_profile_fields' );
add_action( 'personal_options_update', 'ubcpeople_update_profile' );
add_action( 'edit_user_profile_update', 'ubcpeople_update_profile' );

add_action( 'admin_bar_menu', 'ubcpeople_admin_bar_link', 999 );
add_action( 'template_redirect', 'ubcpeople_include_template' );

add_action('init', 'ubcpeople_submit_add_service');
add_action('init', 'ubcpeople_submit_remove_service');

include 'include/receive-image.php';
include 'settings.php';

include 'include/services/twitter.php';
include 'include/services/ubc_blog.php';
include 'include/services/ubc_wiki.php';
include 'include/services/wordpress.php';
include 'include/services/facebook.php';

/**
 * ubcpeople_update_profile
 * Called when profile is updated via the backend edit-profile page, adds the ability to make a profile public and set a profile as homepage
 */
function ubcpeople_update_profile($user_id){
	$user = get_user_by('id', $user_id);
	$user_login = $user->user_login;
	
	if(!ubcpeople_current_user_can_edit($user_login)):
		return;
	endif;
	
	//Enable or disable public-profile based on checkbox
	if( isset( $_POST['public-profile'] ) && $_POST['public-profile'] == 'true' ):
		update_user_meta($user_id, 'public-profile', $_POST['public-profile']);
	else:
		delete_user_meta($user_id, 'public-profile');
	endif;	
	
	//Are we setting this to be the front page?
	if( isset( $_POST['profile-front-page'] ) && $_POST['profile-front-page'] == 'true' ):
		//If so, just set the option
		update_option( 'people-front-page-profile', $user_login );
	else:
		//If the box is not checked
		$frontpage_option = get_option('people-front-page-profile');
		if($frontpage_option == $user_login): 
			//If this page was previously the front page, then reset the front page
			delete_option( 'people-front-page-profile' );
		endif;
		//If this page wasn't the front page then there is no change!
	endif;
	
}


/**
 * ubcpeople_add_extra_profile_fields
 * Displays extra fields on the backend edit profile page
 */
function ubcpeople_add_extra_profile_fields($user){
	?>
	
	<h3>Public</h3>
	<table class="form-table">
		<tr>
			<th>Enable Public Profile</th>
			<td>
				<label for="public-profile">
					<input type="checkbox" id="public-profile" name="public-profile" value="true" <?php checked( get_user_meta( $user->ID, 'public-profile', true), 'true' ); ?> />
					Activate
				</label>
			</td>
		</tr>
		
		<tr>
			<th>Display as Front Page</th>
			<td>
				<label for="profile-front-page">
					<input type="checkbox" id="profile-front-page" name="profile-front-page" value="true" <?php checked( get_option('people-front-page-profile'), $user->user_login ); ?> />
					Override site front page
				</label>
			</td>
		</tr>
	</table>
	
	<?
}


//Adds live edit link (toggles the admin overlay) to the admin bar if user has permission
function ubcpeople_admin_bar_link(){
	global $wp_admin_bar;
	$current_user = wp_get_current_user();
	
	if( 1 /* Current user has a public profile*/ ):
		$wp_admin_bar->remove_menu('logout');
	
		$wp_admin_bar->add_node(
			array(
			'id'=>'people-view-profile',
			'title'=>'Public Profile',
			'href'=>'/?person='.$current_user->user_login,
			'parent'=>'user-actions',
			)
		);
	
		$wp_admin_bar->add_node( array(
			'parent' => 'user-actions',
			'id'     => 'logout',
			'title'  => __( 'Log Out' ),
			'href'   => wp_logout_url(),
			) 
		);
			
	endif;
	
	//todo: check if we are viewing a person page first.
	if( !is_admin() && ubcpeople_current_user_can_edit( $_REQUEST['person'] ) ):
		$wp_admin_bar->add_node(
			array(
			'id'=>'people-edit-profile',
			'title'=>'Edit Public Profile Page',
			'href'=>'',
			)
		);
	endif;	
}


/**
 * ubcpeople_include_template
 * Includes the necessary templates and files for a public profile page if we're viewing one.
 */
function ubcpeople_include_template() {
	
	//Are we  showing a profile on the frontpage?
	$frontpage_option = get_option('people-front-page-profile');
	if(!empty($frontpage_option) && !isset($_REQUEST['person']) && is_front_page() ):
		$_REQUEST['person'] = $frontpage_option;
	endif;
	
	//All the necessary scripts. Most of them are only necessary for admin controls so could be hidden if the user is not logged in
	//(or if they do not have edit access to the current profile)
	if ( !empty( $_REQUEST['person'] ) || (is_front_page() && !empty($frontpage_option)) ):	
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-resizable');
		wp_enqueue_script('jquery-ui-sortable');
		
		wp_enqueue_script('fileuploader', plugins_url( 'js/fileuploader.js', __FILE__ ));
		wp_enqueue_script('backstretch', plugins_url( 'js/jquery.backstretch.min.js', __FILE__ ));
		wp_enqueue_script('colorbox', plugins_url( 'js/jquery.colorbox-min.js', __FILE__ ));
		wp_enqueue_script('eyecon-colorpicker', plugins_url( 'colorpicker/js/colorpicker.js', __FILE__ ), array('jquery'));
		
		wp_enqueue_script('ubcpeople', plugins_url( 'js/profile.js', __FILE__ ));
		
		wp_enqueue_style("fileuploader", plugins_url( 'css/fileuploader.css', __FILE__ ));
		wp_enqueue_style("colorbox", plugins_url( 'css/colorbox.css', __FILE__ ));
		wp_enqueue_style("colorpicker", plugins_url( 'colorpicker/css/colorpicker.css', __FILE__ ));					
		wp_enqueue_style("ubcpeople", plugins_url( 'css/style.css', __FILE__ ));								
						
		wp_enqueue_style("people-jquery-ui", plugins_url( 'css/jquery-ui.css', __FILE__ ));			
		wp_enqueue_script("people-json2", "http://ajax.cdnjs.com/ajax/libs/json2/20110223/json2.js");			

    	include 'template/person-template.php';
    	exit;
    endif;
    
}


/*
 *	Update post via ajax from front-end
 */
function ubcpeople_update_post(){
	// (but why are there even slashes for us to strip?)
	$people_data = json_decode(stripslashes($_POST['people']), true);
	$social_data = json_decode(stripslashes($_POST['social']), true);
	
	$people = array(
		'box'=>$people_data['box'],
		'styles'=>$people_data['styles'],
		'bg'=>$people_data['bg'],
		'images'=>$people_data['images'],
		'tagline'=>$people_data['tagline'],
	);
	
	$social = $social_data;
	
	
	//If the order of social icons has been changed, update it..
	if($social['order']):
		$order = $social['order'];
		$new_social = array();
		foreach($order as $slug):
			$new_social[$slug] = $social[$slug];
		endforeach;
		$social = $new_social;
	endif;
	
	
	$id = $_POST['id']; 
	$post_data = array(
		'ID' => $id,
	);
	
	//wp_update_user( $post_data );
	update_user_meta($id, 'people', $people);
	update_user_meta($id, 'social', $social);
	
	update_user_meta($id, 'first_name', $people_data['first_name']);
	update_user_meta($id, 'last_name', $people_data['last_name']);
	update_user_meta($id, 'description', $people_data['description']);
}


/**
 * ubcpeople_add_service
 * Add a service to a profile
 * @param int $person_name Username associated with the profile that's being edited
 * @param string $service_name Name of the external service being added
 * @param string $service_username Users username on the specified external service
 */
function ubcpeople_add_service($person_name, $service_name, $service_username){
	
	if(!ubcpeople_current_user_can_edit($person_name)):
		return;
	endif;
	
	$person = get_user_by('login', $person_name);
	$user_id = $person->ID;
	
	$social = get_user_meta($user_id, 'social', true);
	$social[$service_name] = $service_username;
	update_user_meta($user_id, 'social', $social);

	
}



/**
 *	ubcpeople_remove_service
 *	Remove a service from a profile
 */
function ubcpeople_remove_service($person_name, $service_name){

	if(!ubcpeople_current_user_can_edit($person_name)):
		return;
	endif;
	
	$person = get_user_by('login', $person_name);
	$user_id = $person->ID;

	$social = get_user_meta($user_id, 'social', true);
	unset($social[$service_name]);
	update_user_meta($user_id, 'social', $social);

}


/**
 * ubcpeople_is_valid_service
 * @param $service_slug
 * Checks if the service slug is valid / corresponds to an implemented service
 */
function ubcpeople_is_valid_service($service_slug){
	$social_array = ubcpeople_get_available_services();
	
	//to do: this should be passed a slug, so all this function should need to do is verify that it exists in the array
	
	if(array_key_exists($service_slug, $social_array)):
		return true;
	endif;
	return false;
}


/**
 * ubcpeople_display_service_icon
 * @param string $service
 * Given a string, displays an icon linking to that service in a popup
 */
function ubcpeople_display_service_icon($service_slug, $count){
	if( ubcpeople_is_valid_service( $service_slug ) ):
		$icon = call_user_func('ubcpeople_' . $service_slug . '_get_icon');
		echo '<a class="open-social-overlay" id="icon-' . $count. '" href="#social-inline-content"><img width="32" height="32" src="' . plugins_url( '/social-icons/png/' . $icon['url'] , __FILE__ ) . '" alt="' . $icon['alt'] . '" /></a>';
	endif;
}


/**
 * ubcpeople_display_service
 * Calls the function to display the content for a particular service (in the overlay)
 *
 */
function ubcpeople_display_service($service_slug, $person_id, $service_username){
	if( ubcpeople_is_valid_service( $service_slug ) ):
		call_user_func('ubcpeople_' .$service_slug, $person_id, $service_username);
	endif;
}


/**
 * ubcpeople_get_user_info
 * Retrieve all the required information for a person
 */
function ubcpeople_get_user_info($id){
	//Retrieve post meta information  
	$user = get_userdata($id);
	$usermeta = array(
		'people' => get_user_meta( $id, 'people', true),
		'social' => get_user_meta( $id, 'social', true),
		'first_name' => get_user_meta( $id, 'first_name', true),
		'last_name' => get_user_meta( $id, 'last_name', true),
		'description' => get_user_meta( $id, 'description', true),
		'id' => $id,
		'login' => $user->user_login,
	);

	if(isset($usermeta['people']) && empty($usermeta['people']))unset($usermeta['people']);
	if(isset($usermeta['social']) && empty($usermeta['social']))unset($usermeta['social']);
	
	//merge with default values
	$usermeta = wp_parse_args( 
		$usermeta,
		array(
			'people'=>array(
				'box'=>array(
					'x'=>'32', 
					'y'=>'32', 
					'w'=>'300',
				),
				'styles'=>array(
					'heading_color'=>'#ffffff', 
					'heading_font'=>'sans-serif',
					'tagline_color'=>'#ffffff', 
					'tagline_font'=>'sans-serif',
					'text_color'=>'#ffffff', 
					'text_font'=>'sans-serif',
					'box_bg'=>'#000000',
					'box_opacity'=>'0.5',
				),
				'bg'=>array('url'=>''),
				'images'=>array(),
				'tagline'=>'',
				),
			'social'=>array(
				
			),
			
		)
	);
	
	return $usermeta;
}


function ubcpeople_get_available_services(){
	return array(
		'facebook'=>'Facebook',
		'twitter'=>'Twitter',
		'linkedin'=>'LinkedIn',
		'ubc_blog'=>'UBC Blog',
		'ubc_wiki' =>'UBC Wiki',
		'wordpress' =>'WordPress.com',
		
	);
}


function ubcpeople_get_service_name_from_slug($service_slug){
	$services = ubcpeople_get_available_services();
	return $services[$service_slug];
}


/**
 *	Called when a user submits the form to add a new service (excluding services requiring authentication )
 */
function ubcpeople_submit_add_service(){
	if(isset($_GET['add-service'])):
		
		ubcpeople_add_service( ubcpeople_get_current_person(), $_GET['add-service'], $_GET['service-username']);
		
		header('Location: ' . ubcpeople_get_person_url() ); 
		exit;
	endif;
	
}


/**
 * Called when a user requests to remove a service
 */
function ubcpeople_submit_remove_service(){
	if(isset($_GET['remove-service'])):
		//$user = get_user_by('login', ubcpeople_get_current_person() );
		ubcpeople_remove_service( ubcpeople_get_current_person(), $_GET['remove-service']);
		
		header('Location: ' . ubcpeople_get_person_url() ); 
		exit;
	endif;
}


/**
 * Determine if the currently logged in user has permission to edit the profile they are currently viewing
 */
function ubcpeople_current_user_can_edit($person_name){
	global $current_user;
	if( isset($person_name) && ( $person_name == $current_user->user_login || current_user_can('edit_users') ) )
		return true;
	return false;
}



//URL and URL parameter

/**
 * ubcpeople_get_person_url
 * Returns URL to a particular persons public profile page, optionally with an array of additional get parameters
 * @param string $person_name
 * @param array $get_parameters
 */
function ubcpeople_get_person_url($person_name = '', $get_parameters = array()){

	if(empty($person_name))
		$person_name = ubcpeople_get_current_person();
		
	$query_string = http_build_query($get_parameters);
	return 'http://'.$_SERVER['SERVER_NAME'].'/?person='.$person_name.'&'.$query_string;
}

function ubcpeople_get_current_person(){
	if(isset($_REQUEST['person']))
		return $_REQUEST['person'];
}
