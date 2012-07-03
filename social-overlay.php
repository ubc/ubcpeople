<?php 
	global $post_meta;
	$social_array = array(); 
	foreach($post_meta['profile_cct']['social'] as $service):
		$social_array[$service['option']] = $service['username'];
	endforeach;
	
	//print_r($social_array_keys);
?>
	<div id="social-overlay" style="display:none;">
		<div id="social-inline-content">
		
		
			<div id="social-tabs">
				<ul>
					<?php
						foreach($post_meta['profile_cct']['social'] as $service):
							echo '<li><a href="#tab-'.str_replace(' ','-', $service['option']).'">'.$service['option'].'</a></li>
							';
						endforeach;
					?>
				</ul>
				
				<?php
				foreach($social_array as $service=>$username): ?>
					<div id="tab-<?php echo str_replace(' ','-', $service); ?>">
						<?php ubcpeople_display_service($service, $username); ?>	
					</div>
				<? endforeach;
				?>
			</div>	
			
		</div>
	</div>