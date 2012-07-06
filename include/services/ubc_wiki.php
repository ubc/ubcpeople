<?php
/**
 *	
 */
function ubcpeople_ubc_wiki($username){ ?>

	<div class="social-header">
		<h2>UBC Wiki/User: <?php echo $username; ?></h2>
	</div>
	<div class="social-body">
		<?php echo do_shortcode("[wiki-embed url='http://wiki.ubc.ca/User:" . $username . "' no-edit no-contents ]"); ?>
	
		<h3>Recent User Contributions</h3>
		
		<ul>
		<?php
			foreach(ubcpeople_ubc_wiki_contributions($username) as $entry): ?>
				<li>
					<a href="<?php echo $entry->id;?>"><?php echo $entry->title;?> </a>
				</li>
			<?php endforeach; 
		?>
		</ul>
		
		<p>
			<a href="http://wiki.ubc.ca/Special:Contributions/<?php echo $username; ?>">See <?php echo $username; ?>'s wiki contributions</a>
		</p>
	</div>
<?php }


function ubcpeople_ubc_wiki_contributions($username){
	$xml = simplexml_load_string(file_get_contents('http://wiki.ubc.ca/api.php?action=feedcontributions&user=' . $username . '&feedformat=atom', false));
	$results = array();
	for($i=0;$i<10;$i++):
		if(!$xml->entry[$i])break;
		$results[] = $xml->entry[$i];
	endfor;
	return $results;
}


function ubcpeople_ubc_wiki_get_icon(){
	return array(
		'url'=>'wiki.png',
		'id'=>'icon-ubc-wiki',
		'alt'=>'UBC Wiki',
	);
}
