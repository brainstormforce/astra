( function( $ ) {
	jQuery(window).on("load", function() {
		jQuery('html').addClass('background-colorpicker-ready');
	});

	wp.customize.controlConstructor['ast-background'] = wp.customize.Control.extend({

		// When we're finished loading continue processing
		ready: function() {

			'use strict';

			var control = this;

			// Init the control.
			control.initAstBgControl();
		},

		initAstBgControl: function() {

			var control = this,
				value   = control.setting._value,
				picker  = control.container.find( '.ast-color-control' );

			// Hide controls by default and show only when More Settings clicked.
			control.container.find( '.background-wrapper > .background-repeat' ).hide();
			control.container.find( '.background-wrapper > .background-position' ).hide();
			control.container.find( '.background-wrapper > .background-size' ).hide();
			control.container.find( '.background-wrapper > .background-attachment' ).hide();
			// Hide More Settings control only when image is not selected.
			if ( _.isUndefined( value['background-image']) || '' === value['background-image']) {
				control.container.find( '.more-settings' ).hide();
			}

			// Color.
			picker.wpColorPicker({
				change: function() {
					if ( jQuery('html').hasClass('background-colorpicker-ready') ) {
						setTimeout( function() {
							control.saveValue( 'background-color', picker.val() );
						}, 100 );
					}
				},

				/**
				 * @param {Event} event - standard jQuery event, produced by "Clear"
				 * button.
				 */
				clear: function (event)
				{
					var element = jQuery(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker')[0];

					if (element) {
						control.saveValue( 'background-color', '' );
					}
				}
			});

			// Background-Repeat.
			control.container.on( 'change', '.background-repeat select', function() {
				control.saveValue( 'background-repeat', jQuery( this ).val() );
			});

			// Background-Size.
			control.container.on( 'change click', '.background-size input', function() {
				jQuery( this ).parent( '.buttonset' ).find( '.switch-input' ).removeAttr('checked');
				jQuery( this ).attr( 'checked', 'checked' );
				control.saveValue( 'background-size', jQuery( this ).val() );
			});

			// Background-Position.
			control.container.on( 'change', '.background-position select', function() {
				control.saveValue( 'background-position', jQuery( this ).val() );
			});

			// Background-Attachment.
			control.container.on( 'change click', '.background-attachment input', function() {
				jQuery( this ).parent( '.buttonset' ).find( '.switch-input' ).removeAttr('checked');
				jQuery( this ).attr( 'checked', 'checked' );
				control.saveValue( 'background-attachment', jQuery( this ).val() );
			});

			// Background-Image.
			control.container.on( 'click', '.background-image-upload-button, .thumbnail-image img', function( e ) {
				var image = wp.media({ multiple: false }).open().on( 'select', function() {

					// This will return the selected image from the Media Uploader, the result is an object.
					var uploadedImage = image.state().get( 'selection' ).first(),
						previewImage   = uploadedImage.toJSON().sizes.full.url,
						imageUrl,
						imageID,
						imageWidth,
						imageHeight,
						preview,
						removeButton;

					if ( ! _.isUndefined( uploadedImage.toJSON().sizes.medium ) ) {
						previewImage = uploadedImage.toJSON().sizes.medium.url;
					} else if ( ! _.isUndefined( uploadedImage.toJSON().sizes.thumbnail ) ) {
						previewImage = uploadedImage.toJSON().sizes.thumbnail.url;
					}

					imageUrl    = uploadedImage.toJSON().sizes.full.url;
					imageID     = uploadedImage.toJSON().id;
					imageWidth  = uploadedImage.toJSON().width;
					imageHeight = uploadedImage.toJSON().height;

					// Show extra controls if the value has an image.
					if ( '' !== imageUrl ) {
						control.container.find( '.more-settings' ).show();
					}

					control.saveValue( 'background-image', imageUrl );
					preview      = control.container.find( '.placeholder, .thumbnail' );
					removeButton = control.container.find( '.background-image-upload-remove-button' );

					if ( preview.length ) {
						preview.removeClass().addClass( 'thumbnail thumbnail-image' ).html( '<img src="' + previewImage + '" alt="" />' );
					}
					if ( removeButton.length ) {
						removeButton.show();
					}
				});

				e.preventDefault();
			});

			control.container.on( 'click', '.background-image-upload-remove-button', function( e ) {

				var preview,
					removeButton;

				e.preventDefault();

				control.saveValue( 'background-image', '' );

				preview      = control.container.find( '.placeholder, .thumbnail' );
				removeButton = control.container.find( '.background-image-upload-remove-button' );

				// Hide unnecessary controls.
				control.container.find( '.background-wrapper > .background-repeat' ).hide();
				control.container.find( '.background-wrapper > .background-position' ).hide();
				control.container.find( '.background-wrapper > .background-size' ).hide();
				control.container.find( '.background-wrapper > .background-attachment' ).hide();
				
				control.container.find( '.more-settings' ).attr('data-direction', 'down');
				control.container.find( '.more-settings' ).find('.message').html( astraCustomizerControlBackground.moreSettings );
				control.container.find( '.more-settings' ).find('.icon').html( '↓' );

				if ( preview.length ) {
					preview.removeClass().addClass( 'placeholder' ).html( astraCustomizerControlBackground.placeholder );
				}
				if ( removeButton.length ) {
					removeButton.hide();
				}
			});

			control.container.on( 'click', '.more-settings', function( e ) {
				// Hide unnecessary controls.
				control.container.find( '.background-wrapper > .background-repeat' ).toggle();
				control.container.find( '.background-wrapper > .background-position' ).toggle();
				control.container.find( '.background-wrapper > .background-size' ).toggle();
				control.container.find( '.background-wrapper > .background-attachment' ).toggle();

				if( 'down' === $(this).attr( 'data-direction' ) )
				{
					$(this).attr('data-direction', 'up');
					$(this).find('.message').html( astraCustomizerControlBackground.lessSettings );
					$(this).find('.icon').html( '↑' );
				} else {
					$(this).attr('data-direction', 'down');
					$(this).find('.message').html( astraCustomizerControlBackground.moreSettings );
					$(this).find('.icon').html( '↓' );
				}
			});
		},

		/**
		 * Saves the value.
		 */
		saveValue: function( property, value ) {

			var control = this,
				input   = jQuery( '#customize-control-' + control.id.replace( '[', '-' ).replace( ']', '' ) + ' .background-hidden-value' ),
				val     = control.setting._value;

			val[ property ] = value;

			jQuery( input ).attr( 'value', JSON.stringify( val ) ).trigger( 'change' );
			control.setting.set( val );
		}
	});
})(jQuery);
