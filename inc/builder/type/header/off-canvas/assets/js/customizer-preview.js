/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra
 * @since 3.0.0
 */

( function( $ ) {
	// Close Icon Color.
	astra_css(
		'astra-settings[off-canvas-close-color]',
		'color',
		'.ast-mobile-popup-drawer.active .ast-mobile-popup-inner',
	);

	// Off-Canvas Background Color.
	wp.customize( 'astra-settings[off-canvas-background]', function( value ) {
		value.bind( function( bg_obj ) {
			var dynamicStyle = ' .ast-mobile-popup-drawer.active .ast-mobile-popup-inner, .ast-mobile-header-wrap .ast-mobile-header-content { {{css}} }';
			astra_background_obj_css( wp.customize, bg_obj, 'off-canvas-background', dynamicStyle );
		} );
	} );

	wp.customize( 'astra-settings[off-canvas-inner-spacing]', function ( value ) {
        value.bind( function ( spacing ) {
			var dynamicStyle = '';
			if( spacing != '' ) {
				dynamicStyle += '.ast-mobile-popup-content > *, .ast-mobile-header-content > * {';
				dynamicStyle += 'padding-top: ' + spacing + 'px;';
				dynamicStyle += 'padding-bottom: ' + spacing + 'px;';
				dynamicStyle += '} ';
			}
			astra_add_dynamic_css( 'off-canvas-inner-spacing', dynamicStyle );
        } );
	} );

	wp.customize( 'astra-settings[mobile-header-type]', function ( value ) {
        value.bind( function ( newVal ) {

			var mobile_header = document.querySelectorAll( "#ast-mobile-header" );
			var header_type = newVal;
			var off_canvas_slide = ( typeof ( wp.customize._value['astra-settings[off-canvas-slide]'] ) != 'undefined' ) ? wp.customize._value['astra-settings[off-canvas-slide]']._value : 'right';

			var side_class = '';

			if ( 'off-canvas' === header_type ) {

				if ( 'left' === off_canvas_slide ) {

					side_class = 'ast-mobile-popup-left';
				} else {

					side_class = 'ast-mobile-popup-right';
				}
			} else if ( 'full-width' === header_type ) {

				side_class = 'ast-mobile-popup-full-width';
			}

			jQuery('.ast-mobile-popup-drawer').removeClass( 'ast-mobile-popup-left' );
			jQuery('.ast-mobile-popup-drawer').removeClass( 'ast-mobile-popup-right' );
			jQuery('.ast-mobile-popup-drawer').removeClass( 'ast-mobile-popup-full-width' );
			jQuery('.ast-mobile-popup-drawer').addClass( side_class );

			if( 'full-width' === header_type ) {

				header_type = 'off-canvas';
			}

			for ( var k = 0; k < mobile_header.length; k++ ) {
				mobile_header[k].setAttribute( 'data-type', header_type );
			}

			var event = new CustomEvent( "astMobileHeaderTypeChange",
				{
					"detail": { 'type' : header_type }
				}
			);

			document.dispatchEvent(event);
        } );
	} );

	wp.customize( 'astra-settings[off-canvas-slide]', function ( value ) {
        value.bind( function ( newval ) {

			var side_class = '';

			if ( 'left' === newval ) {

				side_class = 'ast-mobile-popup-left';
			} else {

				side_class = 'ast-mobile-popup-right';
			}

			jQuery('.ast-mobile-popup-drawer').removeClass( 'ast-mobile-popup-left' );
			jQuery('.ast-mobile-popup-drawer').removeClass( 'ast-mobile-popup-right' );
			jQuery('.ast-mobile-popup-drawer').removeClass( 'ast-mobile-popup-full-width' );
			jQuery('.ast-mobile-popup-drawer').addClass( side_class );
        } );
	} );
	wp.customize( 'astra-settings[header-builder-menu-toggle-target]', function ( value ) {
        value.bind( function ( newval ) {
			var menuTargetClass   = 'ast-builder-menu-toggle-' + newval + ' ';

			jQuery( '.site-header' ).removeClass( 'ast-builder-menu-toggle-icon' );
			jQuery( '.site-header' ).removeClass( 'ast-builder-menu-toggle-link' );
			jQuery( '.site-header' ).addClass( menuTargetClass );
		} );
	} );
	wp.customize( 'astra-settings[header-offcanvas-content-alignment]', function ( value ) {
        value.bind( function ( newval ) {

			var alignment_class   = 'content-align-' + newval + ' ';
			var menu_content_alignment = 'center';

			jQuery('.ast-mobile-header-content').removeClass( 'content-align-flex-start' );
			jQuery('.ast-mobile-header-content').removeClass( 'content-align-flex-end' );
			jQuery('.ast-mobile-header-content').removeClass( 'content-align-center' );
			jQuery('.ast-mobile-popup-drawer').removeClass( 'content-align-flex-end' );
			jQuery('.ast-mobile-popup-drawer').removeClass( 'content-align-flex-start' );
			jQuery('.ast-mobile-popup-drawer').removeClass( 'content-align-center' );
			jQuery('.ast-mobile-header-content').addClass( alignment_class );
			jQuery('.ast-mobile-popup-drawer').addClass( alignment_class );

			if ( 'flex-start' === newval ) {
				menu_content_alignment = 'left';
			} else if ( 'flex-end' === newval ) {
				menu_content_alignment = 'right';
			}

			var dynamicStyle = '.content-align-' + newval + ' .ast-builder-layout-element {';
			dynamicStyle += 'justify-content: ' + newval + ';';
			dynamicStyle += '} ';

			dynamicStyle += '.content-align-' + newval + ' .main-header-menu {';
			dynamicStyle += 'text-align: ' + menu_content_alignment + ';';
			dynamicStyle += '} ';

			astra_add_dynamic_css( 'header-offcanvas-content-alignment', dynamicStyle );
        } );
	} );

} )( jQuery );
