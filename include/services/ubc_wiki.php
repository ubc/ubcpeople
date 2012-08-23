<?php
add_action('ubcpeople_admin', 'ubcpeople_ubc_wiki_init');
/**
 *	
 */
function ubcpeople_ubc_wiki($person_id, $service_username){ ?>

	<div class="social-header">
		<h2>UBC Wiki/User: <?php echo $service_username; ?></h2>
	</div>
	<div class="social-body">
		<?php echo do_shortcode("[wiki-embed url='http://wiki.ubc.ca/User:" . $service_username . "' no-edit no-contents ]"); ?>
	
		<h3>Recent User Contributions</h3>
		
		<ul>
		<?php
			foreach(ubcpeople_ubc_wiki_contributions($service_username) as $entry): ?>
				<li>
					<a href="<?php echo $entry->id;?>"><?php echo $entry->title;?> </a>
				</li>
			<?php endforeach; 
		?>
		</ul>
		
		<p>
			<a href="http://wiki.ubc.ca/Special:Contributions/<?php echo $username; ?>">See <?php echo $service_username; ?>'s wiki contributions</a>
		</p>
	</div>
<?php }


function ubcpeople_ubc_wiki_contributions($service_username){
	if(!($xml_string = get_transient('ubcwiki_'.$service_username) ) ):
		$xml_string = file_get_contents('http://wiki.ubc.ca/api.php?action=feedcontributions&user=' . $service_username . '&feedformat=atom', false);
		set_transient('ubcwiki_'.$service_username, $xml_string, 60*60);
	endif;
	$xml = simplexml_load_string($xml_string);
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


/**
 *	Output the HTML for the add ubc wiki window
 */
function ubcpeople_ubc_wiki_add(){
	
	?>
	<div style="display:none;">
		<div id="add-service-ubc_wiki" class="add-service">
			<h2>Add UBC Wiki</h2>
			<form class="add-service-form" method="get" action="">
				<p>UBC Wiki Username<br /> 	
					<input type="text" id="service-username" name="service-username" />
					<input type="hidden" name="add-service" value="ubc_wiki" />
					<input type="hidden" name="person" value="<?php echo $_REQUEST['person']; ?>" />
					
				</p>
				
				<p><button class="submit-add-social" type="button">Add</button>
					<span class="small">Any changes you have made will be saved.</span>
				</p>
			</form>
			
		</div>
	</div>
	<?php
}


function ubcpeople_ubc_wiki_init(){
	ubcpeople_ubc_wiki_add();
	
}