<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * 1. Functions which can be used for doing some operations on the values.
 * 2. Third party plugins compatibility functions.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

/**
 * Function to get Body Font Family
 */
if ( ! function_exists( 'astra_body_font_family' ) ) {

	/**
	 * Function to get Body Font Family
	 *
	 * @since 1.0.0
	 * @return string
	 */
	function astra_body_font_family() {

		$font_family = astra_get_option( 'body-font-family' );

		// Body Font Family.
		if ( 'inherit' == $font_family ) {
			$font_family = '-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen-Sans, Ubuntu, Cantarell, Helvetica Neue, sans-serif';
		}

		return apply_filters( 'astra_body_font_family', $font_family );
	}
}

/**
 * Function to Add Header Breakpoint Style
 */
if ( ! function_exists( 'astra_header_breakpoint_style' ) ) {

	/**
	 * Function to Add Header Breakpoint Style
	 *
	 * @param  string $dynamic_css          Astra Dynamic CSS.
	 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
	 * @since 1.5.2 Remove ob_start, ob_get_clean and .main-header-bar-wrap::before{content} for our .ast-header-break-point class
	 * @since 1.0.0
	 */
	function astra_header_breakpoint_style( $dynamic_css, $dynamic_css_filtered = '' ) {

		// Header Break Point.
		$header_break_point = astra_header_break_point();

		$astra_header_width = astra_get_option( 'header-main-layout-width' );

		/* Width for Header */
		if ( 'content' != $astra_header_width ) {
			$genral_global_responsive = array(
				'#masthead .ast-container, .ast-header-breadcrumb .ast-container' => array(
					'max-width'     => '100%',
					'padding-left'  => '35px',
					'padding-right' => '35px',
				),
			);
			$padding_below_breakpoint = array(
				'#masthead .ast-container, .ast-header-breadcrumb .ast-container' => array(
					'padding-left'  => '20px',
					'padding-right' => '20px',
				),
			);

			/* Parse CSS from array()*/
			$dynamic_css .= astra_parse_css( $genral_global_responsive );
			$dynamic_css .= astra_parse_css( $padding_below_breakpoint, '', $header_break_point );

			// trim white space for faster page loading.
			$dynamic_css .= Astra_Enqueue_Scripts::trim_css( $dynamic_css );
		}

		return $dynamic_css;
	}
}

add_filter( 'astra_dynamic_theme_css', 'astra_header_breakpoint_style' );

/**
 * Function to filter comment form arguments
 */
if ( ! function_exists( 'astra_404_page_layout' ) ) {

	/**
	 * Function filter comment form arguments
	 *
	 * @since 1.0.0
	 * @param array $layout     Comment form arguments.
	 * @return array
	 */
	function astra_404_page_layout( $layout ) {

		if ( is_404() ) {
			$layout = 'no-sidebar';
		}

		return apply_filters( 'astra_404_page_layout', $layout );
	}
}

add_filter( 'astra_page_layout', 'astra_404_page_layout', 10, 1 );

/**
 * Return current content layout
 */
if ( ! function_exists( 'astra_get_content_layout' ) ) {

	/**
	 * Return current content layout
	 *
	 * @since 1.0.0
	 * @return boolean  content layout.
	 */
	function astra_get_content_layout() {

		$value = false;

		if ( is_singular() ) {

			// If post meta value is empty,
			// Then get the POST_TYPE content layout.
			$content_layout = astra_get_option_meta( 'site-content-layout', '', true );

			if ( empty( $content_layout ) ) {

				$post_type = get_post_type();

				if ( 'post' === $post_type || 'page' === $post_type ) {
					$content_layout = astra_get_option( 'single-' . get_post_type() . '-content-layout' );
				}

				if ( 'default' == $content_layout || empty( $content_layout ) ) {

					// Get the GLOBAL content layout value.
					// NOTE: Here not used `true` in the below function call.
					$content_layout = astra_get_option( 'site-content-layout', 'full-width' );
				}
			}
		} else {

			$content_layout = '';
			$post_type      = get_post_type();

			if ( 'post' === $post_type ) {
				$content_layout = astra_get_option( 'archive-' . get_post_type() . '-content-layout' );
			}

			if ( is_search() ) {
				$content_layout = astra_get_option( 'archive-post-content-layout' );
			}

			if ( 'default' == $content_layout || empty( $content_layout ) ) {

				// Get the GLOBAL content layout value.
				// NOTE: Here not used `true` in the below function call.
				$content_layout = astra_get_option( 'site-content-layout', 'full-width' );
			}
		}

		return apply_filters( 'astra_get_content_layout', $content_layout );
	}
}

/**
 * Function to check if it is Internet Explorer
 */
if ( ! function_exists( 'astra_check_is_ie' ) ) :

	/**
	 * Function to check if it is Internet Explorer.
	 *
	 * @return true | false boolean
	 */
	function astra_check_is_ie() {

		$is_ie = false;

		if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$ua = htmlentities( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), ENT_QUOTES, 'UTF-8' );
			if ( strpos( $ua, 'Trident/7.0' ) !== false ) {
				$is_ie = true;
			}
		}

		return apply_filters( 'astra_check_is_ie', $is_ie );
	}

endif;

/**
 * Replace header logo.
 */
if ( ! function_exists( 'astra_replace_header_logo' ) ) :

	/**
	 * Replace header logo.
	 *
	 * @param array  $image Size.
	 * @param int    $attachment_id Image id.
	 * @param sting  $size Size name.
	 * @param string $icon Icon.
	 *
	 * @return array Size of image
	 */
	function astra_replace_header_logo( $image, $attachment_id, $size, $icon ) {

		$custom_logo_id = get_theme_mod( 'custom_logo' );

		if ( ! is_customize_preview() && $custom_logo_id == $attachment_id && 'full' == $size ) {

			$data = wp_get_attachment_image_src( $attachment_id, 'ast-logo-size' );

			if ( false != $data ) {
				$image = $data;
			}
		}

		return apply_filters( 'astra_replace_header_logo', $image );
	}

endif;

if ( ! function_exists( 'astra_strposa' ) ) :

	/**
	 * Strpos over an array.
	 *
	 * @since  1.2.4
	 * @param  String  $haystack The string to search in.
	 * @param  Array   $needles  Array of needles to be passed to strpos().
	 * @param  integer $offset   If specified, search will start this number of characters counted from the beginning of the string. If the offset is negative, the search will start this number of characters counted from the end of the string.
	 *
	 * @return bool            True if haystack if part of any of the $needles.
	 */
	function astra_strposa( $haystack, $needles, $offset = 0 ) {

		if ( ! is_array( $needles ) ) {
			$needles = array( $needles );
		}

		foreach ( $needles as $query ) {

			if ( strpos( $haystack, $query, $offset ) !== false ) {
				// stop on first true result.
				return true;
			}
		}

		return false;
	}

endif;

if ( ! function_exists( 'astra_get_prop' ) ) :

	/**
	 * Get a specific property of an array without needing to check if that property exists.
	 *
	 * Provide a default value if you want to return a specific value if the property is not set.
	 *
	 * @since  1.2.7
	 * @access public
	 * @author Gravity Forms - Easiest Tool to Create Advanced Forms for Your WordPress-Powered Website.
	 * @link  https://www.gravityforms.com/
	 *
	 * @param array  $array   Array from which the property's value should be retrieved.
	 * @param string $prop    Name of the property to be retrieved.
	 * @param string $default Optional. Value that should be returned if the property is not set or empty. Defaults to null.
	 *
	 * @return null|string|mixed The value
	 */
	function astra_get_prop( $array, $prop, $default = null ) {

		if ( ! is_array( $array ) && ! ( is_object( $array ) && $array instanceof ArrayAccess ) ) {
			return $default;
		}

		if ( ( isset( $array[ $prop ] ) && false === $array[ $prop ] ) ) {
			return false;
		}

		if ( isset( $array[ $prop ] ) ) {
			$value = $array[ $prop ];
		} else {
			$value = '';
		}

		return empty( $value ) && null !== $default ? $default : $value;
	}

endif;

/**
 * Build list of attributes into a string and apply contextual filter on string.
 *
 * The contextual filter is of the form `astra_attr_{context}_output`.
 *
 * @since 1.6.2
 * @credits - Genesis Theme By StudioPress.
 *
 * @param string $context    The context, to build filter name.
 * @param array  $attributes Optional. Extra attributes to merge with defaults.
 * @param array  $args       Optional. Custom data to pass to filter.
 * @return string String of HTML attributes and values.
 */
function astra_attr( $context, $attributes = array(), $args = array() ) {
	return Astra_Attr::get_instance()->astra_attr( $context, $attributes, $args );
}

/**
 * Check the WordPress version.
 *
 * @since  2.5.4
 * @param string $version   WordPress version to compare with the current version.
 * @param string $compare   Comparison value i.e > or < etc.
 * @return bool            True/False based on the  $version and $compare value.
 */
function astra_wp_version_compare( $version, $compare ) {

	return version_compare( get_bloginfo( 'version' ), $version, $compare );
}

/**
 * Get the theme author details 
 *
 * @since  3.1.0
 * @return array            Return theme author URL and name.
 */
function astra_get_theme_author_details() {

	$theme_author = apply_filters(
		'astra_theme_author',
		array(
			'theme_name'       => __( 'Astra WordPress Theme', 'astra' ),
			'theme_author_url' => 'https://wpastra.com/',
		)
	);

	return $theme_author;
}

/**
 * Remove Base Color > Background Color option from the customize array.
 *
 * @since 2.4.0
 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
 * @return $wp_customize
 */
function astra_remove_controls( $wp_customize ) {

	if ( defined( 'ASTRA_EXT_VER' ) && version_compare( ASTRA_EXT_VER, '2.4.0', '<=' ) ) {
		$layout = array(
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[site-layout-outside-bg-obj]',
				'type'      => 'control',
				'transport' => 'postMessage',
				'control'   => 'ast-hidden',
				'section'   => 'section-colors-body',
				'priority'  => 25,
			),
		);

		$wp_customize = array_merge( $wp_customize, $layout );
	}

	return $wp_customize;
}

add_filter( 'astra_customizer_configurations', 'astra_remove_controls', 99 );

/**
 * Is theme existing header footer configs enable.
 *
 * @since 3.0.0
 *
 * @return boolean true/false.
 */
function astra_existing_header_footer_configs() {

	return apply_filters( 'astra_existing_header_footer_configs', true );
}

/**
 * Get Spacing value
 *
 * @param  array  $value        Responsive spacing value with unit.
 * @param  string $operation    + | - | * | /.
 * @param  string $from         Perform operation from the value.
 * @param  string $from_unit    Perform operation from the value of unit.
 *
 * @since 3.0.0
 * @return mixed
 */
function astra_calculate_spacing( $value, $operation = '', $from = '', $from_unit = '' ) {

	$css = '';
	if ( ! empty( $value ) ) {
		$css = $value;
		if ( ! empty( $operation ) && ! empty( $from ) ) {
			if ( ! empty( $from_unit ) ) {
				$css = 'calc( ' . $value . ' ' . $operation . ' ' . $from . $from_unit . ' )';
			}
			if ( '*' === $operation || '/' === $operation ) {
				$css = 'calc( ' . $value . ' ' . $operation . ' ' . $from . ' )';
			}
		}
	}

	return $css;
}
