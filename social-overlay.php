<?php 
	global $post_meta;
	$social_array = array(); //Create array of key->value pairs from the social data
	foreach($post_meta['profile_cct']['social'] as $service):
		$social_array[$service['option']] = $service['username'];
	endforeach;
?>
	<div id="social-overlay" style="display:none;">
		<div id="social-inline-content">
		
			<div id="social-tabs">
				<ul>
					<?php
						//Create the tab list
						foreach($post_meta['profile_cct']['social'] as $service):
							echo '<li><a href="#tab-'.str_replace(array(' ','.'),'-', $service['option']).'">'.$service['option'].'</a></li>
							';
						endforeach;
					?>
				</ul>
				
				<?php
				//Create the tab content
				foreach($social_array as $service=>$username): ?>
					<div id="tab-<?php echo str_replace(array(' ','.'),'-', $service); ?>">
						<?php ubcpeople_display_service($service, $username); ?>	
					</div>
				<? endforeach;
				?>
			</div>	
			
		</div>
	</div>