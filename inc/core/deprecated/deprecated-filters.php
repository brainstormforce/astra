<?php
/**
 * Deprecated Filters of Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.23
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Depreciating astra_color_palletes filter.
add_filter( 'astra_color_palettes', 'astra_deprecated_color_palette', 10, 1 );

/**
 * Astra Color Palettes
 *
 * @since 1.0.23
 * @param array $color_palette  customizer color palettes.
 * @return array  $color_palette updated customizer color palettes.
 */
function astra_deprecated_color_palette( $color_palette ) {

	$color_palette = astra_apply_filters_deprecated( 'astra_color_palletes', array( $color_palette ), '1.0.22', 'astra_color_palettes', '' );

	return $color_palette;
}


// Deprecating astra_sigle_post_navigation_enabled filter.
add_filter( 'astra_single_post_navigation_enabled', 'astra_deprecated_sigle_post_navigation_enabled', 10, 1 );

/**
 * Astra Single Post Navigation
 *
 * @since 1.0.27
 * @param boolean $post_nav true | false.
 * @return boolean $post_nav true for enabled | false for disable.
 */
function astra_deprecated_sigle_post_navigation_enabled( $post_nav ) {

	$post_nav = astra_apply_filters_deprecated( 'astra_sigle_post_navigation_enabled', array( $post_nav ), '1.0.27', 'astra_single_post_navigation_enabled', '' );

	return $post_nav;
}

// Deprecating astra_primary_header_main_rt_section filter.
add_filter( 'astra_header_section_elements', 'astra_deprecated_primary_header_main_rt_section', 10, 2 );

/**
 * Astra Header elements.
 *
 * @since 1.2.2
 * @param array  $elements List of elements.
 * @param string $header Header section type.
 * @return array
 */
function astra_deprecated_primary_header_main_rt_section( $elements, $header ) {

	$elements = astra_apply_filters_deprecated( 'astra_primary_header_main_rt_section', array( $elements, $header ), '1.2.2', 'astra_header_section_elements', '' );

	return $elements;
}

if ( ! function_exists( 'astra_apply_filters_deprecated' ) ) {
	/**
	 * Astra Filter Deprecated
	 *
	 * @since 1.1.1
	 * @param string $tag         The name of the filter hook.
	 * @param array  $args        Array of additional function arguments to be passed to apply_filters().
	 * @param string $version     The version of WordPress that deprecated the hook.
	 * @param string $replacement Optional. The hook that should have been used. Default false.
	 * @param string $message     Optional. A message regarding the change. Default null.
	 */
	function astra_apply_filters_deprecated( $tag, $args, $version, $replacement = false, $message = null ) {
		if ( function_exists( 'apply_filters_deprecated' ) ) { /* WP >= 4.6 */
			return apply_filters_deprecated( $tag, $args, $version, $replacement, $message );
		} else {
			return apply_filters_ref_array( $tag, $args );
		}
	}
}

