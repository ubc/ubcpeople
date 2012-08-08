<?php 
	global $current_user;
	do_action('ubcpeople_admin');
	if( ubcpeople_current_user_can_edit( $_REQUEST['person'] ) ): //if user has permission to edit this page
?>
<!-- Floating admin interface-->
	<div id="editor">	
		<form>
		<div id="editor-tabs">
			 <ul>
					<li><a href="#tab-info">Information</a></li>
					
					<li><a href="#tab-images">Images</a></li>
					<li><a href="#tab-styles">Styles</a></li>
					<li><a href="#tab-services">External Services</a></li>
			 </ul>
	
			 
			 <div id="tab-info">
			 	<div class="admin-tab-contents">
					<div class="form-label"></div>
						<table class="form-table">
							<tr>
								<td>
									<label for="name-first">First Name</label>
								</td><td>
									<label for="name-last">Last Name</label>
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
					
					
					
					
					<div class="form-label"><label for="bio">Bio</label></div>
					<textarea id="bio"><?php echo $usermeta['description']; ?></textarea>
						
					<div class="form-label"><label for="tags">Tags</label></div>
					<input type="text" id="tags" value="<?php echo the_tags(); ?>" />
				</div>
			 </div>
			 
			 <div id="tab-images">
			 	<div class="admin-tab-contents">
					Background Image<br />
					
					<?php 
					if($usermeta['people']['images']):
						foreach($usermeta['people']['images'] as $image):
							echo '<a href="" class="change-bg-link">'.$image.'</a><br />';
						endforeach; 
					endif;
					?>
					
					<div id="file-uploader-demo1">
						<noscript>			
							<p>Please enable JavaScript to use file uploader.</p>
						</noscript>         
					</div>
				</div>
			 </div>
			 
			 <div id="tab-styles">
			 	<div class="admin-tab-contents">
					<div class="form-label"><label for="">Heading</label></div>
						
						<div class="color-selector" id="heading-color">
							<div class="color-preview"></div>
						</div>
						<select id="heading-font">
							<option value="sans-serif">Sans serif</option>
							<option value="serif" <?php selected('serif', $usermeta['people']['styles']['heading_font']);?>>Serif</option>
						</select>
						
						<script type="text/javascript">
						/*jQuery(document).ready(function(){
							jQuery('#heading-color').ColorPickerSetColor(postData.people.styles.heading_color);
						});*/
						</script>
					
					
					<div class="form-label"><label for="">Regular Text</label></div>
					
						<div class="color-selector" id="text-color">
							<div class="color-preview"></div>
						</div>
						
						<select id="text-font">
							<option value="sans-serif">Sans serif</option>
							<option value="serif" <?php selected('serif', $usermeta['people']['styles']['text_font']);?>>Serif</option>
						</select>
					
					
					<div class="form-label"><label for="">Profile Box Background</label></div>
					<input type="text" />
				</div>
			 </div>
			 
			 
			 <div id="tab-services">
			 	<div class="admin-tab-contents">
					<?php
								
						$services = $usermeta['social'];
						$available_services = ubcpeople_get_available_services();
					?>
					
					<table id="manage-services">
						<thead>
						<tr>
							<th style="width:200px;">Site</th>
							<th style="width:150px;">Controls</th>
							<th style="width:32px;">Featured</th>
						</tr>
						</thead>
						<tbody>
						<?php 
						
						foreach($available_services as $slug=>$name):
							if(isset($services[$slug])):
								?>
								<tr>
									<td><?php echo $name; ?></td>
									<td><a class="remove-service" href="<?php echo ubcpeople_get_person_url($usermeta['login'], array('remove-service'=>$slug) ); ?>">Remove</a></td>
									<td><input type="checkbox" /></td>
								</tr>	
								<?php 
							else: 
								?>
								<tr>
									<td><?php echo $name; ?></td>
									<td><a class="open-social-settings" href="#add-service-<?php echo $slug; ?>">Add</a></td>
									<td></td>
								</tr>	
								<?php 
							endif;
						endforeach; 
						?>
						</tbody>
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
<?
endif; //User has permission to edit this page
?>