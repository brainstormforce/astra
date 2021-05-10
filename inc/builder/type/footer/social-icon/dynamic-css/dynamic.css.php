<?php
/**
 * Social Icons control - Dynamic CSS
 *
 * @package Astra Builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Social Icons Colors
 */
add_filter( 'astra_dynamic_theme_css', 'astra_fb_social_icon_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Social Icons Colors.
 *
 * @since 3.0.0
 */
function astra_fb_social_icon_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$dynamic_css .= Astra_Social_Component_Dynamic_CSS::astra_social_dynamic_css( 'footer' );

	return $dynamic_css;
}
