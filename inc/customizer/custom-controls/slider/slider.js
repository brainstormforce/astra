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
