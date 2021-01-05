<?php
/**
 * Mobile Trigger - Dynamic CSS
 *
 * @package astra-builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Mobile Trigger.
 */
add_filter( 'astra_dynamic_theme_css', 'astra_mobile_trigger_row_setting', 11 );

/**
 * Mobile Trigger - Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function astra_mobile_trigger_row_setting( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! Astra_Builder_Helper::is_component_loaded( 'mobile-trigger', 'header' ) ) {
		return $dynamic_css;
	}

	$parse_css = '';

	$_section = 'section-header-mobile-trigger';

	$selector = '[data-section="section-header-mobile-trigger"]';

	$theme_color            = astra_get_option( 'theme-color' );
	$icon_size              = astra_get_option( 'mobile-header-toggle-icon-size' );
	$off_canvas_close_color = astra_get_option( 'off-canvas-close-color', $theme_color );
	$trigger_bg             = astra_get_option( 'mobile-header-toggle-btn-bg-color', $theme_color );
	$trigger_border_width   = astra_get_option( 'mobile-header-toggle-btn-border-size' );
	$trigger_border_color   = astra_get_option( 'mobile-header-toggle-border-color', $trigger_bg );
	$trigger_border_radius  = astra_get_option( 'mobile-header-toggle-border-radius' );
	$font_size              = astra_get_option( 'mobile-header-label-font-size' );
	$style                  = astra_get_option( 'mobile-header-toggle-btn-style' );
	$default                = '#ffffff';

	if ( 'fill' !== $style ) {
		$default = $theme_color;
	}

	$icon_color = astra_get_option( 'mobile-header-toggle-btn-color', $default );

	// Border.
	$trigger_border_width_top = ( isset( $trigger_border_width ) && isset( $trigger_border_width['top'] ) ) ? $trigger_border_width['top'] : 0;

	$trigger_border_width_bottom = ( isset( $trigger_border_width ) && isset( $trigger_border_width['bottom'] ) ) ? $trigger_border_width['bottom'] : 0;

	$trigger_border_width_right = ( isset( $trigger_border_width ) && isset( $trigger_border_width['right'] ) ) ? $trigger_border_width['right'] : 0;

	$trigger_border_width_left = ( isset( $trigger_border_width ) && isset( $trigger_border_width['left'] ) ) ? $trigger_border_width['left'] : 0;

	$margin          = astra_get_option( $_section . '-margin' );
	$margin_selector = $selector . ' .ast-button-wrap .menu-toggle';

	/**
	 * Off-Canvas CSS.
	 */
	$css_output = array(

		$selector . ' .ast-button-wrap .mobile-menu-toggle-icon .ast-mobile-svg' => array(
			'width'  => astra_get_css_value( $icon_size, 'px' ),
			'height' => astra_get_css_value( $icon_size, 'px' ),
			'fill'   => $icon_color,
		),
		$selector . ' .ast-button-wrap .mobile-menu-wrap .mobile-menu' => array(
			// Color.
			'color'     => $icon_color,

			// Typography.
			'font-size' => astra_get_css_value( $font_size, 'px' ),
		),
		$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-fill, ' . $selector . ' .ast-button-wrap .ast-mobile-menu-trigger-minimal' => array(
			// Color & Border.
			'color'  => esc_attr( $icon_color ),
			'border' => 'none',
		),
		$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-outline' => array(
			// Background.
			'background'          => 'transparent',
			'color'               => esc_attr( $icon_color ),
			'border-top-width'    => astra_get_css_value( $trigger_border_width_top, 'px' ),
			'border-bottom-width' => astra_get_css_value( $trigger_border_width_bottom, 'px' ),
			'border-right-width'  => astra_get_css_value( $trigger_border_width_right, 'px' ),
			'border-left-width'   => astra_get_css_value( $trigger_border_width_left, 'px' ),
			'border-style'        => 'solid',
			'border-color'        => $trigger_border_color,
			'border-radius'       => astra_get_css_value( $trigger_border_radius, 'px' ),
		),
		$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-fill' => array(
			'background'    => esc_attr( $trigger_bg ),
			'border-radius' => astra_get_css_value( $trigger_border_radius, 'px' ),
		),
		$selector . ' .ast-button-wrap .ast-mobile-menu-trigger-minimal' => array(
			'background' => 'transparent',
		),
		$margin_selector => array(
			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	// Tablet CSS.
	$css_output_tablet = array(

		$margin_selector => array(
			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	// Mobile CSS.
	$css_output_mobile = array(

		$margin_selector => array(
			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	/* Parse CSS from array() */
	$css_output  = astra_parse_css( $css_output );
	$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
	$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );


	$dynamic_css .= $css_output;

	return $dynamic_css;
}
