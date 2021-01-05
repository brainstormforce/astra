<?php
/**
 * HTML control - Dynamic CSS
 *
 * @package Astra Builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Heading Colors
 */
add_filter( 'astra_dynamic_theme_css', 'astra_fb_html_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function astra_fb_html_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$dynamic_css .= Astra_Html_Component_Dynamic_CSS::astra_html_dynamic_css( 'footer' );

	for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_footer_html; $index++ ) {

		if ( ! Astra_Builder_Helper::is_component_loaded( 'html-' . $index, 'footer' ) ) {
			continue;
		}

		$_section = 'section-fb-html-' . $index;
		$selector = '.footer-widget-area[data-section="section-fb-html-' . $index . '"]';

		$alignment = astra_get_option( 'footer-html-' . $index . '-alignment' );

		$desktop_alignment = ( isset( $alignment['desktop'] ) ) ? $alignment['desktop'] : '';
		$tablet_alignment  = ( isset( $alignment['tablet'] ) ) ? $alignment['tablet'] : '';
		$mobile_alignment  = ( isset( $alignment['mobile'] ) ) ? $alignment['mobile'] : '';

		/**
		 * Copyright CSS.
		 */
		$css_output_desktop = array(
			$selector . ' .ast-builder-html-element' => array(
				'text-align' => $desktop_alignment,
			),
		);

		$css_output_tablet = array(
			$selector . ' .ast-builder-html-element' => array(
				'text-align' => $tablet_alignment,
			),
		);

		$css_output_mobile = array(
			$selector . ' .ast-builder-html-element' => array(
				'text-align' => $mobile_alignment,
			),
		);

		/* Parse CSS from array() */
		$css_output  = astra_parse_css( $css_output_desktop );
		$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
		$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

		$dynamic_css .= $css_output;
	}

	return $dynamic_css;
}
