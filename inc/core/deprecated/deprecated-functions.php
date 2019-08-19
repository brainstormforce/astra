<?php
/**
 * Deprecated Functions of Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.23
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'astra_blog_post_thumbnai_and_title_order' ) ) :

	/**
	 * Blog post thumbnail & title order
	 *
	 * @since 1.4.9
	 * @deprecated 1.4.9 Use astra_blog_post_thumbnail_and_title_order()
	 * @see astra_blog_post_thumbnail_and_title_order()
	 *
	 * @return void
	 */
	function astra_blog_post_thumbnai_and_title_order() {
		_deprecated_function( __FUNCTION__, '1.4.9', 'astra_blog_post_thumbnail_and_title_order()' );

		astra_blog_post_thumbnail_and_title_order();
	}

endif;

if ( ! function_exists( 'get_astra_secondary_class' ) ) :

	/**
	 * Retrieve the classes for the secondary element as an array.
	 *
	 * @since 1.5.2
	 * @deprecated 1.5.2 Use astra_get_secondary_class()
	 * @param string|array $class One or more classes to add to the class list.
	 * @see astra_get_secondary_class()
	 *
	 * @return array
	 */
	function get_astra_secondary_class( $class = '' ) {
		_deprecated_function( __FUNCTION__, '1.5.2', 'astra_get_secondary_class()' );

		return astra_get_secondary_class( $class );
	}

endif;

if ( ! function_exists( 'deprecated_astra_color_palette' ) ) :

	/**
	 * Depreciating astra_color_palletes filter.
	 *
	 * @since 1.5.2
	 * @deprecated 1.5.2 Use astra_deprecated_color_palette()
	 * @param array $color_palette  customizer color palettes.
	 * @see astra_deprecated_color_palette()
	 *
	 * @return array
	 */
	function deprecated_astra_color_palette( $color_palette ) {
		_deprecated_function( __FUNCTION__, '1.5.2', 'astra_deprecated_color_palette()' );

		return astra_deprecated_color_palette( $color_palette );
	}

endif;

if ( ! function_exists( 'deprecated_astra_sigle_post_navigation_enabled' ) ) :

	/**
	 * Deprecating astra_sigle_post_navigation_enabled filter.
	 *
	 * @since 1.5.2
	 * @deprecated 1.5.2 Use astra_deprecated_sigle_post_navigation_enabled()
	 * @param boolean $post_nav true | false.
	 * @see astra_deprecated_sigle_post_navigation_enabled()
	 *
	 * @return array
	 */
	function deprecated_astra_sigle_post_navigation_enabled( $post_nav ) {
		_deprecated_function( __FUNCTION__, '1.5.2', 'astra_deprecated_sigle_post_navigation_enabled()' );

		return astra_deprecated_sigle_post_navigation_enabled( $post_nav );
	}

endif;

if ( ! function_exists( 'deprecated_astra_primary_header_main_rt_section' ) ) :

	/**
	 * Deprecating astra_primary_header_main_rt_section filter.
	 *
	 * @since 1.5.2
	 * @deprecated 1.5.2 Use astra_deprecated_primary_header_main_rt_section()
	 * @param array  $elements List of elements.
	 * @param string $header Header section type.
	 * @see astra_deprecated_primary_header_main_rt_section()
	 *
	 * @return array
	 */
	function deprecated_astra_primary_header_main_rt_section( $elements, $header ) {
		_deprecated_function( __FUNCTION__, '1.5.2', 'astra_deprecated_primary_header_main_rt_section()' );

		return astra_deprecated_primary_header_main_rt_section( $elements, $header );
	}

endif;

if ( ! function_exists( 'astar' ) ) :

	/**
	 * Get a specific property of an array without needing to check if that property exists.
	 *
	 * @since 1.5.2
	 * @deprecated 1.5.2 Use astra_get_prop()
	 * @param array  $array   Array from which the property's value should be retrieved.
	 * @param string $prop    Name of the property to be retrieved.
	 * @param string $default Optional. Value that should be returned if the property is not set or empty. Defaults to null.
	 * @see astra_get_prop()
	 *
	 * @return null|string|mixed The value
	 */
	function astar( $array, $prop, $default = null ) {
		return astra_get_prop( $array, $prop, $default );
	}

endif;
