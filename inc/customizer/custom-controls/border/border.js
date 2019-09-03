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
