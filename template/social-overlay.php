<div id="social-overlay" style="display:none;">
	<div id="social-inline-content">
	
		<div id="social-tabs">
		
			
			<!--Sidebar tabs Area-->
			<div id="social-left-column">
				
				<div style="color:#fff;margin:10px;">@ubc</div>
				
				<ul id="social-overlay-tabs">
					<?php
						//Create the tab list for internal services
						$count = 0;
						foreach( $internal_services as $service=>$service_username ):
							$service_details = ubcpeople_get_service_parameters( $service );
							echo '<li><a href="#tab-'.str_replace(array(' ','.'),'-', $service).'">' . ubcpeople_get_service_name_from_slug($service) . '</a></li>';
							$count++;
						endforeach;
						
						//jquery ui tabs relies with these list items, so we'll output these ones here even though they're not going to be displayed this way
						foreach( $external_services as $service=>$service_username ):
							echo '<li style="display:none;"><a href="#tab-'.str_replace(array(' ','.'),'-', $service).'"></a></li>';
						endforeach;
					?>
				</ul>
				
				<ul style="padding-left:8px;list-style-type:none;">
					<?php
						//Create the external icons list
						foreach( $external_services as $service=>$service_username ):
							$service_details = ubcpeople_get_service_parameters( $service );
							echo '<li style="display:inline;">' . ubcpeople_display_service_icon($service, $count) . '</li>
							';
							$count++;;
						endforeach;
					?>
				</ul>
				
			</div>
			
			<!--Social Content Area-->
			
			<div id="social-main-column">
				<?php
				//Create the tab content
				foreach( array_merge( $internal_services, $external_services ) as $service=>$service_username): ?>
				
					<div id="tab-<?php echo str_replace(array(' ','.'),'-', $service); ?>">
						<?php ubcpeople_display_service($service, $usermeta['id'], $service_username); ?>	
					</div>
				<?php endforeach; 
				?>
			</div>
		
		</div>	
		
	</div>
</div>