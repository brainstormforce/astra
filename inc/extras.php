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
 * Add dropdown icon if menu item has children.
 *
 * @since 3.3.0
 *
 * @param string   $title The menu item title.
 * @param WP_Post  $item All of our menu item data.
 * @param stdClass $args All of our menu item args.
 * @param int      $depth Depth of menu item.
 * @return string The menu item.
 */
function astra_dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {
	$role     = 'presentation';
	$tabindex = '0';
	$icon     = '';

	/**
	 * These menus are not overriden by the 'Astra_Custom_Nav_Walker' class present in Addon - Nav Menu module.
	 *
	 * Hence skipping these menus from getting overriden by blank SVG Icons and adding the icons from theme.
	 *
	 * @since 3.3.0
	 */
	$astra_menu_locations = array(
		'ast-hf-menu-1',        // Builder - Primary menu.
		'ast-hf-menu-2',        // Builder - Secondary menu.
		'ast-hf-menu-3',
		'ast-hf-menu-4',
		'ast-hf-menu-5',
		'ast-hf-menu-6',
		'ast-hf-menu-7',
		'ast-hf-menu-8',
		'ast-hf-menu-9',
		'ast-hf-menu-10',       // Cloned builder menus.
		'ast-hf-mobile-menu',   // Builder - Mobile Menu.
		'ast-hf-account-menu',  // Builder - Login Account Menu.
		'primary-menu',         // Old header - Primary Menu.
		'above_header-menu',    // Old header - Above Menu.
		'below_header-menu',    // Old header - Below Menu.
	);

	$load_svg_menu_icons = false;

	if ( defined( 'ASTRA_EXT_VER' ) ) {
		// Check whether Astra Pro is active + Nav menu addon is deactivate + menu registered by Astra only.
		if ( ! Astra_Ext_Extension::is_active( 'nav-menu' ) && in_array( $args->menu_id, $astra_menu_locations ) ) {
			$load_svg_menu_icons = true;
		}
	} else {
		// Check menu registered by Astra only.
		if ( in_array( $args->menu_id, $astra_menu_locations ) ) {
			$load_svg_menu_icons = true;
		}
	}

	if ( $load_svg_menu_icons ) {
		// Assign icons to only those menu which are registered by Astra.
		$icon = Astra_Icons::get_icons( 'arrow' );
	}
	foreach ( $item->classes as $value ) {
		if ( 'menu-item-has-children' === $value ) {
			$title = $title . '<span role="' . esc_attr( $role ) . '" class="dropdown-menu-toggle" tabindex="' . esc_attr( $tabindex ) . '" >' . $icon . '</span>';
		}
	}
	if ( 0 < $depth ) {
		$title = $icon . $title;
	}
	return $title;
}

if ( Astra_Icons::is_svg_icons() ) {
	add_filter( 'nav_menu_item_title', 'astra_dropdown_icon_to_menu_link', 10, 4 );
}

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

/**
 * Generate HTML Open markup
 *
 * @param string $context unique markup key.
 * @param array  $args {
 *      Contains markup arguments.
 *     @type array  attrs    Initial attributes to apply to `open` markup.
 *     @type bool   echo    Flag indicating whether to echo or return the resultant string.
 * }
 * @since 3.3.0
 * @return mixed
 */
function astra_markup_open( $context, $args = array() ) {
	$defaults = array(
		'open'    => '',
		'attrs'   => array(),
		'echo'    => true,
		'content' => '',
	);

	$args = wp_parse_args( $args, $defaults );
	if ( $context ) {
		$args     = apply_filters( "astra_markup_{$context}_open", $args );
		$open_tag = $args['open'] ? sprintf( $args['open'], astra_attr( $context, $args['attrs'] ) ) : '';

		if ( $args['echo'] ) {
			echo $open_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $open_tag;
		}
	}
	return false;
}

/**
 * Generate HTML close markup
 *
 * @param string $context unique markup key.
 * @param array  $args {
 *      Contains markup arguments.
 *     @type string close   Closing HTML markup.
 *     @type array  attrs    Initial attributes to apply to `open` markup.
 *     @type bool   echo    Flag indicating whether to echo or return the resultant string.
 * }
 * @since 3.3.0
 * @return mixed
 */
function astra_markup_close( $context, $args = array() ) {
	$defaults = array(
		'close' => '',
		'attrs' => array(),
		'echo'  => true,
	);

	$args = wp_parse_args( $args, $defaults );
	if ( $context ) {
		$args      = apply_filters( "astra_markup_{$context}_close", $args );
		$close_tag = $args['close'];
		if ( $args['echo'] ) {
			echo $close_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $close_tag;
		}
	}
	return false;
}

/**
 * Provision to update display rules for visibility of Related Posts section in Astra.
 *
 * @since 3.4.0
 * @return bool
 */
function astra_target_rules_for_related_posts() {

	$allow_related_posts = false;
	$supported_post_type = apply_filters( 'astra_related_posts_supported_post_types', 'post' );

	if ( astra_get_option( 'enable-related-posts' ) && is_singular( $supported_post_type ) ) {
		$allow_related_posts = true;
	}

	return apply_filters( 'astra_showcase_related_posts', $allow_related_posts );
}

/**
 * Check the Astra addon 3.5.0 version is using or not.
 * As this is major update and frequently we used version_compare, added a function for this for easy maintenance.
 *
 * @since  3.5.0
 */
function is_astra_addon_3_5_0_version() {
	return defined( 'ASTRA_EXT_VER' ) && version_compare( ASTRA_EXT_VER, '3.5.0', '<' );
}

/**
 * Get a stylesheet URL for a webfont.
 *
 * @since 3.6.0
 *
 * @param string $url    The URL of the remote webfont.
 * @param string $format The font-format. If you need to support IE, change this to "woff".
 *
 * @return string Returns the CSS.
 */
function ast_get_webfont_url( $url, $format = 'woff2' ) {

	// Check if already Google font URL present or not. Basically avoiding 'Astra_WebFont_Loader' class rendering.
	$astra_font_url = astra_get_option( 'astra_font_url', false );
	if ( $astra_font_url ) {
		return json_decode( $astra_font_url );
	}

	// Now create font URL if its not present.
	$font = astra_webfont_loader_instance( $url );
	$font->set_font_format( $format );
	return $font->get_url();
}

/**
 * Get the file preloads.
 *
 * @param string $url    The URL of the remote webfont.
 * @param string $format The font-format. If you need to support IE, change this to "woff".
 */
function ast_load_preload_local_fonts( $url, $format = 'woff2' ) {

	// Check if cached font files data preset present or not. Basically avoiding 'Astra_WebFont_Loader' class rendering.
	$astra_local_font_files = get_site_option( 'astra_local_font_files', false );

	if ( is_array( $astra_local_font_files ) && ! empty( $astra_local_font_files ) ) {
		$font_format = apply_filters( 'astra_local_google_fonts_format', $format );
		foreach ( $astra_local_font_files as $key => $local_font ) {
			if ( $local_font ) {
				echo '<link rel="preload" href="' . esc_url( $local_font ) . '" as="font" type="font/' . esc_attr( $font_format ) . '" crossorigin>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		return;
	}

	// Now preload font data after processing it, as we didn't get stored data.
	$font = astra_webfont_loader_instance( $url );
	$font->set_font_format( $format );
	$font->preload_local_fonts();
}

/**
 * Set flag to manage backward compatibility for v3.5.0 earlier users for the transparent header border bottom default value changed.
 * 
 * @since 3.6.0 
 */
function astra_get_transparent_header_default_value() {
	$astra_settings                                      = get_option( ASTRA_THEME_SETTINGS );
	$astra_settings['transparent-header-default-border'] = isset( $astra_settings['transparent-header-default-border'] ) ? false : true;
	return apply_filters( 'astra_transparent_header_default_border', $astra_settings['transparent-header-default-border'] );
}
