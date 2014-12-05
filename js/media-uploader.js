jQuery(document).ready(function($){

	var croco_spb_upload;
	var croco_spb_selector;

	function croco_spb_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		croco_spb_selector = selector;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( croco_spb_upload ) {
			croco_spb_upload.open();
		} else {
			// Create the media frame.
			croco_spb_upload = wp.media.frames.croco_spb_upload =  wp.media({
				// Set the title of the modal.
				title: $el.data('choose'),

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: $el.data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			croco_spb_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = croco_spb_upload.state().get('selection').first();
				croco_spb_upload.close();
				croco_spb_selector.find('.upload').val(attachment.attributes.url);
				if ( attachment.attributes.type == 'image' ) {
					croco_spb_selector.find('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><a class="remove-image">Remove</a>').slideDown('fast');
				}
				croco_spb_selector.find('.upload-button').unbind().addClass('remove-file').removeClass('upload-button').val(croco_spb_l10n.remove);
				croco_spb_selector.find('.of-background-properties').slideDown();
				croco_spb_selector.find('.remove-image, .remove-file').on('click', function() {
					croco_spb_remove_file( $(this).parents('.section') );
				});
			});

		}

		// Finally, open the modal.
		croco_spb_upload.open();
	}

	function croco_spb_remove_file(selector) {
		selector.find('.remove-image').hide();
		selector.find('.upload').val('');
		selector.find('.of-background-properties').hide();
		selector.find('.screenshot').slideUp();
		selector.find('.remove-file').unbind().addClass('upload-button').removeClass('remove-file').val(croco_spb_l10n.upload);
		// We don't display the upload button if .upload-notice is present
		// This means the user doesn't have the WordPress 3.5 Media Library Support
		if ( $('.section-upload .upload-notice').length > 0 ) {
			$('.upload-button').remove();
		}
		selector.find('.upload-button').on('click', function(event) {
			croco_spb_add_file(event, $(this).parents('.section'));
		});
	}

	$('.remove-image, .remove-file').on('click', function() {
		croco_spb_remove_file( $(this).parents('.section') );
    });

    $('.upload-button').click( function( event ) {
    	croco_spb_add_file(event, $(this).parents('.section'));
    });

});