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
	$theme_color             = astra_get_option( 'theme-color' );
	$icon_color              = esc_attr( astra_get_option( 'header-woo-cart-icon-color', $theme_color ) );
	$header_cart_icon_radius = astra_get_option( 'woo-header-cart-icon-radius' );
	$cart_h_color            = astra_get_foreground_color( $icon_color );
	$header_cart_icon_style  = astra_get_option( 'woo-header-cart-icon-style' );
	$theme_h_color           = astra_get_foreground_color( $theme_color );
	
	$header_cart_icon = '';
	/**
	 * Woo Cart CSS.
	 */
	$css_output_desktop = array(

		$selector . ' .ast-cart-menu-wrap, ' . $selector . ' .ast-addon-cart-wrap'       => array(
			'color' => $theme_color,
		),
		$selector . ' .ast-cart-menu-wrap .count, ' . $selector . ' .ast-cart-menu-wrap .count:after, ' . $selector . ' .ast-addon-cart-wrap .count, ' . $selector . ' .ast-addon-cart-wrap .ast-icon-shopping-cart:after' => array(
			'color'        => $theme_color,
			'border-color' => $theme_color,
		),
		$selector . ' .ast-addon-cart-wrap .ast-icon-shopping-cart:after'  => array(
			'color'            => esc_attr( $theme_h_color ),
			'background-color' => esc_attr( $theme_color ),
		),
	);

	if ( 'none' != $header_cart_icon_style ) {

		$header_cart_icon = array(

			$selector . ' .ast-cart-menu-wrap, ' . $selector . ' .ast-addon-cart-wrap'       => array(
				'color' => $icon_color,
			),
			$selector . ' .ast-cart-menu-wrap .count, ' . $selector . ' .ast-cart-menu-wrap .count:after, ' . $selector . ' .ast-addon-cart-wrap .count, ' . $selector . ' .ast-addon-cart-wrap .ast-icon-shopping-cart:after' => array(
				'color'        => $icon_color,
				'border-color' => $icon_color,
			),

			// Default icon colors.
			'.ast-site-header-cart .ast-cart-menu-wrap .count, .ast-site-header-cart .ast-cart-menu-wrap .count:after, .ast-site-header-cart .ast-addon-cart-wrap .count' => array(
				'border-color' => esc_attr( $icon_color ),
				'color'        => esc_attr( $icon_color ),
			),
			// Outline icon hover colors.
			'.ast-site-header-cart .ast-cart-menu-wrap:hover .count, .ast-site-header-cart .ast-addon-cart-wrap:hover .count' => array(
				'color'            => esc_attr( $cart_h_color ),
				'background-color' => esc_attr( $icon_color ),
			),
			// Outline icon colors.
			'.ast-menu-cart-outline .ast-cart-menu-wrap .count, .ast-menu-cart-outline .ast-addon-cart-wrap' => array(
				'background' => '#ffffff',
				'border'     => '2px solid ' . $icon_color,
				'color'      => esc_attr( $icon_color ),
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

			// Border radius.
			'.ast-site-header-cart.ast-menu-cart-outline .ast-cart-menu-wrap, .ast-site-header-cart.ast-menu-cart-fill .ast-cart-menu-wrap, .ast-site-header-cart.ast-menu-cart-outline .ast-cart-menu-wrap .count, .ast-site-header-cart.ast-menu-cart-fill .ast-cart-menu-wrap .count, .ast-site-header-cart.ast-menu-cart-outline .ast-addon-cart-wrap, .ast-site-header-cart.ast-menu-cart-fill .ast-addon-cart-wrap, .ast-site-header-cart.ast-menu-cart-outline .ast-woo-header-cart-info-wrap, .ast-site-header-cart.ast-menu-cart-fill .ast-woo-header-cart-info-wrap' => array(
				'border-radius' => astra_get_css_value( $header_cart_icon_radius, 'px' ),
			),
		);

		$header_cart_icon = astra_parse_css( $header_cart_icon );
	}
	
	/* Parse CSS from array() */
	$css_output  = astra_parse_css( $css_output_desktop );
	$css_output .= $header_cart_icon;

	$css_output .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( 'section-header-woo-cart', '.ast-header-woo-cart' );

	$dynamic_css .= $css_output;

	return $dynamic_css;
}
