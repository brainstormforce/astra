/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra
 * @since x.x.x
 */

( function( $ ) {

	var selector = '.ast-site-header-cart';

	// Icon Color.
	astra_css(
		'astra-settings[header-woo-cart-icon-color]',
		'color',
		selector + ' .ast-cart-menu-wrap .count, ' + selector + ' .ast-cart-menu-wrap .count:after,' + selector + ' .ast-woo-header-cart-info-wrap,' + selector + ' .ast-site-header-cart .ast-addon-cart-wrap' 
	);

	// Icon Color.
	astra_css(
		'astra-settings[header-woo-cart-icon-color]',
		'border-color',
		selector + ' .ast-cart-menu-wrap .count, ' + selector + ' .ast-cart-menu-wrap .count:after'
	);

	// Icon BG Color.
	astra_css(
		'astra-settings[header-woo-cart-icon-color]',
		'border-color',
		'.ast-menu-cart-fill .ast-cart-menu-wrap .count, .ast-menu-cart-fill .ast-cart-menu-wrap'
	);
	
	/**
	 * Cart icon style
	 */
	wp.customize( 'astra-settings[woo-header-cart-icon-style]', function( setting ) {
		setting.bind( function( icon_style ) {

			var buttons = $(document).find('.ast-site-header-cart');
			buttons.removeClass('ast-menu-cart-fill ast-menu-cart-outline ast-menu-cart-none');
			buttons.addClass( 'ast-menu-cart-' + icon_style );
			var dynamicStyle = '.ast-site-header-cart a, .ast-site-header-cart a *{ transition: all 0s; } ';
			astra_add_dynamic_css( 'woo-header-cart-icon-style', dynamicStyle );
			wp.customize.preview.send( 'refresh' );
		} );
	} );

	/**
	 * Cart icon style
	 */
	wp.customize( 'astra-settings[header-woo-cart-icon-color]', function( setting ) {
		setting.bind( function( color ) {


			var dynamicStyle = '.ast-menu-cart-fill .ast-cart-menu-wrap .count, .ast-menu-cart-fill .ast-cart-menu-wrap { background-color: ' + color + '; } ';
			dynamicStyle += '.ast-site-header-cart .ast-cart-menu-wrap .count, .ast-site-header-cart .ast-cart-menu-wrap .count:after { border-color: ' + color + '; } ';
			astra_add_dynamic_css( 'header-woo-cart-icon-color', dynamicStyle );
			wp.customize.preview.send( 'refresh' );
		} );
	} );

	/**
	 * Cart Border Radius
	 */
	wp.customize( 'astra-settings[woo-header-cart-icon-radius]', function( setting ) {
		setting.bind( function( radius ) {


			var dynamicStyle = '.ast-site-header-cart.ast-menu-cart-outline .ast-cart-menu-wrap, .ast-site-header-cart.ast-menu-cart-fill .ast-cart-menu-wrap, .ast-site-header-cart.ast-menu-cart-outline .ast-cart-menu-wrap .count, .ast-site-header-cart.ast-menu-cart-fill .ast-cart-menu-wrap .count, .ast-site-header-cart.ast-menu-cart-outline .ast-addon-cart-wrap, .ast-site-header-cart.ast-menu-cart-fill .ast-addon-cart-wrap { border-radius: ' + radius + 'px; } ';
			astra_add_dynamic_css( 'woo-header-cart-icon-radius', dynamicStyle );
		} );
	} );

	// Advanced Visibility CSS Generation.
	astra_builder_visibility_css( 'section-header-woo-cart', '.ast-header-woo-cart' );

} )( jQuery );
