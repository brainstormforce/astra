<?php
/**
 * Butons - Dynamic CSS
 *
 * @package Astra
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Heading Colors
 */
add_filter( 'astra_dynamic_theme_css', 'astra_fb_button_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function astra_fb_button_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$dynamic_css .= Astra_Button_Component_Dynamic_CSS::astra_button_dynamic_css( 'footer' );
	for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_footer_button; $index++ ) {

		if ( ! Astra_Builder_Helper::is_component_loaded( 'button-' . $index, 'footer' ) ) {
			continue;
		}

		$_section = 'section-fb-button-' . $index;
		$selector = '.ast-footer-button-' . $index . '[data-section="section-fb-button-' . $index . '"]';

		$alignment = astra_get_option( 'footer-button-' . $index . '-alignment' );

		$desktop_alignment = ( isset( $alignment['desktop'] ) ) ? $alignment['desktop'] : '';
		$tablet_alignment  = ( isset( $alignment['tablet'] ) ) ? $alignment['tablet'] : '';
		$mobile_alignment  = ( isset( $alignment['mobile'] ) ) ? $alignment['mobile'] : '';

		/**
		 * Copyright CSS.
		 */
		$css_output_desktop = array(
			$selector => array(
				'justify-content' => $desktop_alignment,
			),
		);

		$css_output_tablet = array(
			$selector => array(
				'justify-content' => $tablet_alignment,
			),
		);

		$css_output_mobile = array(
			$selector => array(
				'justify-content' => $mobile_alignment,
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
