<?php
/**
 * Primary Footer control - Dynamic CSS
 *
 * @package Astra Builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Primary Footer CSS
 */
add_filter( 'astra_dynamic_theme_css', 'astra_fb_primary_footer_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Primary Footer.
 *
 * @since 3.0.0
 */
function astra_fb_primary_footer_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! ( Astra_Builder_Helper::is_footer_row_empty( 'primary' ) || is_customize_preview() ) ) {
		return $dynamic_css;
	}

	$global_footer_bg = astra_get_option( 'footer-bg-obj-responsive' );

	$css_output_desktop = array(
		'.ast-main-footer-wrap' => astra_get_responsive_background_obj( $global_footer_bg, 'desktop' ),
	);

	$dynamic_css .= astra_parse_css( $css_output_desktop );
	// Advanced CSS for Header Builder.
	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_advanced_margin_padding_css( 'section-footer-builder-layout', '.astra-hfb-header .ast-main-footer-wrap' );

	if ( ! ( Astra_Builder_Helper::is_footer_row_empty( 'primary' ) || is_customize_preview() ) ) {
		return $dynamic_css;
	}

	$_section = 'section-primary-footer-builder';

	$selector = '.site-primary-footer-wrap[data-section="section-primary-footer-builder"]';

	$footer_bg               = astra_get_option( 'hb-footer-bg-obj-responsive' );
	$footer_top_border_size  = astra_get_option( 'hb-footer-main-sep' );
	$footer_top_border_color = astra_get_option( 'hb-footer-main-sep-color' );
	$footer_width            = astra_get_option( 'hb-footer-layout-width' );
	$content_width           = astra_get_option( 'site-content-width' );
	$inner_spacing           = astra_get_option( 'hb-inner-spacing' );

	$inner_spacing_desktop = ( isset( $inner_spacing['desktop'] ) ) ? $inner_spacing['desktop'] : '';
	$inner_spacing_tablet  = ( isset( $inner_spacing['tablet'] ) ) ? $inner_spacing['tablet'] : '';
	$inner_spacing_mobile  = ( isset( $inner_spacing['mobile'] ) ) ? $inner_spacing['mobile'] : '';

	$css_output_desktop = array(

		$selector                            => astra_get_responsive_background_obj( $footer_bg, 'desktop' ),
		$selector . ' .ast-builder-grid-row' => array(
			'align-items'     => astra_get_option( 'hb-footer-vertical-alignment' ),
			'grid-column-gap' => astra_get_css_value( $inner_spacing_desktop, 'px' ),
		),
		$selector . '.ast-footer-row-inline .site-footer-section' => array(
			'display'       => 'flex',
			'margin-bottom' => '0',
		),

	);

	if ( isset( $footer_top_border_size ) && 1 <= $footer_top_border_size ) {

		$css_output_desktop[ $selector ]['border-style'] = 'solid';

		$css_output_desktop[ $selector ]['border-width'] = '0px';
		
		$css_output_desktop[ $selector ]['border-top-width'] = astra_get_css_value( $footer_top_border_size, 'px' );

		$css_output_desktop[ $selector ]['border-top-color'] = $footer_top_border_color;
	}

	if ( isset( $footer_width ) && 'content' === $footer_width ) {

		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['max-width']    = astra_get_css_value( $content_width, 'px' );
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['margin-left']  = 'auto';
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['margin-right'] = 'auto';
	} else {
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['max-width']     = '100%';
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['padding-left']  = '35px';
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['padding-right'] = '35px';
	}

	$css_output_tablet = array(

		$selector                            => astra_get_responsive_background_obj( $footer_bg, 'tablet' ),
		'.ast-main-footer-wrap'              => astra_get_responsive_background_obj( $global_footer_bg, 'tablet' ),
		$selector . ' .ast-builder-grid-row' => array(
			'grid-column-gap' => astra_get_css_value( $inner_spacing_tablet, 'px' ),
			'grid-row-gap'    => astra_get_css_value( $inner_spacing_tablet, 'px' ),
		),
		$selector . '.ast-footer-row-tablet-inline .site-footer-section' => array(
			'display'       => 'flex',
			'margin-bottom' => '0',
		),
		$selector . '.ast-footer-row-tablet-stack .site-footer-section' => array(
			'display'       => 'block',
			'margin-bottom' => '10px',
		),
	);
	$css_output_mobile = array(

		$selector                            => astra_get_responsive_background_obj( $footer_bg, 'mobile' ),
		'.ast-main-footer-wrap'              => astra_get_responsive_background_obj( $global_footer_bg, 'mobile' ),
		$selector . ' .ast-builder-grid-row' => array(
			'grid-column-gap' => astra_get_css_value( $inner_spacing_mobile, 'px' ),
			'grid-row-gap'    => astra_get_css_value( $inner_spacing_mobile, 'px' ),
		),
		$selector . '.ast-footer-row-mobile-inline .site-footer-section' => array(
			'display'       => 'flex',
			'margin-bottom' => '0',
		),
		$selector . '.ast-footer-row-mobile-stack .site-footer-section' => array(
			'display'       => 'block',
			'margin-bottom' => '10px',
		),
	);

	/* Parse CSS from array() */
	$css_output  = astra_parse_css( $css_output_desktop );
	$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
	$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_advanced_margin_padding_css( $_section, $selector );

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $selector, 'grid' );

	return $dynamic_css;
}
