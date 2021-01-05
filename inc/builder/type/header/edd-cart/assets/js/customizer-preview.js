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

	var selector = '.ast-edd-site-header-cart';

	// Icon Color.
	astra_css(
		'astra-settings[edd-header-cart-icon-color]',
		'color',
		selector + ' .ast-edd-cart-menu-wrap .count, ' + selector + ' .ast-edd-cart-menu-wrap .count:after,' + selector + ' .ast-edd-header-cart-info-wrap' 
	);

	// Icon Color.
	astra_css(
		'astra-settings[edd-header-cart-icon-color]',
		'border-color',
		selector + ' .ast-edd-cart-menu-wrap .count, ' + selector + ' .ast-edd-cart-menu-wrap .count:after'
	);

	/**
	 * Cart icon style
	 */
	wp.customize( 'astra-settings[edd-header-cart-icon-style]', function( setting ) {
		setting.bind( function( icon_style ) {
				var buttons = $(document).find('.ast-edd-site-header-cart');
				buttons.removeClass('ast-edd-menu-cart-fill ast-edd-menu-cart-outline');
				buttons.addClass( 'ast-edd-menu-cart-' + icon_style );
				var dynamicStyle = '.ast-edd-site-header-cart a, .ast-edd-site-header-cart a *{ transition: all 0s; } ';
				astra_add_dynamic_css( 'edd-header-cart-icon-style', dynamicStyle );
				wp.customize.preview.send( 'refresh' );
		} );
	} );

	/**
	 * EDD Cart Button Color
	 */
	wp.customize( 'astra-settings[edd-header-cart-icon-color]', function( setting ) {
		setting.bind( function( cart_icon_color ) {
			
				wp.customize.preview.send( 'refresh' );
		});
	});

	/**
	 * Button Border Radius
	 */
	wp.customize( 'astra-settings[edd-header-cart-icon-radius]', function( setting ) {
		setting.bind( function( border ) {

			var dynamicStyle = '.ast-edd-site-header-cart.ast-edd-menu-cart-outline .ast-addon-cart-wrap, .ast-edd-site-header-cart.ast-edd-menu-cart-fill .ast-addon-cart-wrap, .ast-edd-site-header-cart.ast-edd-menu-cart-outline .count, .ast-edd-site-header-cart.ast-edd-menu-cart-fill .count{ border-radius: ' + ( parseInt( border ) ) + 'px } ';
			astra_add_dynamic_css( 'edd-header-cart-icon-radius', dynamicStyle );

		} );
	} );

	// Advanced Visibility CSS Generation.
	astra_builder_visibility_css( 'section-header-edd-cart', '.ast-header-edd-cart' );

} )( jQuery );
