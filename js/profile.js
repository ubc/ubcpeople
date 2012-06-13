jQuery(function() {
	
	
		savePost = function(){
			postData.title = jQuery("input#name").val();
			postData.content = jQuery("textarea#bio").val();
	
			jQuery.post(ajaxURL, postData);
			jQuery("#editor").toggle();
			return false;
		}
	
		function convertLineBreaks(input_string){
			return input_string.replace(/(\r\n|\r|\n)/g, "<br />");
		}
	
	//Initialize jQuery UI elements
		jQuery( "#editor-tabs" ).tabs();
		
		jQuery( "#editor" ).draggable();
		
		jQuery( ".draggable-box" ).draggable({
			stop:function(event, ui){
				postData.box.x = event.target.offsetLeft;
				postData.box.y = event.target.offsetTop;
				//console.log(event);
			},
			//containment: "parent",
		});
		
		jQuery( ".resizable-box" ).resizable({
			handles: 'e, w',
			stop:function(event, ui){
				postData.box.w = event.target.clientWidth;
			},
			resize:function(event, ui){
				//jquery UI will by default lock the height even if we're only resizing width. force the height to rescale:
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
		
		jQuery( ".save" ).click(savePost);
		
		jQuery( "#bio" ).keypress(function(){
			setTimeout(function(){jQuery("#post-content p").html(convertLineBreaks(jQuery( "#bio" ).val()), 5)});
		});
		
	
});