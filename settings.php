<?php
/*
 *	Settings Page
 */

if( is_admin() ):
	add_action( 'admin_menu', 'ubcpeople_admin_menu' );
	add_action( 'admin_init', 'ubcpeople_register_settings' );
endif;


function ubcpeople_admin_menu(){
	add_options_page('UBC People Settings', 'People', 'manage_options', 'ubcpeople', 'ubcpeople_settings_page');
}


function ubcpeople_register_settings(){
	register_setting( 'people_settings', 'people_settings', 'ubcpeople_options_validate' );
	add_settings_section('ubcpeople_keys', 'API keys', 'ubcpeople_keys_section_string', 'ubcpeople');
		add_settings_field('ubcpeople_fb_key', 'Facebook App ID', 'ubcpeople_fb_key_field', 'ubcpeople', 'ubcpeople_keys');
		add_settings_field('ubcpeople_fb_secret', 'Facebook App Secret', 'ubcpeople_fb_secret_field', 'ubcpeople', 'ubcpeople_keys');
		
		add_settings_field('ubcpeople_linkedin_key', 'LinkedIn App Key', 'ubcpeople_linkedin_key_field', 'ubcpeople', 'ubcpeople_keys');
		add_settings_field('ubcpeople_linkedin_secret', 'LinkedIn App Secret', 'ubcpeople_linkedin_secret_field', 'ubcpeople', 'ubcpeople_keys');

}


function ubcpeople_keys_section_string(){
	?>
	You must create an app on these social networks and fill in the boxes below with the appropriate keys.
	<?
}


function ubcpeople_fb_key_field(){
	$options = get_option('people_settings');
	?>
	<input type="text" id="fb_key" name="people_settings[fb_key]"value="<?php echo $options['fb_key']; ?>" />
	<?
}


function ubcpeople_fb_secret_field(){
	$options = get_option('people_settings');
	?>
	<input type="text" id="fb_secret" name="people_settings[fb_secret]" value="<?php echo $options['fb_secret']; ?>" />
	<?
}


function ubcpeople_linkedin_key_field(){
	$options = get_option('people_settings');
	?>
	<input type="text" id="linkedin_key" name="people_settings[linkedin_key]"value="<?php echo $options['linkedin_key']; ?>" />
	<?
}


function ubcpeople_linkedin_secret_field(){
	$options = get_option('people_settings');
	?>
	<input type="text" id="linkedin_secret" name="people_settings[linkedin_secret]" value="<?php echo $options['linkedin_secret']; ?>" />
	<?
}


function ubcpeople_options_validate($input){
	return $input;
}


function ubcpeople_settings_page(){
	?>
	<div class="wrap">
		<h2>People</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'people_settings' ); ?>
			<?php do_settings_sections( 'ubcpeople' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?
}