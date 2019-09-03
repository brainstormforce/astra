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
