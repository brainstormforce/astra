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

( function( $ ) {
	/**
	 * File spacing.js
	 *
	 * Handles the spacing
	 *
	 * @package Astra
	 */

	wp.customize.controlConstructor['ast-border'] = wp.customize.Control.extend({

		ready: function() {

			'use strict';

			var control = this,
			value;
			

			// Set the spacing container.
			// this.container = control.container.find( 'ul.ast-border-wrapper' ).first();

			// Save the value.
			this.container.on( 'change keyup paste', 'input.ast-border-input', function() {

				value = jQuery( this ).val();

				// Update value on change.
				control.updateValue();
			});
		},

		/**
		 * Updates the spacing values
		 */
		updateValue: function() {

			'use strict';

			var control = this,
				newValue = {
					'top' 	: '',
					'right' : '',
					'bottom' : '',
					'left'	 : '',
				};

			control.container.find( 'input.ast-border-desktop' ).each( function() {
				var spacing_input = jQuery( this ),
				item = spacing_input.data( 'id' ),
				item_value = spacing_input.val();

				newValue[item] = item_value;
			});

			control.setting.set( newValue );
		},

	});

	jQuery( document ).ready( function( ) {

		// Connected button
		jQuery( '.ast-border-connected' ).on( 'click', function() {

			// Remove connected class
			jQuery(this).parent().parent( '.ast-border-wrapper' ).find( 'input' ).removeClass( 'connected' ).attr( 'data-element-connect', '' );
			
			// Remove class
			jQuery(this).parent( '.ast-border-input-item-link' ).removeClass( 'disconnected' );

		} );

		// Disconnected button
		jQuery( '.ast-border-disconnected' ).on( 'click', function() {

			// Set up variables
			var elements 	= jQuery(this).data( 'element-connect' );
			
			// Add connected class
			jQuery(this).parent().parent( '.ast-border-wrapper' ).find( 'input' ).addClass( 'connected' ).attr( 'data-element-connect', elements );

			// Add class
			jQuery(this).parent( '.ast-border-input-item-link' ).addClass( 'disconnected' );

		} );

		// Values connected inputs
		jQuery( '.ast-border-input-item' ).on( 'input', '.connected', function() {

			var dataElement 	  = jQuery(this).attr( 'data-element-connect' ),
				currentFieldValue = jQuery( this ).val();

			jQuery(this).parent().parent( '.ast-border-wrapper' ).find( '.connected[ data-element-connect="' + dataElement + '" ]' ).each( function( key, value ) {
				jQuery(this).val( currentFieldValue ).change();
			} );

		} );
	});
})(jQuery);

( function( $ ) {

	/**
	 * File slider.js
	 *
	 * Handles Slider control
	 *
	 * @package Astra
	 */

	jQuery(window).on("load", function() {
		jQuery('html').addClass('colorpicker-ready');
	});

	wp.customize.controlConstructor['ast-color'] = wp.customize.Control.extend({

		ready: function() {

			'use strict';

			var control = this,
				value,
				thisInput,
				inputDefault,
				changeAction;			

			this.container.find('.ast-color-picker-alpha' ).wpColorPicker({
				/**
			     * @param {Event} event - standard jQuery event, produced by whichever
			     * control was changed.
			     * @param {Object} ui - standard jQuery UI object, with a color member
			     * containing a Color.js object.
			     */
			    change: function (event, ui) {
			        var element = event.target;
			        var color = ui.color.toString();

			        if ( jQuery('html').hasClass('colorpicker-ready') ) {
						control.setting.set( color );
			        }
			    },

			    /**
			     * @param {Event} event - standard jQuery event, produced by "Clear"
			     * button.
			     */
			    clear: function (event) {
			        var element = jQuery(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker')[0];
			        var color = '';

			        if (element) {
			            // Add your code here
			        	control.setting.set( color );
			        }
			    }
			});
		}
	});

})(jQuery);

( function( $ ) {
	/**
	 * File link.js
	 *
	 * Handles the link
	 *
	 * @package Astra
	 */

	wp.customize.controlConstructor['ast-link'] = wp.customize.Control.extend({

		ready: function() {

			'use strict';

			var control = this;
			var data = jQuery( '.customize-link-control-data' ).data('value');

			// Save the value.
			this.container.on( 'change keyup', '.ast-link-input', function(e) {
				e.preventDefault();
				
				value = jQuery( this ).val();
				data.url = value;
				
				jQuery('.customize-link-control-data').attr('data-value', JSON.stringify( data )).trigger( 'change' );

				// Update value on change.
				control.setting.set( data );
			});
			
			// Save the value.
			this.container.on( 'change click', '.ast-link-open-in-new-tab', function() {
				
				value = jQuery( this ).is(":checked");
				data.new_tab = value;
				
				jQuery('.customize-link-control-data').attr('data-value', JSON.stringify( data )).trigger( 'change' );
				
				// Update value on change.
				control.setting.set( data );
			});
			
			// Save the value.
			this.container.on( 'change keyup', '.ast-link-relationship', function(e) {
				e.preventDefault();
				
				value = jQuery( this ).val();
				data.link_rel = value;
				
				jQuery('.customize-link-control-data').attr('data-value', JSON.stringify( data )).trigger( 'change' );
				
				// Update value on change.
                control.setting.set( data );
			});
		},
	});
})(jQuery);

/**
 * File spacing.js
 *
 * Handles the spacing
 *
 * @package Astra
 */

wp.customize.controlConstructor['ast-customizer-link'] = wp.customize.Control.extend({

	ready: function () {
		'use strict';

		// Add event listener for click action.
		this.container.on('click', '.customizer-link', function (e) {
			e.preventDefault();

			var sectionName = this.getAttribute('data-customizer-linked');
			var section = wp.customize.section(sectionName);
			section.expand();
		});
	},

});

/**
 * File radio-image.js
 *
 * Handles toggling the radio images button
 *
 * @package Astra
 */

wp.customize.controlConstructor['ast-radio-image'] = wp.customize.Control.extend({

	ready: function() {

		'use strict';

		var control = this;

		// Change the value.
		this.container.on( 'click', 'input', function() {
			control.setting.set( jQuery( this ).val() );
		});

	}

});

( function( $ ) {
	/**
	 * File responsive.js
	 *
	 * Handles the responsive
	 *
	 * @package Astra
	 */

	wp.customize.controlConstructor['ast-responsive'] = wp.customize.Control.extend({

		// When we're finished loading continue processing.
		ready: function() {

			'use strict';

			var control = this,
			value;

			control.astResponsiveInit();
			
			/**
			 * Save on change / keyup / paste
			 */
			this.container.on( 'change keyup paste', 'input.ast-responsive-input, select.ast-responsive-select', function() {

				value = jQuery( this ).val();

				// Update value on change.
				control.updateValue();
			});

			/**
			 * Refresh preview frame on blur
			 */
			this.container.on( 'blur', 'input', function() {

				value = jQuery( this ).val() || '';

				if ( value == '' ) {
					wp.customize.previewer.refresh();
				}

			});

			jQuery( '.customize-control-ast-responsive .input-wrapper input.' + 'desktop' + ', .customize-control .ast-responsive-btns > li.' + 'desktop' ).addClass( 'active' );

		},

		/**
		 * Updates the sorting list
		 */
		updateValue: function() {

			'use strict';

			var control = this,
			newValue = {};

			// Set the spacing container.
			control.responsiveContainer = control.container.find( '.ast-responsive-wrapper' ).first();

			control.responsiveContainer.find( 'input.ast-responsive-input' ).each( function() {
				var responsive_input = jQuery( this ),
				item = responsive_input.data( 'id' ),
				item_value = responsive_input.val();

				newValue[item] = item_value;

			});

			control.responsiveContainer.find( 'select.ast-responsive-select' ).each( function() {
				var responsive_input = jQuery( this ),
				item = responsive_input.data( 'id' ),
				item_value = responsive_input.val();

				newValue[item] = item_value;
			});

			control.setting.set( newValue );
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
	});

	jQuery(' .wp-full-overlay-footer .devices button ').on('click', function() {

		var device = jQuery(this).attr('data-device');

		jQuery( '.customize-control-ast-responsive .input-wrapper input, .customize-control .ast-responsive-btns > li' ).removeClass( 'active' );
		jQuery( '.customize-control-ast-responsive .input-wrapper input.' + device + ', .customize-control .ast-responsive-btns > li.' + device ).addClass( 'active' );
	});
})(jQuery);

( function( $ ) {
	jQuery(window).on("load", function() {
		jQuery('html').addClass('responsive-background-color-ready');
	});
	/**
	 * File responsive-color.js
	 *
	 * Handles the responsive color
	 *
	 * @package Astra
	 */
	wp.customize.controlConstructor['ast-responsive-color'] = wp.customize.Control.extend({

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
				picker  = control.container.find( '.ast-responsive-color' );

			// Color.
			picker.wpColorPicker({

				change: function(event, ui) {
					if ( jQuery('html').hasClass('responsive-background-color-ready') ) {

						var stored = control.setting.get();
						var device = jQuery( this ).data( 'id' );
						var newValue = {
							'desktop' : stored['desktop'],
							'tablet'  : stored['tablet'],
							'mobile'  : stored['mobile'],
						};
						if ( 'desktop' === device ) {
							newValue['desktop'] = ui.color.toString();
						}
						if ( 'tablet' === device ) {
							newValue['tablet'] = ui.color.toString();
						}
						if ( 'mobile' === device ) {
							newValue['mobile'] = ui.color.toString();
						}
						control.setting.set( newValue );
					}
				},

				/**
				 * @param {Event} event - standard jQuery event, produced by "Clear"
				 * button.
				 */
				clear: function (event) {
					var element = jQuery(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker')[0],
						device = jQuery( this ).closest('.wp-picker-input-wrap').find('.wp-color-picker').data( 'id' );

						var stored = control.setting.get();
						var newValue = {
							'desktop' : stored['desktop'],
							'tablet'  : stored['tablet'],
							'mobile'  : stored['mobile'],
						};
					if ( element ) {
						if ( 'desktop' === device ) {
							newValue['desktop'] = '';
						}
						if ( 'tablet' === device ) {
							newValue['tablet'] = '';
						}
						if ( 'mobile' === device ) {
							newValue['mobile'] = '';
						}
						control.setting.set( newValue );
					}
				}
			});

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

			jQuery( '.customize-control-ast-responsive-color .customize-control-content .ast-responsive-color.desktop' ).parents( '.wp-picker-container' ).addClass( 'active' );

		}
	});


	jQuery('.wp-full-overlay-footer .devices button').on('click', function() {

		var device = jQuery(this).attr('data-device');

		jQuery( '.customize-control-ast-responsive-color .customize-control-content .wp-picker-container' ).removeClass( 'active' );
		jQuery( '.customize-control-ast-responsive-color .customize-control-content .ast-responsive-color.' + device ).parents( '.wp-picker-container' ).addClass( 'active' );
	});
})(jQuery);

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
( function( $ ) {

	/**
	 * File slider.js
	 *
	 * Handles Slider control
	 *
	 * @package Astra
	 */

	wp.customize.controlConstructor['ast-responsive-slider'] = wp.customize.Control.extend({

		ready: function() {

			'use strict';

			var control = this,
				value,
				thisInput,
				inputDefault,
				changeAction;

			control.astResponsiveInit();

			// Update the text value.
			this.container.on( 'input change', 'input[type=range]', function() {
				var value 		 = jQuery( this ).val(),
					input_number = jQuery( this ).closest( '.input-field-wrapper' ).find( '.ast-responsive-range-value-input' );
				
				input_number.val( value );
				input_number.trigger( 'change' );
			});

			// Handle the reset button.
			this.container.on('click', '.ast-responsive-slider-reset', function() {
				
				var wrapper 		= jQuery( this ).parent().find('.input-field-wrapper.active'),
					input_range   	= wrapper.find( 'input[type=range]' ),
					input_number 	= wrapper.find( '.ast-responsive-range-value-input' ),
					default_value	= input_range.data( 'reset_value' );

				input_range.val( default_value );
				input_number.val( default_value );
				input_number.trigger( 'change' );
			});

			// Save changes.
			this.container.on( 'input change', 'input[type=number]', function() {
				var value = jQuery( this ).val();
				jQuery( this ).closest( '.input-field-wrapper' ).find( 'input[type=range]' ).val( value );
				
				control.updateValue();
			});
		},

		/**
		 * Updates the sorting list
		 */
		updateValue: function() {

			'use strict';

			var control = this,
			newValue = {};

			// Set the spacing container.
			control.responsiveContainer = control.container.find( '.wrapper' ).first();

			control.responsiveContainer.find( '.ast-responsive-range-value-input' ).each( function() {
				var responsive_input = jQuery( this ),
				item = responsive_input.data( 'id' ),
				item_value = responsive_input.val();

				newValue[item] = item_value;

			});

			control.setting.set( newValue );
		},

		astResponsiveInit : function() {
			
			this.container.on( 'click', '.ast-responsive-slider-btns button', function( event ) {

				event.preventDefault();
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
	});

	jQuery(' .wp-full-overlay-footer .devices button ').on('click', function() {

		var device = jQuery(this).attr('data-device');

		jQuery( '.customize-control-ast-responsive-slider .input-field-wrapper, .customize-control .ast-responsive-slider-btns > li' ).removeClass( 'active' );
		jQuery( '.customize-control-ast-responsive-slider .input-field-wrapper.' + device + ', .customize-control .ast-responsive-slider-btns > li.' + device ).addClass( 'active' );
	});
	
})(jQuery);

( function( $ ) {
	/**
	 * File spacing.js
	 *
	 * Handles the spacing
	 *
	 * @package Astra
	 */

	wp.customize.controlConstructor['ast-responsive-spacing'] = wp.customize.Control.extend({

		ready: function() {

			'use strict';

			var control = this,
		    value;
		    
		    control.astResponsiveInit();

			// Set the spacing container.
			// this.container = control.container.find( 'ul.ast-spacing-wrapper' ).first();

			// Save the value.
			this.container.on( 'change keyup paste', 'input.ast-spacing-input', function() {

				value = jQuery( this ).val();

				// Update value on change.
				control.updateValue();
			});
		},

		/**
		 * Updates the spacing values
		 */
		updateValue: function() {

			'use strict';

			var control = this,
				newValue = {
					'desktop' 		: {},
					'tablet'  		: {},
					'mobile'  		: {},
					'desktop-unit'	: 'px',
					'tablet-unit'	: 'px',
					'mobile-unit'	: 'px',
				};

			control.container.find( 'input.ast-spacing-desktop' ).each( function() {
				var spacing_input = jQuery( this ),
				item = spacing_input.data( 'id' ),
				item_value = spacing_input.val();

				newValue['desktop'][item] = item_value;
			});

			control.container.find( 'input.ast-spacing-tablet' ).each( function() {
				var spacing_input = jQuery( this ),
				item = spacing_input.data( 'id' ),
				item_value = spacing_input.val();

				newValue['tablet'][item] = item_value;
			});

			control.container.find( 'input.ast-spacing-mobile' ).each( function() {
				var spacing_input = jQuery( this ),
				item = spacing_input.data( 'id' ),
				item_value = spacing_input.val();

				newValue['mobile'][item] = item_value;
			});

			control.container.find('.ast-spacing-unit-wrapper .ast-spacing-unit-input').each( function() {
				var spacing_unit 	= jQuery( this ),
					device 			= spacing_unit.attr('data-device'),
					device_val 		= spacing_unit.val(),
					name 			= device + '-unit';
					
				newValue[ name ] = device_val;
			});

			control.setting.set( newValue );
		},

		/**
		 * Set the responsive devices fields
		 */
		astResponsiveInit : function() {
			
			'use strict';

			var control = this;
			
			control.container.find( '.ast-spacing-responsive-btns button' ).on( 'click', function( event ) {

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

			// Unit click
			control.container.on( 'click', '.ast-spacing-responsive-units .single-unit', function() {
				
				var $this 		= jQuery(this);

				if ( $this.hasClass('active') ) {
					return false;
				}

				var	unit_value 	= $this.attr('data-unit'),
					device 		= jQuery('.wp-full-overlay-footer .devices button.active').attr('data-device');
				
				$this.siblings().removeClass('active');
				$this.addClass('active');

				control.container.find('.ast-spacing-unit-wrapper .ast-spacing-' + device + '-unit').val( unit_value );

				// Update value on change.
				control.updateValue();
			});
		},
	});

	jQuery( document ).ready( function( ) {

		// Connected button
		jQuery( '.ast-spacing-connected' ).on( 'click', function() {

			// Remove connected class
			jQuery(this).parent().parent( '.ast-spacing-wrapper' ).find( 'input' ).removeClass( 'connected' ).attr( 'data-element-connect', '' );
			
			// Remove class
			jQuery(this).parent( '.ast-spacing-input-item-link' ).removeClass( 'disconnected' );

		} );

		// Disconnected button
		jQuery( '.ast-spacing-disconnected' ).on( 'click', function() {

			// Set up variables
			var elements 	= jQuery(this).data( 'element-connect' );
			
			// Add connected class
			jQuery(this).parent().parent( '.ast-spacing-wrapper' ).find( 'input' ).addClass( 'connected' ).attr( 'data-element-connect', elements );

			// Add class
			jQuery(this).parent( '.ast-spacing-input-item-link' ).addClass( 'disconnected' );

		} );

		// Values connected inputs
		jQuery( '.ast-spacing-input-item' ).on( 'input', '.connected', function() {

			var dataElement 	  = jQuery(this).attr( 'data-element-connect' ),
				currentFieldValue = jQuery( this ).val();

			jQuery(this).parent().parent( '.ast-spacing-wrapper' ).find( '.connected[ data-element-connect="' + dataElement + '" ]' ).each( function( key, value ) {
				jQuery(this).val( currentFieldValue ).change();
			} );

		} );
	});

	jQuery('.wp-full-overlay-footer .devices button ').on('click', function() {

		var device = jQuery(this).attr('data-device');
		jQuery( '.customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper, .customize-control .ast-spacing-responsive-btns > li' ).removeClass( 'active' );
		jQuery( '.customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper.' + device + ', .customize-control .ast-spacing-responsive-btns > li.' + device ).addClass( 'active' );
	});
})(jQuery);

( function( $ ) {
    
    /**
     * JS to manage the sticky heading of an open section on scroll up.
     */
    jQuery( document ).ready(function() {
        var last_scroll_top = 0;
        var parentSection   = jQuery( '.wp-full-overlay-sidebar-content' );
        var browser = navigator.userAgent.toLowerCase();
        if ( ! ( browser.indexOf( 'firefox' ) > -1 ) ) {
        var parent_width_remove = 6;
        } else {
        var parent_width_remove = 16;
        }
        jQuery('#customize-controls .wp-full-overlay-sidebar-content .control-section').on( 'scroll', function (event) {
            var $this = jQuery(this);
            // Run sticky js for only open section.
            if ( $this.hasClass( 'open' ) ) {
                var section_title = $this.find( '.customize-section-title' );
                var scroll_top    = $this.scrollTop();
                if ( scroll_top > last_scroll_top ) {
                    // On scroll down, remove sticky section title.
                    section_title.removeClass( 'maybe-sticky' ).removeClass( 'is-in-view' ).removeClass( 'is-sticky' );
                    $this.css( 'padding-top', '' );
                } else {
                    // On scroll up, add sticky section title.
                    var parent_width = $this.outerWidth();
                    section_title.addClass( 'maybe-sticky' ).addClass( 'is-in-view' ).addClass( 'is-sticky' ).width( parent_width - parent_width_remove ).css( 'top', parentSection.css( 'top' ) );
                    if ( ! ( browser.indexOf( 'firefox' ) > -1 ) ) {
                        $this.css( 'padding-top', section_title.height() );
                    }
                    if( scroll_top === 0 ) {
                        // Remove sticky section heading when scrolled to the top.
                        section_title.removeClass( 'maybe-sticky' ).removeClass( 'is-in-view' ).removeClass( 'is-sticky' );
                        $this.css( 'padding-top', '' );
                    }
                }
                last_scroll_top = scroll_top;
            }
        });
    });

    wp.customize.controlConstructor['ast-settings-group'] = wp.customize.Control.extend({

        ready : function() {

            'use strict';

            var control = this,
            value   = control.setting._value;

            control.registerToggleEvents();
            this.container.on( 'ast_settings_changed', control.onOptionChange );
        },

        registerToggleEvents: function() {

            var control = this;

            /* Close popup when click outside anywhere outside of popup */
            $( '.wp-full-overlay-sidebar-content, .wp-picker-container' ).click( function( e ) {
                if ( ! $( e.target ).closest( '.ast-field-settings-modal' ).length ) {
                    $( '.ast-adv-toggle-icon.open' ).trigger( 'click' );
                }
            });
            
            control.container.on( 'click', '.ast-toggle-desc-wrap .ast-adv-toggle-icon', function( e ) {
                
                e.preventDefault();
                e.stopPropagation();
                
                var $this = jQuery(this);
                
                var parent_wrap = $this.closest( '.customize-control-ast-settings-group' );
                var is_loaded = parent_wrap.find( '.ast-field-settings-modal' ).data('loaded');
                var parent_section = parent_wrap.parents('.control-section');
                
                if( $this.hasClass('open') ) {
                    parent_wrap.find( '.ast-field-settings-modal' ).hide();
                } else {
                    /* Close popup when another popup is clicked to open */
                    var get_open_popup = parent_section.find('.ast-adv-toggle-icon.open');
                    if( get_open_popup.length > 0 ) {
                        get_open_popup.trigger('click');
                    }
                    if( is_loaded ) {
                        parent_wrap.find( '.ast-field-settings-modal' ).show();
                    } else {
                        var fields = control.params.ast_fields;

                        var $modal_wrap = $( astra.customizer.group_modal_tmpl );

                        parent_wrap.find( '.ast-field-settings-wrap' ).append( $modal_wrap );
                        parent_wrap.find( '.ast-fields-wrap' ).attr( 'data-control', control.params.name );
                        control.ast_render_field( parent_wrap, fields, control );

                        parent_wrap.find( '.ast-field-settings-modal' ).show();

                        device = jQuery("#customize-footer-actions .active").attr('data-device');

                        if( 'mobile' == device ) {
                            jQuery('.ast-responsive-btns .mobile, .ast-responsive-slider-btns .mobile').addClass('active');
                            jQuery('.ast-responsive-btns .preview-mobile, .ast-responsive-slider-btns .preview-mobile').addClass('active');
                        } else if( 'tablet' == device ) {
                            jQuery('.ast-responsive-btns .tablet, .ast-responsive-slider-btns .tablet').addClass('active');
                            jQuery('.ast-responsive-btns .preview-tablet, .ast-responsive-slider-btns .preview-tablet').addClass('active');
                        } else {
                            jQuery('.ast-responsive-btns .desktop, .ast-responsive-slider-btns .desktop').addClass('active');
                            jQuery('.ast-responsive-btns .preview-desktop, .ast-responsive-slider-btns .preview-desktop').addClass('active');
                        }
                    }
                }

                $this.toggleClass('open');

            });

            control.container.on( "click", ".ast-toggle-desc-wrap > .customizer-text", function( e ) {

                e.preventDefault();
                e.stopPropagation();

                jQuery(this).find( '.ast-adv-toggle-icon' ).trigger('click');
            });
        },

        ast_render_field: function( wrap, fields, control_elem ) {

            var control = this;
            var ast_field_wrap = wrap.find( '.ast-fields-wrap' );
            var fields_html = '';
            var control_types = [];
            var field_values = control.isJsonString( control_elem.params.value ) ? JSON.parse( control_elem.params.value ) : {};

            if( 'undefined' != typeof fields.tabs ) {

                var clean_param_name = control_elem.params.name.replace( '[', '-' ),
                    clean_param_name = clean_param_name.replace( ']', '' );

                fields_html += '<div id="' + clean_param_name + '-tabs" class="ast-group-tabs">'; 
                fields_html += '<ul class="ast-group-list">'; 
                var counter = 0;

                _.each( fields.tabs, function ( value, key ) {

                    var li_class = '';
                    if( 0 == counter ) {
                        li_class = "active";
                    }

                    fields_html += '<li class="'+ li_class + '"><a href="#tab-' + key + '"><span>' + key +  '</span></a></li>';
                    counter++;
                });

                fields_html += '</ul>'; 

                fields_html += '<div class="ast-tab-content" >';

                _.each( fields.tabs, function ( fields_data, key ) {

                    fields_html += '<div id="tab-'+ key +'" class="tab">';

                    var result = control.generateFieldHtml( fields_data, field_values );

                    fields_html += result.html;

                    _.each( result.controls , function ( control_value, control_key ) {
                        control_types.push({
                            key: control_value.key,
                            value : control_value.value,
                            name  : control_value.name 
                        });
                    });

                    fields_html += '</div>';
                });

                fields_html += '</div></div>';

                ast_field_wrap.html( fields_html );

                $( "#" + clean_param_name + "-tabs" ).tabs();

            } else {

                var result = control.generateFieldHtml( fields, field_values );

                fields_html += result.html;
                
                _.each( result.controls, function (control_value, control_key) {
                    control_types.push({
                        key: control_value.key,
                        value: control_value.value,
                        name: control_value.name
                    });
                });

                ast_field_wrap.html(fields_html);
            }

            _.each( control_types, function( control_type, index ) {

                switch( control_type.key ) {

                    case "ast-responsive-color":
                        control.initResponsiveColor( ast_field_wrap, control_elem, control_type.name );
                    break;  

                    case "ast-color": 
                        control.initColor( ast_field_wrap, control_elem, control_type.name );
                    break;

                    case "ast-font": 

                        var googleFontsString = astra.customizer.settings.google_fonts;
                        control.container.find( '.ast-font-family' ).html( googleFontsString );

                        control.container.find( '.ast-font-family' ).each( function() {
                            var selectedValue = $(this).data('value');
                            $(this).val( selectedValue );

                            var optionName = $(this).data('name');

                            // Set inherit option text defined in control parameters.
                            $("select[data-name='" + optionName + "'] option[value='inherit']").text( $(this).data('inherit') );

                            var fontWeightContainer = jQuery(".ast-font-weight[data-connected-control='" + optionName + "']");
                            var weightObject = AstTypography._getWeightObject( AstTypography._cleanGoogleFonts( selectedValue ) );

                            control.generateDropdownHtml( weightObject, fontWeightContainer );
                            fontWeightContainer.val( fontWeightContainer.data('value') );

                        }); 

                        control.container.find( '.ast-font-family' ).selectWoo();
                        control.container.find( '.ast-font-family' ).on( 'select2:select', function() {

                            var value = $(this).val();
                            var weightObject = AstTypography._getWeightObject( AstTypography._cleanGoogleFonts( value ) );
                            var optionName = $(this).data( 'name' );
                            var fontWeightContainer = jQuery(".ast-font-weight[data-connected-control='" + optionName + "']");

                            control.generateDropdownHtml( weightObject, fontWeightContainer );

                            var font_control = $(this).parents( '.customize-control' ).attr( 'id' );
                            font_control = font_control.replace( 'customize-control-', '' );

                            control.container.trigger( 'ast_settings_changed', [ control, jQuery(this), value, font_control ] );

                            var font_weight_control = fontWeightContainer.parents( '.customize-control' ).attr( 'id' );
                            font_weight_control = font_weight_control.replace( 'customize-control-', '' );

                            control.container.trigger( 'ast_settings_changed', [ control, fontWeightContainer, fontWeightContainer.val(), font_weight_control ] );
                            
                        });

                        control.container.find( '.ast-font-weight' ).on( 'change', function() {

                            var value = $(this).val();

                            name = $(this).parents( '.customize-control' ).attr( 'id' );
                            name = name.replace( 'customize-control-', '' );

                            control.container.trigger( 'ast_settings_changed', [ control, jQuery(this), value, name ] );
                        });
                        
                    break;  

                    case "ast-responsive": 

                        control.initResponsiveTrigger( ast_field_wrap, control_elem ); 

                        control.container.on( 'change keyup paste', 'input.ast-responsive-input, select.ast-responsive-select', function() {
            
                            name = $(this).parents( '.customize-control' ).attr( 'id' );
                            name = name.replace( 'customize-control-', '' );

                            // Update value on change.
                            control.updateResonsiveValue( jQuery(this), name );
                        });

                    break;

                    case "ast-select":

                        control.container.on( 'change', '.ast-select-input', function() {

                            var value = jQuery( this ).val();

                            name = $(this).parents( '.customize-control' ).attr( 'id' );
                            name = name.replace( 'customize-control-', '' );  

                            control.container.trigger( 'ast_settings_changed', [ control, jQuery(this), value, name ] );
                        });

                    break;

                    case "ast-slider": 
                    
                        control.container.on('input change', 'input[type=range]', function () {
                            var value = jQuery(this).attr('value'),
                                input_number = jQuery(this).closest('.wrapper').find('.astra_range_value .value');

                            input_number.val(value);

                            name = $(this).parents( '.customize-control' ).attr( 'id' );
                            name = name.replace( 'customize-control-', '' );

                            control.container.trigger('ast_settings_changed', [control, input_number, value, name]);
                        });

                        // Handle the reset button.
                        control.container.on( 'click', '.ast-slider-reset', function () {

                            var wrapper = jQuery(this).closest('.wrapper'),
                                input_range = wrapper.find('input[type=range]'),
                                input_number = wrapper.find('.astra_range_value .value'),
                                default_value = input_range.data('reset_value');

                            input_range.val(default_value);
                            input_number.val(default_value);

                            name = $(this).parents( '.customize-control' ).attr( 'id' );
                            name = name.replace( 'customize-control-', '' );

                            control.container.trigger('ast_settings_changed', [control, input_number, default_value, name]);
                        });

                        // Save changes.
                        control.container.find( '.customize-control-ast-slider' ).on('input change', 'input[type=number]', function () {

                            var value = jQuery(this).val();
                            jQuery(this).closest('.wrapper').find('input[type=range]').val(value);

                            name = $(this).parents( '.customize-control' ).attr( 'id' );
                            name = name.replace( 'customize-control-', '' );
    
                            control.container.trigger('ast_settings_changed', [control, jQuery(this), value, name]);
                        });

                    break;

                    case "ast-responsive-background":

                        control.initAstResonsiveBgControl( control_elem, control_type, control_type.name );

                    break;

                    case "ast-background":

                        control.initAstBgControl( control_elem, control_type, control_type.name );

                    break;

                    case "ast-border":

                        control.initAstBorderControl( control_elem, control_type, control_type.name );

                    break;
                }

            });

            wrap.find( '.ast-field-settings-modal' ).data( 'loaded', true );
            
        },

        initAstBorderControl: function( control_elem, control_type, name ) {

            var control = this,
                value            = control.setting._value,
                control_name     = control_type.name;
            
            // Save the value.
            this.container.on( 'change keyup paste', 'input.ast-border-input', function() {

                // Update value on change.
                control.saveBorderValue( 'border', jQuery( this ).val(), jQuery( this ), name );

            });

            // Connected button
            jQuery( '.ast-border-connected' ).on( 'click', function() {

                // Remove connected class
                jQuery(this).parent().parent( '.ast-border-wrapper' ).find( 'input' ).removeClass( 'connected' ).attr( 'data-element-connect', '' );
                
                // Remove class
                jQuery(this).parent( '.ast-border-input-item-link' ).removeClass( 'disconnected' );

            } );

            // Disconnected button
            jQuery( '.ast-border-disconnected' ).on( 'click', function() {

                // Set up variables
                var elements    = jQuery(this).data( 'element-connect' );
                
                // Add connected class
                jQuery(this).parent().parent( '.ast-border-wrapper' ).find( 'input' ).addClass( 'connected' ).attr( 'data-element-connect', elements );

                // Add class
                jQuery(this).parent( '.ast-border-input-item-link' ).addClass( 'disconnected' );

            } );

            // Values connected inputs
            jQuery( '.ast-border-input-item' ).on( 'input', '.connected', function() {

                var dataElement       = jQuery(this).attr( 'data-element-connect' ),
                    currentFieldValue = jQuery( this ).val();

                jQuery(this).parent().parent( '.ast-border-wrapper' ).find( '.connected[ data-element-connect="' + dataElement + '" ]' ).each( function( key, value ) {
                    jQuery(this).val( currentFieldValue ).change();
                } );

            } );
        },

        generateFieldHtml: function ( fields_data, field_values ) {    

            var fields_html = '';
            var control_types = [];


            _.each(fields_data, function (attr, index) {

                new_value = ( wp.customize.control( 'astra-settings['+attr.name+']' ) ? wp.customize.control( 'astra-settings['+attr.name+']' ).params.value : '' ); 
                var control = attr.control;
                var template_id = "customize-control-" + control + "-content";
                var template = wp.template(template_id);
                var value = new_value || attr.default;
                attr.value = value;
                var dataAtts = '';
                var input_attrs = '';

                attr.label = attr.title;

                // Data attributes.
                _.each( attr.data_attrs, function( value, name ) {
                    dataAtts += " data-" + name + " ='" + value + "'";
                });

                // Input attributes
                _.each( attr.input_attrs, function ( value, name ) {
                    input_attrs += name + " ='" + value + "'";
                });

                attr.dataAttrs = dataAtts;
                attr.inputAttrs = input_attrs;

                control_types.push({
                    key: control,
                    value: value,
                    name: attr.name
                });

                if ('ast-responsive' == control) {
                    var is_responsive = 'undefined' == typeof attr.responsive ? true : attr.responsive;
                    attr.responsive = is_responsive;
                }

                var control_clean_name = attr.name.replace('[', '-');
                control_clean_name = control_clean_name.replace(']', '');

                fields_html += "<li id='customize-control-" + control_clean_name + "' class='customize-control customize-control-" + attr.control + "' >";
                fields_html += template(attr);
                fields_html += '</li>';

            });

            var result = new Object();

            result.controls = control_types;
            result.html     = fields_html;

            return result;
        },

        generateDropdownHtml: function( weightObject, element ) {

            var currentWeightTitle  = element.data( 'inherit' );
            var weightOptions       = '';
            var inheritWeightObject = [ 'inherit' ];
            var counter = 0;
            var weightObject        = $.merge( inheritWeightObject, weightObject );
            var weightValue         = element.val() || '400';
            astraTypo[ 'inherit' ] = currentWeightTitle;

            for ( ; counter < weightObject.length; counter++ ) {

                if ( 0 === counter && -1 === $.inArray( weightValue, weightObject ) ) {
                    weightValue = weightObject[ 0 ];
                    selected 	= ' selected="selected"';
                } else {
                    selected = weightObject[ counter ] == weightValue ? ' selected="selected"' : '';
                }
                if( ! weightObject[ counter ].includes( "italic" ) ){
                    weightOptions += '<option value="' + weightObject[ counter ] + '"' + selected + '>' + astraTypo[ weightObject[ counter ] ] + '</option>';
                }
            }
            
            element.html( weightOptions );
        },

        initResponsiveTrigger: function( wrap, control_elem ) {

            wrap.find('.ast-responsive-btns button').on('click', function (event) {

                var device = jQuery(this).attr('data-device');
                if ('desktop' == device) {
                    device = 'tablet';
                } else if ('tablet' == device) {
                    device = 'mobile';
                } else {
                    device = 'desktop';
                }

                jQuery('.wp-full-overlay-footer .devices button[data-device="' + device + '"]').trigger('click');
            });

        },

        initColor: function ( wrap, control_elem, name ) {

            var control = this;
            var picker = wrap.find('.customize-control-ast-color .ast-color-picker-alpha');

            picker.wpColorPicker({

                change: function (event, ui) {

                    if ('undefined' != typeof event.originalEvent || 'undefined' != typeof ui.color._alpha) {
                    
                        var element = jQuery(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker')[0];
                        jQuery(element).val( ui.color.toString() );
                        name = jQuery(element).parents('.customize-control').attr('id');
                        name = name.replace( 'customize-control-', '' );
                        control.container.trigger( 'ast_settings_changed', [control, jQuery( element ), ui.color.toString(), name ] );
                    }
                },

                /**
                 * @param {Event} event - standard jQuery event, produced by "Clear"
                 * button.
                 */
                clear: function (event) {
                    var element = jQuery(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker')[0];
                    jQuery(element).val('');

                    name = jQuery(element).parents('.customize-control').attr('id');
                    name = name.replace( 'customize-control-', '' );
                    control.container.trigger( 'ast_settings_changed', [control, jQuery(element), '', name ] );
                    wp.customize.previewer.refresh();
                }
            });
        },

        initResponsiveColor: function( wrap, control_elem, name ) {

            var control = this;
            var picker = wrap.find( '.ast-responsive-color' );

            picker.wpColorPicker({

                change: function(event, ui) {

                    if ('undefined' != typeof event.originalEvent || 'undefined' != typeof ui.color._alpha) {
                        if ( jQuery('html').hasClass('responsive-background-color-ready') ) {

                            var option_name = jQuery(this).data('name');
                            var stored = {
                                'desktop' : jQuery( ".desktop.ast-responsive-color[data-name='"+ option_name +"']" ).val(),
                                'tablet'  : jQuery( ".tablet.ast-responsive-color[data-name='"+ option_name +"']" ).val(),
                                'mobile'  : jQuery( ".mobile.ast-responsive-color[data-name='"+ option_name +"']" ).val()
                            };

                            var element = event.target;
                            var device = jQuery( this ).data( 'id' );
                            var newValue = {
                                'desktop' : stored['desktop'],
                                'tablet'  : stored['tablet'],
                                'mobile'  : stored['mobile'],
                            };
                            if ( 'desktop' === device ) {
                                newValue['desktop'] = ui.color.toString();
                            }
                            if ( 'tablet' === device ) {
                                newValue['tablet'] = ui.color.toString();
                            }
                            if ( 'mobile' === device ) {
                                newValue['mobile'] = ui.color.toString();
                            }

                            jQuery(element).val( ui.color.toString() );

                            name = jQuery(element).parents('.customize-control').attr('id');
                            name = name.replace( 'customize-control-', '' );

                            control.container.trigger( 'ast_settings_changed', [ control, jQuery(this), newValue, name ] );
                        }
                    }
                },

                    /**
                 * @param {Event} event - standard jQuery event, produced by "Clear"
                 * button.
                 */
                clear: function (event) {
                    var element = jQuery(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker')[0],
                        device = jQuery( this ).closest('.wp-picker-input-wrap').find('.wp-color-picker').data( 'id' );

                    var option_name = jQuery( element ).attr('data-name');
                    var stored = {
                        'desktop' : jQuery( ".desktop.ast-responsive-color[data-name='"+ option_name +"']" ).val(),
                        'tablet'  : jQuery( ".tablet.ast-responsive-color[data-name='"+ option_name +"']" ).val(),
                        'mobile'  : jQuery( ".mobile.ast-responsive-color[data-name='"+ option_name +"']" ).val()
                    };

                    var newValue = {
                        'desktop' : stored['desktop'],
                        'tablet'  : stored['tablet'],
                        'mobile'  : stored['mobile'],
                    };

                    wp.customize.previewer.refresh();

                    if ( element ) {
                        if ( 'desktop' === device ) {
                            newValue['desktop'] = '';
                        }
                        if ( 'tablet' === device ) {
                            newValue['tablet'] = '';
                        }
                        if ( 'mobile' === device ) {
                            newValue['mobile'] = '';
                        }

                        jQuery(element).val( '' );
                        control.container.trigger( 'ast_settings_changed', [ control, jQuery(element), newValue, name ] );
                    }

                    name = jQuery(element).parents('.customize-control').attr('id');
                    name = name.replace( 'customize-control-', '' );
                }
            });

            wrap.find( '.ast-responsive-btns button' ).on( 'click', function( event ) {

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

            // Set desktop colorpicker active.
            wrap.find( '.ast-responsive-color.desktop' ).parents( '.wp-picker-container' ).addClass( 'active' );
        },

        onOptionChange:function ( e, control, element, value, name ) {

            var control_id  = $( '.hidden-field-astra-settings-' + name );
            control_id.val( value );
            sub_control = wp.customize.control( "astra-settings[" + name + "]" );
            sub_control.setting.set( value );
        },

        /**
         * Updates the responsive param value.
         */
        updateResonsiveValue: function( element, name ) {

            'use strict';

            var control = this,
            newValue = {};

            // Set the spacing container.
            control.responsiveContainer = element.closest( '.ast-responsive-wrapper' );

            control.responsiveContainer.find( 'input.ast-responsive-input' ).each( function() {
                var responsive_input = jQuery( this ),
                item = responsive_input.data( 'id' ),
                item_value = responsive_input.val();

                newValue[item] = item_value;
            });

            control.responsiveContainer.find( 'select.ast-responsive-select' ).each( function() {
                var responsive_input = jQuery( this ),
                item = responsive_input.data( 'id' ),
                item_value = responsive_input.val();

                newValue[item] = item_value;
            });

            control.container.trigger( 'ast_settings_changed', [ control, element, newValue, name ] );
        },

        isJsonString: function( str ) {

            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        },   

        initAstResonsiveBgControl: function( control, control_atts, name ) {

            var value            = control_atts.value;
            var picker           = control.container.find('.ast-responsive-bg-color-control');
            var control_name     = control_atts.name;
            var controlContainer = control.container.find( "#customize-control-" + control_name );

            // Hide unnecessary controls if the value doesn't have an image.
            if (_.isUndefined(value['desktop']['background-image']) || '' === value['desktop']['background-image']) {   
                controlContainer.find('.background-wrapper > .background-container.desktop > .background-repeat').hide();
                controlContainer.find('.background-wrapper > .background-container.desktop > .background-position').hide();
                controlContainer.find('.background-wrapper > .background-container.desktop > .background-size').hide();
                controlContainer.find('.background-wrapper > .background-container.desktop > .background-attachment').hide();
            }
            if (_.isUndefined(value['tablet']['background-image']) || '' === value['tablet']['background-image']) {
                controlContainer.find('.background-wrapper > .background-container.tablet > .background-repeat').hide();
                controlContainer.find('.background-wrapper > .background-container.tablet > .background-position').hide();
                controlContainer.find('.background-wrapper > .background-container.tablet > .background-size').hide();
                controlContainer.find('.background-wrapper > .background-container.tablet > .background-attachment').hide();
            }
            if (_.isUndefined(value['mobile']['background-image']) || '' === value['mobile']['background-image']) {
                controlContainer.find('.background-wrapper > .background-container.mobile > .background-repeat').hide();
                controlContainer.find('.background-wrapper > .background-container.mobile > .background-position').hide();
                controlContainer.find('.background-wrapper > .background-container.mobile > .background-size').hide();
                controlContainer.find('.background-wrapper > .background-container.mobile > .background-attachment').hide();
            }

            // Color.
            picker.wpColorPicker({
                change: function (event, ui) {

                    if ('undefined' != typeof event.originalEvent || 'undefined' != typeof ui.color._alpha ) {
                        var device = jQuery(this).data('id');
                        control.saveValue( device, 'background-color', ui.color.toString(), jQuery(this), name );
                    }
                },

                /**
                 * @param {Event} event - standard jQuery event, produced by "Clear"
                 * button.
                 */
                clear: function (event) {
                    var element = jQuery(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker')[0],
                        responsive_input = jQuery(this),
                        screen = responsive_input.closest('.wp-picker-input-wrap').find('.wp-color-picker').data('id');

                    if ( element ) {
                        control.saveValue( screen, 'background-color', '', jQuery( element ), name );
                    }
                    wp.customize.previewer.refresh();
                }
            });

            // Background-Repeat.
            controlContainer.on('change', '.background-repeat select', function () {
                var responsive_input = jQuery(this),
                    screen = responsive_input.data('id'),
                    item_value = responsive_input.val();

                control.saveValue( screen, 'background-repeat', item_value, jQuery(this), name );
            });

            // Background-Size.
            controlContainer.on('change click', '.background-size input', function () {
                var responsive_input = jQuery(this),
                    screen = responsive_input.data('id'),
                    item_value = responsive_input.val();

                jQuery( this ).parent( '.buttonset' ).find( '.switch-input' ).removeAttr('checked');
                jQuery( this ).attr( 'checked', 'checked' );

                control.saveValue( screen, 'background-size', item_value, responsive_input, name );
            });

            // Background-Position.
            controlContainer.on( 'change', '.background-position select', function () {
                var responsive_input = jQuery(this),
                    screen = responsive_input.data('id'),
                    item_value = responsive_input.val();
                control.saveValue( screen, 'background-position', item_value, responsive_input, name );
            });

            // Background-Attachment.
            controlContainer.on('change click', '.background-attachment input', function () {
                var responsive_input = jQuery(this),
                    screen = responsive_input.data('id'),
                    item_value = responsive_input.val();

                jQuery( this ).parent( '.buttonset' ).find( '.switch-input' ).removeAttr('checked');
                jQuery( this ).attr( 'checked', 'checked' );

                control.saveValue( screen, 'background-attachment', item_value, responsive_input, name );
            });

            // Background-Image.
            controlContainer.on('click', '.background-image-upload-button, .thumbnail-image img', function (e) {
                var responsive_input = jQuery(this),
                    screen = responsive_input.data('id');
                    name = responsive_input.data('name');

                var image = wp.media({ multiple: false }).open().on('select', function () {

                    // This will return the selected image from the Media Uploader, the result is an object.
                    var uploadedImage = image.state().get('selection').first(),
                        previewImage = uploadedImage.toJSON().sizes.full.url,
                        imageUrl,
                        imageID,
                        imageWidth,
                        imageHeight,
                        preview,
                        removeButton;

                    if (!_.isUndefined(uploadedImage.toJSON().sizes.medium)) {
                        previewImage = uploadedImage.toJSON().sizes.medium.url;
                    } else if (!_.isUndefined(uploadedImage.toJSON().sizes.thumbnail)) {
                        previewImage = uploadedImage.toJSON().sizes.thumbnail.url;
                    }

                    imageUrl = uploadedImage.toJSON().sizes.full.url;
                    imageID = uploadedImage.toJSON().id;
                    imageWidth = uploadedImage.toJSON().width;
                    imageHeight = uploadedImage.toJSON().height;

                    // Show extra controls if the value has an image.
                    if ( '' !== imageUrl ) {
                        controlContainer.find('.background-wrapper > .background-repeat, .background-wrapper > .background-position, .background-wrapper > .background-size, .background-wrapper > .background-attachment').show();
                    }

                    control.saveValue( screen, 'background-image', imageUrl, responsive_input, name );
                    preview = controlContainer.find( '.background-container.' + screen + ' .placeholder, .background-container.' + screen + ' .thumbnail' );
                    removeButton = controlContainer.find('.background-container.' + screen + ' .background-image-upload-remove-button');

                    if ( preview.length ) {
                        preview.removeClass().addClass('thumbnail thumbnail-image').html('<img src="' + previewImage + '" alt="" data-id="'+screen+'" data-name="'+name+'"/>');
                    }
                    if ( removeButton.length ) {
                        removeButton.show();
                    }
                });

                e.preventDefault();
            });

            controlContainer.on('click', '.background-image-upload-remove-button', function (e) {

                var preview,
                    removeButton,
                    responsive_input = jQuery(this),
                    screen = responsive_input.data('id');

                control.saveValue( screen, 'background-image', '', jQuery(this), name );

                preview = controlContainer.find('.background-container.' + screen + ' .placeholder, .background-container.' + screen + ' .thumbnail');
                removeButton = controlContainer.find('.background-container.' + screen + ' .background-image-upload-remove-button');

                // Hide unnecessary controls.
                controlContainer.find('.background-wrapper > .background-container.' + screen + ' > .background-repeat').hide();
                controlContainer.find('.background-wrapper > .background-container.' + screen + ' > .background-position').hide();
                controlContainer.find('.background-wrapper > .background-container.' + screen + ' > .background-size').hide();
                controlContainer.find('.background-wrapper > .background-container.' + screen + ' > .background-attachment').hide();

                controlContainer.find('.background-container.' + screen + ' .more-settings').attr('data-direction', 'down');
                controlContainer.find('.background-container.' + screen + ' .more-settings').find('.message').html(astraCustomizerControlBackground.moreSettings);
                controlContainer.find('.background-container.' + screen + ' .more-settings').find('.icon').html('↓');

                if (preview.length) {
                    preview.removeClass().addClass('placeholder').html(astraCustomizerControlBackground.placeholder);
                }
                if (removeButton.length) {
                    removeButton.hide();
                }

                wp.customize.previewer.refresh();
                e.preventDefault();
            });

            controlContainer.on('click', '.more-settings', function (e) {

                var responsive_input = jQuery(this),
                    screen = responsive_input.data('id');
                // Hide unnecessary controls.
                controlContainer.find('.background-wrapper > .background-container.' + screen + ' > .background-repeat').toggle();
                controlContainer.find('.background-wrapper > .background-container.' + screen + ' > .background-position').toggle();
                controlContainer.find('.background-wrapper > .background-container.' + screen + ' > .background-size').toggle();
                controlContainer.find('.background-wrapper > .background-container.' + screen + ' > .background-attachment').toggle();

                if ('down' === $(this).attr('data-direction')) {
                    $(this).attr('data-direction', 'up');
                    $(this).find('.message').html(astraCustomizerControlBackground.lessSettings)
                    $(this).find('.icon').html('↑');
                } else {
                    $(this).attr('data-direction', 'down');
                    $(this).find('.message').html(astraCustomizerControlBackground.moreSettings)
                    $(this).find('.icon').html('↓');
                }
            });


            controlContainer.find('.ast-responsive-btns button').on('click', function (event) {

                var device = jQuery(this).attr('data-device');
                if ('desktop' == device) {
                    device = 'tablet';
                } else if ('tablet' == device) {
                    device = 'mobile';
                } else {
                    device = 'desktop';
                }

                jQuery('.wp-full-overlay-footer .devices button[data-device="' + device + '"]').trigger('click');
            });

            jQuery(' .wp-full-overlay-footer .devices button ').on('click', function () {

                var device = jQuery(this).attr('data-device');

                jQuery('.customize-control-ast-responsive-background .background-container, .customize-control .ast-responsive-btns > li').removeClass('active');
                jQuery('.customize-control-ast-responsive-background .background-container.' + device + ', .customize-control .ast-responsive-btns > li.' + device).addClass('active');
            });
        },

        initAstBgControl: function( control, control_atts, name ) {

            var value            = control.setting._value,
                control_name     = control_atts.name,
                picker           = control.container.find( '.ast-color-control' ),
                controlContainer = control.container.find( "#customize-control-" + control_name );

            // Hide unnecessary controls if the value doesn't have an image.
            if ( _.isUndefined( value['background-image']) || '' === value['background-image']) {
                controlContainer.find( '.background-wrapper > .background-repeat' ).hide();
                controlContainer.find( '.background-wrapper > .background-position' ).hide();
                controlContainer.find( '.background-wrapper > .background-size' ).hide();
                controlContainer.find( '.background-wrapper > .background-attachment' ).hide();
            }

            // Color.
            picker.wpColorPicker({
                change: function() {
                    if ( jQuery('html').hasClass('background-colorpicker-ready') ) {
                        var $this = jQuery(this);
                        setTimeout( function() {
                            control.saveBgValue( 'background-color', picker.val(), $this, name );
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
                        control.saveBgValue( 'background-color', '', jQuery(element), name );
                    }
                    wp.customize.previewer.refresh();
                }
            });

            // Background-Repeat.
            controlContainer.on( 'change', '.background-repeat select', function() {
                control.saveBgValue( 'background-repeat', jQuery( this ).val(), jQuery( this ), name );
            });

            // Background-Size.
            controlContainer.on( 'change click', '.background-size input', function() {
                jQuery( this ).parent( '.buttonset' ).find( '.switch-input' ).removeAttr('checked');
                jQuery( this ).attr( 'checked', 'checked' );
                control.saveBgValue( 'background-size', jQuery( this ).val(), jQuery( this ), name );
            });

            // Background-Position.
            controlContainer.on( 'change', '.background-position select', function() {
                control.saveBgValue( 'background-position', jQuery( this ).val(), jQuery( this ), name );
            });

            // Background-Attachment.
            controlContainer.on( 'change click', '.background-attachment input', function() {
                jQuery( this ).parent( '.buttonset' ).find( '.switch-input' ).removeAttr('checked');
                jQuery( this ).attr( 'checked', 'checked' );
                control.saveBgValue( 'background-attachment', jQuery( this ).val(), jQuery( this ), name );
            });

            // Background-Image.
            controlContainer.on( 'click', '.background-image-upload-button, .thumbnail-image img', function( e ) {
                var upload_img_btn = jQuery(this);
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
                        controlContainer.find( '.background-wrapper > .background-repeat, .background-wrapper > .background-position, .background-wrapper > .background-size, .background-wrapper > .background-attachment' ).show();
                    }

                    control.saveBgValue( 'background-image', imageUrl, upload_img_btn, name );
                    preview      = controlContainer.find( '.placeholder, .thumbnail' );
                    removeButton = controlContainer.find( '.background-image-upload-remove-button' );

                    if ( preview.length ) {
                        preview.removeClass().addClass( 'thumbnail thumbnail-image' ).html( '<img src="' + previewImage + '" alt="" />' );
                    }
                    if ( removeButton.length ) {
                        removeButton.show();
                    }
                });

                e.preventDefault();
            });

            controlContainer.on( 'click', '.background-image-upload-remove-button', function( e ) {

                var preview,
                    removeButton;

                e.preventDefault();

                control.saveBgValue( 'background-image', '', jQuery( this ) );

                preview      = controlContainer.find( '.placeholder, .thumbnail' );
                removeButton = controlContainer.find( '.background-image-upload-remove-button' );

                // Hide unnecessary controls.
                controlContainer.find( '.background-wrapper > .background-repeat' ).hide();
                controlContainer.find( '.background-wrapper > .background-position' ).hide();
                controlContainer.find( '.background-wrapper > .background-size' ).hide();
                controlContainer.find( '.background-wrapper > .background-attachment' ).hide();
                
                controlContainer.find( '.more-settings' ).attr('data-direction', 'down');
                controlContainer.find( '.more-settings' ).find('.message').html( astraCustomizerControlBackground.moreSettings );
                controlContainer.find( '.more-settings' ).find('.icon').html( '↓' );

                if ( preview.length ) {
                    preview.removeClass().addClass( 'placeholder' ).html( astraCustomizerControlBackground.placeholder );
                }
                if ( removeButton.length ) {
                    removeButton.hide();
                }
            });

            controlContainer.on( 'click', '.more-settings', function( e ) {
                // Hide unnecessary controls.
                controlContainer.find( '.background-wrapper > .background-repeat' ).toggle();
                controlContainer.find( '.background-wrapper > .background-position' ).toggle();
                controlContainer.find( '.background-wrapper > .background-size' ).toggle();
                controlContainer.find( '.background-wrapper > .background-attachment' ).toggle();

                if( 'down' === $(this).attr( 'data-direction' ) )
                {
                    $(this).attr('data-direction', 'up');
                    $(this).find('.message').html( astraCustomizerControlBackground.lessSettings )
                    $(this).find('.icon').html( '↑' );
                } else {
                    $(this).attr('data-direction', 'down');
                    $(this).find('.message').html( astraCustomizerControlBackground.moreSettings )
                    $(this).find('.icon').html( '↓' );
                }
            });
        },

        saveValue: function ( screen, property, value, element, name ) {

            var control = this,
                input = jQuery('#customize-control-' + control.id.replace('[', '-').replace(']', '') + ' .responsive-background-hidden-value'); 

            var val = JSON.parse( input.val() );
            val[screen][property] = value;

            jQuery(input).attr( 'value', JSON.stringify(val) ).trigger( 'change' );

            name = jQuery(element).parents('.customize-control').attr('id');
            name = name.replace( 'customize-control-', '' );
            control.container.trigger( 'ast_settings_changed', [control, element, val, name ] );
        },

        /**
         * Saves the value.
         */
        saveBgValue: function( property, value, element, name ) {

            var control = this,
                input   = jQuery( '#customize-control-' + control.id.replace( '[', '-' ).replace( ']', '' ) + ' .background-hidden-value' );

            var val = JSON.parse( input.val() );

            val[ property ] = value;

            jQuery( input ).attr( 'value', JSON.stringify( val ) ).trigger( 'change' );

            name = jQuery(element).parents('.customize-control').attr('id');
            name = name.replace( 'customize-control-', '' );
            control.container.trigger( 'ast_settings_changed', [control, element, val, name ] );
        },

        /**
         * Saves the value.
         */
        saveBorderValue: function( property, value, element, name ) {

            var control = this,
                newValue = {
                    'top'   : '',
                    'right' : '',
                    'bottom' : '',
                    'left'   : '',
                };


            control.container.find( 'input.ast-border-desktop' ).each( function() {
                var spacing_input = jQuery( this ),
                    item          = spacing_input.data( 'id' );

                item_value = spacing_input.val();
                newValue[ item ] = item_value;
                
                spacing_input.attr( 'value', item_value );

            });

            
            control.container.trigger( 'ast_settings_changed', [control, element, newValue, name ] );
        }
    });

})(jQuery);

( function( $ ) {
	/**
	 * File slider.js
	 *
	 * Handles Slider control
	 *
	 * @package Astra
	 */

	wp.customize.controlConstructor['ast-slider'] = wp.customize.Control.extend({

		ready: function() {

			'use strict';

			var control = this,
				value,
				thisInput,
				inputDefault,
				changeAction;

			// Update the text value.
			jQuery( 'input[type=range]' ).on( 'input change', function() {
				var value 		 = jQuery( this ).attr( 'value' ),
					input_number = jQuery( this ).closest( '.wrapper' ).find( '.astra_range_value .value' );

				input_number.val( value );
				input_number.change();
			});

			// Handle the reset button.
			jQuery( '.ast-slider-reset' ).click( function() {
				var wrapper 		= jQuery( this ).closest( '.wrapper' ),
					input_range   	= wrapper.find( 'input[type=range]' ),
					input_number 	= wrapper.find( '.astra_range_value .value' ),
					default_value	= input_range.data( 'reset_value' );

				input_range.val( default_value );
				input_number.val( default_value );
				input_number.change();
			});

			// Save changes.
			this.container.on( 'input change', 'input[type=number]', function() {
				var value = jQuery( this ).val();
				jQuery( this ).closest( '.wrapper' ).find( 'input[type=range]' ).val( value );
				control.setting.set( value );
			});
		}
	});
})(jQuery);

( function( $ ) {
	/**
	 * File sortable.js
	 *
	 * Handles sortable list
	 *
	 * @package Astra
	 */

	wp.customize.controlConstructor['ast-sortable'] = wp.customize.Control.extend({

		ready: function() {

			'use strict';

			var control = this;

			// Set the sortable container.
			control.sortableContainer = control.container.find( 'ul.sortable' ).first();

			// Init sortable.
			control.sortableContainer.sortable({

				// Update value when we stop sorting.
				stop: function() {
					control.updateValue();
				}
			}).disableSelection().find( 'li' ).each( function() {

					// Enable/disable options when we click on the eye of Thundera.
					jQuery( this ).find( 'i.visibility' ).click( function() {
						jQuery( this ).toggleClass( 'dashicons-visibility-faint' ).parents( 'li:eq(0)' ).toggleClass( 'invisible' );
					});
			}).click( function() {

				// Update value on click.
				control.updateValue();
			});
		},

		/**
		 * Updates the sorting list
		 */
		updateValue: function() {

			'use strict';

			var control = this,
		    newValue = [];

			this.sortableContainer.find( 'li' ).each( function() {
				if ( ! jQuery( this ).is( '.invisible' ) ) {
					newValue.push( jQuery( this ).data( 'value' ) );
				}
			});

			control.setting.set( newValue );
		}
	});
})(jQuery);

/**
 * File typography.js
 *
 * Handles Typography of the site
 *
 * @package Astra
 */

( function( $ ) {

	/* Internal shorthand */
	var api = wp.customize;

	/**
	 * Helper class for the main Customizer interface.
	 *
	 * @since 1.0.0
	 * @class AstTypography
	 */
	AstTypography = {

		/**
		 * Initializes our custom logic for the Customizer.
		 *
		 * @since 1.0.0
		 * @method init
		 */
		init: function() {
			AstTypography._initFonts();
		},

		/**
		 * Initializes logic for font controls.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _initFonts
		 */
		_initFonts: function()
		{
			$( '.customize-control-ast-font-family select' ).each( function(e) {

				if( 'undefined' != typeof astra.customizer ) {
					var fonts = astra.customizer.settings.google_fonts;
					var optionName = $(this).data('name');

					$(this).html( fonts );

					// Set inherit option text defined in control parameters.
					$("select[data-name='" + optionName + "'] option[value='inherit']").text( $(this).data('inherit') );

					var font_val = $(this).data('value');

					$(this).val( font_val );
				}
			});

			$( '.customize-control-ast-font-family select' ).each( AstTypography._initFont );
			// Added select2 for all font family & font variant.
			$('.customize-control-ast-font-family select, .customize-control-ast-font-variant select').selectWoo();

			$('.customize-control-ast-font-variant select').on('select2:unselecting', function (e) {
				var variantSelect = $(this).data( 'customize-setting-link' ),
				    unselectedValue = e.params.args.data.id || '';

				if ( unselectedValue ) {
					$(this).find('option[value="' + e.params.args.data.id + '"]').removeAttr('selected');
					if ( null === $(this).val() ) {
						api( variantSelect ).set( '' );
					}
				}
			});
		},

		/**
		 * Initializes logic for a single font control.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _initFont
		 */
		_initFont: function()
		{
			var select  = $( this ),
			link    = select.data( 'customize-setting-link' ),
			weight  = select.data( 'connected-control' ),
			variant  = select.data( 'connected-variant' );

			if ( 'undefined' != typeof weight ) {
				api( link ).bind( AstTypography._fontSelectChange );
				AstTypography._setFontWeightOptions.apply( api( link ), [ true ] );
			}

			if ( 'undefined' != typeof variant ) {
				api( link ).bind( AstTypography._fontSelectChange );
				AstTypography._setFontVarianttOptions.apply( api( link ), [ true ] );
			}
		},

		/**
		 * Callback for when a font control changes.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _fontSelectChange
		 */
		_fontSelectChange: function()
		{
			var fontSelect          = api.control( this.id ).container.find( 'select' ),
			variants            	= fontSelect.data( 'connected-variant' );

			AstTypography._setFontWeightOptions.apply( this, [ false ] );
			
			if ( 'undefined' != typeof variants ) {
				AstTypography._setFontVarianttOptions.apply( this, [ false ] );
			}
		},

		/**
		 * Clean font name.
		 *
		 * Google Fonts are saved as {'Font Name', Category}. This function cleanes this up to retreive only the {Font Name}.
		 *
		 * @since  1.3.0
		 * @param  {String} fontValue Name of the font.
		 * 
		 * @return {String}  Font name where commas and inverted commas are removed if the font is a Google Font.
		 */
		_cleanGoogleFonts: function(fontValue)
		{
			// Bail if fontVAlue does not contain a comma.
			if ( ! fontValue.includes(',') ) return fontValue;

			var splitFont 	= fontValue.split(',');
			var pattern 	= new RegExp("'", 'gi');

			// Check if the cleaned font exists in the Google fonts array.
			var googleFontValue = splitFont[0].replace(pattern, '');
			if ( 'undefined' != typeof AstFontFamilies.google[ googleFontValue ] ) {
				fontValue = googleFontValue;
			}

			return fontValue;
		},

		/**
		 * Get font Weights.
		 *
		 * This function gets the font weights values respective to the selected fonts family{Font Name}.
		 *
		 * @since  1.5.2
		 * @param  {String} fontValue Name of the font.
		 * 
		 * @return {String}  Available font weights for the selected fonts.
		 */
		_getWeightObject: function(fontValue)
		{
			var weightObject        = [ '400', '600' ];
			if ( fontValue == 'inherit' ) {
				weightObject = [ '100','200','300','400','500','600','700','800','900' ];
			} else if ( 'undefined' != typeof AstFontFamilies.system[ fontValue ] ) {
				weightObject = AstFontFamilies.system[ fontValue ].weights;
			} else if ( 'undefined' != typeof AstFontFamilies.google[ fontValue ] ) {
				weightObject = AstFontFamilies.google[ fontValue ][0];
				weightObject = Object.keys(weightObject).map(function(k) {
				  return weightObject[k];
				});
			} else if ( 'undefined' != typeof AstFontFamilies.custom[ fontValue ] ) {
				weightObject = AstFontFamilies.custom[ fontValue ].weights;
			}

			return weightObject;
		},

		/**
		 * Sets the options for a font weight control when a
		 * font family control changes.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _setFontWeightOptions
		 * @param {Boolean} init Whether or not we're initializing this font weight control.
		 */
		_setFontWeightOptions: function( init )
		{
			var i               = 0,
			fontSelect          = api.control( this.id ).container.find( 'select' ),
			fontValue           = this(),
			selected            = '',
			weightKey           = fontSelect.data( 'connected-control' ),
			inherit             = fontSelect.data( 'inherit' ),
			weightSelect        = api.control( weightKey ).container.find( 'select' ),
			currentWeightTitle  = weightSelect.data( 'inherit' ),
			weightValue         = init ? weightSelect.val() : '400',
			inheritWeightObject = [ 'inherit' ],
			weightObject        = [ '400', '600' ],
			weightOptions       = '',
			weightMap           = astraTypo;
			if ( fontValue == 'inherit' ) {
				weightValue     = init ? weightSelect.val() : 'inherit';
			}

			var fontValue = AstTypography._cleanGoogleFonts(fontValue);
			var weightObject = AstTypography._getWeightObject( fontValue );

			weightObject = $.merge( inheritWeightObject, weightObject )
			weightMap[ 'inherit' ] = currentWeightTitle;
			for ( ; i < weightObject.length; i++ ) {

				if ( 0 === i && -1 === $.inArray( weightValue, weightObject ) ) {
					weightValue = weightObject[ 0 ];
					selected 	= ' selected="selected"';
				} else {
					selected = weightObject[ i ] == weightValue ? ' selected="selected"' : '';
				}
				if( ! weightObject[ i ].includes( "italic" ) ){
					weightOptions += '<option value="' + weightObject[ i ] + '"' + selected + '>' + weightMap[ weightObject[ i ] ] + '</option>';
				}
			}

			weightSelect.html( weightOptions );

			if ( ! init ) {
				api( weightKey ).set( '' );
				api( weightKey ).set( weightValue );
			}
		},
		/**
		 * Sets the options for a font variant control when a
		 * font family control changes.
		 *
		 * @since 1.5.2
		 * @access private
		 * @method _setFontVarianttOptions
		 * @param {Boolean} init Whether or not we're initializing this font variant control.
		 */
		_setFontVarianttOptions: function( init )
		{
				var i               = 0,
				fontSelect          = api.control( this.id ).container.find( 'select' ),
				fontValue           = this(),
				selected            = '',
				variants            = fontSelect.data( 'connected-variant' ),
				inherit             = fontSelect.data( 'inherit' ),
				variantSelect       = api.control( variants ).container.find( 'select' ),
				variantSavedField   = api.control( variants ).container.find( '.ast-font-variant-hidden-value' ),
				weightValue        = '',
				weightOptions       = '',
				currentWeightTitle  = variantSelect.data( 'inherit' ),
				weightMap           = astraTypo;

				var variantArray = variantSavedField.val().split(',');

				// Hide font variant for any ohter fonts then Google
				var selectedOptionGroup = fontSelect.find('option[value="' + fontSelect.val() + '"]').closest('optgroup').attr('label') || '';
				if ( 'Google' == selectedOptionGroup ) {
					variantSelect.parent().removeClass('ast-hide');
				} else{
					variantSelect.parent().addClass('ast-hide');
				}

				var fontValue = AstTypography._cleanGoogleFonts(fontValue);
				var weightObject = AstTypography._getWeightObject( fontValue );

				weightMap[ 'inherit' ] = currentWeightTitle;
				
				for ( var i = 0; i < weightObject.length; i++ ) {
					for ( var e = 0; e < variantArray.length; e++ ) {
						if ( weightObject[i] === variantArray[e] ) {
							weightValue = weightObject[ i ];
							selected 	= ' selected="selected"';
						} else{
							selected = ( weightObject[ i ] == weightValue ) ? ' selected="selected"' : '';
						}
					}
					weightOptions += '<option value="' + weightObject[ i ] + '"' + selected + '>' + weightMap[ weightObject[ i ] ] + '</option>';
				}

				variantSelect.html( weightOptions );
				if ( ! init ) {
					api( variants ).set( '' );
				}
		},
	};

	$( function() { AstTypography.init(); } );

})( jQuery );
