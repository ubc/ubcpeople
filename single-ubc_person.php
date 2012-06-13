<?php get_header(); ?>

<link href="<?php echo get_stylesheet_directory_uri(); ?>/fileuploader.css" rel="stylesheet" type="text/css">	
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/fileuploader.js" type="text/javascript"></script>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php	
	$post_meta = array();
	$post_meta['box'] = get_post_meta($post->ID, 'box_parameters', true);
	$post_meta['images'] = get_post_meta($post->ID, 'images', true);
	$post_meta['bg'] = get_post_meta($post->ID, 'background_parameters', true);
	
	if(!$post_meta['box']):
		$post_meta['box'] = array('x'=>32, 'y'=>32, 'w'=>300);
	endif;
	if(!$post_meta['bg']):
		//$post_meta['bg'] = new stdClass();
	endif;
	
	?>	

	<script type="text/javascript">
		postData = <?php echo json_encode($post_meta); ?>;
		postData.id = <?php echo the_ID(); ?>;
		jQuery('html').css("background-image", "url(/wp-content/uploads/people/"+postData.id+"/"+postData.bg.url+")");
	</script>
	
	<div id="main-container">
		<div class="profile-container resizable-box draggable-box" style="position:absolute;width:<?php echo $post_meta['box']['w']?>px;background-color:rgba(0,0,0, 0.3);left:<?php echo $post_meta['box']['x']?>px;top:<?php echo $post_meta['box']['y']?>px";>
			<div id="post-title"><h1><?php echo the_title(); ?></h1></div>
			<div id="post-content"><p><?php echo nl2br(get_the_content()); ?></p></div>			
			<?php 
				$services = get_post_meta( $post->ID, 'services', true );
				if( $services != '' ):
					foreach( $services as $service):
						
					endforeach;
				endif;
			?>
			<img width="32" height="32" src="<?php echo get_stylesheet_directory_uri(); ?>/social-icons/png/twitter.png" alt="Facebook" />
			<img width="32" height="32" src="<?php echo get_stylesheet_directory_uri(); ?>/social-icons/png/facebook.png" alt="Facebook" />

		</div>
	</div>
	<?php people_twitter(); ?>
	<?php get_template_part('admin-overlay'); ?>
	<span class="ajax-spinner" style="display:none;float:left;">Saving...</span>
<?php endwhile;  ?><?php endif; ?>

<?php get_footer(); ?>
