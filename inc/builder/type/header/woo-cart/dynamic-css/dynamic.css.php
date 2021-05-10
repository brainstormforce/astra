<?php
/**
 * WooCommerce Cart - Dynamic CSS
 *
 * @package Astra
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Search
 */
add_filter( 'astra_dynamic_theme_css', 'astra_hb_woo_cart_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Search.
 *
 * @since 3.0.0
 */
function astra_hb_woo_cart_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! Astra_Builder_Helper::is_component_loaded( 'woo-cart', 'header' ) ) {
		return $dynamic_css;
	}

	$selector                = '.ast-site-header-cart';
	$trans_header_selector   = '.ast-theme-transparent-header .ast-site-header-cart';
	$theme_color             = astra_get_option( 'theme-color' );
	$icon_color              = esc_attr( astra_get_option( 'header-woo-cart-icon-color', $theme_color ) );
	$header_cart_icon_radius = astra_get_option( 'woo-header-cart-icon-radius' );
	$cart_h_color            = astra_get_foreground_color( $icon_color );
	$header_cart_icon_style  = astra_get_option( 'woo-header-cart-icon-style' );
	$theme_h_color           = astra_get_foreground_color( $theme_color );

	$transparent_header_icon_color   = esc_attr( astra_get_option( 'transparent-header-woo-cart-icon-color', $icon_color ) );
	$transparent_header_cart_h_color = astra_get_foreground_color( $transparent_header_icon_color );

	if ( 'none' === $header_cart_icon_style ) {
		$icon_color                    = $theme_color;
		$transparent_header_icon_color = $theme_color;
	}

	/**
	 * - WooCommerce cart styles.
	 */
	$cart_text_color      = astra_get_option( 'header-woo-cart-text-color' );
	$cart_link_color      = astra_get_option( 'header-woo-cart-link-color' );
	$cart_bg_color        = astra_get_option( 'header-woo-cart-background-color' );
	$cart_separator_color = astra_get_option( 'header-woo-cart-separator-color' );
	$cart_h_link_color    = astra_get_option( 'header-woo-cart-link-hover-color' );

	$cart_button_text_color   = astra_get_option( 'header-woo-cart-btn-text-color' );
	$cart_button_bg_color     = astra_get_option( 'header-woo-cart-btn-background-color' );
	$cart_button_text_h_color = astra_get_option( 'header-woo-cart-btn-text-hover-color' );
	$cart_button_bg_h_color   = astra_get_option( 'header-woo-cart-btn-bg-hover-color' );

	$checkout_button_text_color   = astra_get_option( 'header-woo-checkout-btn-text-color' );
	$checkout_button_bg_color     = astra_get_option( 'header-woo-checkout-btn-background-color' );
	$checkout_button_text_h_color = astra_get_option( 'header-woo-checkout-btn-text-hover-color' );
	$checkout_button_bg_h_color   = astra_get_option( 'header-woo-checkout-btn-bg-hover-color' );

	$header_cart_icon = '';

	$cart_text_color_desktop = ( ! empty( $cart_text_color['desktop'] ) ) ? $cart_text_color['desktop'] : '';
	$cart_text_color_mobile  = ( ! empty( $cart_text_color['mobile'] ) ) ? $cart_text_color['mobile'] : '';
	$cart_text_color_tablet  = ( ! empty( $cart_text_color['tablet'] ) ) ? $cart_text_color['tablet'] : '';

	$cart_bg_color_desktop = ( ! empty( $cart_bg_color['desktop'] ) ) ? $cart_bg_color['desktop'] : '';
	$cart_bg_color_mobile  = ( ! empty( $cart_bg_color['mobile'] ) ) ? $cart_bg_color['mobile'] : '';
	$cart_bg_color_tablet  = ( ! empty( $cart_bg_color['tablet'] ) ) ? $cart_bg_color['tablet'] : '';

	$cart_link_color_desktop = ( ! empty( $cart_link_color['desktop'] ) ) ? $cart_link_color['desktop'] : '';
	$cart_link_color_mobile  = ( ! empty( $cart_link_color['mobile'] ) ) ? $cart_link_color['mobile'] : '';
	$cart_link_color_tablet  = ( ! empty( $cart_link_color['tablet'] ) ) ? $cart_link_color['tablet'] : '';

	$cart_separator_color_desktop = ( ! empty( $cart_separator_color['desktop'] ) ) ? $cart_separator_color['desktop'] : '';
	$cart_separator_color_mobile  = ( ! empty( $cart_separator_color['mobile'] ) ) ? $cart_separator_color['mobile'] : '';
	$cart_separator_color_tablet  = ( ! empty( $cart_separator_color['tablet'] ) ) ? $cart_separator_color['tablet'] : '';

	$cart_h_link_color_desktop = ( ! empty( $cart_h_link_color['desktop'] ) ) ? $cart_h_link_color['desktop'] : '';
	$cart_h_link_color_mobile  = ( ! empty( $cart_h_link_color['mobile'] ) ) ? $cart_h_link_color['mobile'] : '';
	$cart_h_link_color_tablet  = ( ! empty( $cart_h_link_color['tablet'] ) ) ? $cart_h_link_color['tablet'] : '';

	$checkout_button_text_color_desktop = ( ! empty( $checkout_button_text_color['desktop'] ) ) ? $checkout_button_text_color['desktop'] : '';
	$checkout_button_text_color_mobile  = ( ! empty( $checkout_button_text_color['mobile'] ) ) ? $checkout_button_text_color['mobile'] : '';
	$checkout_button_text_color_tablet  = ( ! empty( $checkout_button_text_color['tablet'] ) ) ? $checkout_button_text_color['tablet'] : '';

	$checkout_button_bg_color_desktop = ( ! empty( $checkout_button_bg_color['desktop'] ) ) ? $checkout_button_bg_color['desktop'] : '';
	$checkout_button_bg_color_mobile  = ( ! empty( $checkout_button_bg_color['mobile'] ) ) ? $checkout_button_bg_color['mobile'] : '';
	$checkout_button_bg_color_tablet  = ( ! empty( $checkout_button_bg_color['tablet'] ) ) ? $checkout_button_bg_color['tablet'] : '';

	$checkout_button_text_h_color_desktop = ( ! empty( $checkout_button_text_h_color['desktop'] ) ) ? $checkout_button_text_h_color['desktop'] : '';
	$checkout_button_text_h_color_mobile  = ( ! empty( $checkout_button_text_h_color['mobile'] ) ) ? $checkout_button_text_h_color['mobile'] : '';
	$checkout_button_text_h_color_tablet  = ( ! empty( $checkout_button_text_h_color['tablet'] ) ) ? $checkout_button_text_h_color['tablet'] : '';

	$checkout_button_bg_h_color_desktop = ( ! empty( $checkout_button_bg_h_color['desktop'] ) ) ? $checkout_button_bg_h_color['desktop'] : '';
	$checkout_button_bg_h_color_mobile  = ( ! empty( $checkout_button_bg_h_color['mobile'] ) ) ? $checkout_button_bg_h_color['mobile'] : '';
	$checkout_button_bg_h_color_tablet  = ( ! empty( $checkout_button_bg_h_color['tablet'] ) ) ? $checkout_button_bg_h_color['tablet'] : '';

	$cart_button_text_color_desktop = ( ! empty( $cart_button_text_color['desktop'] ) ) ? $cart_button_text_color['desktop'] : '';
	$cart_button_text_color_mobile  = ( ! empty( $cart_button_text_color['mobile'] ) ) ? $cart_button_text_color['mobile'] : '';
	$cart_button_text_color_tablet  = ( ! empty( $cart_button_text_color['tablet'] ) ) ? $cart_button_text_color['tablet'] : '';

	$cart_button_bg_color_desktop = ( ! empty( $cart_button_bg_color['desktop'] ) ) ? $cart_button_bg_color['desktop'] : '';
	$cart_button_bg_color_mobile  = ( ! empty( $cart_button_bg_color['mobile'] ) ) ? $cart_button_bg_color['mobile'] : '';
	$cart_button_bg_color_tablet  = ( ! empty( $cart_button_bg_color['tablet'] ) ) ? $cart_button_bg_color['tablet'] : '';

	$cart_button_text_h_color_desktop = ( ! empty( $cart_button_text_h_color['desktop'] ) ) ? $cart_button_text_h_color['desktop'] : '';
	$cart_button_text_h_color_mobile  = ( ! empty( $cart_button_text_h_color['mobile'] ) ) ? $cart_button_text_h_color['mobile'] : '';
	$cart_button_text_h_color_tablet  = ( ! empty( $cart_button_text_h_color['tablet'] ) ) ? $cart_button_text_h_color['tablet'] : '';

	$cart_button_bg_h_color_desktop = ( ! empty( $cart_button_bg_h_color['desktop'] ) ) ? $cart_button_bg_h_color['desktop'] : '';
	$cart_button_bg_h_color_mobile  = ( ! empty( $cart_button_bg_h_color['mobile'] ) ) ? $cart_button_bg_h_color['mobile'] : '';
	$cart_button_bg_h_color_tablet  = ( ! empty( $cart_button_bg_h_color['tablet'] ) ) ? $cart_button_bg_h_color['tablet'] : '';

	/**
	 * Woo Cart CSS.
	 */
	$css_output_desktop = array(

		$selector . ' .ast-cart-menu-wrap, ' . $selector . ' .ast-addon-cart-wrap' => array(
			'color' => $icon_color,
		),
		$selector . ' .ast-cart-menu-wrap .count, ' . $selector . ' .ast-cart-menu-wrap .count:after, ' . $selector . ' .ast-addon-cart-wrap .count, ' . $selector . ' .ast-addon-cart-wrap .ast-icon-shopping-cart:after' => array(
			'color'        => $icon_color,
			'border-color' => $icon_color,
		),
		$selector . ' .ast-addon-cart-wrap .ast-icon-shopping-cart:after' => array(
			'color'            => esc_attr( $theme_h_color ),
			'background-color' => esc_attr( $icon_color ),
		),
		$selector . ' .ast-woo-header-cart-info-wrap' => array(
			'color' => esc_attr( $icon_color ),
		),
		$selector . ' .ast-addon-cart-wrap i.astra-icon:after' => array(
			'color'            => esc_attr( $theme_h_color ),
			'background-color' => esc_attr( $icon_color ),
		),
		/**
		 * Transparent Header - Woo Cart icon color.
		 */
		$trans_header_selector . ' .ast-cart-menu-wrap, ' . $trans_header_selector . ' .ast-addon-cart-wrap' => array(
			'color' => $transparent_header_icon_color,
		),
		$trans_header_selector . ' .ast-cart-menu-wrap .count, ' . $trans_header_selector . ' .ast-cart-menu-wrap .count:after, ' . $trans_header_selector . ' .ast-addon-cart-wrap .count, ' . $trans_header_selector . ' .ast-addon-cart-wrap .ast-icon-shopping-cart:after' => array(
			'color'        => $transparent_header_icon_color,
			'border-color' => $transparent_header_icon_color,
		),
		$trans_header_selector . ' .ast-addon-cart-wrap .ast-icon-shopping-cart:after' => array(
			'color'            => esc_attr( $theme_h_color ),
			'background-color' => esc_attr( $transparent_header_icon_color ),
		),
		$trans_header_selector . ' .ast-woo-header-cart-info-wrap' => array(
			'color' => esc_attr( $transparent_header_icon_color ),
		),
		$trans_header_selector . ' .ast-addon-cart-wrap i.astra-icon:after' => array(
			'color'            => esc_attr( $theme_h_color ),
			'background-color' => esc_attr( $transparent_header_icon_color ),
		),
		/**
		 * General Woo Cart tray color for widget
		 */
		'.ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart_content a:not(.button)' => array(
			'color' => esc_attr( $cart_link_color_desktop ),
		),
		'.ast-site-header-cart-data span, .ast-site-header-cart-data strong, .ast-site-header-cart-data .woocommerce-mini-cart__empty-message, .ast-site-header-cart-data .total .woocommerce-Price-amount, .ast-site-header-cart-data .total .woocommerce-Price-amount .woocommerce-Price-currencySymbol, .ast-header-woo-cart .ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item a.remove' => array(
			'color' => esc_attr( $cart_text_color_desktop ),
		),
		'.ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart_content a:not(.button):hover' => array(
			'color' => esc_attr( $cart_h_link_color_desktop ),
		),
		'#ast-site-header-cart .widget_shopping_cart' => array(
			'background-color' => esc_attr( $cart_bg_color_desktop ),
			'border-color'     => esc_attr( $cart_bg_color_desktop ),
		),
		'#ast-site-header-cart .widget_shopping_cart .woocommerce-mini-cart__total, .astra-cart-drawer .astra-cart-drawer-header' => array(
			'border-top-color'    => esc_attr( $cart_separator_color_desktop ),
			'border-bottom-color' => esc_attr( $cart_separator_color_desktop ),
		),
		'#ast-site-header-cart .widget_shopping_cart .mini_cart_item' => array(
			'border-bottom-color' => astra_hex_to_rgba( $cart_separator_color_desktop ),
		),
		'#ast-site-header-cart:hover .widget_shopping_cart:before, #ast-site-header-cart:hover .widget_shopping_cart:after, .open-preview-woocommerce-cart #ast-site-header-cart .widget_shopping_cart:before' => array(
			'border-bottom-color' => esc_attr( $cart_bg_color_desktop ),
		),
		'.ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item a.remove' => array(
			'border-color' => esc_attr( $cart_text_color_desktop ),
		),
		'.ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item a.remove:hover, .ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart .mini_cart_item:hover > a.remove' => array(
			'color'            => esc_attr( $cart_h_link_color_desktop ),
			'border-color'     => esc_attr( $cart_h_link_color_desktop ),
			'background-color' => esc_attr( $cart_bg_color_desktop ),
		),

		/**
		 * Cart button color for widget
		 */
		'.ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart_content a.button.wc-forward:not(.checkout)' => array(
			'color'            => esc_attr( $cart_button_text_color_desktop ),
			'background-color' => esc_attr( $cart_button_bg_color_desktop ),
		),
		'.ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart_content a.button.wc-forward:not(.checkout):hover' => array(
			'color'            => esc_attr( $cart_button_text_h_color_desktop ),
			'background-color' => esc_attr( $cart_button_bg_h_color_desktop ),
		),

		/**
		 * Checkout button color for widget
		 */
		'.ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart_content a.button.checkout.wc-forward' => array(
			'color'            => esc_attr( $checkout_button_text_color_desktop ),
			'border-color'     => esc_attr( $checkout_button_bg_color_desktop ),
			'background-color' => esc_attr( $checkout_button_bg_color_desktop ),
		),
		'.ast-site-header-cart .ast-site-header-cart-data .widget_shopping_cart_content a.button.checkout.wc-forward:hover' => array(
			'color'            => esc_attr( $checkout_button_text_h_color_desktop ),
			'background-color' => esc_attr( $checkout_button_bg_h_color_desktop ),
		),
	);

	/* Parse CSS from array() */
	$css_output          = astra_parse_css( $css_output_desktop );
	$responsive_selector = '.astra-cart-drawer.woocommerce-active';

	$css_output_mobile = array(
		$responsive_selector . ' .astra-cart-drawer-title, ' . $responsive_selector . ' .widget_shopping_cart_content span, ' . $responsive_selector . ' .widget_shopping_cart_content strong,' . $responsive_selector . ' .woocommerce-mini-cart__empty-message, .astra-cart-drawer .woocommerce-mini-cart *' => array(
			'color' => esc_attr( $cart_text_color_mobile ),
		),
		$responsive_selector . ' .widget_shopping_cart .mini_cart_item a.remove' => array(
			'border-color' => esc_attr( $cart_text_color_mobile ),
		),
		$responsive_selector . '#astra-mobile-cart-drawer' => array(
			'background-color' => esc_attr( $cart_bg_color_mobile ),
			'border-color'     => esc_attr( $cart_bg_color_mobile ),
		),
		'#astra-mobile-cart-drawer:hover .widget_shopping_cart:before, #astra-mobile-cart-drawer:hover .widget_shopping_cart:after, .open-preview-woocommerce-cart #astra-mobile-cart-drawer .widget_shopping_cart:before' => array(
			'border-bottom-color' => esc_attr( $cart_bg_color_mobile ),
		),
		$responsive_selector . ' .widget_shopping_cart .mini_cart_item a.remove:hover,' . $responsive_selector . ' .widget_shopping_cart .mini_cart_item:hover > a.remove' => array(
			'color'            => esc_attr( $cart_h_link_color_mobile ),
			'border-color'     => esc_attr( $cart_h_link_color_mobile ),
			'background-color' => esc_attr( $cart_bg_color_mobile ),
		),
		$responsive_selector . ' .widget_shopping_cart_content a:not(.button)' => array(
			'color' => esc_attr( $cart_link_color_mobile ),
		),
		'#astra-mobile-cart-drawer .widget_shopping_cart .woocommerce-mini-cart__total, .astra-cart-drawer.woocommerce-active .astra-cart-drawer-header' => array(
			'border-top-color'    => esc_attr( $cart_separator_color_mobile ),
			'border-bottom-color' => esc_attr( $cart_separator_color_mobile ),
		),
		'#astra-mobile-cart-drawer .widget_shopping_cart .mini_cart_item' => array(
			'border-bottom-color' => astra_hex_to_rgba( $cart_separator_color_mobile ),
		),
		$responsive_selector . ' .widget_shopping_cart_content a:not(.button):hover' => array(
			'color' => esc_attr( $cart_h_link_color_mobile ),
		),
		/**
		 * Checkout button color for widget
		 */
		$responsive_selector . ' .widget_shopping_cart_content a.button.checkout.wc-forward' => array(
			'color'            => esc_attr( $checkout_button_text_color_mobile ),
			'border-color'     => esc_attr( $checkout_button_bg_color_mobile ),
			'background-color' => esc_attr( $checkout_button_bg_color_mobile ),
		),
		$responsive_selector . ' .widget_shopping_cart_content a.button.checkout.wc-forward:hover' => array(
			'color'            => esc_attr( $checkout_button_text_h_color_mobile ),
			'background-color' => esc_attr( $checkout_button_bg_h_color_mobile ),
		),

		/**
		 * Cart button color for widget
		 */
		$responsive_selector . ' .widget_shopping_cart_content a.button.wc-forward:not(.checkout)' => array(
			'color'            => esc_attr( $cart_button_text_color_mobile ),
			'background-color' => esc_attr( $cart_button_bg_color_mobile ),
		),
		$responsive_selector . ' .widget_shopping_cart_content a.button.wc-forward:not(.checkout):hover' => array(
			'color'            => esc_attr( $cart_button_text_h_color_mobile ),
			'background-color' => esc_attr( $cart_button_bg_h_color_mobile ),
		),

	);

	$css_output_tablet = array(
		$responsive_selector . ' .astra-cart-drawer-title, ' . $responsive_selector . ' .widget_shopping_cart_content span, ' . $responsive_selector . ' .widget_shopping_cart_content strong,' . $responsive_selector . ' .woocommerce-mini-cart__empty-message, .astra-cart-drawer .woocommerce-mini-cart *' => array(
			'color' => esc_attr( $cart_text_color_tablet ),
		),
		$responsive_selector . ' .widget_shopping_cart .mini_cart_item a.remove' => array(
			'border-color' => esc_attr( $cart_text_color_tablet ),
		),
		$responsive_selector . '#astra-mobile-cart-drawer' => array(
			'background-color' => esc_attr( $cart_bg_color_tablet ),
			'border-color'     => esc_attr( $cart_bg_color_tablet ),
		),
		'#astra-mobile-cart-drawer:hover .widget_shopping_cart:before, #astra-mobile-cart-drawer:hover .widget_shopping_cart:after, .open-preview-woocommerce-cart #astra-mobile-cart-drawer .widget_shopping_cart:before' => array(
			'border-bottom-color' => esc_attr( $cart_bg_color_tablet ),
		),
		$responsive_selector . ' .widget_shopping_cart .mini_cart_item a.remove:hover,' . $responsive_selector . ' .widget_shopping_cart .mini_cart_item:hover > a.remove' => array(
			'color'            => esc_attr( $cart_h_link_color_tablet ),
			'border-color'     => esc_attr( $cart_h_link_color_tablet ),
			'background-color' => esc_attr( $cart_bg_color_tablet ),
		),
		$responsive_selector . ' .widget_shopping_cart_content a:not(.button)' => array(
			'color' => esc_attr( $cart_link_color_tablet ),
		),
		'#astra-mobile-cart-drawer .widget_shopping_cart .woocommerce-mini-cart__total, .astra-cart-drawer .astra-cart-drawer-header' => array(
			'border-top-color'    => esc_attr( $cart_separator_color_tablet ),
			'border-bottom-color' => esc_attr( $cart_separator_color_tablet ),
		),
		'#astra-mobile-cart-drawer .widget_shopping_cart .mini_cart_item' => array(
			'border-bottom-color' => astra_hex_to_rgba( $cart_separator_color_tablet ),
		),
		$responsive_selector . ' .widget_shopping_cart_content a:not(.button):hover' => array(
			'color' => esc_attr( $cart_h_link_color_tablet ),
		),
		/**
		 * Checkout button color for widget
		 */
		$responsive_selector . ' .widget_shopping_cart_content a.button.checkout.wc-forward' => array(
			'color'            => esc_attr( $checkout_button_text_color_tablet ),
			'border-color'     => esc_attr( $checkout_button_bg_color_tablet ),
			'background-color' => esc_attr( $checkout_button_bg_color_tablet ),
		),
		$responsive_selector . ' .widget_shopping_cart_content a.button.checkout.wc-forward:hover' => array(
			'color'            => esc_attr( $checkout_button_text_h_color_tablet ),
			'background-color' => esc_attr( $checkout_button_bg_h_color_tablet ),
		),

		/**
		 * Cart button color for widget
		 */
		$responsive_selector . ' .widget_shopping_cart_content a.button.wc-forward:not(.checkout)' => array(
			'color'            => esc_attr( $cart_button_text_color_tablet ),
			'background-color' => esc_attr( $cart_button_bg_color_tablet ),
		),
		$responsive_selector . ' .widget_shopping_cart_content a.button.wc-forward:not(.checkout):hover' => array(
			'color'            => esc_attr( $cart_button_text_h_color_tablet ),
			'background-color' => esc_attr( $cart_button_bg_h_color_tablet ),
		),

	);

	$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
	$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

	if ( 'none' !== $header_cart_icon_style ) {

		$header_cart_icon = array(

			$selector . ' .ast-cart-menu-wrap, ' . $selector . ' .ast-addon-cart-wrap'       => array(
				'color' => $icon_color,
			),
			// Outline icon hover colors.
			'.ast-site-header-cart .ast-cart-menu-wrap:hover .count, .ast-site-header-cart .ast-addon-cart-wrap:hover .count' => array(
				'color'            => esc_attr( $cart_h_color ),
				'background-color' => esc_attr( $icon_color ),
			),
			// Outline icon colors.
			'.ast-menu-cart-outline .ast-cart-menu-wrap .count, .ast-menu-cart-outline .ast-addon-cart-wrap' => array(
				'border' => '2px solid ' . $icon_color,
				'color'  => esc_attr( $icon_color ),
			),
			// Outline Info colors.
			$selector . ' .ast-menu-cart-outline .ast-woo-header-cart-info-wrap' => array(
				'color' => esc_attr( $icon_color ),
			),

			// Fill icon Color.
			'.ast-menu-cart-fill .ast-cart-menu-wrap .count,.ast-menu-cart-fill .ast-cart-menu-wrap, .ast-menu-cart-fill .ast-addon-cart-wrap .ast-woo-header-cart-info-wrap,.ast-menu-cart-fill .ast-addon-cart-wrap' => array(
				'background-color' => esc_attr( $icon_color ),
				'color'            => esc_attr( $cart_h_color ),
			),

			// Transparent Header - Cart Icon color.
			$trans_header_selector . ' .ast-cart-menu-wrap, ' . $trans_header_selector . ' .ast-addon-cart-wrap'       => array(
				'color' => $transparent_header_icon_color,
			),
			// Outline icon hover colors.
			'.ast-theme-transparent-header .ast-site-header-cart .ast-cart-menu-wrap:hover .count, .ast-theme-transparent-header .ast-site-header-cart .ast-addon-cart-wrap:hover .count' => array(
				'color'            => esc_attr( $transparent_header_cart_h_color ),
				'background-color' => esc_attr( $transparent_header_icon_color ),
			),
			// Outline icon colors.
			'.ast-theme-transparent-header .ast-menu-cart-outline .ast-cart-menu-wrap .count, .ast-theme-transparent-header .ast-menu-cart-outline .ast-addon-cart-wrap' => array(
				'border' => '2px solid ' . $transparent_header_icon_color,
				'color'  => esc_attr( $transparent_header_icon_color ),
			),
			// Outline Info colors.
			$trans_header_selector . ' .ast-menu-cart-outline .ast-woo-header-cart-info-wrap' => array(
				'color' => esc_attr( $transparent_header_icon_color ),
			),

			// Fill icon Color.
			'.ast-theme-transparent-header .ast-menu-cart-fill .ast-cart-menu-wrap .count, .ast-theme-transparent-header .ast-menu-cart-fill .ast-cart-menu-wrap, .ast-theme-transparent-header .ast-menu-cart-fill .ast-addon-cart-wrap .ast-woo-header-cart-info-wrap, .ast-theme-transparent-header .ast-menu-cart-fill .ast-addon-cart-wrap' => array(
				'background-color' => esc_attr( $transparent_header_icon_color ),
				'color'            => esc_attr( $transparent_header_cart_h_color ),
			),

			// Border radius.
			'.ast-site-header-cart.ast-menu-cart-outline .ast-cart-menu-wrap, .ast-site-header-cart.ast-menu-cart-fill .ast-cart-menu-wrap, .ast-site-header-cart.ast-menu-cart-outline .ast-cart-menu-wrap .count, .ast-site-header-cart.ast-menu-cart-fill .ast-cart-menu-wrap .count, .ast-site-header-cart.ast-menu-cart-outline .ast-addon-cart-wrap, .ast-site-header-cart.ast-menu-cart-fill .ast-addon-cart-wrap, .ast-site-header-cart.ast-menu-cart-outline .ast-woo-header-cart-info-wrap, .ast-site-header-cart.ast-menu-cart-fill .ast-woo-header-cart-info-wrap' => array(
				'border-radius' => astra_get_css_value( $header_cart_icon_radius, 'px' ),
			),
		);

		$css_output .= astra_parse_css( $header_cart_icon );
	}

	$css_output .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( 'section-header-woo-cart', '.ast-header-woo-cart' );

	$dynamic_css .= $css_output;

	return $dynamic_css;
}
