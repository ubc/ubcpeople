<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
	<?php wp_enqueue_script("jquery"); ?>
	
	<?php wp_head(); ?>
	
	<link type="text/css" href="<?php echo get_stylesheet_uri() ?>" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>
	<script type="text/javascript">
		var postData = { box: {} };
		var ajaxURL = '<?php echo admin_url('admin-ajax.php?action=ubcpeople_update_post')?>';
	</script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/profile.js" type="text/javascript"></script>
	
</head>
<body>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<?php
			$box = get_post_meta($post->ID, 'box_parameters', true);
			if(!$box):
				$box['x'] = 0;
				$box['y'] = 0;
				$box['w'] = 200;
			endif;
		?>	
		
	<script type="text/javascript">
		postData.box.x = <?php echo $box['x']; ?>;
		postData.box.y = <?php echo $box['y']; ?>;
		postData.box.w = <?php echo $box['w']; ?>;
	</script>
	
	<div id="main-container">
		
		<div class="profile-container resizable-box draggable-box" style="position:absolute;width:<?php echo $box['w']?>px;background-color:rgba(0,0,0, 0.3);left:<?php echo $box['x']?>px;top:<?php echo $box['y']?>px";>
			
		
			<script type="text/javascript">
				postData.id = <?php echo the_ID(); ?>
			</script>
		
			<div id="post-title"><?php echo the_title(); ?></div>
			<div id="post-content"><?php echo the_content(); ?></div>
			
			
		</div>
		
	</div>
	
	<!-- Floating admin interface-->
	<div id="editor" style="position:absolute;right:0;width:500px;height:400px;">
	<br />
		<form>
		<div id="editor-tabs">
			 <ul>
					<li><a href="#tabs-1">Info</a></li>
					<li><a href="#tabs-2">BG</a></li>
					<li><a href="#tabs-3">Styles</a></li>
					<li><a href="#tabs-4">Services</a></li>
			 </ul>
			 <div id="tabs-1">
					Name<br /><input type="text" id="name" value="<?php echo the_title(); ?>" /><br />
					Bio<br /><textarea id="bio"><?php echo get_the_content(); ?></textarea><br />
					Tags<br /><input type="text" id="tags" value="<?php echo the_tags(); ?>" /><br />
			 </div>
			 <div id="tabs-2">
					Background Image<br />
					<input type="file" />
			 </div>
			 <div id="tabs-3">
					Heading Font<br />
					<input type="text" />
			 </div>
			 <div id="tabs-4">
					<?php
						$services = get_post_meta( $post->ID, 'services', true );
						if( $services != '' ):
							foreach( $services as $service):
						
							endforeach;
						endif;
					?>
			 </div>
		</div>
		
		<button class="save" id="save-form">Save</button>
		</form>
	</div>
	<!--End of admin interface-->
	
	<span class="ajax-spinner" style="display:none;float:left;">Saving...</span>
	
	<?php endwhile;  ?>
		<?php endif; ?>
	<?php wp_footer(); ?>
</body>

</html>