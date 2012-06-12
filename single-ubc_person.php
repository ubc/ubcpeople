<?php get_header(); ?>

<link href="<?php echo get_stylesheet_directory_uri(); ?>/fileuploader.css" rel="stylesheet" type="text/css">	
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/fileuploader.js" type="text/javascript"></script>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<?php
			$box = get_post_meta($post->ID, 'box_parameters', true);
			$background = get_post_meta($post->ID, 'background_parameters', true);
			if(!$box):
				$box['x'] = 0;
				$box['y'] = 0;
				$box['w'] = 350;
			endif;

		?>	
		
	<script type="text/javascript">
		postData.box.x = <?php echo $box['x']; ?>;
		postData.box.y = <?php echo $box['y']; ?>;
		postData.box.w = <?php echo $box['w']; ?>;
		postData.bg.url = '<?php echo $background['url']; ?>';
		jQuery('html').css("background-image", "url("+postData.bg.url+")");
	</script>
	
	<div id="main-container">
		<div class="profile-container resizable-box draggable-box" style="position:absolute;width:<?php echo $box['w']?>px;background-color:rgba(0,0,0, 0.3);left:<?php echo $box['x']?>px;top:<?php echo $box['y']?>px";>
			<script type="text/javascript">
				postData.id = <?php echo the_ID(); ?>
			</script>
			<div id="post-title"><h1><?php echo the_title(); ?></h1></div>
			<div id="post-content"><p><?php echo nl2br(get_the_content()); ?></p></div>			
			<?php 
				$services = get_post_meta( $post->ID, 'services', true );
				if( $services != '' ):
					foreach( $services as $service):
						
					endforeach;
				endif;
			?>

		</div>
	</div>
	
	
	<?php get_template_part('admin-overlay'); ?>
	<span class="ajax-spinner" style="display:none;float:left;">Saving...</span>
<?php endwhile;  ?><?php endif; ?>

<?php get_footer(); ?>
