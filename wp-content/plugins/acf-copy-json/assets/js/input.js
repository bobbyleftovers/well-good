(function($){
	
	
	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/
	var copyToClipboard = function copyToClipboard(str) {
		var el = document.createElement('textarea');
		el.value = str;
		document.body.appendChild(el);
		el.select();
		document.execCommand('copy');
		document.body.removeChild(el);
	};
	
	function initialize_field( $field ) {
		$field.on('click', async function () {
			var vertical = acf.findFields({ sibling: $field, key: 'field_5f0f6320739ef' });
			var props = acf.findFields({ sibling: $field, key: 'field_5f0f6320739f0' });
			var childFields = acf.findFields({ parent: props })
			var payload = {
				"sub_type": "emailCapture",
				vertical: vertical.data('acf').val(),
				data: {}
			}
			
			for (const el of jQuery.makeArray(childFields)) {
				var field = $(el).data('acf')
				if(field.type === 'image' && field.val()) {
					let data = await wp.media.attachment(field.val()).fetch()
					payload.data[field.get('name')] = data.url
					continue
				}
				payload.data[field.get('name')] = field.val()
			}

			copyToClipboard(JSON.stringify(payload))
			var button = $field.find('button')
			button.text('Copied')
			setTimeout(function () {
				button.text('Copy JSON')
			}, 1000)
		})
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/
		
		acf.add_action('ready_field/type=copy_json', initialize_field);
		acf.add_action('append_field/type=copy_json', initialize_field);
		
		
	} else {
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			// find all relevant fields
			$(postbox).find('.field[data-type="copy_json"]').each(function(){
				
				// initialize
				initialize_field( $(this) );
				
			});
		
		});
	
	}

})(jQuery);
