jQuery(function() {
	
	
		people_savePost = function(){
			//profileData.title = jQuery("input#name").val();
			postData.profile_cct.bio.textarea = jQuery("textarea#bio").val();
			postData.profile_cct.name.first = jQuery("#name-first").val();
			postData.profile_cct.name.middle = jQuery("#name-middle").val();
			postData.profile_cct.name.last = jQuery("#name-last").val();
			console.log(postData.people.styles);
			jQuery.post(ajaxURL, 'postData='+JSON.stringify(postData.people)+'&profile_cct='+JSON.stringify(postData.profile_cct)+"&id="+postData.id);
			jQuery("#editor").toggle();
			return false;
		}
	
	
		people_initUploader = function(post_id){
			var uploader = new qq.FileUploader({
				element: document.getElementById('file-uploader-demo1'),
				action: '/wp-admin/admin-ajax.php?action=people_upload_photo&id='+post_id,
				onComplete: function(id, filename, responseJSON){	

					//console.log(postData);
					jQuery('html').css("background-image", "url(/wp-content/uploads/people/"+post_id+"/"+responseJSON.filename+")");
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
	
		
		
	//Initialize jQuery UI elements
		jQuery('#heading-color').ColorPicker({
			color: '#000000',
			onChange: function(hsb, hex, rgb){
				postData.people.styles.heading_color = '#' + hex;
				jQuery('#heading-color .color-preview').css('background-color', '#' + hex);
				jQuery('#post-title').css('color', '#' + hex);
			}
		});
		
		jQuery('#text-color').ColorPicker({
			color: '#000000',
			onChange: function(hsb, hex, rgb){
				postData.people.styles.text_color = '#' + hex;
				jQuery('#text-color .color-preview').css('background-color', '#' + hex);
				jQuery('#post-content').css('color', '#' + hex);
			}
		});
	
		jQuery('#heading-font').change(function(event){
			postData.people.styles.heading_font = jQuery('#heading-font').val();
			jQuery('#post-title').css('font-family', jQuery('#heading-font').val());
		})
		
		jQuery('#text-font').change(function(event){
			postData.people.styles.heading_font = jQuery('#text-font').val();
			jQuery('#post-content').css('font-family', jQuery('#text-font').val());
		})
	
	
	
		jQuery( "#editor-tabs" ).tabs();
		
		jQuery( "#editor" ).draggable();
		
		jQuery( "#social-tabs" ).tabs();
		
		jQuery('.open-social-overlay').colorbox({
			inline:true,
			width:'800px',
			height:'600px',
		});
		
		jQuery('#icon-twitter').click(function(){
			jQuery("#social-tabs").tabs('select', 0);
		});
		jQuery('#icon-ubc-blogs').click(function(){
			jQuery("#social-tabs").tabs('select', 1);
		});
		jQuery('#icon-facebook').click(function(){
			jQuery("#social-tabs").tabs('select', 2);
		});
		
		jQuery( ".draggable-box" ).draggable({
			stop:function(event, ui){
				postData.people.box.x = ui.position.left;
				postData.people.box.y = ui.position.top;
				//console.log(event);

			},
			//containment: "parent",
		});
		
		
		jQuery( ".resizable-box" ).resizable({
			handles: 'e, w',
			stop:function(event, ui){
				postData.people.box.w = event.target.clientWidth;
			},
			resize:function(event, ui){
				//jquery UI will by default lock the height even if we're only resizing width. force the height to autoadjust:
				jQuery(this).css('height','auto');
			}
		});	
		
	
	//Events
		jQuery(document)
			.ajaxStart(function(){
					jQuery(".ajax-spinner").show();
			})
			.ajaxStop(function(){
					jQuery(".ajax-spinner").hide();
			});
		
		
		jQuery("#wp-admin-bar-edit").click(function(){
			jQuery("#editor").fadeToggle();
			return false;
		});
		
		
		jQuery( ".save" ).click(people_savePost);
		
		jQuery( ".change-bg-link" ).click(function(){
			jQuery('html').css("background-image", "url(/wp-content/uploads/people/"+postData.id+"/"+jQuery(this).text()+")");
			postData.people.bg.url = jQuery(this).text();
			return false;
		});
		
		jQuery( "#bio" ).keydown(function(){
			setTimeout(function(){jQuery("#post-content p").html(convertLineBreaks(jQuery( "#bio" ).val()), 10)});
		});
		jQuery( "#name-first, #name-middle, #name-last" ).keydown(function(){
			setTimeout(function(){jQuery("#post-title h1").html(jQuery( "#name-first" ).val() +" "+ jQuery( "#name-middle" ).val() +" "+ jQuery( "#name-last" ).val() , 10)});
		});
		
	
});