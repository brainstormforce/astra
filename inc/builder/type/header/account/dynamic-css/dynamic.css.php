<?php
/**
 * Account - Dynamic CSS
 *
 * @package Astra
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Account
 */
add_filter( 'astra_dynamic_theme_css', 'astra_hb_account_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Account.
 *
 * @since 3.0.0
 */
function astra_hb_account_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! Astra_Builder_Helper::is_component_loaded( 'account', 'header' ) ) {
		return $dynamic_css;
	}

	$_section            = 'section-header-account';
	$selector            = '.ast-header-account-wrap';
	$visibility_selector = '.ast-header-account[data-section="section-header-account"]';

	$icon_size = astra_get_option( 'header-account-icon-size' );

	$icon_size_desktop = ( isset( $icon_size ) && isset( $icon_size['desktop'] ) && ! empty( $icon_size['desktop'] ) ) ? $icon_size['desktop'] : 20;
	
	$icon_size_tablet = ( isset( $icon_size ) && isset( $icon_size['tablet'] ) && ! empty( $icon_size['tablet'] ) ) ? $icon_size['tablet'] : 20;

	$icon_size_mobile = ( isset( $icon_size ) && isset( $icon_size['mobile'] ) && ! empty( $icon_size['mobile'] ) ) ? $icon_size['mobile'] : 20;

	$image_width = astra_get_option( 'header-account-image-width' );

	$image_width_desktop = ( isset( $image_width ) && isset( $image_width['desktop'] ) && ! empty( $image_width['desktop'] ) ) ? $image_width['desktop'] : 20;
	
	$image_width_tablet = ( isset( $image_width ) && isset( $image_width['tablet'] ) && ! empty( $image_width['tablet'] ) ) ? $image_width['tablet'] : 20;

	$image_width_mobile = ( isset( $image_width ) && isset( $image_width['mobile'] ) && ! empty( $image_width['mobile'] ) ) ? $image_width['mobile'] : 20;

	$margin          = astra_get_option( 'header-account-margin' );
	$margin_selector = '.ast-header-account-wrap';

	/**
	 * Account CSS.
	 */
	$css_output_desktop = array(

		$selector . ' .ast-header-account-type-icon .ahfb-svg-iconset svg path:not( .ast-hf-account-unfill ), ' . $selector . ' .ast-header-account-type-icon .ahfb-svg-iconset svg circle' => array(
			'fill' => esc_attr( astra_get_option( 'header-account-icon-color' ) ),
		),
		'.ast-mobile-popup-content ' . $selector . ' .ast-header-account-type-icon .ahfb-svg-iconset svg path:not( .ast-hf-account-unfill ), .ast-mobile-popup-content ' . $selector . ' .ast-header-account-type-icon .ahfb-svg-iconset svg circle' => array(
			'fill' => esc_attr( astra_get_option( 'header-account-icon-color' ) ),
		),
		$selector . ' .ast-header-account-type-icon .ahfb-svg-iconset svg' => array(
			'height' => astra_get_css_value( $icon_size_desktop, 'px' ),
			'width'  => astra_get_css_value( $icon_size_desktop, 'px' ),
		),
		$selector . ' .ast-header-account-type-avatar .avatar' => array(
			'width' => astra_get_css_value( $image_width_desktop, 'px' ),
		),
		$selector . ' .ast-header-account-text' => array(
			'color' => esc_attr( astra_get_option( 'header-account-type-text-color' ) ),
		),
		$margin_selector                        => array(
			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	$css_output_tablet = array(

		$selector . ' .ast-header-account-type-icon .ahfb-svg-iconset svg' => array(
			'height' => astra_get_css_value( $icon_size_tablet, 'px' ),
			'width'  => astra_get_css_value( $icon_size_tablet, 'px' ),
		),
		$selector . ' .ast-header-account-type-avatar .avatar' => array(
			'width' => astra_get_css_value( $image_width_tablet, 'px' ),
		),
		$margin_selector => array(
			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	$css_output_mobile = array(

		$selector . ' .ast-header-account-type-icon .ahfb-svg-iconset svg' => array(
			'height' => astra_get_css_value( $icon_size_mobile, 'px' ),
			'width'  => astra_get_css_value( $icon_size_mobile, 'px' ),
		),
		$selector . ' .ast-header-account-type-avatar .avatar' => array(
			'width' => astra_get_css_value( $image_width_mobile, 'px' ),
		),
		$margin_selector => array(
			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	/* Parse CSS from array() */
	$css_output  = astra_parse_css( $css_output_desktop );
	$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
	$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_advanced_typography_css( $_section, $selector . ' .ast-header-account-text' );

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $selector );

	return $dynamic_css;
}
