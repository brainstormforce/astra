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
 * Apply CSS for the element
 */
function astra_color_responsive_css( addon, control, css_property, selector ) {

	wp.customize( control, function( value ) {
		value.bind( function( value ) {
			if ( value.desktop || value.mobile || value.tablet ) {
				// Remove <style> first!
				control = control.replace( '[', '-' );
				control = control.replace( ']', '' );
				jQuery( 'style#' + control + '-' + addon ).remove();

				var DeskVal = '',
					TabletFontVal = '',
					MobileVal = '';

				if ( '' != value.desktop ) {
					DeskVal = css_property + ': ' + value.desktop;
				}
				if ( '' != value.tablet ) {
					TabletFontVal = css_property + ': ' + value.tablet;
				}
				if ( '' != value.mobile ) {
					MobileVal = css_property + ': ' + value.mobile;
				}

				// Concat and append new <style>.
				jQuery( 'head' ).append(
					'<style id="' + control + '-' + addon + '">'
					+ selector + '	{ ' + DeskVal + ' }'
					+ '@media (max-width: 768px) {' + selector + '	{ ' + TabletFontVal + ' } }'
					+ '@media (max-width: 544px) {' + selector + '	{ ' + MobileVal + ' } }'
					+ '</style>'
				);

			} else {
				wp.customize.preview.send( 'refresh' );
				jQuery( 'style#' + control + '-' + addon ).remove();
			}

		} );
	} );
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
 * Responsive Spacing CSS
 */
function astra_responsive_spacing( control, selector, type, side ) {

	wp.customize( control, function( value ) {
		value.bind( function( value ) {
			var sidesString = "";
			var spacingType = "padding";
			if ( value.desktop.top || value.desktop.right || value.desktop.bottom || value.desktop.left || value.tablet.top || value.tablet.right || value.tablet.bottom || value.tablet.left || value.mobile.top || value.mobile.right || value.mobile.bottom || value.mobile.left ) {
				if ( typeof side != undefined ) {
					sidesString = side + "";
					sidesString = sidesString.replace(/,/g , "-");
				}
				if ( typeof type != undefined ) {
					spacingType = type + "";
				}
				// Remove <style> first!
				control = control.replace( '[', '-' );
				control = control.replace( ']', '' );
				jQuery( 'style#' + control + '-' + spacingType + '-' + sidesString ).remove();

				var desktopPadding = '',
					tabletPadding = '',
					mobilePadding = '';

				var paddingSide = ( typeof side != undefined ) ? side : [ 'top','bottom','right','left' ];

				jQuery.each(paddingSide, function( index, sideValue ){
					if ( '' != value['desktop'][sideValue] ) {
						desktopPadding += spacingType + '-' + sideValue +': ' + value['desktop'][sideValue] + value['desktop-unit'] +';';
					}
				});

				jQuery.each(paddingSide, function( index, sideValue ){
					if ( '' != value['tablet'][sideValue] ) {
						tabletPadding += spacingType + '-' + sideValue +': ' + value['tablet'][sideValue] + value['tablet-unit'] +';';
					}
				});

				jQuery.each(paddingSide, function( index, sideValue ){
					if ( '' != value['mobile'][sideValue] ) {
						mobilePadding += spacingType + '-' + sideValue +': ' + value['mobile'][sideValue] + value['mobile-unit'] +';';
					}
				});

				// Concat and append new <style>.
				jQuery( 'head' ).append(
					'<style id="' + control + '-' + spacingType + '-' + sidesString + '">'
					+ selector + '	{ ' + desktopPadding +' }'
					+ '@media (max-width: 768px) {' + selector + '	{ ' + tabletPadding + ' } }'
					+ '@media (max-width: 544px) {' + selector + '	{ ' + mobilePadding + ' } }'
					+ '</style>'
				);

			} else {
				wp.customize.preview.send( 'refresh' );
				jQuery( 'style#' + control + '-' + spacingType + '-' + sidesString ).remove();
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

/**
 * Generate background_obj CSS
 */
function astra_background_obj_css( wp_customize, bg_obj, ctrl_name, style ) {

	var gen_bg_css 	= '';
	var bg_img		= bg_obj['background-image'];
	var bg_color	= bg_obj['background-color'];

	if( '' === bg_color && '' === bg_img ) {
		wp_customize.preview.send( 'refresh' );
	}else{
		if ( '' !== bg_img && '' !== bg_color) {
			if ( undefined !== bg_color ) {
				gen_bg_css = 'background-image: linear-gradient(to right, ' + bg_color + ', ' + bg_color + '), url(' + bg_img + ');';
			}
		}else if ( '' !== bg_img ) {
			gen_bg_css = 'background-image: url(' + bg_img + ');';
		}else if ( '' !== bg_color ) {
			gen_bg_css = 'background-color: ' + bg_color + ';';
			gen_bg_css += 'background-image: none;';
		}
		
		if ( '' !== bg_img ) {

			gen_bg_css += 'background-repeat: ' + bg_obj['background-repeat'] + ';';
			gen_bg_css += 'background-position: ' + bg_obj['background-position'] + ';';
			gen_bg_css += 'background-size: ' + bg_obj['background-size'] + ';';
			gen_bg_css += 'background-attachment: ' + bg_obj['background-attachment'] + ';';
		}

		var dynamicStyle = style.replace( "{{css}}", gen_bg_css );

		astra_add_dynamic_css( ctrl_name, dynamicStyle );
	}
}

/*
* Generate Responsive Color CSS
*/
function astra_apply_responsive_color_property( group, subControl, selector, cssProperty, addon, device ) {
	wp.customize( group, function( control ) {
		control.bind(function (value, oldValue) {

			var optionValue = JSON.parse( value );
			var changedKey  = getChangedKey( value, oldValue );
			if ('undefined' != typeof changedKey && changedKey == subControl ) {
				var changedValue = optionValue[changedKey];
				var control = subControl.replace( '[', '-' );
					control = control.replace( ']', '' );

				addon = addon || '';

				addon = ( addon ) ? addon : 'theme';
				
				jQuery( 'style#' + control + '-' + addon + '-' + cssProperty ).remove();

				var DeskVal = '',
					TabletVal = '',
					MobileVal = '';
			
				if ( '' != changedValue.desktop ) {
					DeskVal = cssProperty + ': ' + changedValue.desktop;
				}
				if ( '' != changedValue.tablet ) {
					TabletVal = cssProperty + ': ' + changedValue.tablet;
				}
				if ( '' != changedValue.mobile ) {
					MobileVal = cssProperty + ': ' + changedValue.mobile;
				}

				if ( 'desktop' === device ) {

					var dynamicStyle = '<style id="' + control + '-' + addon + '-' + cssProperty + '-' + device + '">' + selector + '	{ ' + DeskVal + ' }'
						+ '</style>';
				}

				if ( 'tablet' === device ) {

					var dynamicStyle = '<style id="' + control + '-' + addon + '-' + cssProperty + '-' + device + '">'
						+ '@media (max-width: 768px) {' + selector + '	{ ' + TabletVal + ' }'
						+ '</style>';
				}

				if ( 'mobile' === device ) {

					var dynamicStyle = '<style id="' + control + '-' + addon + '-' + cssProperty + '-' + device + '">'
						+ '@media (max-width: 544px) {' + selector + '	{ ' + MobileVal + ' }'
						+ '</style>';
				}

				if( 'undefined' != typeof device ) {

					// Concat and append new <style>.
					jQuery('head').append(
						dynamicStyle
					);

				} else {

					// Concat and append new <style>.
					jQuery('head').append(
						'<style id="' + control + '-' + addon + '-' + cssProperty + '">'
						+ selector + '	{ ' + DeskVal + ' }'
						+ '@media (max-width: 768px) {' + selector + '	{ ' + TabletVal + ' } }'
						+ '@media (max-width: 544px) {' + selector + '	{ ' + MobileVal + ' } }'
						+ '</style>'
					);
				}
			}
		});
	});
}

/*
* Generate Responsive Font CSS
*/
function astra_apply_responsive_font_size( group, subControl, selector ) {
	wp.customize( group, function (control) {
		control.bind(function ( value, oldValue ) {
			var changedKey  = getChangedKey( value, oldValue );
			if ( subControl != changedKey ) {
				return;
			}
			var control = changedKey.replace( '[', '-' );
				control = control.replace( ']', '' );
				jQuery( 'style#' + control ).remove();
				
			var fontSize = '',
				TabletFontSize = '',
				MobileFontSize = '';

			var option_value = JSON.parse(value);
			value = option_value[changedKey];

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
		});
	});    
}

/*
* Generate CSS
*/
function astra_generate_css( group, subControl, selector, cssProperty, unittype )	 {

	wp.customize( group, function (control) {
		control.bind(function ( value, oldValue ) {
			
			var optionValue = JSON.parse(value);
			var changedKey  = getChangedKey( value, oldValue );

			unittype = unittype || '';

			if ( subControl != changedKey) {
				return;
			}

			value = optionValue[changedKey];
			var control = changedKey;
			
			if ('undefined' != typeof unit) {
		
				if ('url' === unit) {
					value = 'url(' + value + ')';
				} else {
					value = value + unit;
				}
			}
			// Remove old.
			jQuery( 'style#' + control + '-' + cssProperty ).remove();
			console.log(unittype);
			console.log(typeof unittype);
			// Concat and append new <style>.
			jQuery('head').append(
				'<style id="' + control + '-' + cssProperty + '">'
				+ selector + '	{ ' + cssProperty + ': ' + value + unittype + ' }'
				+ '</style>'
			);

		});
	});
}

/**
 * Apply CSS for the element
 */
function astra_apply_background_css(group, subControl, selector ) {
	wp.customize(group, function (control) {
		control.bind(function (value, oldValue) {
			var parse_bg_obj = JSON.parse(value);

				bg_obj = parse_bg_obj[subControl];
			if ( '' === bg_obj || undefined === bg_obj ) {
				return;
			}

			jQuery( 'style#' + subControl ).remove();

			var gen_bg_css = '';
			var bg_img = bg_obj['background-image'];
			var bg_color = bg_obj['background-color'];
			if ('' !== bg_img && '' !== bg_color) {
				if (undefined !== bg_color) {
					gen_bg_css = 'background-image: linear-gradient(to right, ' + bg_color + ', ' + bg_color + '), url(' + bg_img + ');';
				}
			} else if ('' !== bg_img) {
				gen_bg_css = 'background-image: url(' + bg_img + ');';
			} else if ('' !== bg_color) {
				gen_bg_css = 'background-color: ' + bg_color + ';';
			}

			if ('' == bg_img) {
				gen_bg_css += 'background-image: none;';
			} else {
				gen_bg_css += 'background-repeat: ' + bg_obj['background-repeat'] + ';';
				gen_bg_css += 'background-position: ' + bg_obj['background-position'] + ';';
				gen_bg_css += 'background-size: ' + bg_obj['background-size'] + ';';
				gen_bg_css += 'background-attachment: ' + bg_obj['background-attachment'] + ';';
			}

			var dynamicStyle = '<style id="' + subControl + '">'
				+ selector + '	{ ' + gen_bg_css + ' }'
				+ '</style>'

			// Concat and append new <style>.
			jQuery('head').append(
				dynamicStyle
			);
		});
	});
}

/*
* Generate Font Family CSS
*/
function astra_generate_font_family_css( group, subControl, selector ) {

	wp.customize( group, function (control) {

		control.bind( function ( value, oldValue ) {
				
			var optionValue = JSON.parse(value);
			var cssProperty = 'font-family';
			var changedKey  = getChangedKey( value, oldValue );
			var link = '';

			if ( subControl != changedKey ) {
				return;
			}
			
			value = optionValue[changedKey];
			var control = changedKey;
			
			var fontName = value.split(",")[0];
			fontName = fontName.replace(/'/g, '')
			
			// Remove old.
			jQuery('style#' + control).remove();
			
			if ( fontName in astraCustomizer.googleFonts ) {
				// Remove old.

				var fontName = fontName.split(' ').join('+');

				jQuery('link#' + control).remove();
				link = '<link id="' + control + '" href="https://fonts.googleapis.com/css?family=' + fontName + '"  rel="stylesheet">';
			}
		
			// Concat and append new <style> and <link>.
			jQuery('head').append(
				'<style id="' + control + '">'
				+ selector + '	{ ' + cssProperty + ': ' + value + ' }'
				+ '</style>'
				+ link
			);
		});
	});
}

function getChangedKey( value, other ) {

	value = isJsonString(value) ? JSON.parse(value) : value;
	other = isJsonString(other) ? JSON.parse(other) : other;

	// Compare two items
	var compare = function ( item1, item2 ) {

		// Get the object type
		var itemType = Object.prototype.toString.call(item1);

		// If an object or array, compare recursively
		if (['[object Array]', '[object Object]'].indexOf(itemType) >= 0) {
			if ('string' == typeof getChangedKey(item1, item2)) {
				return false;
			}
		}

		// Otherwise, do a simple comparison
		else {

			// If the two items are not the same type, return false
			if (itemType !== Object.prototype.toString.call(item2)) return false;

			// Else if it's a function, convert to a string and compare
			// Otherwise, just compare
			if (itemType === '[object Function]') {
				if (item1.toString() !== item2.toString()) return false;
			} else {
				if (item1 !== item2) return false;
			}

		}
	};

	for ( var key in value ) {
		if ( other.hasOwnProperty(key) && value.hasOwnProperty(key) ) {
			if ( compare( value[key], other[key] ) === false ) return key;
		} else {
			return key;
		}
	}

	// If nothing failed, return true
	return true;

}

function isJsonString( str ) {

	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
} 

( function( $ ) {

	/*
	 * Site Identity Logo Width
	 */
	wp.customize( 'astra-settings[ast-header-responsive-logo-width]', function( setting ) {
		setting.bind( function( logo_width ) {
			if ( logo_width['desktop'] != '' || logo_width['tablet'] != '' || logo_width['mobile'] != '' ) {
				var dynamicStyle = '#masthead .site-logo-img .custom-logo-link img { max-width: ' + logo_width['desktop'] + 'px;} .astra-logo-svg{width: ' + logo_width['desktop'] + 'px;} @media( max-width: 768px ) { #masthead .site-logo-img .custom-logo-link img { max-width: ' + logo_width['tablet'] + 'px;} .astra-logo-svg{width: ' + logo_width['tablet'] + 'px; } } @media( max-width: 544px ) { .ast-header-break-point .site-branding img, .ast-header-break-point #masthead .site-logo-img .custom-logo-link img { max-width: ' + logo_width['mobile'] + 'px;} .astra-logo-svg{width: ' + logo_width['mobile'] + 'px; } }';
				astra_add_dynamic_css( 'ast-header-responsive-logo-width', dynamicStyle );
				var mobileLogoStyle = '.ast-header-break-point #masthead .site-logo-img .custom-mobile-logo-link img { max-width: ' + logo_width['tablet'] + 'px; } @media( max-width: 768px ) { .ast-header-break-point #masthead .site-logo-img .custom-mobile-logo-link img { max-width: ' + logo_width['tablet'] + 'px; }  @media( max-width: 544px ) { .ast-header-break-point #masthead .site-logo-img .custom-mobile-logo-link img { max-width: ' + logo_width['mobile'] + 'px; }';
				astra_add_dynamic_css( 'mobile-header-logo-width', mobileLogoStyle );
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
	 * Layout Body Background
	 */
	wp.customize( 'astra-settings[site-layout-outside-bg-obj]', function( value ) {
		value.bind( function( bg_obj ) {

			var dynamicStyle = 'body,.ast-separate-container { {{css}} }';
			
			astra_background_obj_css( wp.customize, bg_obj, 'site-layout-outside-bg-obj', dynamicStyle );
		} );
	} );

	/*
	 * Blog Custom Width
	 */
	wp.customize( 'astra-settings[blog-max-width]', function( setting ) {
		setting.bind( function( width ) {

			var dynamicStyle = '@media all and ( min-width: 921px ) {';

			if ( ! jQuery( 'body' ).hasClass( 'ast-woo-shop-archive' ) ) {
			dynamicStyle += '.blog .site-content > .ast-container,.archive .site-content > .ast-container{ max-width: ' + (  parseInt( width ) ) + 'px } ';
			}

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

			dynamicStyle += '.ast-desktop .main-header-bar {';
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
	 * Footer Widget Top Border
	 */
	wp.customize( 'astra-settings[footer-adv-border-width]', function( value ) {
		value.bind( function( border_width ) {
			jQuery( '.footer-adv .footer-adv-overlay' ).css( 'border-top-width', border_width + 'px' );
		} );
	} );


	wp.customize( 'astra-settings[footer-adv-border-color]', function( value ) {
		value.bind( function( border_color ) {
			jQuery( '.footer-adv .footer-adv-overlay' ).css( 'border-top-color', border_color );
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
	wp.customize( 'astra-settings[theme-button-border-group]', function( setting ) {
		setting.bind( function( border ) {

			var dynamicStyle = '.menu-toggle,button,.ast-button,input#submit,input[type="button"],input[type="submit"],input[type="reset"] { border-radius: ' + ( parseInt( border ) ) + 'px } ';
			if (  jQuery( 'body' ).hasClass( 'woocommerce' ) ) {
				dynamicStyle += '.woocommerce a.button, .woocommerce button.button, .woocommerce .product a.button, .woocommerce .woocommerce-message a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce input.button,.woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled] { border-radius: ' + ( parseInt( border ) ) + 'px } ';
			}
			astra_add_dynamic_css( 'button-radius', dynamicStyle );

		} );
	} );

	/**
	 * Button Vertical Padding
	 */
	wp.customize( 'astra-settings[theme-button-border-group]', function( setting ) {
		setting.bind( function( padding ) {

			var dynamicStyle = '.menu-toggle,button,.ast-button,input#submit,input[type="button"],input[type="submit"],input[type="reset"] { padding-top: ' + ( parseInt( padding ) ) + 'px; padding-bottom: ' + ( parseInt( padding ) ) + 'px } ';
			
			if (  jQuery( 'body' ).hasClass( 'woocommerce' ) ) {
				dynamicStyle += '.woocommerce a.button, .woocommerce button.button, .woocommerce .product a.button, .woocommerce .woocommerce-message a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce input.button,.woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled] { padding-top: ' + ( parseInt( padding ) ) + 'px; padding-bottom: ' + ( parseInt( padding ) ) + 'px } ';
			}
			astra_add_dynamic_css( 'button-v-padding', dynamicStyle );

		} );
	} );

	/**
	 * Button Horizontal Padding
	 */
	wp.customize( 'astra-settings[theme-button-border-group]', function( setting ) {
		setting.bind( function( padding ) {

			var dynamicStyle = '.menu-toggle,button,.ast-button,input#submit,input[type="button"],input[type="submit"],input[type="reset"] { padding-left: ' + ( parseInt( padding ) ) + 'px; padding-right: ' + ( parseInt( padding ) ) + 'px } ';
			if (  jQuery( 'body' ).hasClass( 'woocommerce' ) ) {
				dynamicStyle += '.woocommerce a.button, .woocommerce button.button, .woocommerce .product a.button, .woocommerce .woocommerce-message a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce input.button,.woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled] { padding-left: ' + ( parseInt( padding ) ) + 'px; padding-right: ' + ( parseInt( padding ) ) + 'px } ';
			}
			astra_add_dynamic_css( 'button-h-padding', dynamicStyle );

		} );
	} );

	/**
	 * Header Bottom Border width
	 */
	wp.customize( 'astra-settings[header-main-sep]', function( value ) {
		value.bind( function( border ) {

			var dynamicStyle = ' body.ast-header-break-point .site-header { border-bottom-width: ' + border + 'px } ';

			dynamicStyle += '.ast-desktop .main-header-bar {';
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

				var dynamicStyle = ' .ast-desktop .main-header-bar { border-bottom-color: ' + color + '; } ';
					dynamicStyle += ' body.ast-header-break-point .site-header { border-bottom-color: ' + color + '; } ';

				astra_add_dynamic_css( 'header-main-sep-color', dynamicStyle );
			}

		} );
	} );


	astra_responsive_font_size( 'astra-settings[font-size-site-tagline]', '.site-header .site-description' );
	astra_responsive_font_size( 'astra-settings[font-size-site-title]', '.site-title' );

	astra_apply_responsive_font_size( 'astra-settings[blog-single-title-typo]', 'font-size-entry-title', '.ast-single-post .entry-title, .page-title' );
	astra_apply_responsive_font_size( 'astra-settings[blog-content-archive-summary-typo]', 'font-size-archive-summary-title', '.ast-archive-description .ast-archive-title' );
	astra_apply_responsive_font_size( 'astra-settings[blog-content-blog-post-title-typo]', 'font-size-page-title', 'body:not(.ast-single-post) .entry-title' );

	// Check if anchors should be loaded in the CSS for headings.	
	if (true == astraCustomizer.includeAnchorsInHeadindsCss) {
		astra_responsive_font_size('astra-settings[font-size-h1]', 'h1, .entry-content h1, .entry-content h1 a');
		astra_responsive_font_size('astra-settings[font-size-h2]', 'h2, .entry-content h2, .entry-content h2 a');
		astra_responsive_font_size('astra-settings[font-size-h3]', 'h3, .entry-content h3, .entry-content h3 a');
		astra_responsive_font_size('astra-settings[font-size-h4]', 'h4, .entry-content h4, .entry-content h4 a');
		astra_responsive_font_size('astra-settings[font-size-h5]', 'h5, .entry-content h5, .entry-content h5 a');
		astra_responsive_font_size('astra-settings[font-size-h6]', 'h6, .entry-content h6, .entry-content h6 a');
	} else {
		astra_responsive_font_size('astra-settings[font-size-h1]', 'h1, .entry-content h1');
		astra_responsive_font_size('astra-settings[font-size-h2]', 'h2, .entry-content h2');
		astra_responsive_font_size('astra-settings[font-size-h3]', 'h3, .entry-content h3');
		astra_responsive_font_size('astra-settings[font-size-h4]', 'h4, .entry-content h4');
		astra_responsive_font_size('astra-settings[font-size-h5]', 'h5, .entry-content h5');
		astra_responsive_font_size('astra-settings[font-size-h6]', 'h6, .entry-content h6');
	}

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

	// Check if anchors should be loaded in the CSS for headings.
	if (true == astraCustomizer.includeAnchorsInHeadindsCss) {
		astra_css('astra-settings[headings-text-transform]', 'text-transform', 'h1, .entry-content h1, .entry-content h1 a, h2, .entry-content h2, .entry-content h2 a, h3, .entry-content h3, .entry-content h3 a, h4, .entry-content h4, .entry-content h4 a, h5, .entry-content h5, .entry-content h5 a, h6, .entry-content h6, .entry-content h6 a');
	} else {
		astra_css('astra-settings[headings-text-transform]', 'text-transform', 'h1, .entry-content h1, h2, .entry-content h2, h3, .entry-content h3, h4, .entry-content h4, h5, .entry-content h5, h6, .entry-content h6');		
	}


	// Footer Bar.
	astra_generate_css( 'astra-settings[footer-bar-content-group]', 'footer-color', '.ast-small-footer', 'color' );
	astra_generate_css( 'astra-settings[footer-bar-content-group]', 'footer-link-color', '.ast-small-footer a', 'color' );
	astra_generate_css( 'astra-settings[footer-bar-content-group]', 'footer-link-h-color', '.ast-small-footer a:hover', 'color' );
	astra_apply_background_css( 'astra-settings[footer-bar-background-group]', 'footer-bg-obj', ' .ast-small-footer > .ast-footer-overlay ' );
	// Footer Widgets.
	astra_generate_css( 'astra-settings[footer-widget-content-group]', 'footer-adv-wgt-title-color', '.footer-adv .widget-title, .footer-adv .widget-title a', 'color' );
	astra_generate_css( 'astra-settings[footer-widget-content-group]', 'footer-adv-text-color', '.footer-adv', 'color' );
	astra_generate_css( 'astra-settings[footer-widget-content-group]', 'footer-adv-link-color', '.footer-adv a', 'color' );
	astra_generate_css( 'astra-settings[footer-widget-content-group]', 'footer-adv-link-h-color', '.footer-adv a:hover, .footer-adv .no-widget-text a:hover, .footer-adv a:focus, .footer-adv .no-widget-text a:focus', 'color' );
	astra_apply_background_css( 'astra-settings[footer-widget-background-group]', 'footer-adv-bg-obj', '.footer-adv-overlay' );

	/*
	 * Woocommerce Shop Archive Custom Width
	 */
	wp.customize( 'astra-settings[shop-archive-max-width]', function( setting ) {
		setting.bind( function( width ) {

			var dynamicStyle = '@media all and ( min-width: 921px ) {';

			dynamicStyle += '.ast-woo-shop-archive .site-content > .ast-container{ max-width: ' + (  parseInt( width ) ) + 'px } ';

			if (  jQuery( 'body' ).hasClass( 'ast-fluid-width-layout' ) ) {
				dynamicStyle += '.ast-woo-shop-archive .site-content > .ast-container{ padding-left:20px; padding-right:20px; } ';
			}
				dynamicStyle += '}';
				astra_add_dynamic_css( 'shop-archive-max-width', dynamicStyle );

		} );
	} );

	//[1] Primary Menu Toggle Button Style.
	wp.customize( 'astra-settings[mobile-header-toggle-btn-style]', function( setting ) {
		setting.bind( function( icon_style ) {
			var buttons = $(document).find('.ast-mobile-menu-buttons .menu-toggle');
			buttons.removeClass('ast-mobile-menu-buttons-default ast-mobile-menu-buttons-fill ast-mobile-menu-buttons-outline');
			buttons.removeClass('ast-mobile-menu-buttons-default ast-mobile-menu-buttons-fill ast-mobile-menu-buttons-minimal');
			buttons.addClass( 'ast-mobile-menu-buttons-' + icon_style );
		} );
	} );

	//[1] Toggle Button Border Radius.
	wp.customize( 'astra-settings[mobile-header-toggle-btn-border-radius]', function( setting ) {
		setting.bind( function( border ) {

			var dynamicStyle = '.ast-header-break-point .main-header-bar .ast-button-wrap .menu-toggle { border-radius: ' + ( parseInt( border ) ) + 'px } ';
			astra_add_dynamic_css( 'mobile-header-toggle-btn-border-radius', dynamicStyle );

		} );
	} );

	/**
	 * Primary Submenu border 
	 */
	wp.customize( 'astra-settings[primary-submenu-border]', function( value ) {
		value.bind( function( border ) {
			var color = wp.customize( 'astra-settings[primary-submenu-b-color]' ).get();

			if( '' != border.top || '' != border.right || '' != border.bottom || '' != border.left ) {

				var dynamicStyle = '.ast-desktop .main-header-menu.submenu-with-border .sub-menu, .ast-desktop .main-header-menu.submenu-with-border .children';
					dynamicStyle += '{';
					dynamicStyle += 'border-top-width:'   + border.top + 'px;';
					dynamicStyle += 'border-right-width:'  + border.right + 'px;';
					dynamicStyle += 'border-left-width:'   + border.left + 'px;';
					dynamicStyle += 'border-bottom-width:'   + border.bottom + 'px;';
					dynamicStyle += 'border-color:'        + color + ';';
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += '}';

					dynamicStyle += '.ast-desktop .main-header-menu.submenu-with-border .sub-menu .sub-menu, .ast-desktop .main-header-menu.submenu-with-border .children .children';
					dynamicStyle += '{';
					dynamicStyle += 'top:-'   + border.top + 'px;';
					dynamicStyle += '}';

					// Submenu items goes outside?
					dynamicStyle += '@media (min-width: 769px){';
					dynamicStyle += '.main-header-menu .sub-menu li.ast-left-align-sub-menu:hover > ul, .main-header-menu .sub-menu li.ast-left-align-sub-menu.focus > ul';
					dynamicStyle += '{';
					dynamicStyle += 'margin-left:-'   + ( +border.left + +border.right ) + 'px;';
					dynamicStyle += '}';
					dynamicStyle += '}';

				astra_add_dynamic_css( 'primary-submenu-border', dynamicStyle );
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );
	/**
	 * Primary Submenu border COlor
	 */
	wp.customize( 'astra-settings[primary-submenu-b-color]', function( value ) {
		value.bind( function( color ) {
			var border = wp.customize( 'astra-settings[primary-submenu-border]' ).get();
			if ( '' != color ) {
				if( '' != border.top || '' != border.right || '' != border.bottom || '' != border.left ) {

					var dynamicStyle = '.ast-desktop .main-header-menu.submenu-with-border .sub-menu, .ast-desktop .main-header-menu.submenu-with-border .children';
					dynamicStyle += '{';
					dynamicStyle += 'border-top-width:'   + border.top + 'px;';
					dynamicStyle += 'border-right-width:'  + border.right + 'px;';
					dynamicStyle += 'border-left-width:'   + border.left + 'px;';
					dynamicStyle += 'border-bottom-width:'   + border.bottom + 'px;';
					dynamicStyle += 'border-color:'        + color + ';';
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += '}';

					dynamicStyle += '.ast-desktop .main-header-menu.submenu-with-border .sub-menu .sub-menu, .ast-desktop .main-header-menu.submenu-with-border .children .children';
					dynamicStyle += '{';
					dynamicStyle += 'top:-'   + border.top + 'px;';
					dynamicStyle += '}';

					// Submenu items goes outside?
					dynamicStyle += '@media (min-width: 769px){';
					dynamicStyle += '.main-header-menu .sub-menu li.ast-left-align-sub-menu:hover > ul, .main-header-menu .sub-menu li.ast-left-align-sub-menu.focus > ul';
					dynamicStyle += '{';
					dynamicStyle += 'margin-left:-'   + ( +border.left + +border.right ) + 'px;';
					dynamicStyle += '}';
					dynamicStyle += '}';

					astra_add_dynamic_css( 'primary-submenu-border-color', dynamicStyle );
				}
			} else {
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );


	/**
	 * Primary Submenu border COlor
	 */
	wp.customize('astra-settings[primary-submenu-item-b-color]', function (value) {
		value.bind(function (color) {
			var insideBorder = wp.customize('astra-settings[primary-submenu-item-border]').get();
			if ('' != color) {
				if ( true == insideBorder ) {

					var dynamicStyle = '';

					dynamicStyle += '.ast-desktop .main-header-menu.submenu-with-border .sub-menu a, .ast-desktop .main-header-menu.submenu-with-border .children a';
					dynamicStyle += '{';
					dynamicStyle += 'border-bottom-width:' + ( ( true === insideBorder ) ? '1px;' : '0px;' );
					dynamicStyle += 'border-color:' + color + ';';
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += '}';


					astra_add_dynamic_css('primary-submenu-item-b-color', dynamicStyle);
				}
			} else {
				wp.customize.preview.send('refresh');
			}
		});
	});

	/**
	 * Primary Submenu border COlor
	 */
	wp.customize( 'astra-settings[primary-submenu-item-border]', function( value ) {
		value.bind( function( border ) {
			var color = wp.customize( 'astra-settings[primary-submenu-item-b-color]' ).get();

			if( true === border  ) {
				var dynamicStyle = '.ast-desktop .main-header-menu.submenu-with-border .sub-menu a, .ast-desktop .main-header-menu.submenu-with-border .children a';
					dynamicStyle += '{';
					dynamicStyle += 'border-bottom-width:' + ( ( true === border ) ? '1px;' : '0px;' );
					dynamicStyle += 'border-color:'        + color + ';';
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += '}';

				astra_add_dynamic_css( 'primary-submenu-item-border', dynamicStyle );
			} else {
				wp.customize.preview.send( 'refresh' );
			}

		} );
	} );

	astra_generate_css( 'astra-settings[primary-header-button-color-group]', 'header-main-rt-section-button-text-color', '.main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button', 'color' );
	astra_generate_css( 'astra-settings[primary-header-button-color-group]', 'header-main-rt-section-button-back-color', '.main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button', 'background-color' );
	astra_generate_css( 'astra-settings[primary-header-button-color-group]', 'header-main-rt-section-button-text-h-color', '.main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button:hover', 'color' );
	astra_generate_css( 'astra-settings[primary-header-button-color-group]', 'header-main-rt-section-button-back-h-color', '.main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button:hover', 'background-color' );
	astra_responsive_spacing( 'astra-settings[header-main-rt-section-button-padding]','.main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button', 'padding', ['top', 'right', 'bottom', 'left' ] );
	astra_generate_css( 'astra-settings[primary-header-button-border-group]', 'header-main-rt-section-button-border-radius', '.main-header-bar .ast-container .button-custom-menu-item .ast-custom-button-link .ast-custom-button', 'border-radius', 'px' );
	astra_generate_css( 'astra-settings[primary-header-button-border-group]', 'header-main-rt-section-button-border-color', '.main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button', 'border-color' );
	astra_generate_css( 'astra-settings[primary-header-button-border-group]', 'header-main-rt-section-button-border-h-color', '.main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button:hover', 'border-color' );

	/**
	 * Button border
	 */
	wp.customize( 'astra-settings[transparent-header-button-border-group]', function( value ) {
		value.bind( function( value ) {

			var optionValue = JSON.parse(value);
			var border =  optionValue['header-main-rt-trans-section-button-border-size'];
			
			if( '' != border.top || '' != border.right || '' != border.bottom || '' != border.left ) {
				var dynamicStyle = '.ast-theme-transparent-header .main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button';
					dynamicStyle += '{';
					dynamicStyle += 'border-top-width:'  + border.top + 'px;';
					dynamicStyle += 'border-right-width:'  + border.right + 'px;';
					dynamicStyle += 'border-left-width:'   + border.left + 'px;';
					dynamicStyle += 'border-bottom-width:'   + border.bottom + 'px;';
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += '}';

				astra_add_dynamic_css( 'header-main-rt-trans-section-button-border-size', dynamicStyle );
			}
		} );
	} );

	// Site Title - Font family
	astra_generate_font_family_css( 'astra-settings[site-title-typography]', 'font-family-site-title', '.site-title, .site-title a' );

	// Site Title - Font Weight
	astra_generate_css( 'astra-settings[site-title-typography]', 'font-weight-site-title', '.site-title, .site-title a', 'font-weight' );

	// Site Title - Font Size
	astra_apply_responsive_font_size( 'astra-settings[site-title-typography]', 'font-size-site-title', '.site-title, .site-title a' );
	
	// Site Title - Line Height
	astra_generate_css( 'astra-settings[site-title-typography]', 'line-height-site-title', '.site-title, .site-title a', 'line-height' );
	
	// Site Title - Text Transform
	astra_generate_css( 'astra-settings[site-title-typography]', 'text-transform-site-title', '.site-title, .site-title a', 'text-transform' );


	// Site tagline - Font family
	astra_generate_font_family_css( 'astra-settings[site-tagline-typography]', 'font-family-site-tagline', '.site-header .site-description' );
	
	// Site Tagline - Font Weight
	astra_generate_css( 'astra-settings[site-tagline-typography]', 'font-weight-site-tagline', '.site-header .site-description', 'font-weight' );

	// Site Tagline - Font Size
	astra_apply_responsive_font_size( 'astra-settings[site-tagline-typography]', 'font-size-site-tagline', '.site-header .site-description' );

	// Site Tagline - Line Height
	astra_generate_css( 'astra-settings[site-tagline-typography]', 'line-height-site-tagline', '.site-header .site-description', 'line-height' );

	// Site Tagline - Text Transform
	astra_generate_css( 'astra-settings[site-tagline-typography]', 'text-transform-site-tagline', '.site-header .site-description', 'text-transform' );

	// Theme Button - Text Color
	astra_generate_css( 'astra-settings[theme-button-color-group]', 'button-color', '.menu-toggle, button, .ast-button, .button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]', 'color' );

	// Theme Button - Background Color
	astra_generate_css( 'astra-settings[theme-button-color-group]', 'button-bg-color', '.menu-toggle, button, .ast-button, .button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]', 'background-color' );

	// Theme Button - Border Color
	astra_generate_css( 'astra-settings[theme-button-color-group]', 'button-bg-color', '.menu-toggle, button, .ast-button, .button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]', 'border-color' );

	// Theme Button - Text Hover Color
	astra_generate_css( 'astra-settings[theme-button-color-group]', 'button-h-color', 'button:focus, .menu-toggle:hover, button:hover, .ast-button:hover, .button:hover, input[type=reset]:hover, input[type=reset]:focus, input#submit:hover, input#submit:focus, input[type="button"]:hover, input[type="button"]:focus, input[type="submit"]:hover, input[type="submit"]:focus', 'color' );

	// Theme Button - Background Hover Color
	astra_generate_css( 'astra-settings[theme-button-color-group]', 'button-bg-h-color', 'button:focus, .menu-toggle:hover, button:hover, .ast-button:hover, .button:hover, input[type=reset]:hover, input[type=reset]:focus, input#submit:hover, input#submit:focus, input[type="button"]:hover, input[type="button"]:focus, input[type="submit"]:hover, input[type="submit"]:focus', 'background-color' );

	// Theme Button - Border Hover Color
	astra_generate_css( 'astra-settings[theme-button-color-group]', 'button-bg-h-color', 'button:focus, .menu-toggle:hover, button:hover, .ast-button:hover, .button:hover, input[type=reset]:hover, input[type=reset]:focus, input#submit:hover, input#submit:focus, input[type="button"]:hover, input[type="button"]:focus, input[type="submit"]:hover, input[type="submit"]:focus', 'border-color' );

	// Theme Button - Border Radius Color
	astra_generate_css( 'astra-settings[theme-button-border-group]', 'button-radius', '.menu-toggle, button, .ast-button, .button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]', 'border-radius', 'px' );

	// Theme Button - Border Radius Color
	astra_generate_css( 'astra-settings[theme-button-border-group]', 'button-v-padding', '.menu-toggle, button, .ast-button, .button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]', 'padding-top', 'px' );
	astra_generate_css( 'astra-settings[theme-button-border-group]', 'button-v-padding', '.menu-toggle, button, .ast-button, .button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]', 'padding-bottom', 'px' );
	astra_generate_css( 'astra-settings[theme-button-border-group]', 'button-h-padding', '.menu-toggle, button, .ast-button, .button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]', 'padding-left', 'px' );
	astra_generate_css( 'astra-settings[theme-button-border-group]', 'button-h-padding', '.menu-toggle, button, .ast-button, .button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]', 'padding-right', 'px' );
} )( jQuery );