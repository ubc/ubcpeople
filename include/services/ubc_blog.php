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
	<?
}



function ubcpeople_ubc_blog_get_data($username){
	//temporary. maybe.
	$opts = array(
		'http'=>array(
			'header'=>'Accept-Encoding: \r\n',
		),
	);
	$context = stream_context_create($opts);
	
	$xml = simplexml_load_string(file_get_contents('http://blogs.ubc.ca/'.$username.'/feed/', false, $context));
	
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
		<div id="add-service-ubc-blog" class="add-service">
			<h2>Add UBC Blog</h2>
			<form class="add-service-form" method="get" action="">
				<p>UBC Blogs Username<br /> 	
					<input type="text" id="service-username" name="service-username" />
					<input type="hidden" name="add-service" value="ubc-blog" />
					<input type="hidden" name="person" value="<?php echo $_REQUEST['person']; ?>" />
					
				</p>
				
				<p><button class="submit-add-social" type="button">Add</button>
					<span class="small">Any changes you have made will be saved.</span>
				</p>
			</form>
			
		</div>
	</div>
	<?
}


function ubcpeople_ubc_blog_init(){
	ubcpeople_ubc_blog_add();
	
}