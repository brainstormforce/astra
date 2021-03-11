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
	var responsive_selector = '.astra-cart-drawer.edd-active';

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

	// EDD Cart Colors.
	astra_color_responsive_css(
		'edd-cart-colors', 
		'astra-settings[header-edd-cart-text-color]',
		'color',
		selector + ' .widget_edd_cart_widget span, ' + selector + ' .widget_edd_cart_widget strong, ' + selector + ' .widget_edd_cart_widget *, ' + responsive_selector + ' .widget_edd_cart_widget span, .astra-cart-drawer .widget_edd_cart_widget *, ' + responsive_selector + ' .astra-cart-drawer-title'
	);

	astra_color_responsive_css(
		'edd-cart-colors', 
		'astra-settings[header-edd-cart-link-color]',
		'color',
		selector + ' .widget_edd_cart_widget a, ' + selector + ' .widget_edd_cart_widget a.edd-remove-from-cart, ' + selector + ' .widget_edd_cart_widget .cart-total, ' + selector + ' .widget_edd_cart_widget a.edd-remove-from-cart:after, '+ responsive_selector + ' .widget_edd_cart_widget a, ' + responsive_selector + ' .widget_edd_cart_widget a.edd-remove-from-cart, ' + responsive_selector + ' .widget_edd_cart_widget .cart-total, ' + responsive_selector + ' .widget_edd_cart_widget a.edd-remove-from-cart:after'
	);
	astra_color_responsive_css(
		'edd-cart-colors', 
		'astra-settings[header-edd-cart-link-color]',
		'border-color',
		selector + ' .widget_edd_cart_widget a.edd-remove-from-cart:after,' + responsive_selector + ' .widget_edd_cart_widget a.edd-remove-from-cart:after,'
	);

	astra_color_responsive_css(
		'edd-cart-colors', 
		'astra-settings[header-edd-cart-background-color]',
		'background-color',
		'.ast-builder-layout-element ' + selector + ' .widget_edd_cart_widget ,' + responsive_selector + ', ' + responsive_selector + '#astra-mobile-cart-drawer'
	);

	astra_color_responsive_css(
		'edd-cart-border-color', 
		'astra-settings[header-edd-cart-background-color]',
		'border-color',
		'.ast-builder-layout-element ' + selector + ' .widget_edd_cart_widget, ' + responsive_selector + ' .widget_edd_cart_widget'
	);

	astra_color_responsive_css(
		'edd-cart-border-bottom-color', 
		'astra-settings[header-edd-cart-background-color]',
		'border-bottom-color',
		'.ast-builder-layout-element ' + selector + ' .widget_edd_cart_widget:before, .ast-builder-layout-element ' + selector + ' .widget_edd_cart_widget:after, ' + responsive_selector + ' .widget_edd_cart_widget:before, ' + responsive_selector + ' .widget_edd_cart_widget:after'
	);

	astra_color_responsive_css(
		'edd-cart-colors', 
		'astra-settings[header-edd-cart-separator-color]',
		'border-bottom-color',
		'.ast-builder-layout-element ' + selector + ' .widget_edd_cart_widget .edd-cart-item, .ast-builder-layout-element ' + selector + ' .widget_edd_cart_widget .edd-cart-number-of-items, .ast-builder-layout-element ' + selector + ' .widget_edd_cart_widget .edd-cart-meta,'+ responsive_selector + ' .widget_edd_cart_widget .edd-cart-item, ' + responsive_selector + ' .widget_edd_cart_widget .edd-cart-number-of-items, ' + responsive_selector + ' .widget_edd_cart_widget .edd-cart-meta, ' + responsive_selector + ' .astra-cart-drawer-header'
	);

	astra_color_responsive_css(
		'edd-cart-colors', 
		'astra-settings[header-edd-checkout-btn-text-color]',
		'color',
		selector + ' .widget_edd_cart_widget .edd_checkout a, .widget_edd_cart_widget .edd_checkout a, '+ responsive_selector + ' .widget_edd_cart_widget .edd_checkout a'
	);

	astra_color_responsive_css(
		'edd-cart-colors', 
		'astra-settings[header-edd-checkout-btn-background-color]',
		'background-color',
		selector + ' .widget_edd_cart_widget .edd_checkout a, .widget_edd_cart_widget .edd_checkout a,' + responsive_selector + ' .widget_edd_cart_widget .edd_checkout a, .widget_edd_cart_widget .edd_checkout a'
	);

	astra_color_responsive_css(
		'edd-cart-border-color', 
		'astra-settings[header-edd-checkout-btn-background-color]',
		'border-color',
		selector + ' .widget_edd_cart_widget .edd_checkout a, .widget_edd_cart_widget .edd_checkout a,' + responsive_selector + ' .widget_edd_cart_widget .edd_checkout a, .widget_edd_cart_widget .edd_checkout a'
	);

	astra_color_responsive_css(
		'edd-cart-colors', 
		'astra-settings[header-edd-checkout-btn-text-hover-color]',
		'color',
		selector +' .widget_edd_cart_widget .edd_checkout a:hover, .widget_edd_cart_widget .edd_checkout a:hover,' + responsive_selector +' .widget_edd_cart_widget .edd_checkout a:hover, .widget_edd_cart_widget .edd_checkout a:hover'
	);

	astra_color_responsive_css(
		'edd-cart-colors', 
		'astra-settings[header-edd-checkout-btn-bg-hover-color]',
		'background-color',
		selector +' .widget_edd_cart_widget .edd_checkout a:hover, .widget_edd_cart_widget .edd_checkout a:hover, ' + responsive_selector + ' .widget_edd_cart_widget .edd_checkout a:hover, .widget_edd_cart_widget .edd_checkout a:hover'
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
