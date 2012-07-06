<?php

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