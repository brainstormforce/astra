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
			jQuery( 'input[type=range]' ).on( 'mousedown', function() {
				value = jQuery( this ).attr( 'value' );
				jQuery( this ).mousemove( function() {
					value = jQuery( this ).attr( 'value' );
					jQuery( this ).closest( 'label' ).find( '.astra_range_value .value' ).val( value );
				});
			});

			jQuery( '.ast-range-value-input' ).on('keyup change', function(){
				thisInput    = jQuery( this ).closest( 'label' ).find( 'input[type=range]' );
				value = jQuery( this ).attr( 'value' );
				thisInput.val( value );
				thisInput.change();
			});

			// Handle the reset button.
			jQuery( '.ast-slider-reset' ).click( function() {
				thisInput    = jQuery( this ).closest( 'label' ).find( 'input[type=range]' );
				inputDefault = thisInput.data( 'reset_value' );
				thisInput.val( inputDefault );
				thisInput.change();
				jQuery( this ).closest( 'label' ).find( '.astra_range_value .value' ).val( inputDefault );
			});

			if ( 'postMessage' === control.setting.transport ) {
				changeAction = 'mousemove change';
			} else {
				changeAction = 'change';
			}

				// Save changes.
				this.container.on( changeAction, 'input[type=range]', function() {
					control.setting.set( jQuery( this ).val() );
				});
		}

	});
