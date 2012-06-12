<!-- Floating admin interface-->
	<div id="editor" style="position:absolute;right:0;width:500px;height:400px;display:none;">	
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
					<div id="file-uploader-demo1">		
						<noscript>			
							<p>Please enable JavaScript to use file uploader.</p>
							<!-- or put a simple form for upload here -->
						</noscript>         
					</div>
			 </div>
			 
			 <div id="tab-styles">
					Heading Font<br />
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
					<a>Add New</a>
			 </div>
			 
		</div>
		
		<button class="save" id="save-form">Save</button>
		</form>
		
	</div>
	
	<script>        
        function createUploader(){  
			
            var uploader = new qq.FileUploader({
                element: document.getElementById('file-uploader-demo1'),
                action: '/wp-admin/admin-ajax.php?action=people_upload_photo',
				onComplete: function(id, filename, responseJSON){
					
					jQuery('html').css("background-image", "url(/wp-content/uploads/people/"+responseJSON.filename+")");
					postData.bg.url = "/wp-content/uploads/people/"+responseJSON.filename;
				},
                debug: true
            });           
        }
        
        // in your app create uploader as soon as the DOM is ready
        // don't wait for the window to load  
        window.onload = createUploader;    
		//jQuery.ready(function(){
		//	createUploader();
		//});
		
    </script>    
	
	<!--End of admin interface-->