( function( $ ) {
	
	$(window).load( function() {

		$('#tagsdiv-post_tag .howto').append(' Maximum 3 tags are allowed per post.');
		
		// Hide tag cloud if 3 tags exist
		if ( $("#tagsdiv-post_tag .tagchecklist li").length == 3 ) {
			$("#tagsdiv-post_tag .tagcloud-link").hide();
		}

		// Prevent adding more than 3 tags
		$("#tagsdiv-post_tag input.newtag").bind("keyup keypress focus", function(e) {
			var value = $(this).val().replace(" ", ""),
				  wordCount = value.split(",").length,
				  tagCount = $("#tagsdiv-post_tag .tagchecklist li").length;
				  
			if( wordCount + tagCount > 3 ) {
				e.preventDefault();
				$("#tagsdiv-post_tag .tagcloud-link, #tagsdiv-post_tag .the-tagcloud").hide(); // hide tag cloud
				return;
			} else {
				$("#tagsdiv-post_tag .tagcloud-link").show(); // show tag cloud
			}
			
		});
		
		// Show tag cloud if more than 3 tags are removed from the tag list 
		$("#tagsdiv-post_tag .tagchecklist").on( "click", function() {
			if( $("#tagsdiv-post_tag .tagchecklist li").length < 3 ) {
				$("#tagsdiv-post_tag .tagcloud-link").show();
			}
		});
		
		// Empty tag input when selecting from tag cloud
		$('#tagsdiv-post_tag').on('focus', '.wp-tag-cloud', function() {
			$("#tagsdiv-post_tag input.newtag").val("");
		});

	});
	
} )( jQuery );