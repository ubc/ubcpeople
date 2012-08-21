<style>
	#social-left-column{
		width:180px;float:left;height:600px;background-color:#000;
	}
	
	#social-main-column{
		float:left; 
		width:700px;
	}
	
	#social-tabs li{
		width:100%;;
	}
</style>


<div id="social-overlay" style="display:none;">
	<div id="social-inline-content">
	
		<div id="social-tabs">
		
			
			<!--Sidebar tabs Area
			-->
			<div id="social-left-column">
				
				<div style="color:#fff;margin:10px;">@ubc</div>
				
				<ul id="social-overlay-tabs">
					<?php
						//Create the tab list
						foreach($usermeta['social'] as $service=>$service_username):
							echo '<li><a href="#tab-'.str_replace(array(' ','.'),'-', $service).'">'.ubcpeople_get_service_name_from_slug($service).'</a></li>
							';
						endforeach;
					?>
				</ul>
				
			</div>
			
			<!--Social Content Area-->
			
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