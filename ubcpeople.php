<?php
/*
Plugin Name: People
Plugin URI: http://ctlt.ubc.ca
Description:
Version: 0.1
Author: ejackisch, ctltdev
Author URI: http://ctlt.ubc.ca
License: A "Slug" license name e.g. GPL2
*/

add_action( 'wp_ajax_ubcpeople_update_post', 'ubcpeople_update_post' );

add_action( 'edit_user_profile', 'ubcpeople_add_extra_profile_fields' );
add_action( 'show_user_profile', 'ubcpeople_add_extra_profile_fields' );
add_action( 'personal_options_update', 'ubcpeople_update_profile' );
add_action( 'edit_user_profile_update', 'ubcpeople_update_profile' );

add_action( 'admin_bar_menu', 'ubcpeople_admin_bar_link', 999 );
add_action( 'template_redirect', 'ubcpeople_include_template' );

include 'include/receive-image.php';
include 'include/services/twitter.php';
include 'include/services/ubc_blog.php';
include 'include/services/ubc_wiki.php';
include 'include/services/wordpress.php';
include 'include/services/facebook.php';


//called when profile is updated via the backend edit-profile page. Updates the custom fields for this profile in the DB
function ubcpeople_update_profile($user_id){
	if( isset( $_POST['public-profile'] ) && $_POST['public-profile'] == 'true' )
		update_user_meta($user_id, 'public-profile', $_POST['public-profile']);
	else
		delete_user_meta($user_id, 'public-profile');
}


function ubcpeople_add_extra_profile_fields($user){
	?>
	
	<h3>Privacy</h3>
	<table class="form-table">
		<tr>
			<th>Public Profile</th>
			<td>
				<label for="public-profile">
					<input type="checkbox" id="public-profile" name="public-profile" value="true" <?php checked( get_user_meta($user->ID, 'public-profile', true), 'true' ); ?> />
					Enable public profile page
				</label>
			</td>
		</tr>
	</table>
	
	<!--
	<h3>Social Feeds</h3>
	
	<table class="form-table">
	
		<tr>
			<th><label for="social-ubc-blog">UBC Blog</label></th>
			<td>
				<input type="text" name="social-ubc-blog" id="social-ubc-blog" /> 
				<span class="description"></span>
				<br />
			</td>
		</tr>
	
		<tr>
			<th><label for="social-facebook">Facebook</label></th>
			<td>
				(Authentication Button here!)
			</td>
		</tr>
		
		<tr>
			<th><label for="social-twitter">Twitter</label></th>
			<td>
				<input type="text" name="social-twitter" id="social-twitter" /> 
				<span class="description"></span>
				<br />
			</td>
		</tr>
		
	</table>
	-->
	<?
}


//Adds live edit link to admin bar if user has permission
function ubcpeople_admin_bar_link(){
	global $wp_admin_bar;
	$current_user = wp_get_current_user();
	
	
	if( isset($_REQUEST['person']) && ( $_REQUEST['person'] == $current_user->user_login || current_user_can('edit_users') ) ):
		$wp_admin_bar->add_node(
			array(
			'id'=>'people-edit-profile',
			'title'=>'Edit Public Profile Page',
			'href'=>'',
			)
		);
	endif;
	
}

function ubcpeople_include_template() {
	
	if ( !empty( $_REQUEST['person'] ) ):	
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-resizable');
		
		wp_enqueue_script('fileuploader', plugins_url( 'js/fileuploader.js', __FILE__ ));
		wp_enqueue_script('colorbox', plugins_url( 'js/jquery.colorbox-min.js', __FILE__ ));
		wp_enqueue_script('eyecon-colorpicker', plugins_url( 'colorpicker/js/colorpicker.js', __FILE__ ), array('jquery'));
		
		wp_enqueue_script('ubcpeople', plugins_url( 'js/profile.js', __FILE__ ));
		
		wp_enqueue_style("fileuploader", plugins_url( 'fileuploader.css', __FILE__ ));
		wp_enqueue_style("colorbox", plugins_url( 'colorbox.css', __FILE__ ));
		wp_enqueue_style("colorpicker", plugins_url( 'colorpicker/css/colorpicker.css', __FILE__ ));					
		wp_enqueue_style("ubcpeople", plugins_url( 'style.css', __FILE__ ));								
						
		wp_enqueue_style("people-jquery-ui", plugins_url( 'jquery-ui.css', __FILE__ ));			
		wp_enqueue_script("people-json2", "http://ajax.cdnjs.com/ajax/libs/json2/20110223/json2.js");			

    	include 'person-template.php';
    	exit;
    endif;
    
}


/*
 *	Update post via ajax from front-end
 */
function ubcpeople_update_post(){

	$people_data = json_decode(stripslashes($_POST['people']), true);
	$social_data = json_decode(stripslashes($_POST['social']), true);
	
	$people = array(
		'box'=>$people_data['box'],
		'styles'=>$people_data['styles'],
		'bg'=>$people_data['bg'],
		'images'=>$people_data['images'],
	);
	
	$social = $social_data;
	
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


function ubcpeople_add_service($user_id, $service_name, $service_username){
	$social = get_user_meta($user_id, 'social', true);
	$social[$service_name] = $service_username;
	
	update_user_meta($user_id, 'social', $social);
}


/**
 * ubcpeople_get_service_function()
 * @param $service_name
 * Given a string referring to an external service as an input, checks its validity and returns a string which is used to call functions for that service, or false on failure
 */
function ubcpeople_get_service_function($service_name){
	//$social_options = profile_cct_social_options();
	
	//Make key->value array of slug->name correspond
	//$social_array = array();
	//foreach($social_options as $service):
	//	$social_array[] = $service['label'];
	//endforeach;
	
	//if(in_array($service_name, $social_array)):
		return str_replace( array(' ', '-','.'), '_', $service_name);
	//endif;
	return false;
}


/**
 * ubcpeople_display_service_icon()
 * @param string $service
 * Given a string, displays an icon linking to that service in a popup
 */
function ubcpeople_display_service_icon($service){
	$func = ubcpeople_get_service_function($service);
	
	if($func):
		$icon = call_user_func('ubcpeople_' . $func . '_get_icon');
		echo '<a class="open-social-overlay" id="' . $icon['id'] . '" href="#social-inline-content"><img width="32" height="32" src="' . plugins_url( '/social-icons/png/' . $icon['url'] , __FILE__ ) . '" alt="' . $icon['alt'] . '" /></a>';
	endif;
}


/**
 * ubcpeople_display_service()
 * Calls the function to display the content for a particular service
 *
 */
function ubcpeople_display_service($service, $person_id, $service_username){
	$func = ubcpeople_get_service_function($service);
	if($func):
		call_user_func('ubcpeople_' . $func, $person_id, $service_username);
	endif;
}




function ubcpeople_get_user_info($id){
	//Retrieve post meta information (this array_map bit is from the codex for get_user_meta) 
	$usermeta = array(
		'people' => get_user_meta( $id, 'people', true),
		'social' => get_user_meta( $id, 'social', true),
		'first_name' => get_user_meta( $id, 'first_name', true),
		'last_name' => get_user_meta( $id, 'last_name', true),
		'description' => get_user_meta( $id, 'description', true),
		'id' => $id,
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
					'text_color'=>'#ffffff', 
					'text_font'=>'sans-serif',
					'box_bg'=>'#000',
					'box_opacity'=>'0.5',
				),
				'bg'=>array('url'=>''),
				'images'=>array(),
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
		'ubc-blog'=>'UBC Blog',
		'ubc-wiki' =>'UBC Wiki',
	);
}