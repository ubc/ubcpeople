<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<?php	
	//Retrieve post meta information
	$post_meta = array(
		'people'=> get_post_meta($post->ID, 'people', true),
		'profile_cct'=> get_post_meta($post->ID, 'profile_cct', true),
		);
		if($post_meta['people']=='')unset($post_meta['people']);
		if($post_meta['profile_cct']=='')unset($post_meta['profile_cct']);
		
	//merge with default values
	$post_meta = wp_parse_args( 
		$post_meta,
		array(
			'people'=>array(
				'box'=>array(
					'x'=>'32', 
					'y'=>'32', 
					'w'=>'300',
				),
				'styles'=>array(
					'heading_color'=>'#ff0000', 
					'heading_font'=>'serif',
					'text_color'=>'#00ff00', 
					'text_font'=>'sans-serif',
					'box_bg'=>'#000',
					'box_opacity'=>'0.5',
				),
				'bg'=>array('url'=>''),
				'images'=>array(),
			),
			'profile_cct'=>array(
				
			),
		)
	);
	
	//TEMP
	/*
	$post_meta['people']['styles'] = array(
					'heading_color'=>'#ff0000', 
					'heading_font'=>'serif',
					'text_color'=>'#00ff00', 
					'text_font'=>'sans-serif',
					'box_bg'=>'#000',
					'box_opacity'=>'0.5',
				);*/
	?>	

	<script type="text/javascript">
		postData = <?php echo json_encode($post_meta); ?>;
		postData.id = <?php echo the_ID(); ?>;
		jQuery('html').css("background-image", "url(/wp-content/uploads/people/"+postData.id+"/"+postData.people.bg.url+")"); //to do: use css
	</script>
	
	<div id="main-container">
		<div class="profile-container resizable-box draggable-box" style="position:absolute;width:<?php echo $post_meta['people']['box']['w']?>px;background-color:rgba(0,0,0, 0.3);left:<?php echo $post_meta['people']['box']['x']?>px;top:<?php echo $post_meta['people']['box']['y']?>px";>
			<div id="post-title" style="font-family:<?php echo $post_meta['people']['styles']['heading_font']?>;color: <?php echo $post_meta['people']['styles']['heading_color']; ?>"><h1><?php echo $post_meta['profile_cct']['name']['first'].' '.$post_meta['profile_cct']['name']['middle'].' '.$post_meta['profile_cct']['name']['last']; ?></h1></div>
			<div id="post-content" style="font-family:<?php echo $post_meta['people']['styles']['text_font']?>;color: <?php echo $post_meta['people']['styles']['text_color']; ?>"><p><?php echo nl2br($post_meta['profile_cct']['bio']['textarea']); ?></p></div>			
			<?php 

				if( $post_meta['profile_cct']['social'] != '' ):
					foreach( $post_meta['profile_cct']['social'] as $service):
						ubcpeople_display_service_icon($service);
					endforeach;
				endif;
			?>
		</div>
	</div>
	
	<?php get_template_part('social-overlay'); ?>

	
	<?php get_template_part('admin-overlay'); ?>
	
	<span class="ajax-spinner" style="display:none;float:left;">Saving...</span>
	
<?php endwhile;  ?><?php endif; ?>
<?php get_footer(); ?>
