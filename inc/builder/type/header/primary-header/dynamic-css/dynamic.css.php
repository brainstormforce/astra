<?php
/**
 * Primary Header - Dynamic CSS
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
add_filter( 'astra_dynamic_theme_css', 'astra_primary_header_breakpoint_style', 11 );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function astra_primary_header_breakpoint_style( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! is_customize_preview() && ( ! Astra_Builder_helper::is_row_empty( 'primary', 'header', 'desktop' ) && ! Astra_Builder_helper::is_row_empty( 'primary', 'header', 'mobile' ) ) ) {
		return $dynamic_css;
	}

	// Parsed CSS.
	$parse_css = '';

	$hb_header_height = astra_get_option( 'hb-header-height' );
	
	// Header Height.
	$hb_header_height_desktop = ( isset( $hb_header_height['desktop'] ) && ! empty( $hb_header_height['desktop'] ) ) ? $hb_header_height['desktop'] : '';
	$hb_header_height_tablet  = ( isset( $hb_header_height['tablet'] ) && ! empty( $hb_header_height['tablet'] ) ) ? $hb_header_height['tablet'] : '';
	$hb_header_height_mobile  = ( isset( $hb_header_height['mobile'] ) && ! empty( $hb_header_height['mobile'] ) ) ? $hb_header_height['mobile'] : '';


	$common_css_output = array(
		'.ast-mobile-header-wrap .ast-primary-header-bar, .ast-primary-header-bar .site-primary-header-wrap' => array(
			'min-height' => astra_get_css_value( $hb_header_height_desktop, 'px' ),
		),
		'.ast-desktop .ast-primary-header-bar .main-header-menu > .menu-item' => array(
			'line-height' => astra_get_css_value( $hb_header_height_desktop, 'px' ),
		),
	);

	$parse_css .= astra_parse_css( $common_css_output );

	if ( Astra_Builder_Helper::is_component_loaded( 'woo-cart', 'header' ) || Astra_Builder_Helper::is_component_loaded( 'edd-cart', 'header' ) ) {
		$common_css_cart_output = array(
			'.ast-desktop .ast-primary-header-bar .ast-header-woo-cart, .ast-desktop .ast-primary-header-bar .ast-header-edd-cart' => array(
				'line-height' => astra_get_css_value( $hb_header_height_desktop, 'px' ),
			),
		);

		$parse_css .= astra_parse_css( $common_css_cart_output );
	}
	

	$astra_header_width         = astra_get_option( 'hb-header-main-layout-width' );
	$header_breadcrumb_position = astra_get_option( 'breadcrumb-position' );

	/* Width for Header */
	if ( 'content' !== $astra_header_width ) {
		$general_global_responsive = array(
			'#masthead .ast-container, .site-header-focus-item + .ast-breadcrumbs-wrapper' => array(
				'max-width'     => '100%',
				'padding-left'  => '35px',
				'padding-right' => '35px',
			),
		);
		
		/* Parse CSS from array()*/
		$parse_css .= astra_parse_css( $general_global_responsive );

	} elseif ( 'astra_header_primary_container_after' == $header_breadcrumb_position ) {
		$site_content_width        = astra_get_option( 'site-content-width', 1200 );
		$general_global_responsive = array(
			'.site-header-focus-item + .ast-breadcrumbs-wrapper' => array(
				'max-width'     => astra_get_css_value( $site_content_width + 40, 'px' ),
				'margin-left'   => 'auto',
				'margin-right'  => 'auto',
				'padding-left'  => '20px',
				'padding-right' => '20px',
			),
		);

		/* Parse CSS from array()*/
		$parse_css .= astra_parse_css( $general_global_responsive );
	}

	$padding_below_breakpoint = array(
		'#masthead .ast-mobile-header-wrap .ast-above-header-bar, #masthead .ast-mobile-header-wrap .ast-primary-header-bar, #masthead .ast-mobile-header-wrap .ast-below-header-bar' => array(
			'padding-left'  => '20px',
			'padding-right' => '20px',
		),
	);

	$parse_css .= astra_parse_css( $padding_below_breakpoint, '', astra_get_tablet_breakpoint() );

	// Header Separator.
	$header_separator       = astra_get_option( 'hb-header-main-sep' );
	$header_separator_color = astra_get_option( 'hb-header-main-sep-color' );

	$meta_style = array(
		'.ast-header-break-point .ast-primary-header-bar' => array(
			'border-bottom-width' => astra_get_css_value( $header_separator, 'px' ),
			'border-bottom-color' => esc_attr( $header_separator_color ),
			'border-bottom-style' => 'solid',
		),
	);

	$parse_css .= astra_parse_css( $meta_style );

	$meta_style = array(
		'.ast-primary-header-bar' => array(
			'border-bottom-width' => astra_get_css_value( $header_separator, 'px' ),
			'border-bottom-color' => esc_attr( $header_separator_color ),
			'border-bottom-style' => 'solid',
		),
	);

	$parse_css .= astra_parse_css( $meta_style, astra_get_tablet_breakpoint( '', 1 ) );

	$header_bg_obj = astra_get_option( 'hb-header-bg-obj-responsive' );

	/**
	 * Responsive Colors options
	 * Header Responsive Background with Image
	 */
	$desktop_colors = array(
		'.ast-primary-header-bar' => astra_get_responsive_background_obj( $header_bg_obj, 'desktop' ),
	);

	$tablet_colors = array(
		'.ast-primary-header-bar.ast-primary-header' => astra_get_responsive_background_obj( $header_bg_obj, 'tablet' ),
		'.ast-mobile-header-wrap .ast-primary-header-bar, .ast-primary-header-bar .site-primary-header-wrap' => array(
			'min-height' => astra_get_css_value( $hb_header_height_tablet, 'px' ),
		),
	);
	$mobile_colors = array(
		'.ast-primary-header-bar.ast-primary-header' => astra_get_responsive_background_obj( $header_bg_obj, 'mobile' ),
		'.ast-mobile-header-wrap .ast-primary-header-bar , .ast-primary-header-bar .site-primary-header-wrap' => array(
			'min-height' => astra_get_css_value( $hb_header_height_mobile, 'px' ),
		),
	);

	/* Parse CSS from array() */
	/**
	 * Tweak - Check for AMP Support.
	 */
	$parse_css .= apply_filters( 'astra_addon_colors_dynamic_css_desktop', astra_parse_css( $desktop_colors ) );
	$parse_css .= apply_filters( 'astra_addon_colors_dynamic_css_tablet', astra_parse_css( $tablet_colors, '', astra_get_tablet_breakpoint() ) );
	$parse_css .= apply_filters( 'astra_addon_colors_dynamic_css_mobile', astra_parse_css( $mobile_colors, '', astra_get_mobile_breakpoint() ) );

	/**
	 * Tweak - $remove_bottom_sire_brancing - Search in Astra Pro.
	 */

	// Trim white space for faster page loading.
	$dynamic_css .= Astra_Enqueue_Scripts::trim_css( $parse_css );

	$_section = 'section-primary-header-builder';

	$selector = '.site-primary-header-wrap[data-section="ast_header_primary"]';

	$parent_selector = '.ast-desktop .ast-primary-header-bar.main-header-bar, .ast-header-break-point #masthead .ast-primary-header-bar.main-header-bar';

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_advanced_margin_padding_css( $_section, $parent_selector );

	$dynamic_css .= Astra_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, '.ast-primary-header-bar', 'block', 'grid' );

	// Advanced CSS for Header Builder.
	$margin = astra_get_option( 'section-header-builder-layout-margin' );

	// Desktop CSS.
	$css_output_desktop = array(

		'.astra-hfb-header .site-header' => array(
			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	// Tablet CSS.
	$css_output_tablet = array(

		'.astra-hfb-header .site-header' => array(
			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	// Mobile CSS.
	$css_output_mobile = array(

		'.astra-hfb-header .site-header' => array(
			// Margin CSS.
			'margin-top'    => astra_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => astra_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => astra_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => astra_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	$dynamic_css .= astra_parse_css( $css_output_desktop );
	$dynamic_css .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
	$dynamic_css .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

	return $dynamic_css;
}
