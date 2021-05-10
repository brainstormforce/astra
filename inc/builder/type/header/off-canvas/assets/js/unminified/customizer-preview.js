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

	var tablet_break_point    = astraBuilderPreview.tablet_break_point || 768,
		mobile_break_point    = astraBuilderPreview.mobile_break_point || 544;

	// Close Icon Color.
	astra_css(
		'astra-settings[off-canvas-close-color]',
		'color',
		'.ast-mobile-popup-drawer.active .menu-toggle-close'
	);

	// Off-Canvas Background Color.
	wp.customize( 'astra-settings[off-canvas-background]', function( value ) {
		value.bind( function( bg_obj ) {
			var dynamicStyle = ' .ast-mobile-popup-drawer.active .ast-mobile-popup-inner, .ast-mobile-header-wrap .ast-mobile-header-content, .ast-desktop-header-content { {{css}} }';
			astra_background_obj_css( wp.customize, bg_obj, 'off-canvas-background', dynamicStyle );
		} );
	} );

	wp.customize( 'astra-settings[off-canvas-inner-spacing]', function ( value ) {
        value.bind( function ( spacing ) {
			var dynamicStyle = '';
			if( spacing != '' ) {
				dynamicStyle += '.ast-mobile-popup-content > *, .ast-mobile-header-content > *, .ast-desktop-popup-content > *, .ast-desktop-header-content > * {';
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
			var desktop_header = document.querySelectorAll( "#ast-desktop-header" );
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
			for ( var k = 0; k < desktop_header.length; k++ ) {
				desktop_header[k].setAttribute( 'data-type', header_type );
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

    // Padding.
    wp.customize( 'astra-settings[off-canvas-padding]', function( value ) {
        value.bind( function( padding ) {
            if(
                padding.desktop.bottom != '' || padding.desktop.top != '' || padding.desktop.left != '' || padding.desktop.right != '' ||
                padding.tablet.bottom != '' || padding.tablet.top != '' || padding.tablet.left != '' || padding.tablet.right != '' ||
                padding.mobile.bottom != '' || padding.mobile.top != '' || padding.mobile.left != '' || padding.mobile.right != ''
            ) {
                var dynamicStyle = '';
                dynamicStyle += '.ast-mobile-popup-drawer.active .ast-desktop-popup-content, .ast-mobile-popup-drawer.active .ast-mobile-popup-content {';
                dynamicStyle += 'padding-left: ' + padding['desktop']['left'] + padding['desktop-unit'] + ';';
                dynamicStyle += 'padding-right: ' + padding['desktop']['right'] + padding['desktop-unit'] + ';';
                dynamicStyle += 'padding-top: ' + padding['desktop']['top'] + padding['desktop-unit'] + ';';
                dynamicStyle += 'padding-bottom: ' + padding['desktop']['bottom'] + padding['desktop-unit'] + ';';
                dynamicStyle += '} ';

                dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
                dynamicStyle += '.ast-mobile-popup-drawer.active .ast-desktop-popup-content, .ast-mobile-popup-drawer.active .ast-mobile-popup-content {';
                dynamicStyle += 'padding-left: ' + padding['tablet']['left'] + padding['tablet-unit'] + ';';
                dynamicStyle += 'padding-right: ' + padding['tablet']['right'] + padding['tablet-unit'] + ';';
                dynamicStyle += 'padding-top: ' + padding['tablet']['top'] + padding['tablet-unit'] + ';';
                dynamicStyle += 'padding-bottom: ' + padding['tablet']['bottom'] + padding['tablet-unit'] + ';';
                dynamicStyle += '} ';
                dynamicStyle += '} ';

                dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
                dynamicStyle += '.ast-mobile-popup-drawer.active .ast-desktop-popup-content, .ast-mobile-popup-drawer.active .ast-mobile-popup-content {';
                dynamicStyle += 'padding-left: ' + padding['mobile']['left'] + padding['mobile-unit'] + ';';
                dynamicStyle += 'padding-right: ' + padding['mobile']['right'] + padding['mobile-unit'] + ';';
                dynamicStyle += 'padding-top: ' + padding['mobile']['top'] + padding['mobile-unit'] + ';';
                dynamicStyle += 'padding-bottom: ' + padding['mobile']['bottom'] + padding['mobile-unit'] + ';';
                dynamicStyle += '} ';
                dynamicStyle += '} ';
                astra_add_dynamic_css( 'off-canvas-padding', dynamicStyle );
            } else {
                astra_add_dynamic_css( 'off-canvas-padding', '' );
            }
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
			jQuery('.ast-desktop-header-content').removeClass( 'content-align-flex-start' );
			jQuery('.ast-desktop-header-content').removeClass( 'content-align-flex-end' );
			jQuery('.ast-desktop-header-content').removeClass( 'content-align-center' );
			
			jQuery('.ast-desktop-header-content').addClass( alignment_class );
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
