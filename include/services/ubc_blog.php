<?php
add_action('ubcpeople_admin', 'ubcpeople_ubc_blog_init');

function ubcpeople_ubc_blog($username){
	$xml = ubcpeople_ubc_blog_get_data($username);
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
 *	Output the HTML for the add Facebook window
 */
function ubcpeople_ubc_blog_add(){
	
	?>
	<div style="display:none;">
		<div id="add-service-ubc-blog" class="add-service">
			<h2>Add UBC Blog</h2>
			
			<p>UBC Blogs Username<br /> <input type="text" id="ubc-blog-username" name="ubc-blog-username" /></p>
			
			<p><a class="submit-add-social" href="https://www.facebook.com/dialog/oauth/?client_id=391752004205525&redirect_uri=<?php echo $current_url ?>&state=todocsrf&scope=user_status">Add</a>
			<span class="small">Any changes you have made will be saved.</span>
			</p>
		</div>
	</div>
	<?
}


function ubcpeople_ubc_blog_init(){
	ubcpeople_ubc_blog_add();
	
}