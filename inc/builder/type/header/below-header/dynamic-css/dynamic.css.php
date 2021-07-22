<?php
/**
 * Below Header - Dynamic CSS
 *
 * @package astra-builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Below Header Row.
 */
add_filter( 'astra_dynamic_theme_css', 'astra_below_header_row_setting', 11 );

/**
 * Below Header Row - Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function astra_below_header_row_setting( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! is_customize_preview() && ( ! Astra_Builder_helper::is_row_empty( 'below', 'header', 'desktop' ) && ! Astra_Builder_helper::is_row_empty( 'below', 'header', 'mobile' ) ) ) {
		return $dynamic_css;
	}

	$parse_css = '';

	// Common CSS options.
	$hbb_header_height  = astra_get_option( 'hbb-header-height' );
	$hbb_header_divider = astra_get_option( 'hbb-header-separator' );
	$hbb_border_color   = astra_get_option( 'hbb-header-bottom-border-color' );

	// Header Height.
	$hbb_header_height_desktop = ( isset( $hbb_header_height['desktop'] ) && ! empty( $hbb_header_height['desktop'] ) ) ? $hbb_header_height['desktop'] : '';
	$hbb_header_height_tablet  = ( isset( $hbb_header_height['tablet'] ) && ! empty( $hbb_header_height['tablet'] ) ) ? $hbb_header_height['tablet'] : '';
	$hbb_header_height_mobile  = ( isset( $hbb_header_height['mobile'] ) && ! empty( $hbb_header_height['mobile'] ) ) ? $hbb_header_height['mobile'] : '';

	// Background CSS options.
	$hbb_header_bg_obj  = astra_get_option( 'hbb-header-bg-obj-responsive' );
	$desktop_background = isset( $hbb_header_bg_obj['desktop']['background-color'] ) ? $hbb_header_bg_obj['desktop']['background-color'] : '';
	$tablet_background  = isset( $hbb_header_bg_obj['tablet']['background-color'] ) ? $hbb_header_bg_obj['tablet']['background-color'] : '';
	$mobile_background  = isset( $hbb_header_bg_obj['mobile']['background-color'] ) ? $hbb_header_bg_obj['mobile']['background-color'] : '';


	/**
	 * Below Header General options
	 */
	$common_css_output = array(
		'.ast-below-header .main-header-bar-navigation' => array(
			'height' => '100%',
		),
		'.ast-header-break-point .ast-mobile-header-wrap .ast-below-header-wrap .main-header-bar-navigation .inline-on-mobile .menu-item .menu-link' => array(
			'border' => 'none',
		),
		'.ast-header-break-point .ast-mobile-header-wrap .ast-below-header-wrap .main-header-bar-navigation .inline-on-mobile .menu-item-has-children > .ast-menu-toggle::before' => array(
			'font-size' => '.6rem',
		),
		'.ast-header-break-point .ast-mobile-header-wrap .ast-below-header-wrap .main-header-bar-navigation .ast-submenu-expanded > .ast-menu-toggle::before' => array(
			'transform' => 'rotateX(180deg)',
		),
		'#masthead .ast-mobile-header-wrap .ast-below-header-bar' => array(
			'padding-left'  => '20px',
			'padding-right' => '20px',
		),
		'.ast-mobile-header-wrap .ast-below-header-bar , .ast-below-header-bar .site-below-header-wrap' => array(
			'min-height' => astra_get_css_value( $hbb_header_height_desktop, 'px' ),
		),
		'.ast-desktop .ast-below-header-bar .main-header-menu > .menu-item' => array(
			'line-height' => astra_get_css_value( $hbb_header_height_desktop, 'px' ),
		),
		'.ast-desktop .ast-below-header-bar .ast-header-woo-cart, .ast-desktop .ast-below-header-bar .ast-header-edd-cart' => array(
			'line-height' => astra_get_css_value( $hbb_header_height_desktop, 'px' ),
		),
	);

	// Apply border only when it has positive value.
	if ( '' !== $hbb_header_divider && 'inherit' !== $hbb_header_divider ) {
		$common_css_output['.ast-below-header-bar'] = array(
			'border-bottom-width' => astra_get_css_value( $hbb_header_divider, 'px' ),
			'border-bottom-color' => esc_attr( $hbb_border_color ),
			'border-bottom-style' => 'solid',
		);
	} else {
		$common_css_output['.ast-below-header-bar'] = array(
			'border-bottom-style' => 'none',
		);
	}

	$parse_css .= astra_parse_css( $common_css_output );

	// Below Header Background Responsive - Desktop.
	$desktop_bg = array(
		'.ast-below-header-bar'                         => astra_get_responsive_background_obj( $hbb_header_bg_obj, 'desktop' ),
		'.ast-header-break-point .ast-below-header-bar' => array(
			'background-color' => esc_attr( $desktop_background ),
		),
	);
	$parse_css .= astra_parse_css( $desktop_bg );

	// Below Header Background Responsive - Tablet.
	$tablet_bg  = array(
		'.ast-below-header-bar'                         => astra_get_responsive_background_obj( $hbb_header_bg_obj, 'tablet' ),
		'.ast-header-break-point .ast-below-header-bar' => array(
			'background-color' => esc_attr( $tablet_background ),
		),
		'.ast-mobile-header-wrap .ast-below-header-bar , .ast-below-header-bar .site-below-header-wrap' => array(
			'min-height' => astra_get_css_value( $hbb_header_height_tablet, 'px' ),
		),
	);
	$parse_css .= astra_parse_css( $tablet_bg, '', astra_get_tablet_breakpoint() );

	// Below Header Background Responsive - Mobile.
	$mobile_bg  = array(
		'.ast-below-header-bar'                         => astra_get_responsive_background_obj( $hbb_header_bg_obj, 'mobile' ),
		'.ast-header-break-point .ast-below-header-bar' => array(
			'background-color' => esc_attr( $mobile_background ),
		),
		'.ast-mobile-header-wrap .ast-below-header-bar , .ast-below-header-bar .site-below-header-wrap' => array(
			'min-height' => astra_get_css_value( $hbb_header_height_mobile, 'px' ),
		),
	);
	$parse_css .= astra_parse_css( $mobile_bg, '', astra_get_mobile_breakpoint() );

	// Trim white space for faster page loading.
	$dynamic_css .= Astra_Enqueue_Scripts::trim_css( $parse_css );

	$_section = 'section-below-header-builder';


	$parent_selector = '.ast-below-header-bar.ast-below-header, .ast-header-break-point .ast-below-header-bar.ast-below-header';

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_advanced_margin_padding_css( $_section, $parent_selector );

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, '.ast-below-header-bar', 'block', 'grid' );

	return $dynamic_css;
}
