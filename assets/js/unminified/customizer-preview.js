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
function ast_font_size_rem( size, with_rem ) {

	// font size with 'px'.
	var css = 'font-size: ' + size + 'px;';

	// font size with 'rem'.
	if ( with_rem ) {
		var body_font_size = wp.customize( 'ast-settings[font-size-body]' ).get();

		css += 'font-size: ' + ( size / body_font_size ) + 'rem;';
	}

	return css;
}

/**
 * CSS
 */
function ast_css_font_size( control, selector, min_media, max_media ) {

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
					fontSize = ast_font_size_rem( size, true );
				}

				//Min & Max width.
				// 'undefined' != typeof
				 min_media = (typeof min_media !== 'undefined') ?  min_media : '';
				 max_media = (typeof max_media !== 'undefined') ?  max_media : '';
				if ( '' !== min_media || '' !== max_media ) {
					var media_css       = '@media ';
					var min_media_css   = '';
					var max_media_css   = '';
					var media_separator = '';

					if ( '' !== min_media ) {
						min_media_css += '(min-width:' + min_media + 'px)';
					}
					if ( '' !== max_media ) {
						max_media_css += '(max-width:' + max_media + 'px)';
					}
					if ( '' !== min_media && '' !== max_media ) {
						media_separator += ' and ';
					}

					 media_css += min_media_css + media_separator + max_media_css;
					// Concat and append new <style>.
					jQuery( 'head' ).append(
						'<style id="' + control + '">'
						+ media_css + ' { '
						+ selector + '	{ ' + fontSize + ' }'
						+ ' }'
						+ '</style>'
					);
				}
				else{
					jQuery( 'head' ).append(
						'<style id="' + control + '">'
						+ selector + '	{ ' + fontSize + ' }'
						+ '</style>'
					);
				}

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
function ast_css( control, css_property, selector, unit , min_media , max_media  ) {

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
					} else if ( 'dimension' === unit ) {
						if ( ! isNaN( new_value ) ) {
							new_value = new_value + 'px';
						}
					} else {
						new_value = new_value + unit;
					}
				}

				// Remove old.
				jQuery( 'style#' + control ).remove();
				
				//Min & Max width.
				min_media = (typeof min_media !== 'undefined') ?  min_media : '';
				max_media = (typeof max_media !== 'undefined') ?  max_media : '';
				if ( '' !== min_media || '' !== max_media ) {
					var media_css       = '@media ';
					var min_media_css   = '';
					var max_media_css   = '';
					var media_separator = '';

					if ( '' !== min_media ) {
						min_media_css += '(min-width:' + min_media + 'px)';
					}
					if ( '' !== max_media ) {
						max_media_css += '(max-width:' + max_media + 'px)';
					}
					if ( '' !== min_media && '' !== max_media ) {
						media_separator += ' and ';
					}

					 media_css += min_media_css + media_separator + max_media_css;
					// Concat and append new <style>.
					jQuery( 'head' ).append(
						'<style id="' + control + '">'
						+ media_css + ' { '
						+ selector + '	{ ' + css_property + ': ' + new_value + ' }'
						+ ' }'
						+ '</style>'
					);
				}
				else{
					// Concat and append new <style>.
					jQuery( 'head' ).append(
						'<style id="' + control + '">'
						+ selector + '	{ ' + css_property + ': ' + new_value + ' }'
						+ '</style>'
					);
				}

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
function ast_add_dynamic_css( control, style ) {
	control = control.replace( '[', '-' );
	control = control.replace( ']', '' );
	jQuery( 'style#' + control ).remove();

	jQuery( 'head' ).append(
		'<style id="' + control + '">' + style + '</style>'
	);
}


( function( $ ) {

	/*
	 * Full width layout
	 */
	wp.customize( 'ast-settings[site-content-width]', function( setting ) {
		setting.bind( function( width ) {


				var dynamicStyle = '@media (min-width: 554px) {';
				dynamicStyle += '.ast-container, .fl-builder #content .entry-header { max-width: ' + ( 40 + parseInt( width ) ) + 'px } ';
				dynamicStyle += '}';
				if (  jQuery( 'body' ).hasClass( 'ast-page-builder-template' ) ) {
					dynamicStyle += '@media (min-width: 554px) {';
					dynamicStyle += '.ast-page-builder-template .comments-area { max-width: ' + ( 40 + parseInt( width ) ) + 'px } ';
					dynamicStyle += '}';
				}

				ast_add_dynamic_css( 'site-content-width', dynamicStyle );

		} );
	} );

	/*
	 * Layout Body Background Color
	 */
	wp.customize( 'ast-settings[site-layout-outside-bg-color]', function( setting ) {
		setting.bind( function( bg_color ) {
				if (bg_color != '') {
					var dynamicStyle = 'body,.ast-separate-container {background-color: ' + bg_color + '}';
					ast_add_dynamic_css( 'site-outside-bg-color', dynamicStyle );
				}
				else{
					wp.customize.preview.send( 'refresh' );
				}

		} );
	} );

	/*
	 * Blog Custom Width
	 */
	wp.customize( 'ast-settings[blog-max-width]', function( setting ) {
		setting.bind( function( width ) {

			var dynamicStyle = '@media all and ( min-width: 920px ) {';

			dynamicStyle += '.blog .site-content > .ast-container,.archive .site-content > .ast-container{ max-width: ' + (  parseInt( width ) ) + 'px } ';

			if (  jQuery( 'body' ).hasClass( 'ast-fluid-width-layout' ) ) {
				dynamicStyle += '.blog .site-content > .ast-container,.archive .site-content > .ast-container{ padding-left:20px; padding-right:20px; } ';
			}
				dynamicStyle += '}';
				ast_add_dynamic_css( 'blog-max-width', dynamicStyle );

		} );
	} );

	/*
	 * Single Blog Custom Width
	 */
	wp.customize( 'ast-settings[blog-single-max-width]', function( setting ) {
		setting.bind( function( width ) {

				var dynamicStyle = '@media all and ( min-width: 920px ) {';

				dynamicStyle += '.single-post .site-content > .ast-container{ max-width: ' + ( 40 + parseInt( width ) ) + 'px } ';

			if (  jQuery( 'body' ).hasClass( 'ast-fluid-width-layout' ) ) {
				dynamicStyle += '.single-post .site-content > .ast-container{ padding-left:20px; padding-right:20px; } ';
			}
				dynamicStyle += '}';
				ast_add_dynamic_css( 'blog-single-max-width', dynamicStyle );

		} );
	} );

	/**
	 * Primary Width Option
	 */
	wp.customize( 'ast-settings[site-sidebar-width]', function( setting ) {
		setting.bind( function( width ) {

			if ( ! jQuery( 'body' ).hasClass( 'ast-no-sidebar' ) && width >= 15 && width <= 50 ) {

				var dynamicStyle = '@media (min-width: 768px) {';

				dynamicStyle += '#primary { width: ' + ( 100 - parseInt( width ) ) + '% } ';
				dynamicStyle += '#secondary { width: ' + width + '% } ';
				dynamicStyle += '}';

				ast_add_dynamic_css( 'site-sidebar-width', dynamicStyle );
			}

		} );
	} );

	/**
	 * Header Bottom Border
	 */
	wp.customize( 'ast-settings[header-main-sep]', function( setting ) {
		setting.bind( function( border ) {

			var dynamicStyle = 'body.ast-header-break-point .site-header { border-bottom-width: ' + border + 'px }';

			dynamicStyle += 'body:not(.ast-header-break-point) .main-header-bar {';
			dynamicStyle += 'border-bottom-width: ' + border + 'px';
			dynamicStyle += '}';

			ast_add_dynamic_css( 'header-main-sep', dynamicStyle );

		} );
	} );

	/**
	 * Small Footer Top Border
	 */
	wp.customize( 'ast-settings[footer-sml-divider]', function( value ) {
		value.bind( function( border_width ) {
			jQuery( '.ast-small-footer' ).css( 'border-top-width', border_width + 'px' );
		} );
	} );

	/**
	 * Small Footer Top Border Color
	 */
	wp.customize( 'ast-settings[footer-sml-divider-color]', function( value ) {
		value.bind( function( border_color ) {
			jQuery( '.ast-small-footer' ).css( 'border-top-color', border_color );
		} );
	} );

	/**
	 * Button Border Radius
	 */
	wp.customize( 'ast-settings[button-radius]', function( setting ) {
		setting.bind( function( border ) {

			var dynamicStyle = '.menu-toggle,button,.ast-button,input#submit,input[type="button"],input[type="submit"],input[type="reset"] { border-radius: ' + ( parseInt( border ) ) + 'px } ';
			ast_add_dynamic_css( 'button-radius', dynamicStyle );

		} );
	} );

	/**
	 * Button Vertical Padding
	 */
	wp.customize( 'ast-settings[button-v-padding]', function( setting ) {
		setting.bind( function( padding ) {

			var dynamicStyle = '.menu-toggle,button,.ast-button,input#submit,input[type="button"],input[type="submit"],input[type="reset"] { padding-top: ' + ( parseInt( padding ) ) + 'px; padding-bottom: ' + ( parseInt( padding ) ) + 'px } ';
			ast_add_dynamic_css( 'button-v-padding', dynamicStyle );

		} );
	} );

	/**
	 * Button Horizontal Padding
	 */
	wp.customize( 'ast-settings[button-h-padding]', function( setting ) {
		setting.bind( function( padding ) {

			var dynamicStyle = '.menu-toggle,button,.ast-button,input#submit,input[type="button"],input[type="submit"],input[type="reset"] { padding-left: ' + ( parseInt( padding ) ) + 'px; padding-right: ' + ( parseInt( padding ) ) + 'px } ';
			ast_add_dynamic_css( 'button-h-padding', dynamicStyle );

		} );
	} );

	/**
	 * Header Bottom Border width
	 */
	wp.customize( 'ast-settings[header-main-sep]', function( value ) {
		value.bind( function( border ) {

			var dynamicStyle = ' body.ast-header-break-point .site-header { border-bottom-width: ' + border + 'px } ';

			dynamicStyle += 'body:not(.ast-header-break-point) .main-header-bar {';
			dynamicStyle += 'border-bottom-width: ' + border + 'px';
			dynamicStyle += '}';

			ast_add_dynamic_css( 'header-main-sep', dynamicStyle );

		} );
	} );

	/**
	 * Header Bottom Border color
	 */
	wp.customize( 'ast-settings[header-main-sep-color]', function( value ) {
		value.bind( function( color ) {
			if (color == '') {
				wp.customize.preview.send( 'refresh' );
			}

			if ( color ) {

				var dynamicStyle = ' body:not(.ast-header-break-point) .main-header-bar { border-bottom-color: ' + color + '; } ';
					dynamicStyle += ' body.ast-header-break-point .site-header { border-bottom-color: ' + color + '; } ';

				ast_add_dynamic_css( 'header-main-sep-color', dynamicStyle );
			}

		} );
	} );

	ast_css_font_size( 'ast-settings[font-size-site-tagline]', '.site-header .site-description', 768 );
	ast_css_font_size( 'ast-settings[font-size-site-title]', '.site-title a', 768 );
	ast_css_font_size( 'ast-settings[font-size-entry-title]', '.ast-single-post .entry-title, .page-title', 768 );
	ast_css_font_size( 'ast-settings[font-size-page-title]', 'body:not(.ast-single-post) .entry-title', 768 );
	ast_css_font_size( 'ast-settings[font-size-h1]', 'h1, .entry-content h1, .entry-content h1 a', 768 );
	ast_css_font_size( 'ast-settings[font-size-h2]', 'h2, .entry-content h2, .entry-content h2 a', 768 );
	ast_css_font_size( 'ast-settings[font-size-h3]', 'h3, .entry-content h3, .entry-content h3 a', 768 );
	ast_css_font_size( 'ast-settings[font-size-h4]', 'h4, .entry-content h4, .entry-content h4 a', 768 );
	ast_css_font_size( 'ast-settings[font-size-h5]', 'h5, .entry-content h5, .entry-content h5 a', 768 );
	ast_css_font_size( 'ast-settings[font-size-h6]', 'h6, .entry-content h6, .entry-content h6 a', 768 );


	ast_css( 'ast-settings[body-line-height]', 'line-height', 'body, button, input, select, textarea', 'dimension', 768 );
	ast_css( 'ast-settings[body-text-transform]', 'text-transform', 'body, button, input, select, textarea' );

} )( jQuery );
