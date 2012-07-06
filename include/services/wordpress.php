<?php
function ubcpeople_wordpress_com($username){

//temporary. maybe.
	$opts = array(
		'http'=>array(
			'header'=>'Accept-Encoding: \r\n',
		),
	);
	$context = stream_context_create($opts);
	$xml = simplexml_load_string(file_get_contents('http://'.$username.'.wordpress.com/feed/', false, $context));
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



function ubcpeople_wordpress_com_get_icon(){
	return array(
		'url'=>'wordpress.png',
		'id'=>'icon-wordpress',
		'alt'=>'WordPress',
	);
}