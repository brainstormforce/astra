import SortableComponent from './sortable.js';

export const sortableControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
	ReactDOM.render( <SortableComponent control={ control } />, control.container[0] );
	},
	ready: function() {

		'use strict';

		let control = this;

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

		let control = this,
		choices = control.params.choices,
		newValue = [];

		this.sortableContainer.find( 'li' ).each( function() {
			if ( ! jQuery( this ).is( '.invisible' ) ) {
				newValue.push( jQuery( this ).data( 'value' ) );
			}
		});

		control.setting.set( newValue );
	}
} );