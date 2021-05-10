<?php
/**
 * Heading Colors - Dynamic CSS
 *
 * @package astra-builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Heading Colors
 */
add_filter( 'astra_dynamic_theme_css', 'astra_hb_menu_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function astra_hb_menu_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_header_menu; $index++ ) {

		if ( ! Astra_Builder_Helper::is_component_loaded( 'menu-' . $index, 'header' ) ) {
			continue;
		}

		$_prefix  = 'menu' . $index;
		$_section = 'section-hb-menu-' . $index;

		$selector = '.ast-builder-menu-' . $index;

		// Theme color.
		$theme_color = astra_get_option( 'theme-color' );

		// Sub Menu.
		$sub_menu_border         = astra_get_option( 'header-' . $_prefix . '-submenu-border' );
		$sub_menu_divider_toggle = astra_get_option( 'header-' . $_prefix . '-submenu-item-border' );
		$sub_menu_divider_size   = astra_get_option( 'header-' . $_prefix . '-submenu-item-b-size' );
		$sub_menu_divider_color  = astra_get_option( 'header-' . $_prefix . '-submenu-item-b-color' );
		$sub_menu_border_radius  = astra_get_option( 'header-' . $_prefix . '-submenu-border-radius' );
		$sub_menu_top_offset     = astra_get_option( 'header-' . $_prefix . '-submenu-top-offset' );
		$sub_menu_width          = astra_get_option( 'header-' . $_prefix . '-submenu-width' );

		// Menu.
		$menu_resp_color           = astra_get_option( 'header-' . $_prefix . '-color-responsive' );
		$menu_resp_bg_color        = astra_get_option( 'header-' . $_prefix . '-bg-obj-responsive' );
		$menu_resp_color_hover     = astra_get_option( 'header-' . $_prefix . '-h-color-responsive' );
		$menu_resp_bg_color_hover  = astra_get_option( 'header-' . $_prefix . '-h-bg-color-responsive' );
		$menu_resp_color_active    = astra_get_option( 'header-' . $_prefix . '-a-color-responsive' );
		$menu_resp_bg_color_active = astra_get_option( 'header-' . $_prefix . '-a-bg-color-responsive' );

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
		$menu_font_family    = astra_get_option( 'header-' . $_prefix . '-font-family' );
		$menu_font_size      = astra_get_option( 'header-' . $_prefix . '-font-size' );
		$menu_font_weight    = astra_get_option( 'header-' . $_prefix . '-font-weight' );
		$menu_text_transform = astra_get_option( 'header-' . $_prefix . '-text-transform' );
		$menu_line_height    = astra_get_option( 'header-' . $_prefix . '-line-height' );

		$menu_font_size_desktop      = ( isset( $menu_font_size['desktop'] ) ) ? $menu_font_size['desktop'] : '';
		$menu_font_size_tablet       = ( isset( $menu_font_size['tablet'] ) ) ? $menu_font_size['tablet'] : '';
		$menu_font_size_mobile       = ( isset( $menu_font_size['mobile'] ) ) ? $menu_font_size['mobile'] : '';
		$menu_font_size_desktop_unit = ( isset( $menu_font_size['desktop-unit'] ) ) ? $menu_font_size['desktop-unit'] : '';
		$menu_font_size_tablet_unit  = ( isset( $menu_font_size['tablet-unit'] ) ) ? $menu_font_size['tablet-unit'] : '';
		$menu_font_size_mobile_unit  = ( isset( $menu_font_size['mobile-unit'] ) ) ? $menu_font_size['mobile-unit'] : '';


		// Spacing.
		$menu_spacing = astra_get_option( 'header-' . $_prefix . '-menu-spacing' );

		$sub_menu_border_top = ( isset( $sub_menu_border ) && ! empty( $sub_menu_border['top'] ) ) ? $sub_menu_border['top'] : 0;

		$sub_menu_border_bottom = ( isset( $sub_menu_border ) && ! empty( $sub_menu_border['bottom'] ) ) ? $sub_menu_border['bottom'] : 0;

		$sub_menu_border_right = ( isset( $sub_menu_border ) && ! empty( $sub_menu_border['right'] ) ) ? $sub_menu_border['right'] : 0;

		$sub_menu_border_left = ( isset( $sub_menu_border ) && ! empty( $sub_menu_border['left'] ) ) ? $sub_menu_border['left'] : 0;

		// Border-Radius.
		$sub_menu_border_radius = ! empty( $sub_menu_border_radius ) ? $sub_menu_border_radius : 0;

		// Top offset position.
		$sub_menu_top_offset = ! empty( $sub_menu_top_offset ) ? $sub_menu_top_offset : 0;

		// Submenu container width.
		$sub_menu_width = ! empty( $sub_menu_width ) ? $sub_menu_width : '';

		// Margin.
		$margin          = astra_get_option( $_section . '-margin' );
		$margin_selector = '.ast-builder-menu-' . $index . ' .main-header-menu, .ast-header-break-point .ast-builder-menu-' . $index . ' .main-header-menu';

		$css_output_desktop = array(

			// Menu.
			$selector                                    => array(
				'font-family'    => astra_get_font_family( $menu_font_family ),
				'font-weight'    => esc_attr( $menu_font_weight ),
				'text-transform' => esc_attr( $menu_text_transform ),
			),
			$selector . ' .menu-item > .menu-link'       => array(
				'line-height'    => esc_attr( $menu_line_height ),
				'font-size'      => astra_get_font_css_value( $menu_font_size_desktop, $menu_font_size_desktop_unit ),
				'color'          => $menu_resp_color_desktop,
				'padding-top'    => astra_responsive_spacing( $menu_spacing, 'top', 'desktop' ),
				'padding-bottom' => astra_responsive_spacing( $menu_spacing, 'bottom', 'desktop' ),
				'padding-left'   => astra_responsive_spacing( $menu_spacing, 'left', 'desktop' ),
				'padding-right'  => astra_responsive_spacing( $menu_spacing, 'right', 'desktop' ),
			),
			$selector . ' .menu-item > .ast-menu-toggle' => array(
				'color' => $menu_resp_color_desktop,
			),
			$selector . ' .menu-item:hover > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item:hover > .ast-menu-toggle' => array(
				'color'      => $menu_resp_color_hover_desktop,
				'background' => $menu_resp_bg_color_hover_desktop,
			),
			$selector . ' .menu-item:hover > .ast-menu-toggle' => array(
				'color' => $menu_resp_color_hover_desktop,
			),
			$selector . ' .menu-item.current-menu-item > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item.current-menu-item > .ast-menu-toggle, ' . $selector . ' .current-menu-ancestor > .menu-link' => array(
				'color'      => $menu_resp_color_active_desktop,
				'background' => $menu_resp_bg_color_active_desktop,
			),
			$selector . ' .menu-item.current-menu-item > .ast-menu-toggle' => array(
				'color' => $menu_resp_color_active_desktop,
			),
			// Sub Menu.
			$selector . ' .sub-menu, ' . $selector . ' .inline-on-mobile .sub-menu' => array(
				'border-top-width'    => astra_get_css_value( $sub_menu_border_top, 'px' ),
				'border-bottom-width' => astra_get_css_value( $sub_menu_border_bottom, 'px' ),
				'border-right-width'  => astra_get_css_value( $sub_menu_border_right, 'px' ),
				'border-left-width'   => astra_get_css_value( $sub_menu_border_left, 'px' ),
				'border-color'        => esc_attr( astra_get_option( 'header-' . $_prefix . '-submenu-b-color', $theme_color ) ),
				'border-style'        => 'solid',
				'width'               => astra_get_css_value( $sub_menu_width, 'px' ),
				'border-radius'       => astra_get_css_value( $sub_menu_border_radius, 'px' ),
			),
			$selector . ' .main-header-menu > .menu-item > .sub-menu, ' . $selector . ' .main-header-menu > .menu-item > .astra-full-megamenu-wrapper' => array(
				'margin-top' => astra_get_css_value( $sub_menu_top_offset, 'px' ),
			),
			'.ast-desktop ' . $selector . ' .main-header-menu > .menu-item > .sub-menu:before, .ast-desktop ' . $selector . ' .main-header-menu > .menu-item > .astra-full-megamenu-wrapper:before' => array(
				'height' => astra_calculate_spacing( $sub_menu_top_offset . 'px', '+', '5', 'px' ),
			),
			$selector . ' .menu-item.menu-item-has-children > .ast-menu-toggle' => array(
				'top'   => astra_responsive_spacing( $menu_spacing, 'top', 'desktop' ),
				'right' => astra_calculate_spacing( astra_responsive_spacing( $menu_spacing, 'right', 'desktop' ), '-', '0.907', 'em' ),
			),
			// Margin CSS.
			$margin_selector                             => array(
				'margin-top'    => astra_responsive_spacing( $margin, 'top', 'desktop' ),
				'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'desktop' ),
				'margin-left'   => astra_responsive_spacing( $margin, 'left', 'desktop' ),
				'margin-right'  => astra_responsive_spacing( $margin, 'right', 'desktop' ),
			),
		);

		$css_output_desktop[ $selector . ' .main-header-menu, ' . $selector . ' .main-header-menu .sub-menu' ] = astra_get_responsive_background_obj( $menu_resp_bg_color, 'desktop' );

		$mobile_selector = '.ast-header-break-point .ast-builder-menu-' . $index;

		$menu_spacing_mobile_top = astra_responsive_spacing( $menu_spacing, 'top', 'mobile' );
		$menu_spacing_mobile_top = ( isset( $menu_spacing_mobile_top ) && ! empty( $menu_spacing_mobile_top ) ) ? $menu_spacing_mobile_top : 0;

		$menu_spacing_tablet_top = astra_responsive_spacing( $menu_spacing, 'top', 'tablet' );
		$menu_spacing_tablet_top = ( isset( $menu_spacing_tablet_top ) && ! empty( $menu_spacing_tablet_top ) ) ? $menu_spacing_tablet_top : 0;

		$css_output_tablet = array(

			$mobile_selector                             => array(
				'font-size' => astra_get_font_css_value( $menu_font_size_tablet, $menu_font_size_tablet_unit ),
			),
			$mobile_selector . ' .main-header-menu .menu-item > .menu-link' => array(
				'padding-top'    => astra_responsive_spacing( $menu_spacing, 'top', 'tablet' ),
				'padding-bottom' => astra_responsive_spacing( $menu_spacing, 'bottom', 'tablet' ),
				'padding-left'   => astra_responsive_spacing( $menu_spacing, 'left', 'tablet' ),
				'padding-right'  => astra_responsive_spacing( $menu_spacing, 'right', 'tablet' ),
			),
			$selector . ' .main-header-menu .menu-item > .menu-link' => array(
				'color' => $menu_resp_color_tablet,
			),
			$selector . ' .menu-item > .ast-menu-toggle' => array(
				'color' => $menu_resp_color_tablet,
			),
			$selector . ' .menu-item:hover > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item:hover > .ast-menu-toggle' => array(
				'color'      => $menu_resp_color_hover_tablet,
				'background' => $menu_resp_bg_color_hover_tablet,
			),
			$selector . ' .menu-item:hover > .ast-menu-toggle' => array(
				'color' => $menu_resp_color_hover_tablet,
			),
			$selector . ' .menu-item.current-menu-item > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item.current-menu-item > .ast-menu-toggle, ' . $selector . ' .current-menu-ancestor > .menu-link, ' . $selector . ' .current-menu-ancestor > .ast-menu-toggle' => array(
				'color'      => $menu_resp_color_active_tablet,
				'background' => $menu_resp_bg_color_active_tablet,
			),
			$selector . ' .menu-item.current-menu-item > .ast-menu-toggle' => array(
				'color' => $menu_resp_color_active_tablet,
			),
			$mobile_selector . ' .menu-item.menu-item-has-children > .ast-menu-toggle' => array(
				'top'   => $menu_spacing_tablet_top,
				'right' => astra_calculate_spacing( astra_responsive_spacing( $menu_spacing, 'right', 'tablet' ), '-', '0.907', 'em' ),
			),
			$selector . ' .menu-item-has-children > .menu-link:after' => array(
				'content' => 'unset',
			),
			// Margin CSS.
			$margin_selector                             => array(
				'margin-top'    => astra_responsive_spacing( $margin, 'top', 'tablet' ),
				'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'tablet' ),
				'margin-left'   => astra_responsive_spacing( $margin, 'left', 'tablet' ),
				'margin-right'  => astra_responsive_spacing( $margin, 'right', 'tablet' ),
			),
		);

		$css_output_tablet[ $selector . ' .main-header-menu, ' . $selector . ' .main-header-menu .sub-menu' ] = astra_get_responsive_background_obj( $menu_resp_bg_color, 'tablet' );

		$css_output_mobile = array(

			$mobile_selector                              => array(
				'font-size' => astra_get_font_css_value( $menu_font_size_mobile, $menu_font_size_mobile_unit ),
			),
			$mobile_selector . ' .main-header-menu .menu-item > .menu-link' => array(
				'padding-top'    => astra_responsive_spacing( $menu_spacing, 'top', 'mobile' ),
				'padding-bottom' => astra_responsive_spacing( $menu_spacing, 'bottom', 'mobile' ),
				'padding-left'   => astra_responsive_spacing( $menu_spacing, 'left', 'mobile' ),
				'padding-right'  => astra_responsive_spacing( $menu_spacing, 'right', 'mobile' ),
			),
			$selector . ' .main-header-menu .menu-item > .menu-link' => array(
				'color' => $menu_resp_color_mobile,
			),
			$selector . ' .menu-item  > .ast-menu-toggle' => array(
				'color' => $menu_resp_color_mobile,
			),
			$selector . ' .menu-item:hover > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item:hover > .ast-menu-toggle' => array(
				'color'      => $menu_resp_color_hover_mobile,
				'background' => $menu_resp_bg_color_hover_mobile,
			),
			$selector . ' .menu-item:hover  > .ast-menu-toggle' => array(
				'color' => $menu_resp_color_hover_mobile,
			),
			$selector . ' .menu-item.current-menu-item > .menu-link, ' . $selector . ' .inline-on-mobile .menu-item.current-menu-item > .ast-menu-toggle, ' . $selector . ' .current-menu-ancestor > .menu-link, ' . $selector . ' .current-menu-ancestor > .ast-menu-toggle' => array(
				'color'      => $menu_resp_color_active_mobile,
				'background' => $menu_resp_bg_color_active_mobile,
			),
			$selector . ' .menu-item.current-menu-item  > .ast-menu-toggle' => array(
				'color' => $menu_resp_color_active_mobile,
			),
			$mobile_selector . ' .menu-item.menu-item-has-children > .ast-menu-toggle' => array(
				'top'   => $menu_spacing_mobile_top,
				'right' => astra_calculate_spacing( astra_responsive_spacing( $menu_spacing, 'right', 'mobile' ), '-', '0.907', 'em' ),
			),
			// Margin CSS.
			$margin_selector                              => array(
				'margin-top'    => astra_responsive_spacing( $margin, 'top', 'mobile' ),
				'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'mobile' ),
				'margin-left'   => astra_responsive_spacing( $margin, 'left', 'mobile' ),
				'margin-right'  => astra_responsive_spacing( $margin, 'right', 'mobile' ),
			),
		);

		$css_output_mobile[ $selector . ' .main-header-menu, ' . $selector . ' .main-header-menu .sub-menu' ] = astra_get_responsive_background_obj( $menu_resp_bg_color, 'mobile' );

		if ( true === $sub_menu_divider_toggle ) {
			// Sub Menu Divider.
			$css_output_desktop[ '.ast-desktop ' . $selector . ' .menu-item .sub-menu .menu-link' ]                       = array(
				'border-bottom-width' => $sub_menu_divider_size . 'px',
				'border-color'        => $sub_menu_divider_color,
				'border-style'        => 'solid',
			);
			$css_output_desktop[ '.ast-desktop ' . $selector . ' .menu-item .sub-menu .menu-item:last-child .menu-link' ] = array(
				'border-bottom-width' => 0,
			);
		} else {

			$css_output_desktop[ '.ast-desktop .ast-builder-menu-' . $index . ' .menu-item .sub-menu .menu-link' ] = array(
				'border-style' => 'none',
			);
		}

		/* Parse CSS from array() */
		$css_output  = astra_parse_css( $css_output_desktop );
		$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
		$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

		$dynamic_css .= $css_output;
		
		$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $selector );

	}

	return $dynamic_css;
}
