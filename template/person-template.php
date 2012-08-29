<?php
	do_action('ubcpeople_admin');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Profile</title>
		<script type="text/javascript">
			var ajaxURL = '<?php echo admin_url('admin-ajax.php?action=ubcpeople_update_post')?>';
		</script>
		<?php wp_head(); ?>
	</head>
	<body>
	
		<?php
		//If user exists and has a public profile 
		$user = get_user_by('login', $_REQUEST['person']);
		if( $user && get_user_meta($user->ID, "public-profile", true)=='true'): 		
		
			$usermeta = ubcpeople_get_user_info ($user->ID);
			?>	
		
			<script type="text/javascript">
				postData = <?php echo json_encode($usermeta); ?>;
				postData.id = <?php echo $user->ID; ?>;
				jQuery.backstretch("/wp-content/uploads/people/"+postData.id+"/"+postData.people.bg.url);
			</script>
			
			<div id="main-container">
				<div class="profile-container resizable-box draggable-box" style="position:absolute; width:<?php echo $usermeta['people']['box']['w']?>px; background-color:<?php echo $usermeta['people']['styles']['box_bg']; ?>; left:<?php echo $usermeta['people']['box']['x']?>px; top:<?php echo $usermeta['people']['box']['y']?>px;">
					<div id="post-title" style="font-family:<?php echo $usermeta['people']['styles']['heading_font']?>;color: <?php echo $usermeta['people']['styles']['heading_color']; ?>">
						<h1><?php echo $usermeta['first_name'].' '.$usermeta['last_name']; ?></h1>
					</div>
					
					<div id="tagline" style="font-family:<?php echo $usermeta['people']['styles']['tagline_font']?>;color: <?php echo $usermeta['people']['styles']['tagline_color']; ?>">
						<?php echo $usermeta['people']['tagline']?>
					</div>
					
					<div id="post-content" style="font-family:<?php echo $usermeta['people']['styles']['text_font']?>;color: <?php echo $usermeta['people']['styles']['text_color']; ?>">
						<p><?php echo nl2br($usermeta['description']); ?></p>
					</div>
					
					<?php 
						
						//todo: seperate these elsewhere
						$internal_services = array();
						$external_services = array();
						foreach($usermeta['social'] as $service=>$service_username):
							$service_details = ubcpeople_get_service_parameters( $service );
							if( $service_details['category'] == 'internal' )
								$internal_services[$service] = $service_username;
							else
								$external_services[$service] = $service_username;
						endforeach;
						
						echo '<div>@UBC:</div>';
						
						if( $usermeta['social'] != '' ):
							$count = 0;
							foreach( $internal_services as $service=>$service_username):
								$service_details = ubcpeople_get_service_parameters($service);
								ubcpeople_display_service_icon($service, $count);
								$count++;
							endforeach;			
						
						echo '<div>Elsewhere</div>';
							
							foreach( $external_services as $service=>$service_username):
								$service_details = ubcpeople_get_service_parameters($service);
								ubcpeople_display_service_icon($service, $count);
								$count++;
							endforeach;
						endif;
					?>
					
				</div>
			</div>
			
			<?php 
				include 'social-overlay.php'; 	
				include 'admin-overlay.php'; 
			?>
			
			<span class="ajax-spinner" style="display:none;float:left;">Saving...</span>
			
		<?php else: ?>
			Nothing here
		<?php endif; ?>
		<?php wp_footer(); ?>
	</body>
</html>
