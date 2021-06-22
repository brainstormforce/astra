<?php
/**
 * Global color palette - Dynamic CSS
 *
 * @package astra-builder
 * @since x.x.x
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'astra_dynamic_theme_css', 'astra_generate_palette_editor_style', 11 );

/**
 * Generate editor style on front end compatible for global palette.
 *
 * @since x.x.x
 * @param string $dynamic_css dynamic css.
 * @return array
 */
function astra_generate_palette_editor_style( $dynamic_css ) {

	$global_palette  = astra_get_option( 'global-color-palette' );
	$palette_style   = array();
	$variable_prefix = Astra_Global_Palette::get_css_variable_prefix();

	if ( isset( $global_palette['palette'] ) ) {
		foreach ( $global_palette['palette'] as $key => $color ) {
			$palette_key = str_replace( '--', '-', $variable_prefix ) . $key;

			$palette_style[ ':root .has' . $palette_key . '-color' ] = array(
				'color' => 'var(' . $variable_prefix . $key . ')',
			);

			$palette_style[ ':root .has' . $palette_key . '-background-color' ] = array(
				'background-color' => 'var(' . $variable_prefix . $key . ')',
			);

			$palette_style[ ':root .wp-block-button .has' . $palette_key . '-color' ] = array(
				'color' => 'var(' . $variable_prefix . $key . ')',
			);

			$palette_style[ ':root .wp-block-button .has' . $palette_key . '-background-color' ] = array(
				'background-color' => 'var(' . $variable_prefix . $key . ')',
			);
		}
	}

	if ( ! empty( $palette_style ) ) {
		$dynamic_css .= astra_parse_css( $palette_style );
	}

	return $dynamic_css;
}
