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
			this.container.on( 'change keyup paste', 'input.ast-responsive-input', function() {

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

			control.setting.set( newValue );
		},

		astResponsiveInit : function() {
			jQuery( '.customize-control .ast-responsive-btns button' ).on( 'click', function( event ) {

				var device = jQuery(this).attr('data-device');
				jQuery( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
			});

			jQuery(' .wp-full-overlay-footer .devices button ').on('click', function() {

				var device = jQuery(this).attr('data-device');

				jQuery( '.customize-control .ast-responsive-btns button, .customize-control-ast-responsive .input-wrapper input' ).removeClass( 'active' );
				jQuery( '.customize-control .ast-responsive-btns button[data-device="' + device + '"], .customize-control-ast-responsive .input-wrapper input.' + device ).addClass( 'active' );				
			});
		},
	});
