<?php
/**
 * EDD Cart - Dynamic CSS
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
add_filter( 'astra_dynamic_theme_css', 'astra_hb_edd_cart_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Search.
 *
 * @since 3.0.0
 */
function astra_hb_edd_cart_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! Astra_Builder_Helper::is_component_loaded( 'edd-cart', 'header' ) ) {
		return $dynamic_css;
	}

	$selector                = '.ast-edd-site-header-cart';
	$theme_color             = astra_get_option( 'theme-color' );
	$link_color              = astra_get_option( 'link-color', $theme_color );
	$header_cart_icon_style  = astra_get_option( 'edd-header-cart-icon-style' );
	$header_cart_icon_color  = astra_get_option( 'edd-header-cart-icon-color', $theme_color );
	$header_cart_icon_radius = astra_get_option( 'edd-header-cart-icon-radius' );
	$cart_h_color            = astra_get_foreground_color( $header_cart_icon_color );

	$btn_color    = astra_get_option( 'button-color' );
	$btn_bg_color = astra_get_option( 'button-bg-color', $theme_color );

	if ( empty( $btn_color ) ) {
		$btn_color = astra_get_foreground_color( $theme_color );
	}

	if ( 'none' === $header_cart_icon_style ) {
		$header_cart_icon_color = $theme_color;
	}
	/**
	 * - EDD cart styles.
	 */
	$cart_text_color      = astra_get_option( 'header-edd-cart-text-color' );
	$cart_link_color      = astra_get_option( 'header-edd-cart-link-color', $link_color );
	$cart_bg_color        = astra_get_option( 'header-edd-cart-background-color' );
	$cart_separator_color = astra_get_option( 'header-edd-cart-separator-color' );

	$checkout_button_text_color   = astra_get_option( 'header-edd-checkout-btn-text-color', $btn_color );
	$checkout_button_bg_color     = astra_get_option( 'header-edd-checkout-btn-background-color', $btn_bg_color );
	$checkout_button_text_h_color = astra_get_option( 'header-edd-checkout-btn-text-hover-color', $btn_color );
	$checkout_button_bg_h_color   = astra_get_option( 'header-edd-checkout-btn-bg-hover-color', $btn_bg_color );

	$header_cart_icon        = '';
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
	
	/**
	 * EDD Cart CSS.
	 */
	$css_output_desktop = array(

		$selector . ' .ast-edd-cart-menu-wrap .count, ' . $selector . ' .ast-edd-cart-menu-wrap .count:after' => array(
			'color'        => $theme_color,
			'border-color' => $theme_color,
		),
		$selector . ' .ast-edd-cart-menu-wrap:hover .count' => array(
			'color'            => esc_attr( $cart_h_color ),
			'background-color' => esc_attr( $theme_color ),
		),
		$selector . ' .ast-icon-shopping-cart'        => array(
			'color' => $theme_color,
		),
		$selector . ' .ast-edd-header-cart-info-wrap' => array(
			'color' => esc_attr( $header_cart_icon_color ),
		),
		$selector . ' .ast-addon-cart-wrap span.astra-icon:after' => array(
			'color'            => esc_attr( $cart_h_color ),
			'background-color' => esc_attr( $header_cart_icon_color ),
		),
		/**
		 * General EDD Cart tray color for widget
		 */
		$selector . ' .widget_edd_cart_widget a, ' . $selector . ' .widget_edd_cart_widget a.edd-remove-from-cart, ' . $selector . ' .widget_edd_cart_widget .cart-total' => array(
			'color' => esc_attr( $cart_link_color_desktop ),
		),
		$selector . ' .widget_edd_cart_widget a.edd-remove-from-cart:after' => array(
			'color'        => esc_attr( $cart_link_color_desktop ),
			'border-color' => esc_attr( $cart_link_color_desktop ),
		),
		$selector . ' .widget_edd_cart_widget span, ' . $selector . ' .widget_edd_cart_widget strong, ' . $selector . ' .widget_edd_cart_widget *' => array(
			'color' => esc_attr( $cart_text_color_desktop ),
		),
		'.ast-builder-layout-element ' . $selector . ' .widget_edd_cart_widget' => array(
			'background-color' => esc_attr( $cart_bg_color_desktop ),
			'border-color'     => esc_attr( $cart_bg_color_desktop ),
		),
		'.ast-builder-layout-element ' . $selector . ' .widget_edd_cart_widget:before, .ast-builder-layout-element ' . $selector . ' .widget_edd_cart_widget:after' => array(
			'border-bottom-color' => esc_attr( $cart_bg_color_desktop ),
		),
		$selector . ' .widget_edd_cart_widget .edd-cart-item, ' . $selector . ' .widget_edd_cart_widget .edd-cart-number-of-items, ' . $selector . ' .widget_edd_cart_widget .edd-cart-meta' => array(
			'border-bottom-color' => esc_attr( $cart_separator_color_desktop ),
		),

		/**
		 * Checkout button color for widget
		 */
		'.ast-edd-site-header-cart .widget_edd_cart_widget .edd_checkout a, .widget_edd_cart_widget .edd_checkout a' => array(
			'color'            => esc_attr( $checkout_button_text_color_desktop ),
			'border-color'     => esc_attr( $checkout_button_bg_color_desktop ),
			'background-color' => esc_attr( $checkout_button_bg_color_desktop ),
		),
		'.ast-edd-site-header-cart .widget_edd_cart_widget .edd_checkout a:hover, .widget_edd_cart_widget .edd_checkout a:hover' => array(
			'color'            => esc_attr( $checkout_button_text_h_color_desktop ),
			'background-color' => esc_attr( $checkout_button_bg_h_color_desktop ),
		),
	);

	$css_output = astra_parse_css( $css_output_desktop );

	$responsive_selector = '.astra-cart-drawer.edd-active';
	
	$css_output_mobile = array(
		$responsive_selector . ' .widget_edd_cart_widget a, ' . $responsive_selector . ' .widget_edd_cart_widget a.edd-remove-from-cart, ' . $responsive_selector . ' .widget_edd_cart_widget .cart-total' => array(
			'color' => esc_attr( $cart_link_color_mobile ),
		),
		$selector . ' .widget_edd_cart_widget a.edd-remove-from-cart:after' => array(
			'color'        => esc_attr( $cart_link_color_mobile ),
			'border-color' => esc_attr( $cart_link_color_mobile ),
		),
		$responsive_selector . ' .astra-cart-drawer-title, ' . $responsive_selector . ' .widget_edd_cart_widget span, ' . $responsive_selector . ' .widget_edd_cart_widget strong, ' . $responsive_selector . ' .widget_edd_cart_widget *' => array(
			'color' => esc_attr( $cart_text_color_mobile ),
		),
		$responsive_selector => array(
			'background-color' => esc_attr( $cart_bg_color_mobile ),
			'border-color'     => esc_attr( $cart_bg_color_mobile ),
		),
		$responsive_selector . ' .widget_edd_cart_widget:before, .ast-builder-layout-element ' . $responsive_selector . ' .widget_edd_cart_widget:after' => array(
			'border-bottom-color' => esc_attr( $cart_bg_color_mobile ),
		),
		$responsive_selector . ' .widget_edd_cart_widget .edd-cart-item, ' . $responsive_selector . ' .widget_edd_cart_widget .edd-cart-number-of-items, ' . $responsive_selector . ' .widget_edd_cart_widget .edd-cart-meta, ' .
		$responsive_selector . ' .astra-cart-drawer-header' => array(
			'border-bottom-color' => esc_attr( $cart_separator_color_mobile ),
		),
		/**
		 * Checkout button color for widget
		 */
		$responsive_selector . ' .widget_edd_cart_widget .edd_checkout a, .widget_edd_cart_widget .edd_checkout a' => array(
			'color'            => esc_attr( $checkout_button_text_color_mobile ),
			'border-color'     => esc_attr( $checkout_button_bg_color_mobile ),
			'background-color' => esc_attr( $checkout_button_bg_color_mobile ),
		),
		$responsive_selector . ' .widget_edd_cart_widget .edd_checkout a:hover, .widget_edd_cart_widget .edd_checkout a:hover' => array(
			'color'            => esc_attr( $checkout_button_text_h_color_mobile ),
			'background-color' => esc_attr( $checkout_button_bg_h_color_mobile ),
		),
	);  

	$css_output_tablet = array(
		$responsive_selector . ' .widget_edd_cart_widget a, ' . $responsive_selector . ' .widget_edd_cart_widget a.edd-remove-from-cart, ' . $responsive_selector . ' .widget_edd_cart_widget .cart-total' => array(
			'color' => esc_attr( $cart_link_color_tablet ),
		),
		$selector . ' .widget_edd_cart_widget a.edd-remove-from-cart:after' => array(
			'color'        => esc_attr( $cart_link_color_tablet ),
			'border-color' => esc_attr( $cart_link_color_tablet ),
		),
		$responsive_selector . ' .astra-cart-drawer-title, ' . $responsive_selector . ' .widget_edd_cart_widget span, ' . $responsive_selector . ' .widget_edd_cart_widget strong, ' . $responsive_selector . ' .widget_edd_cart_widget *' => array(
			'color' => esc_attr( $cart_text_color_tablet ),
		),
		$responsive_selector => array(
			'background-color' => esc_attr( $cart_bg_color_tablet ),
			'border-color'     => esc_attr( $cart_bg_color_tablet ),
		),
		$responsive_selector . ' .widget_edd_cart_widget:before, .ast-builder-layout-element ' . $responsive_selector . ' .widget_edd_cart_widget:after' => array(
			'border-bottom-color' => esc_attr( $cart_bg_color_tablet ),
		),
		$responsive_selector . ' .widget_edd_cart_widget .edd-cart-item, ' . $responsive_selector . ' .widget_edd_cart_widget .edd-cart-number-of-items, ' . $responsive_selector . ' .widget_edd_cart_widget .edd-cart-meta, ' .
		$responsive_selector . ' .astra-cart-drawer-header' => array(
			'border-bottom-color' => esc_attr( $cart_separator_color_tablet ),
		),
		/**
		 * Checkout button color for widget
		 */
		$responsive_selector . ' .widget_edd_cart_widget .edd_checkout a, .widget_edd_cart_widget .edd_checkout a' => array(
			'color'            => esc_attr( $checkout_button_text_color_tablet ),
			'border-color'     => esc_attr( $checkout_button_bg_color_tablet ),
			'background-color' => esc_attr( $checkout_button_bg_color_tablet ),
		),
		$responsive_selector . ' .widget_edd_cart_widget .edd_checkout a:hover, .widget_edd_cart_widget .edd_checkout a:hover' => array(
			'color'            => esc_attr( $checkout_button_text_h_color_tablet ),
			'background-color' => esc_attr( $checkout_button_bg_h_color_tablet ),
		),
	);
	
	$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
	$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

	/**
	 * Header Cart color
	 */
	if ( 'none' !== $header_cart_icon_style ) {

		/**
		 * Header Cart Icon colors
		 */
		$header_cart_icon = array(

			$selector . ' .ast-edd-cart-menu-wrap .count' => array(
				'color'        => esc_attr( astra_get_option( 'edd-header-cart-icon-color' ) ),
				'border-color' => esc_attr( astra_get_option( 'edd-header-cart-icon-color' ) ),
			),
			$selector . ' .ast-edd-cart-menu-wrap .count:after' => array(
				'color'        => esc_attr( astra_get_option( 'edd-header-cart-icon-color' ) ),
				'border-color' => esc_attr( astra_get_option( 'edd-header-cart-icon-color' ) ),
			),
			$selector . ' .ast-icon-shopping-cart'        => array(
				'color' => esc_attr( astra_get_option( 'edd-header-cart-icon-color' ) ),
			),

			// Default icon colors.
			'.ast-edd-cart-menu-wrap .count, .ast-edd-cart-menu-wrap .count:after' => array(
				'border-color' => esc_attr( $header_cart_icon_color ),
				'color'        => esc_attr( $header_cart_icon_color ),
			),
			// Outline icon hover colors.
			$selector . ' .ast-edd-cart-menu-wrap:hover .count' => array(
				'color'            => esc_attr( $cart_h_color ),
				'background-color' => esc_attr( $header_cart_icon_color ),
			),
			// Outline icon colors.
			'.ast-edd-menu-cart-outline .ast-addon-cart-wrap' => array(
				'background' => '#ffffff',
				'border'     => '1px solid ' . $header_cart_icon_color,
				'color'      => esc_attr( $header_cart_icon_color ),
			),
			// Outline Info colors.
			$selector . ' .ast-menu-cart-outline .ast-edd-header-cart-info-wrap' => array(
				'color' => esc_attr( $header_cart_icon_color ),
			),
			// Fill icon Color.
			'.ast-edd-site-header-cart.ast-edd-menu-cart-fill .ast-edd-cart-menu-wrap .count,.ast-edd-menu-cart-fill .ast-addon-cart-wrap, .ast-edd-menu-cart-fill .ast-addon-cart-wrap .ast-edd-header-cart-info-wrap, .ast-edd-menu-cart-fill .ast-addon-cart-wrap .ast-icon-shopping-cart' => array(
				'background-color' => esc_attr( $header_cart_icon_color ),
				'color'            => esc_attr( $cart_h_color ),
			),

			// Border radius.
			'.ast-edd-site-header-cart.ast-edd-menu-cart-outline .ast-addon-cart-wrap, .ast-edd-site-header-cart.ast-edd-menu-cart-fill .ast-addon-cart-wrap, .ast-edd-site-header-cart.ast-edd-menu-cart-outline .count, .ast-edd-site-header-cart.ast-edd-menu-cart-fill .count, .ast-edd-site-header-cart.ast-edd-menu-cart-outline .ast-addon-cart-wrap .ast-edd-header-cart-info-wrap, .ast-edd-site-header-cart.ast-edd-menu-cart-fill .ast-addon-cart-wrap .ast-edd-header-cart-info-wrap' => array(
				'border-radius' => astra_get_css_value( $header_cart_icon_radius, 'px' ),
			),
		);

		$header_cart_icon = astra_parse_css( $header_cart_icon );
	}

	/* Parse CSS from array() */
	$css_output .= $header_cart_icon;

	$css_output .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( 'section-header-edd-cart', '.ast-header-edd-cart' );

	$dynamic_css .= $css_output;

	return $dynamic_css;
}
