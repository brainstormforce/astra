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
