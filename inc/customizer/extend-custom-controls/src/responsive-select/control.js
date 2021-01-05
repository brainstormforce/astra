import ResponsiveSelectComponent from './responsive-select-component.js';

export const responsiveSelectControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <ResponsiveSelectComponent control={ control } />, control.container[0] );
	},
	ready: function() {

		'use strict';

		let device = jQuery('.wp-full-overlay-footer .devices button.active').attr('data-device')

		jQuery( '.customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container' ).removeClass( 'active' );

		jQuery( '.customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container.' + device ).addClass( 'active' );

		jQuery( '.customize-control-ast-responsive-select .ast-responsive-btns li' ).removeClass( 'active' );

		jQuery( '.customize-control-ast-responsive-select .ast-responsive-btns li.' + device ).addClass( 'active' );

		jQuery('.wp-full-overlay-footer .devices button').on('click', function() {

			let currentDevice = jQuery(this).attr('data-device');

			jQuery( '.customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container' ).removeClass( 'active' );
			jQuery( '.customize-control-ast-responsive-select .customize-control-content .ast-responsive-select-container.' + currentDevice ).addClass( 'active' );
			jQuery( '.customize-control-ast-responsive-select .ast-responsive-btns li' ).removeClass( 'active' );
			jQuery( '.customize-control-ast-responsive-select .ast-responsive-btns li.' + currentDevice ).addClass( 'active' );
		});

		this.container.find( '.ast-responsive-btns button' ).on( 'click', function( event ) {

			let respBtnDevice = jQuery(this).attr('data-device');
			if( 'desktop' == respBtnDevice ) {
				respBtnDevice = 'tablet';
			} else if( 'tablet' == respBtnDevice ) {
				respBtnDevice = 'mobile';
			} else {
				respBtnDevice = 'desktop';
			}

			jQuery( '.wp-full-overlay-footer .devices button[data-device="' + respBtnDevice + '"]' ).trigger( 'click' );
		});
	},
} );
