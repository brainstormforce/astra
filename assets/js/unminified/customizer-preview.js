/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra
 */

/**
 * Generate font size in PX & REM
 */
function astra_font_size_rem( size, with_rem, device ) {

	var css = '';

	if( size != '' ) {

		var device = ( typeof device != undefined ) ? device : 'desktop';

		// font size with 'px'.
		css = 'font-size: ' + size + 'px;';

		// font size with 'rem'.
		if ( with_rem ) {
			var body_font_size = wp.customize( 'astra-settings[font-size-body]' ).get();

			body_font_size['desktop'] 	= ( body_font_size['desktop'] != '' ) ? body_font_size['desktop'] : 15;
			body_font_size['tablet'] 	= ( body_font_size['tablet'] != '' ) ? body_font_size['tablet'] : body_font_size['desktop'];
			body_font_size['mobile'] 	= ( body_font_size['mobile'] != '' ) ? body_font_size['mobile'] : body_font_size['tablet'];

			css += 'font-size: ' + ( size / body_font_size[device] ) + 'rem;';
		}
	}

	return css;
}

/**
 * Responsive Font Size CSS
 */
function astra_responsive_font_size( control, selector ) {

	wp.customize( control, function( value ) {
		value.bind( function( value ) {

			if ( value.desktop || value.mobile || value.tablet ) {

				// Remove <style> first!
				control = control.replace( '[', '-' );
				control = control.replace( ']', '' );
				jQuery( 'style#' + control ).remove();

				var fontSize = '',
					TabletFontSize = '',
					MobileFontSize = '';


				if ( '' != value.desktop ) {
					fontSize = 'font-size: ' + value.desktop + value['desktop-unit'];
				}
				if ( '' != value.tablet ) {
					TabletFontSize = 'font-size: ' + value.tablet + value['tablet-unit'];
				}
				if ( '' != value.mobile ) {
					MobileFontSize = 'font-size: ' + value.mobile + value['mobile-unit'];
				}

				if( value['desktop-unit'] == 'px' ) {
					fontSize = astra_font_size_rem( value.desktop, true, 'desktop' );
				}

				// Concat and append new <style>.
				jQuery( 'head' ).append(
					'<style id="' + control + '">'
					+ selector + '	{ ' + fontSize + ' }'
					+ '@media (max-width: 768px) {' + selector + '	{ ' + TabletFontSize + ' } }'
					+ '@media (max-width: 544px) {' + selector + '	{ ' + MobileFontSize + ' } }'
					+ '</style>'
				);

			} else {

				jQuery( 'style#' + control ).remove();
			}

		} );
	} );
}

/**
 * CSS
 */
function astra_css_font_size( control, selector ) {

	wp.customize( control, function( value ) {
		value.bind( function( size ) {

			if ( size ) {

				// Remove <style> first!
				control = control.replace( '[', '-' );
				control = control.replace( ']', '' );
				jQuery( 'style#' + control ).remove();

				var fontSize = 'font-size: ' + size;
				if ( ! isNaN( size ) || size.indexOf( 'px' ) >= 0 ) {
					size = size.replace( 'px', '' );
					fontSize = astra_font_size_rem( size, true );
				}

				// Concat and append new <style>.
				jQuery( 'head' ).append(
					'<style id="' + control + '">'
					+ selector + '	{ ' + fontSize + ' }'
					+ '</style>'
				);

			} else {

				jQuery( 'style#' + control ).remove();
			}

		} );
	} );
}

/**
 * Return get_hexdec()
 */
function get_hexdec( hex ) {
	var hexString = hex.toString( 16 );
	return parseInt( hexString, 16 );
}

/**
 * Apply CSS for the element
 */
function astra_css( control, css_property, selector, unit ) {

	wp.customize( control, function( value ) {
		value.bind( function( new_value ) {

			// Remove <style> first!
			control = control.replace( '[', '-' );
			control = control.replace( ']', '' );

			if ( new_value ) {

				/**
				 *	If ( unit == 'url' ) then = url('{VALUE}')
				 *	If ( unit == 'px' ) then = {VALUE}px
				 *	If ( unit == 'em' ) then = {VALUE}em
				 *	If ( unit == 'rem' ) then = {VALUE}rem.
				 */
				if ( 'undefined' != typeof unit) {

					if ( 'url' === unit ) {
						new_value = 'url(' + new_value + ')';
					} else {
						new_value = new_value + unit;
					}
				}

				// Remove old.
				jQuery( 'style#' + control ).remove();

				// Concat and append new <style>.
				jQuery( 'head' ).append(
					'<style id="' + control + '">'
					+ selector + '	{ ' + css_property + ': ' + new_value + ' }'
					+ '</style>'
				);

			} else {

				wp.customize.preview.send( 'refresh' );

				// Remove old.
				jQuery( 'style#' + control ).remove();
			}

		} );
	} );
}


/**
 * Dynamic Internal/Embedded Style for a Control
 */
function astra_add_dynamic_css( control, style ) {
	control = control.replace( '[', '-' );
	control = control.replace( ']', '' );
	jQuery( 'style#' + control ).remove();

	jQuery( 'head' ).append(
		'<style id="' + control + '">' + style + '</style>'
	);
}


( function( $ ) {

	/*
	 * Site Identity Logo Width
	 */
	wp.customize( 'astra-settings[ast-header-logo-width]', function( setting ) {
		setting.bind( function( logo_width ) {
			if ( logo_width != '' ) {
				var dynamicStyle = '#masthead .site-logo-img .custom-logo-link img {max-width: ' + logo_width + 'px;} .astra-logo-svg{width: ' + logo_width + 'px;} ';
				astra_add_dynamic_css( 'ast-header-logo-width', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/*
	 * Full width layout
	 */
	wp.customize( 'astra-settings[site-content-width]', function( setting ) {
		setting.bind( function( width ) {


				var dynamicStyle = '@media (min-width: 554px) {';
				dynamicStyle += '.ast-container, .fl-builder #content .entry-header { max-width: ' + ( 40 + parseInt( width ) ) + 'px } ';
				dynamicStyle += '}';
				if (  jQuery( 'body' ).hasClass( 'ast-page-builder-template' ) ) {
					dynamicStyle += '@media (min-width: 554px) {';
					dynamicStyle += '.ast-page-builder-template .comments-area { max-width: ' + ( 40 + parseInt( width ) ) + 'px } ';
					dynamicStyle += '}';
				}

				astra_add_dynamic_css( 'site-content-width', dynamicStyle );

		} );
	} );

	/*
	 * Full width layout
	 */
	wp.customize( 'astra-settings[header-main-menu-label]', function( setting ) {
		setting.bind( function( label ) {
			if( $('button.main-header-menu-toggle .mobile-menu-wrap .mobile-menu').length > 0 ) {
				if ( label != '' ) {
					$('button.main-header-menu-toggle .mobile-menu-wrap .mobile-menu').text(label);
				} else {
					$('button.main-header-menu-toggle .mobile-menu-wrap').remove();
				}
			} else {
				var html = $('button.main-header-menu-toggle').html();
				if( '' != label ) {
					html += '<div class="mobile-menu-wrap"><span class="mobile-menu">'+ label +'</span> </div>';
				}
				$('button.main-header-menu-toggle').html( html )
			}
		} );
	} );

	/*
	 * Layout Body Background Color
	 */
	wp.customize( 'astra-settings[site-layout-outside-bg-color]', function( setting ) {
		setting.bind( function( bg_color ) {
				if (bg_color != '') {
					var dynamicStyle = 'body,.ast-separate-container {background-color: ' + bg_color + '}';
					astra_add_dynamic_css( 'site-outside-bg-color', dynamicStyle );
				}
				else{
					wp.customize.preview.send( 'refresh' );
				}

		} );
	} );

	/*
	 * Blog Custom Width
	 */
	wp.customize( 'astra-settings[blog-max-width]', function( setting ) {
		setting.bind( function( width ) {

			var dynamicStyle = '@media all and ( min-width: 921px ) {';

			dynamicStyle += '.blog .site-content > .ast-container,.archive .site-content > .ast-container{ max-width: ' + (  parseInt( width ) ) + 'px } ';

			if (  jQuery( 'body' ).hasClass( 'ast-fluid-width-layout' ) ) {
				dynamicStyle += '.blog .site-content > .ast-container,.archive .site-content > .ast-container{ padding-left:20px; padding-right:20px; } ';
			}
				dynamicStyle += '}';
				astra_add_dynamic_css( 'blog-max-width', dynamicStyle );

		} );
	} );

	/*
	 * Single Blog Custom Width
	 */
	wp.customize( 'astra-settings[blog-single-max-width]', function( setting ) {
		setting.bind( function( width ) {

				var dynamicStyle = '@media all and ( min-width: 921px ) {';

				dynamicStyle += '.single-post .site-content > .ast-container{ max-width: ' + ( 40 + parseInt( width ) ) + 'px } ';

			if (  jQuery( 'body' ).hasClass( 'ast-fluid-width-layout' ) ) {
				dynamicStyle += '.single-post .site-content > .ast-container{ padding-left:20px; padding-right:20px; } ';
			}
				dynamicStyle += '}';
				astra_add_dynamic_css( 'blog-single-max-width', dynamicStyle );

		} );
	} );

	/**
	 * Primary Width Option
	 */
	wp.customize( 'astra-settings[site-sidebar-width]', function( setting ) {
		setting.bind( function( width ) {

			if ( ! jQuery( 'body' ).hasClass( 'ast-no-sidebar' ) && width >= 15 && width <= 50 ) {

				var dynamicStyle = '@media (min-width: 769px) {';

				dynamicStyle += '#primary { width: ' + ( 100 - parseInt( width ) ) + '% } ';
				dynamicStyle += '#secondary { width: ' + width + '% } ';
				dynamicStyle += '}';

				astra_add_dynamic_css( 'site-sidebar-width', dynamicStyle );
			}

		} );
	} );

	/**
	 * Header Bottom Border
	 */
	wp.customize( 'astra-settings[header-main-sep]', function( setting ) {
		setting.bind( function( border ) {

			var dynamicStyle = 'body.ast-header-break-point .site-header { border-bottom-width: ' + border + 'px }';

			dynamicStyle += 'body:not(.ast-header-break-point) .main-header-bar {';
			dynamicStyle += 'border-bottom-width: ' + border + 'px';
			dynamicStyle += '}';

			astra_add_dynamic_css( 'header-main-sep', dynamicStyle );

		} );
	} );

	/**
	 * Small Footer Top Border
	 */
	wp.customize( 'astra-settings[footer-sml-divider]', function( value ) {
		value.bind( function( border_width ) {
			jQuery( '.ast-small-footer' ).css( 'border-top-width', border_width + 'px' );
		} );
	} );

	/**
	 * Small Footer Top Border Color
	 */
	wp.customize( 'astra-settings[footer-sml-divider-color]', function( value ) {
		value.bind( function( border_color ) {
			jQuery( '.ast-small-footer' ).css( 'border-top-color', border_color );
		} );
	} );

	/**
	 * Button Border Radius
	 */
	wp.customize( 'astra-settings[button-radius]', function( setting ) {
		setting.bind( function( border ) {

			var dynamicStyle = '.menu-toggle,button,.ast-button,.button,input#submit,input[type="button"],input[type="submit"],input[type="reset"] { border-radius: ' + ( parseInt( border ) ) + 'px } ';
			astra_add_dynamic_css( 'button-radius', dynamicStyle );

		} );
	} );

	/**
	 * Button Vertical Padding
	 */
	wp.customize( 'astra-settings[button-v-padding]', function( setting ) {
		setting.bind( function( padding ) {

			var dynamicStyle = '.menu-toggle,button,.ast-button,.button,input#submit,input[type="button"],input[type="submit"],input[type="reset"] { padding-top: ' + ( parseInt( padding ) ) + 'px; padding-bottom: ' + ( parseInt( padding ) ) + 'px } ';
			astra_add_dynamic_css( 'button-v-padding', dynamicStyle );

		} );
	} );

	/**
	 * Button Horizontal Padding
	 */
	wp.customize( 'astra-settings[button-h-padding]', function( setting ) {
		setting.bind( function( padding ) {

			var dynamicStyle = '.menu-toggle,button,.ast-button,.button,input#submit,input[type="button"],input[type="submit"],input[type="reset"] { padding-left: ' + ( parseInt( padding ) ) + 'px; padding-right: ' + ( parseInt( padding ) ) + 'px } ';
			astra_add_dynamic_css( 'button-h-padding', dynamicStyle );

		} );
	} );

	/**
	 * Header Bottom Border width
	 */
	wp.customize( 'astra-settings[header-main-sep]', function( value ) {
		value.bind( function( border ) {

			var dynamicStyle = ' body.ast-header-break-point .site-header { border-bottom-width: ' + border + 'px } ';

			dynamicStyle += 'body:not(.ast-header-break-point) .main-header-bar {';
			dynamicStyle += 'border-bottom-width: ' + border + 'px';
			dynamicStyle += '}';

			astra_add_dynamic_css( 'header-main-sep', dynamicStyle );

		} );
	} );

	/**
	 * Header Bottom Border color
	 */
	wp.customize( 'astra-settings[header-main-sep-color]', function( value ) {
		value.bind( function( color ) {
			if (color == '') {
				wp.customize.preview.send( 'refresh' );
			}

			if ( color ) {

				var dynamicStyle = ' body:not(.ast-header-break-point) .main-header-bar { border-bottom-color: ' + color + '; } ';
					dynamicStyle += ' body.ast-header-break-point .site-header { border-bottom-color: ' + color + '; } ';

				astra_add_dynamic_css( 'header-main-sep-color', dynamicStyle );
			}

		} );
	} );


	astra_responsive_font_size( 'astra-settings[font-size-site-tagline]', '.site-header .site-description' );
	astra_responsive_font_size( 'astra-settings[font-size-site-title]', '.site-title' );
	astra_responsive_font_size( 'astra-settings[font-size-entry-title]', '.ast-single-post .entry-title, .page-title' );
	astra_responsive_font_size( 'astra-settings[font-size-archive-summary-title]', '.ast-archive-description .ast-archive-title' );
	astra_responsive_font_size( 'astra-settings[font-size-page-title]', 'body:not(.ast-single-post) .entry-title' );
	astra_responsive_font_size( 'astra-settings[font-size-h1]', 'h1, .entry-content h1, .entry-content h1 a' );
	astra_responsive_font_size( 'astra-settings[font-size-h2]', 'h2, .entry-content h2, .entry-content h2 a' );
	astra_responsive_font_size( 'astra-settings[font-size-h3]', 'h3, .entry-content h3, .entry-content h3 a' );
	astra_responsive_font_size( 'astra-settings[font-size-h4]', 'h4, .entry-content h4, .entry-content h4 a' );
	astra_responsive_font_size( 'astra-settings[font-size-h5]', 'h5, .entry-content h5, .entry-content h5 a' );
	astra_responsive_font_size( 'astra-settings[font-size-h6]', 'h6, .entry-content h6, .entry-content h6 a' );

	astra_css( 'astra-settings[body-line-height]', 'line-height', 'body, button, input, select, textarea' );
	// paragraph margin bottom.
	wp.customize( 'astra-settings[para-margin-bottom]', function( value ) {
		value.bind( function( marginBottom ) {
			if ( marginBottom == '' ) {
				wp.customize.preview.send( 'refresh' );
			}

			if ( marginBottom ) {
				var dynamicStyle = ' p, .entry-content p { margin-bottom: ' + marginBottom + 'em; } ';
				astra_add_dynamic_css( 'para-margin-bottom', dynamicStyle );
			}

		} );
	} );

	astra_css( 'astra-settings[body-text-transform]', 'text-transform', 'body, button, input, select, textarea' );

	astra_css( 'astra-settings[headings-text-transform]', 'text-transform', 'h1, .entry-content h1, .entry-content h1 a, h2, .entry-content h2, .entry-content h2 a, h3, .entry-content h3, .entry-content h3 a, h4, .entry-content h4, .entry-content h4 a, h5, .entry-content h5, .entry-content h5 a, h6, .entry-content h6, .entry-content h6 a' );

	// Footer Bar.
	astra_css( 'astra-settings[footer-color]', 'color', '.ast-small-footer' );
	astra_css( 'astra-settings[footer-link-color]', 'color', '.ast-small-footer a' );
	astra_css( 'astra-settings[footer-link-h-color]', 'color', '.ast-small-footer a:hover' );

	wp.customize( 'astra-settings[footer-bg-color]', function( value ) {
		value.bind( function( bgcolor ) {
			if ( bgcolor == '' ) {
				wp.customize.preview.send( 'refresh' );
			}

			if ( bgcolor ) {
				var dynamicStyle = ' .ast-small-footer > .ast-footer-overlay { background-color: ' + bgcolor + '; } ';
				astra_add_dynamic_css( 'footer-bg-color', dynamicStyle );
			}

		} );
	} );

	// Footer Widgets.
	astra_css( 'astra-settings[footer-adv-wgt-title-color]', 'color', '.footer-adv .widget-title, .footer-adv .widget-title a' );
	astra_css( 'astra-settings[footer-adv-text-color]', 'color', '.footer-adv' );

	wp.customize( 'astra-settings[footer-adv-bg-color]', function( value ) {
		value.bind( function( bgcolor ) {
			if ( bgcolor == '' ) {
				wp.customize.preview.send( 'refresh' );
			}

			if ( bgcolor ) {
				var dynamicStyle = ' .footer-adv-overlay { background-color: ' + bgcolor + '; } ';
				astra_add_dynamic_css( 'footer-adv-bg-color', dynamicStyle );
			}

		} );
	} );

} )( jQuery );
