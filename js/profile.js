//todo: tidy this
jQuery(document).ready(function() {
		var admin = true;
		
		/*
		 *	Saves the person data to the server via ajax. If provided, call a callback function after
		 */
		people_savePost = function(event, callback){
			callback = callback || function(){};
			//console.log(callback);
			//profileData.title = jQuery("input#name").val();
			postData.people.description = jQuery("textarea#bio").val();
			postData.people.first_name = jQuery("#name-first").val();
			postData.people.last_name = jQuery("#name-last").val();
			postData.people.tagline = jQuery("#input-tagline").val();

			jQuery.post(ajaxURL, 'people='+JSON.stringify(postData.people)+"&social="+JSON.stringify(postData.social)+"&id="+postData.id, callback);
			//jQuery("#editor").toggle();
			event.preventDefault();
		}
	
	
		/*
		 *  Set up the image uploader
		 */
		people_initUploader = function(post_id){
			var uploader = new qq.FileUploader({
				element: document.getElementById('file-uploader-demo1'),
				action: '/wp-admin/admin-ajax.php?action=people_upload_photo&id='+post_id,
				onComplete: function(id, filename, responseJSON){	

					jQuery.backstretch("/wp-content/uploads/people/"+post_id+"/"+responseJSON.filename);
					postData.people.bg.url = responseJSON.filename;
					postData.people.images.push(responseJSON.filename);
					//"/wp-content/uploads/people/"+post_id+"/"+
				},
				debug: false
			});           
        
		}
	
	
		function convertLineBreaks(input_string){
			return input_string.replace(/(\r\n|\r|\n)/g, "<br />");
		}
		
		
		/*
		 * Initialize jQuery UI elements
		 */
			jQuery('#heading-color').ColorPicker({
				onChange: function(hsb, hex, rgb){
					postData.people.styles.heading_color = '#' + hex;
					jQuery('#heading-color .color-preview').css('background-color', '#' + hex);
					jQuery('#post-title').css('color', '#' + hex);
				}
			});
			
			
			jQuery('#tagline-color').ColorPicker({
				onChange: function(hsb, hex, rgb){
					postData.people.styles.tagline_color = '#' + hex;
					jQuery('#tagline-color .color-preview').css('background-color', '#' + hex);
					jQuery('#tagline').css('color', '#' + hex);
				}
			});
			
			
			jQuery('#text-color').ColorPicker({
				onChange: function(hsb, hex, rgb){
					postData.people.styles.text_color = '#' + hex;
					jQuery('#text-color .color-preview').css('background-color', '#' + hex);
					jQuery('#post-content').css('color', '#' + hex);
				}
			});
			
			jQuery('#background-color').ColorPicker({
				onChange: function(hsb, hex, rgb){
					postData.people.styles.box_bg = 'rgba('+rgb.r+', '+rgb.g+', '+rgb.b+', 0.3)';
					jQuery('#background-color .color-preview').css('background-color', '#' + hex);

					jQuery('.profile-container').css('background-color', 'rgba('+rgb.r+', '+rgb.g+', '+rgb.b+', 0.3)');
				}
			})
		
			jQuery('#heading-font').change(function(event){
				postData.people.styles.heading_font = jQuery('#heading-font').val();
				jQuery('#post-title').css('font-family', jQuery('#heading-font').val());
			})
			
			jQuery('#tagline-font').change(function(event){
				postData.people.styles.tagline_font = jQuery('#tagline-font').val();
				jQuery('#tagline').css('font-family', jQuery('#tagline-font').val());
			})
			
			jQuery('#text-font').change(function(event){
				postData.people.styles.heading_font = jQuery('#text-font').val();
				jQuery('#post-content').css('font-family', jQuery('#text-font').val());
			})
		
		
			jQuery( "#editor-tabs" ).tabs();
			
			jQuery( "#editor" ).draggable({containment: "body"});
			
			jQuery( "#social-tabs" ).tabs();
		
		
		/*
		 * Open the overlay when one of the appropriate icons is clicked in the profile
		 */
		jQuery('.open-social-overlay').colorbox({
			inline:true,
			width:'900px',
			height:'640px',
		});
		
		
		/*
		 * Open the 'Add Social' overlay when one of the appropriate links is clicked in the admin overlay
		 */
		jQuery('.open-social-settings').colorbox({
			inline:true,
			width:'450px',
			height:'250px',
		});
		
		
		/*
		 * On adding facebook for example, where you follow an external link. Saves post data and then goes to the link
		 */
		jQuery('a.submit-add-social').click(function(event){
			
			var link = jQuery(this).attr('href');
			people_savePost( event, function(){ window.location = link; } );
		});
		
		jQuery('a.remove-service').click(function(event){
			
			var link = jQuery(this).attr('href');
			people_savePost( event, function(){ window.location = link; } );
		});
		
		
		/*
		 * On submitting simple 'Add Service' form
		 */
		jQuery('.add-service-form button').click(function(event){
			
			var el = jQuery(this).parent().parent();
			people_savePost( event, function(){el.submit();} );	
			
		});
		
		
		jQuery('.open-social-overlay').click(function(){
			var id = jQuery(this).attr('id');
			var number = parseInt(id.substring(5));
			jQuery("#social-tabs").tabs('select', number)
		});
		
		
		jQuery( ".draggable-box" ).draggable({
			stop:function(event, ui){
				postData.people.box.x = ui.position.left;
				postData.people.box.y = ui.position.top;

			},
			disabled: "true",
			containment: "body",
		}).disableSelection();
		
		
		jQuery( ".resizable-box" ).resizable({
			handles: 'e, w',
			disabled: "true",
			stop:function(event, ui){
				postData.people.box.w = event.target.clientWidth;
			},
			resize:function(event, ui){
				//jquery UI will by default lock the height even if we're only resizing width. force the height to autoadjust:
				jQuery(this).css('height','auto');
			}
		});	
		
		jQuery( "#manage-services tbody").sortable({
			update: function(event, ui) {
				var itemOrder = jQuery(this).sortable('toArray');
				var newSocialArray = {};
				//need to reorder the users social array in js here
				for(var i = 0; i < itemOrder.length; i++){
					itemOrder[i] = itemOrder[i].substr(4);
				}
				
				postData.social.order  = itemOrder;
			}
		}).disableSelection();
	
	//Events
		jQuery(document)
			.ajaxStart(function(){
					jQuery(".ajax-spinner").show();
			})
			.ajaxStop(function(){
					jQuery(".ajax-spinner").hide();
			});
		
		
		jQuery("#wp-admin-bar-people-edit-profile").click(function(){
			toggleEditor();
			return false;
		});
		
		toggleEditor = function(){
			admin = !admin;
			jQuery("#editor").fadeToggle();
			jQuery(".profile-container").draggable( "option", "disabled", admin );
			jQuery(".profile-container").resizable( "option", "disabled", admin );
			
			if(!admin){
				jQuery(".profile-container").addClass("profile-container-editable");
			}else{
				jQuery(".profile-container").removeClass("profile-container-editable");
			}
		}
		
		jQuery( ".save" ).click(event, people_savePost);
		
		jQuery( ".close" ).click(event, function(){toggleEditor();event.preventDefault();});
		
		jQuery( ".change-bg-link" ).click(function(){
			//jQuery('html').css("background-image", "url(/wp-content/uploads/people/"+postData.id+"/"+jQuery(this).text()+")");
			jQuery.backstretch("/wp-content/uploads/people/"+postData.id+"/"+jQuery(this).text());
			postData.people.bg.url = jQuery(this).text();
			return false;
		});
		
		jQuery( "#bio" ).keydown(function(){
			setTimeout(function(){jQuery("#post-content p").html(convertLineBreaks(jQuery( "#bio" ).val()), 10)});
		});
		jQuery( "#name-first, #name-last" ).keydown(function(){
			setTimeout(function(){jQuery("#post-title h1").html(jQuery( "#name-first" ).val() +" "+ jQuery( "#name-last" ).val() , 10)});
		});
		jQuery( "#input-tagline" ).keydown(function(){
			setTimeout(function(){jQuery("#tagline").html(jQuery('#input-tagline').val() , 10)});
		});
		
		
	
});