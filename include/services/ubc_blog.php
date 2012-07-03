<?php
function ubcpeople_ubc_blog($username){

//temporary. maybe.
	$opts = array(
		'http'=>array(
			'header'=>'Accept-Encoding: \r\n',
		),
	);
	$context = stream_context_create($opts);
	
	$xml = simplexml_load_string(file_get_contents('http://blogs.ubc.ca/'.$username.'/feed/', false, $context));
	echo '<h2><a href="' . $xml->channel->link . '">UBC Blogs/' . $xml->channel->title . '</a></h2>';
	//print_r($xml);
	foreach($xml->channel->item as $item):
		echo '<h3><a href="'.$item->link.'">'.$item->title.'</a></h3>';

		echo '<p>'.$item->description.'</p>';

	endforeach;
	
}