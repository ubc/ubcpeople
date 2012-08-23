<?php
add_action('ubcpeople_admin', 'ubcpeople_wordpress_init');

function ubcpeople_wordpress($person_id, $service_username){
	$xml = ubcpeople_wordpress_get_data($service_username);
	?>
	
	<div class="social-header">
		<h2><a href="<?php echo $xml->channel->link ?>"><?php echo $xml->channel->title ?> on WordPress.com</a></h2>
	</div>
	
	<div class="social-body">
	<?php foreach($xml->channel->item as $item): ?>
		<h3><a href="<?php echo $item->link ?>"><?php echo $item->title ?></a></h3>
		<p><?php echo $item->description ?></p>
	<?php endforeach; ?>
	</div>
<?php }



function ubcpeople_wordpress_get_data($username){
	//temporary. maybe.
	if(!($xml_string = get_transient('wordpress_'.$username) ) ):	
		$opts = array(
			'http'=>array(
				'header'=>'Accept-Encoding: \r\n',
			),
		);
		$context = stream_context_create($opts);
		$xml_string = file_get_contents('http://'.$username.'.wordpress.com/feed/', false, $context);
		set_transient('wordpress_'.$username, $xml_string, 60*60);
	endif;	
		
	$xml = simplexml_load_string( $xml_string );
	return $xml;
	
}



function ubcpeople_wordpress_get_icon(){
	return array(
		'url'=>'wordpress.png',
		'id'=>'icon-wordpress',
		'alt'=>'WordPress',
	);
}


/**
 *	Output the HTML for the add Blog window
 */
function ubcpeople_wordpress_add(){
	
	?>
	<div style="display:none;">
		<div id="add-service-wordpress" class="add-service">
			<h2>Add WordPress.com blog</h2>
			<form class="add-service-form" method="get" action="">
				<p>WordPress.com Site<br /> 	
					<input type="text" id="service-username" name="service-username" />.wordpress.com
					<input type="hidden" name="add-service" value="wordpress" />
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


function ubcpeople_wordpress_init(){
	ubcpeople_wordpress_add();
	
}