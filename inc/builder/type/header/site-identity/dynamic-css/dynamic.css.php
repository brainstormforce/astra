<?php
/**
 * Site Identity - Dynamic CSS
 *
 * @package Astra
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Site Identity
 */
add_filter( 'astra_dynamic_theme_css', 'astra_hb_site_identity_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Site Identity.
 *
 * @since 3.0.0
 */
function astra_hb_site_identity_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! Astra_Builder_Helper::is_component_loaded( 'logo', 'header' ) ) {
		return $dynamic_css;
	}

	$_section            = 'title_tagline';
	$selector            = '.ast-builder-layout-element .ast-site-identity';
	$visibility_selector = '.ast-builder-layout-element[data-section="title_tagline"]';

	$margin            = astra_get_option( $_section . '-margin' );
	$title_color       = astra_get_option( 'header-color-site-title' );
	$title_hover_color = astra_get_option( 'header-color-h-site-title' );
	$tagline_color     = astra_get_option( 'header-color-site-tagline' );

	// Desktop CSS.
	$css_output_desktop = array(

		$selector . ' .site-title a'       => array(
			'color' => esc_attr( $title_color ),
		),
		$selector . ' .site-title a:hover' => array(
			'color' => esc_attr( $title_hover_color ),
		),
		$selector . ' .site-description'   => array(
			'color' => esc_attr( $tagline_color ),
		),
		$selector                          => array(

			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	// Tablet CSS.
	$css_output_tablet = array(

		$selector => array(

			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	// Mobile CSS.
	$css_output_mobile = array(

		$selector => array(

			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	$css_output  = astra_parse_css( $css_output_desktop );
	$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
	$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;
	
	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $visibility_selector );

	return $dynamic_css;
}
