jQuery(function() {
	
	
	savePost = function(){
		postData.title = jQuery("input#name").val();
		postData.content = jQuery("textarea#bio").val();

		jQuery.post(ajaxURL, postData);
		
		return false;
	}
	
	function convertLineBreaks(input_string)
	{
		return input_string.replace(/(\r\n|\r|\n)/g, "<br />");
	}
	
	jQuery(document)
		.ajaxStart(function(){
				jQuery(".ajax-spinner").show();
		})
		.ajaxStop(function(){
				jQuery(".ajax-spinner").hide();
		});
	
	
	jQuery( "#editor-tabs" ).tabs();
	jQuery( "#editor" ).draggable();
	
	//up next:
	
	jQuery( "#bio" ).keypress(function(){
		setTimeout(function(){jQuery("#post-content").html(convertLineBreaks(jQuery( "#bio" ).val()), 5)});
	});
	
	
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
	});
	
	
	jQuery( ".save" ).click(savePost);
});