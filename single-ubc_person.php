No longer in use!  ithink! single-ubc_person.php 
Use single-profile_cct instead?

<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<?php	
	//Retrieve page layout meta with default values added where necessary
	$post_meta = get_post_meta($post->ID, 'people', true);
	$profile_meta = get_post_meta($post->ID, 'profile_cct', true);
	$post_meta = wp_parse_args( 
		$post_meta,
		array(
			'box'=>array('x'=>'32', 'y'=>'32', 'w'=>'300'),
			'bg'=>array('url'=>''),
			'images'=>array(),
		)
	);
	?>	

	<script type="text/javascript">
		postData = {};
		postData.meta = <?php echo json_encode($post_meta); ?>;
		postData.id = <?php echo the_ID(); ?>;
		jQuery('html').css("background-image", "url(/wp-content/uploads/people/"+postData.id+"/"+postData.meta.bg.url+")"); //to do: use css
	</script>
	
	<div id="main-container">
		<div class="profile-container resizable-box draggable-box" style="position:absolute;width:<?php echo $post_meta['box']['w']?>px;background-color:rgba(0,0,0, 0.3);left:<?php echo $post_meta['box']['x']?>px;top:<?php echo $post_meta['box']['y']?>px";>
			<div id="post-title"><h1><?php echo the_title(); ?></h1></div>
			<div id="post-content"><p><?php echo nl2br(get_the_content()); ?></p></div>	sdfsdfdf		
			<?php 
				print_r($profile_meta);
				if( $profile_meta['services'] != '' ):
					foreach( $profile_meta['services'] as $service):
						ubcpeople_display_service($service);
					endforeach;
				endif;
			?>
			<a id="open-social-overlay" href="#social-inline-content"><img width="32" height="32" src="<?php echo get_stylesheet_directory_uri(); ?>/social-icons/png/twitter.png" alt="Facebook" /></a>
			<img width="32" height="32" src="<?php echo get_stylesheet_directory_uri(); ?>/social-icons/png/facebook.png" alt="Facebook" />
		</div>
	</div>
	
	
	<div id="social-overlay" style="display:none;">
		<div id="social-inline-content">
		
		
			<div id="social-tabs">
				<ul>
					<li><a href="#tab-twitter">Twitter</a></li>
					<li><a href="#tab-blah">Blah</a></li>
				</ul>
				<div id="tab-twitter">
					<?php people_twitter(); ?>
				</div>
				<div id="tab-blah">
					
				</div>
			</div>	
			
		</div>
	</div>
	
	<?php get_template_part('admin-overlay'); ?>
	
	<span class="ajax-spinner" style="display:none;float:left;">Saving...</span>
	
<?php endwhile;  ?><?php endif; ?>
<?php get_footer(); ?>
