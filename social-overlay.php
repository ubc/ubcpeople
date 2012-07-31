<div id="social-overlay" style="display:none;">
	<div id="social-inline-content">
	
		<div id="social-tabs">
			<ul>
				<?php
					//Create the tab list
					foreach($usermeta['social'] as $service=>$service_username):
						echo '<li><a href="#tab-'.str_replace(array(' ','.'),'-', $service).'">'.$service.'</a></li>
						';
					endforeach;
				?>
			</ul>
			
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