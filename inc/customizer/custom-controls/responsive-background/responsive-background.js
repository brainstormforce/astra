jQuery(window).on("load", function() {
	jQuery('html').addClass('responsive-background-img-ready');
});
wp.customize.controlConstructor['ast-responsive-background'] = wp.customize.Control.extend({

	// When we're finished loading continue processing
	ready: function() {

		'use strict';

		var control = this;

		// Init the control.
		control.initAstBgControl();
		control.astResponsiveInit();
	},

	initAstBgControl: function() {

		var control = this,
			value   = control.setting._value,
			picker  = control.container.find( '.ast-responsive-bg-color-control' );

		// Hide unnecessary controls if the value doesn't have an image.
		if ( _.isUndefined( value['desktop']['background-image']) || '' === value['desktop']['background-image']) {
			control.container.find( '.background-wrapper > .background-container.desktop > .background-repeat' ).hide();
			control.container.find( '.background-wrapper > .background-container.desktop > .background-position' ).hide();
			control.container.find( '.background-wrapper > .background-container.desktop > .background-size' ).hide();
			control.container.find( '.background-wrapper > .background-container.desktop > .background-attachment' ).hide();
		}
		if ( _.isUndefined( value['tablet']['background-image']) || '' === value['tablet']['background-image']) {
			control.container.find( '.background-wrapper > .background-container.tablet > .background-repeat' ).hide();
			control.container.find( '.background-wrapper > .background-container.tablet > .background-position' ).hide();
			control.container.find( '.background-wrapper > .background-container.tablet > .background-size' ).hide();
			control.container.find( '.background-wrapper > .background-container.tablet > .background-attachment' ).hide();
		}
		if ( _.isUndefined( value['mobile']['background-image']) || '' === value['mobile']['background-image']) {
			control.container.find( '.background-wrapper > .background-container.mobile > .background-repeat' ).hide();
			control.container.find( '.background-wrapper > .background-container.mobile > .background-position' ).hide();
			control.container.find( '.background-wrapper > .background-container.mobile > .background-size' ).hide();
			control.container.find( '.background-wrapper > .background-container.mobile > .background-attachment' ).hide();
		}

		// Color.
		picker.wpColorPicker({
			change: function(event, ui) {
				var device = jQuery( this ).data( 'id' );
				if ( jQuery('html').hasClass('responsive-background-img-ready') ) {
					control.saveValue( device, 'background-color', ui.color.toString() );
				}
			},

			/**
		     * @param {Event} event - standard jQuery event, produced by "Clear"
		     * button.
		     */
		    clear: function (event)
		    {
		    	var element = jQuery(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker')[0],
			    	responsive_input = jQuery( this ),
					screen = responsive_input.closest('.wp-picker-input-wrap').find('.wp-color-picker').data( 'id' );
		        if (element) {
					control.saveValue( screen, 'background-color', '' );
				}
		    }
		});

		// Background-Repeat.
		control.container.on( 'change', '.background-repeat select', function() {
			var responsive_input = jQuery( this ),
				screen = responsive_input.data( 'id' ),
				item_value = responsive_input.val();

			control.saveValue( screen, 'background-repeat', item_value );
		});

		// Background-Size.
		control.container.on( 'change click', '.background-size input', function() {
			var responsive_input = jQuery( this ),
				screen = responsive_input.data( 'id' ),
				item_value = responsive_input.val();

			responsive_input.parent( '.buttonset' ).find( '.switch-input' ).removeAttr('checked');
			responsive_input.attr( 'checked', 'checked' );

			control.saveValue( screen, 'background-size', item_value );
		});

		// Background-Position.
		control.container.on( 'change', '.background-position select', function() {
			var responsive_input = jQuery( this ),
				screen = responsive_input.data( 'id' ),
				item_value = responsive_input.val();

			control.saveValue( screen, 'background-position', item_value );
		});

		// Background-Attachment.
		control.container.on( 'change click', '.background-attachment input', function() {
			var responsive_input = jQuery( this ),
				screen = responsive_input.data( 'id' ),
				item_value = responsive_input.val();

			responsive_input.parent( '.buttonset' ).find( '.switch-input' ).removeAttr('checked');
			responsive_input.attr( 'checked', 'checked' );

			control.saveValue( screen, 'background-attachment', item_value );
		});

		// Background-Image.
		control.container.on( 'click', '.background-image-upload-button', function( e ) {
			var responsive_input = jQuery( this ),
			screen = responsive_input.data( 'id' );

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
					control.container.find( '.background-wrapper > .background-repeat, .background-wrapper > .background-position, .background-wrapper > .background-size, .background-wrapper > .background-attachment' ).show();
				}

				control.saveValue( screen, 'background-image', imageUrl );
				preview      = control.container.find( '.background-container.'+screen+' .placeholder, .background-container.'+screen+' .thumbnail' );
				removeButton = control.container.find( '.background-container.'+screen+' .background-image-upload-remove-button' );

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
				removeButton,
				responsive_input = jQuery( this ),
				screen = responsive_input.data( 'id' );

			e.preventDefault();

			control.saveValue( screen, 'background-image', '' );

			preview      = control.container.find( '.background-container.'+ screen +' .placeholder, .background-container.'+ screen +' .thumbnail' );
			removeButton = control.container.find( '.background-container.'+ screen +' .background-image-upload-remove-button' );

			// Hide unnecessary controls.
			control.container.find( '.background-wrapper > .background-container.'+ screen +' > .background-repeat' ).hide();
			control.container.find( '.background-wrapper > .background-container.'+ screen +' > .background-position' ).hide();
			control.container.find( '.background-wrapper > .background-container.'+ screen +' > .background-size' ).hide();
			control.container.find( '.background-wrapper > .background-container.'+ screen +' > .background-attachment' ).hide();

			control.container.find( '.background-container.'+ screen +' .more-settings' ).attr('data-direction', 'down');
			control.container.find( '.background-container.'+ screen +' .more-settings' ).find('.message').html( astraCustomizerControlBackground.moreSettings );
			control.container.find( '.background-container.'+ screen +' .more-settings' ).find('.icon').html( '↓' );

			if ( preview.length ) {
				preview.removeClass().addClass( 'placeholder' ).html( astraCustomizerControlBackground.placeholder );
			}
			if ( removeButton.length ) {
				removeButton.hide();
			}
		});

		control.container.on( 'click', '.more-settings', function( e ) {

			var responsive_input = jQuery( this ),
				screen = responsive_input.data( 'id' );
			// Hide unnecessary controls.
			control.container.find( '.background-wrapper > .background-container.'+ screen +' > .background-repeat' ).toggle();
			control.container.find( '.background-wrapper > .background-container.'+ screen +' > .background-position' ).toggle();
			control.container.find( '.background-wrapper > .background-container.'+ screen +' > .background-size' ).toggle();
			control.container.find( '.background-wrapper > .background-container.'+ screen +' > .background-attachment' ).toggle();

			if( 'down' === jQuery(this).attr( 'data-direction' ) )
			{
				jQuery(this).attr('data-direction', 'up');
				jQuery(this).find('.message').html( astraCustomizerControlBackground.lessSettings )
				jQuery(this).find('.icon').html( '↑' );
			} else {
				jQuery(this).attr('data-direction', 'down');
				jQuery(this).find('.message').html( astraCustomizerControlBackground.moreSettings )
				jQuery(this).find('.icon').html( '↓' );
			}
		});
	},
	astResponsiveInit : function() {

			'use strict';
			this.container.find( '.ast-responsive-btns button' ).on( 'click', function( event ) {

				var device = jQuery(this).attr('data-device');
				if( 'desktop' == device ) {
					device = 'tablet';
				} else if( 'tablet' == device ) {
					device = 'mobile';
				} else {
					device = 'desktop';
				}

				jQuery( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
			});
	},

	/**
	 * Saves the value.
	 */
	saveValue: function( screen, property, value ) {

		var control = this,
			input   = jQuery( '#customize-control-' + control.id.replace( '[', '-' ).replace( ']', '' ) + ' .responsive-background-hidden-value' ),
			val     = control.setting._value;

		val[ screen ][ property ] = value;

		jQuery( input ).attr( 'value', JSON.stringify( val ) ).trigger( 'change' );
		control.setting.set( val );
	}
});


	jQuery(' .wp-full-overlay-footer .devices button ').on('click', function() {

		var device = jQuery(this).attr('data-device');

		jQuery( '.customize-control-ast-responsive-background .background-container, .customize-control .ast-responsive-btns > li' ).removeClass( 'active' );
		jQuery( '.customize-control-ast-responsive-background .background-container.' + device + ', .customize-control .ast-responsive-btns > li.' + device ).addClass( 'active' );
	});