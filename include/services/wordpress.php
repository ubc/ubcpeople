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
	echo '<h2><a href="' . $xml->channel->link . '">WordPress.com/' . $xml->channel->title . '</a></h2>';
	//print_r($xml);
	foreach($xml->channel->item as $item):
		echo '<h3><a href="'.$item->link.'">'.$item->title.'</a></h3>';

		echo '<p>'.$item->description.'</p>';

	endforeach;
	
}


function ubcpeople_get_icon_wordpress_com(){
	return array(
		'url'=>'wordpress.png',
		'id'=>'icon-wordpress',
		'alt'=>'WordPress',
	);
}