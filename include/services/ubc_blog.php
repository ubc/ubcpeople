<?php
add_action('ubcpeople_admin', 'ubcpeople_ubc_blog_init');

/**
 *	Display the content of the UBC blog social overlay. To be called from main plugin file
 *	@param int $person_id
 *	@param string $service_username
 */
function ubcpeople_ubc_blog($person_id, $service_username){ 
	$xml = ubcpeople_ubc_blog_get_data($service_username);
	?>
	<div class="social-header">
		<h2><a href="<?php echo $xml->channel->link ?>">UBC Blogs/<?php echo $xml->channel->title ?></a></h2>
	</div>
	
	<div class="social-body">
		<?php foreach($xml->channel->item as $item): ?>
			<h3>
				<a href="<?php echo $item->link ?>"><?php echo $item->title ?></a>
			</h3>
			<p><?php echo $item->description ?></p>
		<?php endforeach; ?>
	</div>
	<?php
}



function ubcpeople_ubc_blog_get_data($username){
	if(!($xml_string = get_transient('blogs_'.$username) ) ):
		//temporary. maybe.
		$opts = array(
			'http'=>array(
				'header'=>'Accept-Encoding: \r\n',
			),
		);
		$context = stream_context_create($opts);
		$xml_string = file_get_contents('http://blogs.ubc.ca/'.$username.'/feed/', false, $context);
		set_transient('blogs_'.$username, $xml_string, 60*60);
	endif;	
	$xml = simplexml_load_string( $xml_string );
	return $xml;
}



function ubcpeople_ubc_blog_get_icon(){
	return array(
		'url'=>'wordpress.png',
		'id'=>'icon-ubc-blogs',
		'alt'=>'UBC Blogs',
	);
}


/**
 *	Output the HTML for the add Blog window
 */
function ubcpeople_ubc_blog_add(){
	
	?>
	<div style="display:none;">
		<div id="add-service-ubc_blog" class="add-service">
			<h2>Add UBC Blog</h2>
			<form class="add-service-form" method="get" action="">
				<p>UBC Blog Name<br /> 	
					<div class="url">blogs.ubc.ca/</div><input type="text" id="service-username" name="service-username" />
					<input type="hidden" name="add-service" value="ubc_blog" />
					<input type="hidden" name="person" value="<?php echo ubcpeople_get_current_person(); ?>" />
					
				</p>
				
				<p><button class="submit-add-social" type="button">Add</button>
					<span class="small">Any changes you have made will be saved.</span>
				</p>
			</form>
			
		</div>
	</div>
	<?php
}


function ubcpeople_ubc_blog_init(){
	ubcpeople_ubc_blog_add();
	
}