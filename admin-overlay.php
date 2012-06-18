<?php global $post_meta; ?>
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
					<div class="form-label"><label for="name">Name</label></div>
					<input type="text" id="name" value="<?php echo the_title(); ?>" />
					
					<div class="form-label"><label for="bio">Bio</label></div>
					<textarea id="bio"><?php echo get_the_content(); ?></textarea>
						
					<div class="form-label"><label for="tags">Tags</label></div>
					<input type="text" id="tags" value="<?php echo the_tags(); ?>" />
			 </div>
			 
			 
			 <div id="tab-images">
					Background Image<br />
					
					<?php 
					if($post_meta['images']):
						foreach($post_meta['images'] as $image):
							echo '<a href="" class="change-bg-link">'.$image.'</a><br />';
						endforeach; 
					endif;
					?>
					
					<div id="file-uploader-demo1">
						<noscript>			
							<p>Please enable JavaScript to use file uploader.</p>
							<!-- or put a simple form for upload here -->
						</noscript>         
					</div>
			 </div>
			 
			 
			 <div id="tab-styles">
					<div class="form-label"><label for="">Heading</label></div>
					<select>
					</select>
					<div class="form-label"><label for="">Text</label></div>
					<select>
					</select>
					<div class="form-label"><label for="">Profile Box color</label></div>
					<input type="text" />
			 </div>
			 
			 
			 <div id="tab-services">
					<?php
						$services = get_post_meta( $post->ID, 'services', true );
						if( $services != '' ):
							foreach( $services as $service):
						
							endforeach;
						endif;
					?>
					<a>Add More</a>
			 </div>
			 
		</div>
		
		<button class="save" id="save-form">Save</button>
		</form>
		
	</div>
	
	<script>        
		window.onload = function(){
			people_initUploader(<?php echo the_ID(); ?>);  
		}  
	</script>
	
	<!--End of admin interface-->