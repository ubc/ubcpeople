<div id="social-overlay" style="display:none;">
	<div id="social-inline-content">
	
		<div id="social-tabs">
		
			<!--tab list -->
			<ul style="display:none;">
				<?php
					//Create the tab list
					foreach($usermeta['social'] as $service=>$service_username):
						echo '<li><a href="#tab-'.str_replace(array(' ','.'),'-', $service).'">'.ubcpeople_get_service_name_from_slug($service).'</a></li>
						';
					endforeach;
				?>
			</ul>
			
			<div id="social-left-column">
			
			</div>
			
			<div id="social-main-column">
				<?php
				//Create the tab content
				foreach($usermeta['social'] as $service=>$service_username): ?>
					<div id="tab-<?php echo str_replace(array(' ','.'),'-', $service); ?>">
						<?php ubcpeople_display_service($service, $usermeta['id'], $service_username); ?>	
					</div>
				<? endforeach; 
				?>
			</div>
		
		</div>	
		
	</div>
</div>