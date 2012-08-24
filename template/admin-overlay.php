<?php 
	global $current_user;
	do_action('ubcpeople_add_service_form');
	if( ubcpeople_current_user_can_edit( $_REQUEST['person'] ) ): //if user has permission to edit this page
?>
<!-- Floating admin interface-->
	<div id="editor">	
		<form>
		<div id="editor-tabs">
			 <ul>
					<li><a href="#tab-info">Information</a></li>
					
					<li><a href="#tab-images">Background</a></li>
					<li><a href="#tab-styles">Styles</a></li>
					<li><a href="#tab-services">External Services</a></li>
			 </ul>
	
			 
			 <div id="tab-info">
			 	<div class="admin-tab-contents">
					<div class="form-heading">Name</div>
						<table class="form-table">
							<tr>
								<td>
									<label for="name-first">First</label>
								</td><td>
									<label for="name-last">Last</label>
								</td>
							</tr>
							<tr>
								<td>
									<input type="text" id="name-first" value="<?php echo $usermeta['first_name']; ?>" size="16" />
								</td><td>
									<input type="text" id="name-last" value="<?php echo $usermeta['last_name']; ?>" size="16" />
								</td>
							</tr>
						</table>
					
					
					<div class="form-label form-heading"><label for="input-tagline">Tagline</label></div>
					<input type="text" id="input-tagline" value="<?php echo $usermeta['people']['tagline'] ?>" />
					
					<div class="form-label form-heading"><label for="bio">Bio</label></div>
					<textarea id="bio"><?php echo $usermeta['description']; ?></textarea>
					<!--
					<div class="form-label form-heading"><label for="tags">Tags</label></div>
					<input type="text" id="tags" value="<?php echo the_tags(); ?>" />-->
				</div>
			 </div>
			 
			 <div id="tab-images">
			 	<div class="admin-tab-contents">
					<div class="form-heading">Background Image</div>
					<div id="file-uploader-demo1">
						<noscript>			
							<p>Please enable JavaScript to use file uploader.</p>
						</noscript>         
					</div>
					
					<?php 
					if($usermeta['people']['images']):
						echo '<div class="form-heading">Your uploads</div>';
						foreach($usermeta['people']['images'] as $image):
							echo '<a href="" class="change-bg-link">'.$image.'</a><br />';
						endforeach; 
						echo '<br />';
					endif;
					?>	
					
					<div class="form-heading">Background Positioning</div>
					<input type="radio" name="background-pos" value="scale" id="background-scale" /><label for="background-scale">Scale</label><br />
					<input type="radio" name="background-pos" value="center" id="background-center" /><label for="background-center">Center</label>
				</div>
			 </div>
			 
			 <div id="tab-styles">
			 	<div class="admin-tab-contents">
					<div class="form-label form-heading"><label for="">Heading Text</label></div>
						
						<div class="color-selector" id="heading-color">
							<div class="color-preview"></div>
						</div>
						<select id="heading-font">
							<option value="geneva">Sans serif</option>
							<option value="serif" <?php selected('serif', $usermeta['people']['styles']['heading_font']);?>>Serif</option>
						</select>
						
						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery('#heading-color').ColorPickerSetColor(postData.people.styles.heading_color);
							jQuery('#heading-color .color-preview').css('background-color', postData.people.styles.heading_color);
						});
						
						jQuery(document).ready(function(){
							jQuery('#text-color').ColorPickerSetColor(postData.people.styles.text_color);
							jQuery('#text-color .color-preview').css('background-color', postData.people.styles.text_color);
						});
						
						jQuery(document).ready(function(){
							jQuery('#tagline-color').ColorPickerSetColor(postData.people.styles.tagline_color);
							jQuery('#tagline-color .color-preview').css('background-color', postData.people.styles.tagline_color);
						});
						
						jQuery(document).ready(function(){
							jQuery('#background-color').ColorPickerSetColor(postData.people.styles.box_bg);
							jQuery('#background-color .color-preview').css('background-color', postData.people.styles.box_bg);
						});
						</script>
					
					
					<div class="form-label form-heading"><label for="">Tagline Text</label></div>
					
					<div class="color-selector" id="tagline-color">
						<div class="color-preview"></div>
					</div>
					
					<select id="tagline-font">
						<option value="geneva">Sans serif</option>
						<option value="serif" <?php selected('serif', $usermeta['people']['styles']['tagline_font']);?>>Serif</option>
					</select>
					
						
					<div class="form-label form-heading"><label for="">Regular Text</label></div>
					
					<div class="color-selector" id="text-color">
						<div class="color-preview"></div>
					</div>
					
					<select id="text-font">
						<option value="geneva">Sans serif</option>
						<option value="serif" <?php selected('serif', $usermeta['people']['styles']['text_font']);?>>Serif</option>
					</select>
				
					
					<div class="form-label form-heading"><label for="">Profile Box Background</label></div>
					<div class="color-selector" id="background-color">
						<div class="color-preview"></div>
					</div>
					
					<div class="form-label form-heading"><label for="">Profile Box Positioning</label></div>
					<span>Relative to</span><br />
					<input type="radio" name="profile-box-position" value="top-left" id="top-left" />
					<label for="top-left">Top left</label>
					<input type="radio" name="profile-box-position" value="top-right" id="top-right" />
					<label for="top-right">Top Right</label>
					<input type="radio" name="profile-box-position" value="bottom-left" id="bottom-left" />
					<label for="bottom-left">Bottom Left </label>
					<input type="radio" name="profile-box-position" value="bottom-right" id="bottom-right" />
					<label for="bottom-right">Bottom Right</label>
					<br />
					<input type="radio" name="profile-box-position" value="scale" id="scale" />
					<label for="scale">Scale coordinates based on window size</label>
				</div>
			 </div>
			 
			 
			 <div id="tab-services">
			 	<div class="admin-tab-contents">
					<?php
								
						$services = $usermeta['social'];
						$available_services = ubcpeople_get_available_services();
						
						$inactive = array_diff( array_keys($available_services), array_keys($services) );
					?>
					<p>Click and drag active services up/down to reorder them on profile</p>
					<table id="manage-services">
						<thead>
						<tr>
							<th style="width:200px;">Active Services</th>
							<th style="width:150px;">Controls</th>
							<th style="width:32px;">Featured</th>
						</tr>
						</thead>
						<tbody>
						<?php 
						
						foreach($services as $service_slug=>$service_username):
							?>
							<tr id="row-<?php echo $service_slug; ?>">
								<td><?php echo $available_services[$service_slug]; ?></td>
								<td><a class="remove-service" href="<?php echo ubcpeople_get_person_url($usermeta['login'], array('remove-service'=>$service_slug) ); ?>">Remove</a></td>
								<td><input type="checkbox" /></td>
							</tr>	
							<?php 
						endforeach; 
						?>
						</tbody>
					</table>
					
					<table id="available-services">
						<tr>
							<th style="width:200px;">Available Services</th>
						</tr>
						<?php
						foreach($inactive as $service):										
							?>
							<tr>
								<td><?php echo $available_services[$service]; ?></td>
								<td><a class="open-social-settings" href="#add-service-<?php echo $service; ?>">Add</a></td>
								<td></td>
							</tr>	
							<?php 
							
						endforeach;
						?>
					</table>
				</div>
			 </div>
			 
		</div>
		
		<div id="admin-form-controls">
			<button class="save" id="save-form">Save All Changes</button>
			<button class="close" id="close-form">Close</button>
		</div>
		</form>
		
	</div>
	
	<script>        
		jQuery(document).ready(function(){
			people_initUploader(<?php echo $user->ID ?>);  
		}) 
	</script>
	
	<!--End of admin interface-->
<?php
endif; //User has permission to edit this page
?>