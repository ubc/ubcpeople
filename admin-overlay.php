<?php 
	//print_r($usermeta);
	//print_r($user);
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
					<div class="form-label"><label for="name">Name (first, last)</label></div>
					
					<input type="text" id="name-first" value="<?php echo $usermeta['first_name']; ?>" size="16" />
					<input type="text" id="name-last" value="<?php echo $usermeta['last_name']; ?>" size="16" />
					
					<div class="form-label"><label for="bio">Bio</label></div>
					<textarea id="bio"><?php echo $usermeta['description']; ?></textarea>
						
					<div class="form-label"><label for="tags">Tags</label></div>
					<input type="text" id="tags" value="<?php echo the_tags(); ?>" />
			 </div>
			 
			 
			 <div id="tab-images">
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
			 
			 <div id="tab-styles">
					<div class="form-label"><label for="">Heading</label></div>
						
						<div class="color-selector" id="heading-color">
							<div class="color-preview"></div>
						</div>
						<select id="heading-font">
							<option value="sans-serif">Sans serif</option>
							<option value="serif" <?php selected('serif', $usermeta['people']['styles']['heading_font']);?>>Serif</option>
						</select>
						
						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery('#heading-color').ColorPickerSetColor(postData.people.styles.heading_color);
						});
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
			 
			 
			 <div id="tab-services">
					<?php
						function people_show_option_box($service){
							$social_options = profile_cct_social_options();
							//print_r($social_options);
							echo '<select>';
							foreach($social_options as $option):
								echo '<option ' . selected($option['label'], $service, false) . '>' . $option['label'] . '</option>';
							endforeach;
							echo '</select>';
						}
						
						//people_show_option_box();
												
						$services = $usermeta['social'];
						if( $services != '' ):
							foreach( $services as $service):
								people_show_option_box($service['option']);
								echo '<input type="text" value="'. $service['username'] . '" /><br />';
							endforeach;
						endif;
					?>
					<a>Add More</a>
					<br /><a href="https://www.facebook.com/dialog/oauth/?
    client_id=391752004205525
    &redirect_uri=<?php the_permalink(); ?>
    &state=todocsrf
    &scope=user_status">FB Authenticate</a>
			 </div>
			 
		</div>
		
		<button class="save" id="save-form">Save</button>
		</form>
		
	</div>
	
	<script>        
		jQuery(document).ready(function(){
			people_initUploader(<?php echo $user->ID ?>);  
		}) 
	</script>
	
	<!--End of admin interface-->