/**
 * Extending Customizer Control wp.customize.Control.
 *
 * @since 2.6.0
 */
export const coreControl = wp.customize.astraControl = wp.customize.Control.extend( {
	/**
	 * A Customizer Control.
	 *
	 * A control provides a UI element that allows a user to modify a Customizer Setting.
	 * Overriding this method to provide lazy loading of controls by initializing all the controls.
	 *
	 * @see PHP class WP_Customize_Control.
	 *
	 * @file wp-admin/js/customize-nav-menus.js
	 *
	 * @constructs wp.customize.Control
	 * @augments   wp.customize.Class
	 *
	 * @since 2.6.0
	 *
	 * @return {void}
	 */
	initialize: function( id, options ) {
		let control = this,
			args    = options || {};

		args.params = args.params || {};
		if ( ! args.params.type ) {
			args.params.type = 'ast-core';
		}
		if ( ! args.params.content ) {
			args.params.content = jQuery( '<li></li>' );
			args.params.content.attr( 'id', 'customize-control-' + id.replace( /]/g, '' ).replace( /\[/g, '-' ) );
			args.params.content.attr( 'class', 'customize-control customize-control-' + args.params.type );
		}

		control.propertyElements = [];
		wp.customize.Control.prototype.initialize.call( control, id, args );
	},

	/**
	 * Triggered when the control's markup has been injected into the DOM.
	 * Injecting markup from component based controls.
	 *
	 * @file wp-admin/js/customize-nav-menus.js
	 *
	 * @since 2.6.0
	 *
	 * @returns {void}
	 */
	ready: function() {
		let control = this;
		wp.customize.Control.prototype.ready.call( control );
		control.deferred.embedded.done();
	},

	/**
	 * Override the embed() method to do nothing,
	 * so that the control isn't embedded on load,
	 * unless the containing section is already expanded.
	 *
	 * @file wp-admin/js/customize-nav-menus.js
	 *
	 * @since 2.6.0
	 *
	 * @returns {void}
	 */
	embed: function() {
		let control   = this,
			sectionId = control.section();

		if ( ! sectionId ) {
			return;
		}

		wp.customize.section( sectionId, function( section ) {
			if ( section.expanded() || wp.customize.settings.autofocus.control === control.id ) {
				control.actuallyEmbed();
			} else {
				section.expanded.bind( function( expanded ) {
					if ( expanded ) {
						control.actuallyEmbed();
					}
				} );
			}
		} );
	},

	/**
	 * This function is called in control.embed() & control.focus() so the control
	 * will only get embedded when the Section is first expanded.
	 *
	 * @file wp-admin/js/customize-nav-menus.js
	 *
	 * @since 2.6.0
	 *
	 * @returns {void}
	 */
	actuallyEmbed: function() {
		let control = this;
		if ( 'resolved' === control.deferred.embedded.state() ) {
			return;
		}
		control.renderContent();
		control.deferred.embedded.resolve(); // This triggers control.ready().
	},

	/**
	 * Expand the containing section and focus on the control.
	 *
	 * @file wp-admin/js/customize-nav-menus.js
	 *
	 * @since 2.6.0
	 *
	 * @param {Object}   [params] - Params object.
	 * @param {Function} [params.completeCallback] - Optional callback function when focus has completed.
	 */
	focus: function( params ) {
		let control = this;
		control.actuallyEmbed();
		wp.customize.Control.prototype.focus.call( control, params );
	},

} );
