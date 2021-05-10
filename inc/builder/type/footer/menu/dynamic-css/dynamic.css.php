<?php
/**
 * Footer Menu Colors - Dynamic CSS
 *
 * @package astra-builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Footer Menu Colors
 */
add_filter( 'astra_dynamic_theme_css', 'astra_hb_footer_menu_dynamic_css', 11 );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Footer Menu Colors.
 *
 * @since 3.0.0
 */
function astra_hb_footer_menu_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! Astra_Builder_Helper::is_component_loaded( 'menu', 'footer' ) ) {
		return $dynamic_css;
	}

	$_section = 'section-footer-menu';

	$selector = '#astra-footer-menu';

	$visibility_selector = '.footer-widget-area[data-section="section-footer-menu"]';

	// Menu.
	$menu_resp_color           = astra_get_option( 'footer-menu-color-responsive' );
	$menu_resp_bg_color        = astra_get_option( 'footer-menu-bg-obj-responsive' );
	$menu_resp_color_hover     = astra_get_option( 'footer-menu-h-color-responsive' );
	$menu_resp_bg_color_hover  = astra_get_option( 'footer-menu-h-bg-color-responsive' );
	$menu_resp_color_active    = astra_get_option( 'footer-menu-a-color-responsive' );
	$menu_resp_bg_color_active = astra_get_option( 'footer-menu-a-bg-color-responsive' );

	$alignment = astra_get_option( 'footer-menu-alignment' );

	$desktop_alignment = ( isset( $alignment['desktop'] ) ) ? $alignment['desktop'] : '';
	$tablet_alignment  = ( isset( $alignment['tablet'] ) ) ? $alignment['tablet'] : '';
	$mobile_alignment  = ( isset( $alignment['mobile'] ) ) ? $alignment['mobile'] : '';

	$menu_resp_color_desktop = ( isset( $menu_resp_color['desktop'] ) ) ? $menu_resp_color['desktop'] : '';
	$menu_resp_color_tablet  = ( isset( $menu_resp_color['tablet'] ) ) ? $menu_resp_color['tablet'] : '';
	$menu_resp_color_mobile  = ( isset( $menu_resp_color['mobile'] ) ) ? $menu_resp_color['mobile'] : '';

	$menu_resp_color_hover_desktop = ( isset( $menu_resp_color_hover['desktop'] ) ) ? $menu_resp_color_hover['desktop'] : '';
	$menu_resp_color_hover_tablet  = ( isset( $menu_resp_color_hover['tablet'] ) ) ? $menu_resp_color_hover['tablet'] : '';
	$menu_resp_color_hover_mobile  = ( isset( $menu_resp_color_hover['mobile'] ) ) ? $menu_resp_color_hover['mobile'] : '';

	$menu_resp_bg_color_hover_desktop = ( isset( $menu_resp_bg_color_hover['desktop'] ) ) ? $menu_resp_bg_color_hover['desktop'] : '';
	$menu_resp_bg_color_hover_tablet  = ( isset( $menu_resp_bg_color_hover['tablet'] ) ) ? $menu_resp_bg_color_hover['tablet'] : '';
	$menu_resp_bg_color_hover_mobile  = ( isset( $menu_resp_bg_color_hover['mobile'] ) ) ? $menu_resp_bg_color_hover['mobile'] : '';

	$menu_resp_color_active_desktop = ( isset( $menu_resp_color_active['desktop'] ) ) ? $menu_resp_color_active['desktop'] : '';
	$menu_resp_color_active_tablet  = ( isset( $menu_resp_color_active['tablet'] ) ) ? $menu_resp_color_active['tablet'] : '';
	$menu_resp_color_active_mobile  = ( isset( $menu_resp_color_active['mobile'] ) ) ? $menu_resp_color_active['mobile'] : '';

	$menu_resp_bg_color_active_desktop = ( isset( $menu_resp_bg_color_active['desktop'] ) ) ? $menu_resp_bg_color_active['desktop'] : '';
	$menu_resp_bg_color_active_tablet  = ( isset( $menu_resp_bg_color_active['tablet'] ) ) ? $menu_resp_bg_color_active['tablet'] : '';
	$menu_resp_bg_color_active_mobile  = ( isset( $menu_resp_bg_color_active['mobile'] ) ) ? $menu_resp_bg_color_active['mobile'] : '';

	// Typography.
	$menu_font_size = astra_get_option( 'footer-menu-font-size' );

	$menu_font_size_desktop      = ( isset( $menu_font_size['desktop'] ) ) ? $menu_font_size['desktop'] : '';
	$menu_font_size_tablet       = ( isset( $menu_font_size['tablet'] ) ) ? $menu_font_size['tablet'] : '';
	$menu_font_size_mobile       = ( isset( $menu_font_size['mobile'] ) ) ? $menu_font_size['mobile'] : '';
	$menu_font_size_desktop_unit = ( isset( $menu_font_size['desktop-unit'] ) ) ? $menu_font_size['desktop-unit'] : '';
	$menu_font_size_tablet_unit  = ( isset( $menu_font_size['tablet-unit'] ) ) ? $menu_font_size['tablet-unit'] : '';
	$menu_font_size_mobile_unit  = ( isset( $menu_font_size['mobile-unit'] ) ) ? $menu_font_size['mobile-unit'] : '';

	// Menu Spacing.
	$menu_spacing = astra_get_option( 'footer-main-menu-spacing' );

	// - Desktop.
	$menu_desktop_spacing_top = ( isset( $menu_spacing['desktop']['top'] ) && ! empty( $menu_spacing['desktop']['top'] ) ) ? $menu_spacing['desktop']['top'] : '';

	$menu_desktop_spacing_bottom = ( isset( $menu_spacing['desktop']['bottom'] ) && ! empty( $menu_spacing['desktop']['bottom'] ) ) ? $menu_spacing['desktop']['bottom'] : '';

	$menu_desktop_spacing_right = ( isset( $menu_spacing['desktop']['right'] ) && ! empty( $menu_spacing['desktop']['right'] ) ) ? $menu_spacing['desktop']['right'] : '';

	$menu_desktop_spacing_left = ( isset( $menu_spacing['desktop']['left'] ) && ! empty( $menu_spacing['desktop']['left'] ) ) ? $menu_spacing['desktop']['left'] : '';

	$menu_desktop_spacing_unit = ( isset( $menu_spacing['desktop-unit'] ) && ! empty( $menu_spacing['desktop-unit'] ) ) ? $menu_spacing['desktop-unit'] : '';

	// - Tablet.
	$menu_tablet_spacing_top = ( isset( $menu_spacing['tablet']['top'] ) && ! empty( $menu_spacing['tablet']['top'] ) ) ? $menu_spacing['tablet']['top'] : '';

	$menu_tablet_spacing_bottom = ( isset( $menu_spacing['tablet']['bottom'] ) && ! empty( $menu_spacing['tablet']['bottom'] ) ) ? $menu_spacing['tablet']['bottom'] : '';

	$menu_tablet_spacing_right = ( isset( $menu_spacing['tablet']['right'] ) && ! empty( $menu_spacing['tablet']['right'] ) ) ? $menu_spacing['tablet']['right'] : '';

	$menu_tablet_spacing_left = ( isset( $menu_spacing['tablet']['left'] ) && ! empty( $menu_spacing['tablet']['left'] ) ) ? $menu_spacing['tablet']['left'] : '';

	$menu_tablet_spacing_unit = ( isset( $menu_spacing['tablet-unit'] ) && ! empty( $menu_spacing['tablet-unit'] ) ) ? $menu_spacing['tablet-unit'] : '';

	// - Mobile.
	$menu_mobile_spacing_top = ( isset( $menu_spacing['mobile']['top'] ) && ! empty( $menu_spacing['mobile']['top'] ) ) ? $menu_spacing['mobile']['top'] : '';

	$menu_mobile_spacing_bottom = ( isset( $menu_spacing['mobile']['bottom'] ) && ! empty( $menu_spacing['mobile']['bottom'] ) ) ? $menu_spacing['mobile']['bottom'] : '';

	$menu_mobile_spacing_right = ( isset( $menu_spacing['mobile']['right'] ) && ! empty( $menu_spacing['mobile']['right'] ) ) ? $menu_spacing['mobile']['right'] : '';

	$menu_mobile_spacing_left = ( isset( $menu_spacing['mobile']['left'] ) && ! empty( $menu_spacing['mobile']['left'] ) ) ? $menu_spacing['mobile']['left'] : '';

	$menu_mobile_spacing_unit = ( isset( $menu_spacing['mobile-unit'] ) && ! empty( $menu_spacing['mobile-unit'] ) ) ? $menu_spacing['mobile-unit'] : '';

	$margin = astra_get_option( $_section . '-margin' );

	$arr_footer_ul_desktop = array(
		// Margin CSS.
		'margin-top'    => astra_responsive_spacing( $margin, 'top', 'desktop' ),
		'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'desktop' ),
		'margin-left'   => astra_responsive_spacing( $margin, 'left', 'desktop' ),
		'margin-right'  => astra_responsive_spacing( $margin, 'right', 'desktop' ),
	);

	$arr_footer_ul_desktop = array_merge( $arr_footer_ul_desktop, astra_get_responsive_background_obj( $menu_resp_bg_color, 'desktop' ) );

	$css_output_desktop = array(
		'.footer-widget-area[data-section="section-footer-menu"] .astra-footer-horizontal-menu' => array(
			'justify-content' => $desktop_alignment,
		),
		'.footer-widget-area[data-section="section-footer-menu"] .astra-footer-vertical-menu .menu-item' => array(
			'align-items' => $desktop_alignment,
		),
		$selector . ' .menu-item > a'                   => array(
			'color'          => $menu_resp_color_desktop,
			'font-size'      => astra_get_font_css_value( $menu_font_size_desktop, $menu_font_size_desktop_unit ),
			'padding-top'    => astra_get_css_value( $menu_desktop_spacing_top, $menu_desktop_spacing_unit ),
			'padding-bottom' => astra_get_css_value( $menu_desktop_spacing_bottom, $menu_desktop_spacing_unit ),
			'padding-left'   => astra_get_css_value( $menu_desktop_spacing_left, $menu_desktop_spacing_unit ),
			'padding-right'  => astra_get_css_value( $menu_desktop_spacing_right, $menu_desktop_spacing_unit ),
		),
		$selector . ' .menu-item:hover > a'             => array(
			'color'      => $menu_resp_color_hover_desktop,
			'background' => $menu_resp_bg_color_hover_desktop,
		),
		$selector . ' .menu-item.current-menu-item > a' => array(
			'color'      => $menu_resp_color_active_desktop,
			'background' => $menu_resp_bg_color_active_desktop,
		),
		$selector                                       => $arr_footer_ul_desktop,
	);

	$arr_footer_ul_tablet = array(
		// Margin CSS.
		'margin-top'    => astra_responsive_spacing( $margin, 'top', 'tablet' ),
		'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'tablet' ),
		'margin-left'   => astra_responsive_spacing( $margin, 'left', 'tablet' ),
		'margin-right'  => astra_responsive_spacing( $margin, 'right', 'tablet' ),
	);

	$arr_footer_ul_tablet = array_merge( $arr_footer_ul_tablet, astra_get_responsive_background_obj( $menu_resp_bg_color, 'tablet' ) );

	$css_output_tablet = array(
		'.footer-widget-area[data-section="section-footer-menu"] .astra-footer-tablet-horizontal-menu' => array(
			'justify-content' => $tablet_alignment,
		),
		'.footer-widget-area[data-section="section-footer-menu"] .astra-footer-tablet-vertical-menu' => array(
			'display' => 'grid',
		),
		'.footer-widget-area[data-section="section-footer-menu"] .astra-footer-tablet-vertical-menu .menu-item' => array(
			'align-items' => $tablet_alignment,
		),
		$selector . ' .menu-item > a'                   => array(
			'color'          => $menu_resp_color_tablet,
			'font-size'      => astra_get_font_css_value( $menu_font_size_tablet, $menu_font_size_tablet_unit ),
			'padding-top'    => astra_get_css_value( $menu_tablet_spacing_top, $menu_tablet_spacing_unit ),
			'padding-bottom' => astra_get_css_value( $menu_tablet_spacing_bottom, $menu_tablet_spacing_unit ),
			'padding-left'   => astra_get_css_value( $menu_tablet_spacing_left, $menu_tablet_spacing_unit ),
			'padding-right'  => astra_get_css_value( $menu_tablet_spacing_right, $menu_tablet_spacing_unit ),
		),
		$selector . ' .menu-item:hover > a'             => array(
			'color'      => $menu_resp_color_hover_tablet,
			'background' => $menu_resp_bg_color_hover_tablet,
		),
		$selector . ' .menu-item.current-menu-item > a' => array(
			'color'      => $menu_resp_color_active_tablet,
			'background' => $menu_resp_bg_color_active_tablet,
		),
		$selector                                       => $arr_footer_ul_tablet,
	);

	$arr_footer_ul_mobile = array(
		// Margin CSS.
		'margin-top'    => astra_responsive_spacing( $margin, 'top', 'mobile' ),
		'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'mobile' ),
		'margin-left'   => astra_responsive_spacing( $margin, 'left', 'mobile' ),
		'margin-right'  => astra_responsive_spacing( $margin, 'right', 'mobile' ),
	);

	$arr_footer_ul_mobile = array_merge( $arr_footer_ul_mobile, astra_get_responsive_background_obj( $menu_resp_bg_color, 'mobile' ) );

	$css_output_mobile = array(
		$selector                                       => astra_get_responsive_background_obj( $menu_resp_bg_color, 'mobile' ),
		'.footer-widget-area[data-section="section-footer-menu"] .astra-footer-mobile-horizontal-menu' => array(
			'justify-content' => $mobile_alignment,
		),
		'.footer-widget-area[data-section="section-footer-menu"] .astra-footer-mobile-vertical-menu' => array(
			'display' => 'grid',
		),
		'.footer-widget-area[data-section="section-footer-menu"] .astra-footer-mobile-vertical-menu .menu-item' => array(
			'align-items' => $mobile_alignment,
		),
		$selector . ' .menu-item > a'                   => array(
			'color'          => $menu_resp_color_mobile,
			'font-size'      => astra_get_font_css_value( $menu_font_size_mobile, $menu_font_size_mobile_unit ),
			'padding-top'    => astra_get_css_value( $menu_mobile_spacing_top, $menu_mobile_spacing_unit ),
			'padding-bottom' => astra_get_css_value( $menu_mobile_spacing_bottom, $menu_mobile_spacing_unit ),
			'padding-left'   => astra_get_css_value( $menu_mobile_spacing_left, $menu_mobile_spacing_unit ),
			'padding-right'  => astra_get_css_value( $menu_mobile_spacing_right, $menu_mobile_spacing_unit ),
		),
		$selector . ' .menu-item:hover > a'             => array(
			'color'      => $menu_resp_color_hover_mobile,
			'background' => $menu_resp_bg_color_hover_mobile,
		),
		$selector . ' .menu-item.current-menu-item > a' => array(
			'color'      => $menu_resp_color_active_mobile,
			'background' => $menu_resp_bg_color_active_mobile,
		),
		$selector                                       => $arr_footer_ul_mobile,
	);

	/* Parse CSS from array() */
	$css_output  = astra_parse_css( $css_output_desktop );
	$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
	$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $visibility_selector, 'block' );

	return $dynamic_css;
}
