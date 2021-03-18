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
	var responsive_selector = '.astra-cart-drawer.woocommerce-active';

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

	// WooCommerce Cart Colors.

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-cart-text-color]',
		'color',
		'.astra-cart-drawer-title, .ast-site-header-cart-data span, .ast-site-header-cart-data strong, .ast-site-header-cart-data .woocommerce-mini-cart__empty-message, .ast-site-header-cart-data .total .woocommerce-Price-amount, .ast-site-header-cart-data .total .woocommerce-Price-amount .woocommerce-Price-currencySymbol, .ast-header-woo-cart .ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item a.remove,' + responsive_selector + ' .widget_shopping_cart_content span, '+ responsive_selector + ' .widget_shopping_cart_content strong,'+ responsive_selector + ' .woocommerce-mini-cart__empty-message, .astra-cart-drawer .woocommerce-mini-cart *, ' + responsive_selector + ' .astra-cart-drawer-title'
	);

	astra_color_responsive_css(
		'woo-cart-border-color',
		'astra-settings[header-woo-cart-text-color]',
		'border-color',
		'.ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item a.remove, '+ responsive_selector + ' .widget_shopping_cart .mini_cart_item a.remove'
	);

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-cart-link-color]',
		'color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart_content a:not(.button),' + responsive_selector + ' .widget_shopping_cart_content a:not(.button)'
	);

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-cart-background-color]',
		'background-color',
		'#ast-site-header-cart .widget_shopping_cart, .ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item a.remove:hover, .ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item:hover > a.remove,' + responsive_selector + ','+ responsive_selector + ' .widget_shopping_cart .mini_cart_item a.remove:hover,'+ responsive_selector + ' .widget_shopping_cart .mini_cart_item:hover > a.remove, #astra-mobile-cart-drawer' + responsive_selector
	);

	astra_color_responsive_css(
		'woo-cart-border-color',
		'astra-settings[header-woo-cart-background-color]',
		'border-color',
		'#ast-site-header-cart .widget_shopping_cart,' + responsive_selector + ' .widget_shopping_cart'
	);

	astra_color_responsive_css(
		'woo-cart-border-bottom-color',
		'astra-settings[header-woo-cart-background-color]',
		'border-bottom-color',
		'#ast-site-header-cart:hover .widget_shopping_cart:before, #ast-site-header-cart:hover .widget_shopping_cart:after, .open-preview-woocommerce-cart #ast-site-header-cart .widget_shopping_cart:before, #astra-mobile-cart-drawer:hover .widget_shopping_cart:before, #astra-mobile-cart-drawer:hover .widget_shopping_cart:after, #astra-mobile-cart-drawer .widget_shopping_cart:before'
	);

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-cart-separator-color]',
		'border-top-color',
		'#ast-site-header-cart .widget_shopping_cart .woocommerce-mini-cart__total, #astra-mobile-cart-drawer .widget_shopping_cart .woocommerce-mini-cart__total, .astra-cart-drawer .astra-cart-drawer-header'
	);

	astra_color_responsive_css(
		'woo-cart-border-bottom-colors',
		'astra-settings[header-woo-cart-separator-color]',
		'border-bottom-color',
		'#ast-site-header-cart .widget_shopping_cart .woocommerce-mini-cart__total, #astra-mobile-cart-drawer .widget_shopping_cart .woocommerce-mini-cart__total, .astra-cart-drawer .astra-cart-drawer-header, #ast-site-header-cart .widget_shopping_cart .mini_cart_item, #astra-mobile-cart-drawer .widget_shopping_cart .mini_cart_item'
	);

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-cart-link-hover-color]',
		'color',
		'.ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart_content a:not(.button):hover, .ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item a.remove:hover, .ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item:hover > a.remove,' + responsive_selector + ' .widget_shopping_cart_content a:not(.button):hover,'+ responsive_selector +' .widget_shopping_cart .mini_cart_item a.remove:hover,'+ responsive_selector + ' .widget_shopping_cart .mini_cart_item:hover > a.remove'
	);

	astra_color_responsive_css(
		'woo-cart-border-colors',
		'astra-settings[header-woo-cart-link-hover-color]',
		'border-color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item a.remove:hover,'+ selector + ' .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item:hover > a.remove,' + responsive_selector + ' .widget_shopping_cart .mini_cart_item a.remove:hover,'+ responsive_selector + ' .widget_shopping_cart .mini_cart_item:hover > a.remove'
	);

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-cart-btn-text-color]',
		'color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart_content a.button.wc-forward:not(.checkout),' + responsive_selector + ' .widget_shopping_cart_content a.button.wc-forward:not(.checkout)'
	);

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-cart-btn-background-color]',
		'background-color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart_content a.button.wc-forward:not(.checkout),' + responsive_selector + ' .widget_shopping_cart_content a.button.wc-forward:not(.checkout)'
	);

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-cart-btn-text-hover-color]',
		'color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart_content a.button.wc-forward:not(.checkout):hover,' + responsive_selector + ' .widget_shopping_cart_content a.button.wc-forward:not(.checkout):hover'
	);

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-cart-btn-bg-hover-color]',
		'background-color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart_content a.button.wc-forward:not(.checkout):hover,' + responsive_selector + ' .widget_shopping_cart_content a.button.wc-forward:not(.checkout):hover'
	);

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-checkout-btn-text-color]',
		'color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart_content a.button.checkout.wc-forward,' + responsive_selector + ' .widget_shopping_cart_content a.button.checkout.wc-forward'
	);

	astra_color_responsive_css(
		'woo-cart-colors',
		'astra-settings[header-woo-checkout-btn-background-color]',
		'border-color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart_content a.button.checkout.wc-forward,' + responsive_selector + ' .widget_shopping_cart_content a.button.checkout.wc-forward'
	);

	astra_color_responsive_css(
		'woo-cart-background-color',
		'astra-settings[header-woo-checkout-btn-background-color]',
		'background-color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart_content a.button.checkout.wc-forward,' + responsive_selector + ' .widget_shopping_cart_content a.button.checkout.wc-forward'
	);

	astra_color_responsive_css(
		'woo-cart-background-color',
		'astra-settings[header-woo-checkout-btn-text-hover-color]',
		'color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart_content a.button.checkout.wc-forward:hover,' + responsive_selector + ' .widget_shopping_cart_content a.button.checkout.wc-forward:hover'
	);

	astra_color_responsive_css(
		'woo-cart-background-color',
		'astra-settings[header-woo-checkout-btn-bg-hover-color]',
		'background-color',
		selector + ' .ast-site-header-cart-data .widget_shopping_cart_content a.button.checkout.wc-forward:hover,' + responsive_selector + ' .widget_shopping_cart_content a.button.checkout.wc-forward:hover'
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

	/**
	 * Transparent Header WOO-Cart color options - Customizer preview CSS.
	 */
	wp.customize( 'astra-settings[transparent-header-woo-cart-icon-color]', function( setting ) {
		setting.bind( function( cart_icon_color ) {
			wp.customize.preview.send( 'refresh' );
		});
	});

	// Advanced Visibility CSS Generation.
	astra_builder_visibility_css( 'section-header-woo-cart', '.ast-header-woo-cart' );

} )( jQuery );
