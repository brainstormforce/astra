<?php
/**
 * Below Footer control - Dynamic CSS
 *
 * @package Astra Builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Below Footer CSS
 */
add_filter( 'astra_dynamic_theme_css', 'astra_fb_below_footer_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for below Footer.
 *
 * @since 3.0.0
 */
function astra_fb_below_footer_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! ( Astra_Builder_Helper::is_footer_row_empty( 'below' ) || is_customize_preview() ) ) {
		return $dynamic_css;
	}

	$_section = 'section-below-footer-builder';

	$selector = '.site-below-footer-wrap[data-section="section-below-footer-builder"]';

	$footer_bg               = astra_get_option( 'hbb-footer-bg-obj-responsive' );
	$footer_height           = astra_get_option( 'hbb-footer-height' );
	$footer_top_border_size  = astra_get_option( 'hbb-footer-separator' );
	$footer_top_border_color = astra_get_option( 'hbb-footer-top-border-color' );
	$footer_width            = astra_get_option( 'hbb-footer-layout-width' );
	$content_width           = astra_get_option( 'site-content-width' );
	$inner_spacing           = astra_get_option( 'hbb-inner-spacing' );

	$layout = astra_get_option( 'hbb-footer-layout' );

	$desk_layout = ( isset( $layout['desktop'] ) ) ? $layout['desktop'] : 'full';
	$tab_layout  = ( isset( $layout['tablet'] ) ) ? $layout['tablet'] : 'full';
	$mob_layout  = ( isset( $layout['mobile'] ) ) ? $layout['mobile'] : 'full';

	$inner_spacing_desktop = ( isset( $inner_spacing['desktop'] ) ) ? $inner_spacing['desktop'] : '';
	$inner_spacing_tablet  = ( isset( $inner_spacing['tablet'] ) ) ? $inner_spacing['tablet'] : '';
	$inner_spacing_mobile  = ( isset( $inner_spacing['mobile'] ) ) ? $inner_spacing['mobile'] : '';

	$css_output_desktop = array(

		$selector                            => astra_get_responsive_background_obj( $footer_bg, 'desktop' ),
		$selector . ' .ast-builder-grid-row' => array(
			'grid-column-gap' => astra_get_css_value( $inner_spacing_desktop, 'px' ),
		),
		$selector . ' .ast-builder-grid-row, ' . $selector . ' .site-footer-section' => array(
			'align-items' => astra_get_option( 'hbb-footer-vertical-alignment' ),
		),
		$selector . '.ast-footer-row-inline .site-footer-section' => array(
			'display'       => 'flex',
			'margin-bottom' => '0',
		),
		'.ast-builder-grid-row-' . $desk_layout . ' .ast-builder-grid-row' => array(
			'grid-template-columns' => Astra_Builder_Helper::$grid_size_mapping[ $desk_layout ],
		),

	);

	$css_output_desktop[ $selector ]['min-height'] = astra_get_css_value( $footer_height, 'px' );

	if ( isset( $footer_top_border_size ) && 1 <= $footer_top_border_size ) {

		$css_output_desktop[ $selector ]['border-style'] = 'solid';

		$css_output_desktop[ $selector ]['border-width'] = '0px';

		$css_output_desktop[ $selector ]['border-top-width'] = astra_get_css_value( $footer_top_border_size, 'px' );

		$css_output_desktop[ $selector ]['border-top-color'] = $footer_top_border_color;
	}

	$css_output_tablet = array(

		$selector                            => astra_get_responsive_background_obj( $footer_bg, 'tablet' ),
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
		'.ast-builder-grid-row-container.ast-builder-grid-row-tablet-' . $tab_layout . ' .ast-builder-grid-row' => array(
			'grid-template-columns' => Astra_Builder_Helper::$grid_size_mapping[ $tab_layout ],
		),
	);
	$css_output_mobile = array(

		$selector                            => astra_get_responsive_background_obj( $footer_bg, 'mobile' ),
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
		'.ast-builder-grid-row-container.ast-builder-grid-row-mobile-' . $mob_layout . ' .ast-builder-grid-row' => array(
			'grid-template-columns' => Astra_Builder_Helper::$grid_size_mapping[ $mob_layout ],
		),
	);

	if ( isset( $footer_width ) && 'content' === $footer_width ) {

		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['max-width']    = astra_get_css_value( $content_width, 'px' );
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['margin-left']  = 'auto';
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['margin-right'] = 'auto';
	} else {
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['max-width']     = '100%';
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['padding-left']  = '35px';
		$css_output_desktop[ $selector . ' .ast-builder-grid-row' ]['padding-right'] = '35px';
	}


	/* Parse CSS from array() */
	$css_output  = astra_parse_css( $css_output_desktop );
	$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
	$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_advanced_margin_padding_css( $_section, $selector );

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $selector, 'grid' );

	return $dynamic_css;
}
