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