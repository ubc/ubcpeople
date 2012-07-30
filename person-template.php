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
		
		$user = get_user_by('login', 'admin');

		if( $user ): ?>
		<?php
			//TEMPORARY! This should go somewhere else
			//Retrieve FB access code
			//ubcpeople_facebook_get_access_code(get_permalink(), get_the_ID());
			//--
		?>
		
		<?php	
		
		$usermeta = ubcpeople_get_user_info ($user->ID);
		
		//echo '<pre>';print_r($usermeta);die();
		?>	
		
			<script type="text/javascript">
				postData = <?php echo json_encode($usermeta); ?>;
				postData.id = <?php echo $user->ID; ?>;
				
				jQuery('html').css("background-image", "url(/wp-content/uploads/people/"+postData.id+"/"+postData.people.bg.url+")"); //to do: use css
			</script>
			<!-- <?php echo '<pre>';print_r($usermeta);echo '</pre><br />';?> -->
			<div id="main-container">
				<div class="profile-container resizable-box draggable-box" style="position:absolute; width:<?php echo $usermeta['people']['box']['w']?>px; background-color:rgba(0,0,0, 0.3); left:<?php echo $usermeta['people']['box']['x']?>px; top:<?php echo $usermeta['people']['box']['y']?>px;">
					<div id="post-title" style="font-family:<?php echo $usermeta['people']['styles']['heading_font']?>;color: <?php echo $usermeta['people']['styles']['heading_color']; ?>"><h1><?php echo $usermeta['first_name'].' '.$usermeta['last_name']; ?></h1></div>
					<div id="post-content" style="font-family:<?php echo $usermeta['people']['styles']['text_font']?>;color: <?php echo $usermeta['people']['styles']['text_color']; ?>"><p><?php echo nl2br($usermeta['description']); ?></p></div>			
					<?php 
						
						if( $usermeta['social'] != '' ):
							foreach( $usermeta['social'] as $service):
								ubcpeople_display_service_icon($service);
							endforeach;
						endif;
					?>
				</div>
			</div>
			
			<?php include 'social-overlay.php'; ?>
		
			<?php include 'admin-overlay.php'; ?>
			
			<span class="ajax-spinner" style="display:none;float:left;">Saving...</span>
			
		<?php endif; ?>
		
		<?php wp_footer(); ?>
	</body>
</html>
