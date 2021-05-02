<?php
/**
 * Transparent Header - Dynamic CSS
 *
 * @package Astra Addon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'astra_dynamic_theme_css', 'astra_ext_transparent_header_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  String $dynamic_css          Astra Dynamic CSS.
 * @param  String $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Dynamic CSS.
 */
function astra_ext_transparent_header_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( true != Astra_Ext_Transparent_Header_Markup::is_transparent_header() ) {
		return $dynamic_css;
	}

	/**
	 * Set colors
	 *
	 * If colors extension is_active then get color from it.
	 * Else set theme default colors.
	 */
	$transparent_header_separator       = astra_get_option( 'transparent-header-main-sep' );
	$transparent_header_separator_color = astra_get_option( 'transparent-header-main-sep-color' );

	$transparent_header_logo_width = astra_get_option( 'transparent-header-logo-width' );

	$transparent_header_inherit = astra_get_option( 'different-transparent-logo' );
	$transparent_header_logo    = astra_get_option( 'transparent-header-logo' );

	$transparent_bg_color_desktop = astra_get_prop( astra_get_option( 'transparent-header-bg-color-responsive' ), 'desktop' );
	$transparent_bg_color_tablet  = astra_get_prop( astra_get_option( 'transparent-header-bg-color-responsive' ), 'tablet', $transparent_bg_color_desktop );
	$transparent_bg_color_mobile  = astra_get_prop( astra_get_option( 'transparent-header-bg-color-responsive' ), 'mobile', ( $transparent_bg_color_tablet ) ? $transparent_bg_color_tablet : $transparent_bg_color_desktop );

	$transparent_color_site_title_desktop = astra_get_prop( astra_get_option( 'transparent-header-color-site-title-responsive' ), 'desktop' );
	$transparent_color_site_title_tablet  = astra_get_prop( astra_get_option( 'transparent-header-color-site-title-responsive' ), 'tablet' );
	$transparent_color_site_title_mobile  = astra_get_prop( astra_get_option( 'transparent-header-color-site-title-responsive' ), 'mobile' );

	$transparent_color_h_site_title_desktop = astra_get_prop( astra_get_option( 'transparent-header-color-h-site-title-responsive' ), 'desktop' );
	$transparent_color_h_site_title_tablet  = astra_get_prop( astra_get_option( 'transparent-header-color-h-site-title-responsive' ), 'tablet' );
	$transparent_color_h_site_title_mobile  = astra_get_prop( astra_get_option( 'transparent-header-color-h-site-title-responsive' ), 'mobile' );

	$transparent_menu_bg_color_desktop = astra_get_prop( astra_get_option( 'transparent-menu-bg-color-responsive' ), 'desktop' );
	$transparent_menu_color_desktop    = astra_get_prop( astra_get_option( 'transparent-menu-color-responsive' ), 'desktop' );
	$transparent_menu_h_color_desktop  = astra_get_prop( astra_get_option( 'transparent-menu-h-color-responsive' ), 'desktop' );

	$transparent_menu_bg_color_tablet = astra_get_prop( astra_get_option( 'transparent-menu-bg-color-responsive' ), 'tablet' );
	$transparent_menu_color_tablet    = astra_get_prop( astra_get_option( 'transparent-menu-color-responsive' ), 'tablet' );
	$transparent_menu_h_color_tablet  = astra_get_prop( astra_get_option( 'transparent-menu-h-color-responsive' ), 'tablet' );

	$transparent_menu_bg_color_mobile = astra_get_prop( astra_get_option( 'transparent-menu-bg-color-responsive' ), 'mobile' );
	$transparent_menu_color_mobile    = astra_get_prop( astra_get_option( 'transparent-menu-color-responsive' ), 'mobile' );
	$transparent_menu_h_color_mobile  = astra_get_prop( astra_get_option( 'transparent-menu-h-color-responsive' ), 'mobile' );

	$transparent_sub_menu_color_desktop    = astra_get_prop( astra_get_option( 'transparent-submenu-color-responsive' ), 'desktop' );
	$transparent_sub_menu_h_color_desktop  = astra_get_prop( astra_get_option( 'transparent-submenu-h-color-responsive' ), 'desktop' );
	$transparent_sub_menu_bg_color_desktop = astra_get_prop( astra_get_option( 'transparent-submenu-bg-color-responsive' ), 'desktop' );

	$transparent_sub_menu_color_tablet    = astra_get_prop( astra_get_option( 'transparent-submenu-color-responsive' ), 'tablet' );
	$transparent_sub_menu_h_color_tablet  = astra_get_prop( astra_get_option( 'transparent-submenu-h-color-responsive' ), 'tablet' );
	$transparent_sub_menu_bg_color_tablet = astra_get_prop( astra_get_option( 'transparent-submenu-bg-color-responsive' ), 'tablet' );

	$transparent_sub_menu_color_mobile    = astra_get_prop( astra_get_option( 'transparent-submenu-color-responsive' ), 'mobile' );
	$transparent_sub_menu_h_color_mobile  = astra_get_prop( astra_get_option( 'transparent-submenu-h-color-responsive' ), 'mobile' );
	$transparent_sub_menu_bg_color_mobile = astra_get_prop( astra_get_option( 'transparent-submenu-bg-color-responsive' ), 'mobile' );

	$transparent_content_section_text_color_desktop   = astra_get_prop( astra_get_option( 'transparent-content-section-text-color-responsive' ), 'desktop' );
	$transparent_content_section_link_color_desktop   = astra_get_prop( astra_get_option( 'transparent-content-section-link-color-responsive' ), 'desktop' );
	$transparent_content_section_link_h_color_desktop = astra_get_prop( astra_get_option( 'transparent-content-section-link-h-color-responsive' ), 'desktop' );

	$transparent_content_section_text_color_tablet   = astra_get_prop( astra_get_option( 'transparent-content-section-text-color-responsive' ), 'tablet' );
	$transparent_content_section_link_color_tablet   = astra_get_prop( astra_get_option( 'transparent-content-section-link-color-responsive' ), 'tablet' );
	$transparent_content_section_link_h_color_tablet = astra_get_prop( astra_get_option( 'transparent-content-section-link-h-color-responsive' ), 'tablet' );

	$transparent_content_section_text_color_mobile   = astra_get_prop( astra_get_option( 'transparent-content-section-text-color-responsive' ), 'mobile' );
	$transparent_content_section_link_color_mobile   = astra_get_prop( astra_get_option( 'transparent-content-section-link-color-responsive' ), 'mobile' );
	$transparent_content_section_link_h_color_mobile = astra_get_prop( astra_get_option( 'transparent-content-section-link-h-color-responsive' ), 'mobile' );

	$transparent_header_devices = astra_get_option( 'transparent-header-on-devices' );

	/**
	 * Generate Dynamic CSS
	 */

	$css = '';

	if ( '0' === $transparent_header_inherit && '' != $transparent_header_logo ) {
		$css_output = array(
			'.ast-theme-transparent-header .site-logo-img .custom-logo-link' => array(
				'display' => 'none',
			),
		);
		$css       .= astra_parse_css( $css_output );
	}

	// Desktop Transparent Heder Logo Width.
	$css_output = array(
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo .astra-logo-svg' => array(
			'width' => astra_get_css_value( $transparent_header_logo_width['desktop'], 'px' ),
		),
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo img' => array(
			' max-width' => astra_get_css_value( $transparent_header_logo_width['desktop'], 'px' ),
		),
	);
	$css       .= astra_parse_css( $css_output );

	// Tablet Transparent Heder Logo Width.
	$tablet_css_output = array(
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo .astra-logo-svg' => array(
			'width' => astra_get_css_value( $transparent_header_logo_width['tablet'], 'px' ),
		),
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo img' => array(
			' max-width' => astra_get_css_value( $transparent_header_logo_width['tablet'], 'px' ),
		),
	);
	$css              .= astra_parse_css( $tablet_css_output, '', astra_get_tablet_breakpoint() );

	// Mobile Transparent Heder Logo Width.
	$mobile_css_output = array(
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo .astra-logo-svg' => array(
			'width' => astra_get_css_value( $transparent_header_logo_width['mobile'], 'px' ),
		),
		'.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo img' => array(
			' max-width' => astra_get_css_value( $transparent_header_logo_width['mobile'], 'px' ),
		),
	);
	$css              .= astra_parse_css( $mobile_css_output, '', astra_get_mobile_breakpoint( 1 ) );

	$transparent_header_base = array(
		'.ast-theme-transparent-header #masthead'         => array(
			'position' => 'absolute',
			'left'     => '0',
			'right'    => '0',
		),

		'.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .main-header-bar' => array(
			'background' => 'none',
		),

		'body.elementor-editor-active.ast-theme-transparent-header #masthead, .fl-builder-edit .ast-theme-transparent-header #masthead, body.vc_editor.ast-theme-transparent-header #masthead, body.brz-ed.ast-theme-transparent-header #masthead' => array(
			'z-index' => '0',
		),

		'.ast-header-break-point.ast-replace-site-logo-transparent.ast-theme-transparent-header .custom-mobile-logo-link' => array(
			'display' => 'none',
		),

		'.ast-header-break-point.ast-replace-site-logo-transparent.ast-theme-transparent-header .transparent-custom-logo' => array(
			'display' => 'inline-block',
		),

		'.ast-theme-transparent-header .ast-above-header, .ast-theme-transparent-header .ast-above-header.ast-above-header-bar' => array(
			'background-image' => 'none',
			'background-color' => 'transparent',
		),

		'.ast-theme-transparent-header .ast-below-header' => array(
			'background-image' => 'none',
			'background-color' => 'transparent',
		),
	);

	/**
	 * Transparent Header Colors
	 */
	$transparent_header_desktop = array(

		'.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .main-header-bar-wrap .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .main-header-bar-wrap .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .ast-mobile-header-wrap .main-header-bar' => array(
			'background-color' => esc_attr( $transparent_bg_color_desktop ),
		),
		'.ast-theme-transparent-header .main-header-bar .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color_desktop ),
		),

		'.ast-theme-transparent-header .ast-above-header, .ast-theme-transparent-header .ast-above-header.ast-above-header-bar, .ast-theme-transparent-header .ast-below-header, .ast-header-break-point.ast-theme-transparent-header .ast-above-header, .ast-header-break-point.ast-theme-transparent-header .ast-below-header' => array(
			'background-color' => esc_attr( $transparent_bg_color_desktop ),
		),

		'.ast-theme-transparent-header .site-title a, .ast-theme-transparent-header .site-title a:focus, .ast-theme-transparent-header .site-title a:hover, .ast-theme-transparent-header .site-title a:visited' => array(
			'color' => esc_attr( $transparent_color_site_title_desktop ),
		),
		'.ast-theme-transparent-header .site-header .site-title a:hover' => array(
			'color' => esc_attr( $transparent_color_h_site_title_desktop ),
		),

		'.ast-theme-transparent-header .site-header .site-description' => array(
			'color' => esc_attr( $transparent_color_site_title_desktop ),
		),

		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .sub-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-builder-menu .main-header-bar-wrap .main-header-menu, .ast-flyout-menu-enable.ast-header-break-point.ast-theme-transparent-header .main-header-bar-navigation #site-navigation, .ast-fullscreen-menu-enable.ast-header-break-point.ast-theme-transparent-header .main-header-bar-navigation #site-navigation, .ast-flyout-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation-wrap .ast-above-header-navigation, .ast-flyout-below-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-below-header-navigation-wrap .ast-below-header-actual-nav, .ast-fullscreen-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation-wrap, .ast-fullscreen-below-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-below-header-navigation-wrap, .ast-theme-transparent-header .main-header-menu .menu-link' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color_desktop ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .ast-builder-menu .main-header-bar-navigation .main-header-menu .menu-item .sub-menu, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .ast-builder-menu .main-header-bar-navigation [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item .sub-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .ast-builder-menu .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .ast-builder-menu .main-header-bar-navigation [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item .sub-menu .menu-link, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu' => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color_desktop ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link' => array(
			'color' => esc_attr( $transparent_sub_menu_color_desktop ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-link:hover,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.focus > .menu-item,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.current-menu-item > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.current-menu-item > .ast-menu-toggle,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.focus > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-item:hover > .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color_desktop ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item > .menu-link, .ast-theme-transparent-header .ast-masthead-custom-menu-items, .ast-theme-transparent-header .ast-masthead-custom-menu-items a, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-navigation a, .ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation a, .ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation > ul.ast-above-header-menu > .menu-item-has-children:not(.current-menu-item) > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu, .ast-theme-transparent-header .ast-below-header-menu a, .ast-header-break-point.ast-theme-transparent-header .ast-below-header-menu a, .ast-header-break-point.ast-theme-transparent-header .ast-below-header-menu, .ast-theme-transparent-header .main-header-menu .menu-link' => array(
			'color' => esc_attr( $transparent_menu_color_desktop ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .focus > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-ancestor > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-ancestor > .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > .menu-link' => array(
			'color' => esc_attr( $transparent_menu_h_color_desktop ),
		),
		// Content Section text color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget-title, .ast-theme-transparent-header .site-header-section [CLASS*="ast-header-html-"] .ast-builder-html-element' => array(
			'color' => esc_attr( $transparent_content_section_text_color_desktop ),
		),
		// Content Section link color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a, .ast-theme-transparent-header .site-header-section [CLASS*="ast-header-html-"] .ast-builder-html-element a' => array(
			'color' => esc_attr( $transparent_content_section_link_color_desktop ),
		),
		// Content Section link hover color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a:hover, .ast-theme-transparent-header .site-header-section [CLASS*="ast-header-html-"] .ast-builder-html-element a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color_desktop ),
		),
	);

	$transparent_header_tablet = array(

		'.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .main-header-bar-wrap .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .main-header-bar-wrap .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .ast-mobile-header-wrap .main-header-bar' => array(
			'background-color' => esc_attr( $transparent_bg_color_tablet ),
		),
		'.ast-theme-transparent-header .main-header-bar .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color_tablet ),
		),
		'.ast-theme-transparent-header .ast-above-header, .ast-theme-transparent-header .ast-above-header.ast-above-header-bar, .ast-theme-transparent-header .ast-below-header, .ast-header-break-point.ast-theme-transparent-header .ast-above-header, .ast-header-break-point.ast-theme-transparent-header .ast-below-header' => array(
			'background-color' => esc_attr( $transparent_bg_color_tablet ),
		),

		'.ast-theme-transparent-header .site-title a, .ast-theme-transparent-header .site-title a:focus, .ast-theme-transparent-header .site-title a:hover, .ast-theme-transparent-header .site-title a:visited, .ast-theme-transparent-header .ast-builder-layout-element .ast-site-identity .site-title a, .ast-theme-transparent-header .ast-builder-layout-element .ast-site-identity .site-title a:hover, .ast-theme-transparent-header .ast-builder-layout-element .ast-site-identity .site-title a:focus, .ast-theme-transparent-header .ast-builder-layout-element .ast-site-identity .site-title a:visited' => array(
			'color' => esc_attr( $transparent_color_site_title_tablet ),
		),
		'.ast-theme-transparent-header .site-header .site-title a:hover' => array(
			'color' => esc_attr( $transparent_color_h_site_title_tablet ),
		),

		'.ast-theme-transparent-header .site-header .site-description' => array(
			'color' => esc_attr( $transparent_color_site_title_tablet ),
		),

		'.ast-theme-transparent-header.ast-header-break-point .ast-builder-menu .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-builder-menu  .main-header-menu .sub-menu, .ast-theme-transparent-header.ast-header-break-point .ast-builder-menu  .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-builder-menu .main-header-bar-wrap .main-header-menu, .ast-flyout-menu-enable.ast-header-break-point.ast-theme-transparent-header .main-header-bar-navigation #site-navigation, .ast-fullscreen-menu-enable.ast-header-break-point.ast-theme-transparent-header .main-header-bar-navigation #site-navigation, .ast-flyout-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation-wrap .ast-above-header-navigation, .ast-flyout-below-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-below-header-navigation-wrap .ast-below-header-actual-nav, .ast-fullscreen-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation-wrap, .ast-fullscreen-below-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-below-header-navigation-wrap, .ast-theme-transparent-header .main-header-menu .menu-link' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color_tablet ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .ast-builder-menu .main-header-bar-navigation .main-header-menu .menu-item .sub-menu, .ast-theme-transparent-header.astra-hfb-header .ast-builder-menu [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item .sub-menu, .ast-header-break-point.ast-flyout-menu-enable.astra-hfb-header .ast-builder-menu .main-header-bar-navigation [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item .sub-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .ast-builder-menu .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link, .ast-theme-transparent-header.astra-hfb-header .ast-builder-menu [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.astra-hfb-header .ast-builder-menu .main-header-bar-navigation [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item .sub-menu .menu-link, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu' => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color_tablet ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link' => array(
			'color' => esc_attr( $transparent_sub_menu_color_tablet ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-link:hover,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.focus > .menu-item,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.current-menu-item > .menu-link,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.current-menu-item > .ast-menu-toggle,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.focus > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-item:hover > .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color_tablet ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item > .menu-link, .ast-theme-transparent-header .ast-masthead-custom-menu-items, .ast-theme-transparent-header .ast-masthead-custom-menu-items a,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-link' => array(
			'color' => esc_attr( $transparent_menu_color_tablet ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .focus > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-ancestor > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-ancestor > .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > .menu-link' => array(
			'color' => esc_attr( $transparent_menu_h_color_tablet ),
		),
		// Content Section text color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget-title, .ast-theme-transparent-header .site-header-section [CLASS*="ast-header-html-"] .ast-builder-html-element' => array(
			'color' => esc_attr( $transparent_content_section_text_color_tablet ),
		),
		// Content Section link color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color_tablet ),
		),
		// Content Section link hover color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color_tablet ),
		),
	);

	$transparent_header_mobile = array(

		'.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .main-header-bar-wrap .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .main-header-bar-wrap .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .ast-mobile-header-wrap .main-header-bar' => array(
			'background-color' => esc_attr( $transparent_bg_color_mobile ),
		),
		'.ast-theme-transparent-header .main-header-bar .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color_mobile ),
		),

		'.ast-theme-transparent-header .ast-above-header, .ast-theme-transparent-header .ast-above-header.ast-above-header-bar, .ast-theme-transparent-header .ast-below-header, .ast-header-break-point.ast-theme-transparent-header .ast-above-header, .ast-header-break-point.ast-theme-transparent-header .ast-below-header' => array(
			'background-color' => esc_attr( $transparent_bg_color_mobile ),
		),

		'.ast-theme-transparent-header .site-title a, .ast-theme-transparent-header .site-title a:focus, .ast-theme-transparent-header .site-title a:hover, .ast-theme-transparent-header .site-title a:visited, .ast-theme-transparent-header .ast-builder-layout-element .ast-site-identity .site-title a, .ast-theme-transparent-header .ast-builder-layout-element .ast-site-identity .site-title a:hover, .ast-theme-transparent-header .ast-builder-layout-element .ast-site-identity .site-title a:focus, .ast-theme-transparent-header .ast-builder-layout-element .ast-site-identity .site-title a:visited' => array(
			'color' => esc_attr( $transparent_color_site_title_mobile ),
		),
		'.ast-theme-transparent-header .site-header .site-title a:hover' => array(
			'color' => esc_attr( $transparent_color_h_site_title_mobile ),
		),

		'.ast-theme-transparent-header .site-header .site-description' => array(
			'color' => esc_attr( $transparent_color_site_title_mobile ),
		),

		'.ast-theme-transparent-header.ast-header-break-point .ast-builder-menu .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-builder-menu  .main-header-menu .sub-menu, .ast-theme-transparent-header.ast-header-break-point .ast-builder-menu  .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-builder-menu .main-header-bar-wrap .main-header-menu, .ast-flyout-menu-enable.ast-header-break-point.ast-theme-transparent-header .main-header-bar-navigation #site-navigation, .ast-fullscreen-menu-enable.ast-header-break-point.ast-theme-transparent-header .main-header-bar-navigation #site-navigation, .ast-flyout-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation-wrap .ast-above-header-navigation, .ast-flyout-below-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-below-header-navigation-wrap .ast-below-header-actual-nav, .ast-fullscreen-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation-wrap, .ast-fullscreen-below-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-below-header-navigation-wrap, .ast-theme-transparent-header .main-header-menu .menu-link' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color_mobile ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .ast-builder-menu .main-header-bar-navigation .main-header-menu .menu-item .sub-menu, .ast-theme-transparent-header.astra-hfb-header .ast-builder-menu [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item .sub-menu, .ast-header-break-point.ast-flyout-menu-enable.astra-hfb-header .ast-builder-menu .main-header-bar-navigation [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item .sub-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .ast-builder-menu .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link, .ast-theme-transparent-header.astra-hfb-header .ast-builder-menu [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.astra-hfb-header .ast-builder-menu .main-header-bar-navigation [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item .sub-menu .menu-link, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu' => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color_mobile ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link' => array(
			'color' => esc_attr( $transparent_sub_menu_color_mobile ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-link:hover,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.focus > .menu-item,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.current-menu-item > .menu-link,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.current-menu-item > .ast-menu-toggle,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.focus > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-item.focus > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-item:hover > .menu-link, .ast-header-break-point.ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation .main-header-menu .menu-item .sub-menu .menu-link' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color_mobile ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-link, .ast-theme-transparent-header .ast-masthead-custom-menu-items, .ast-theme-transparent-header .ast-masthead-custom-menu-items a, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-link' => array(
			'color' => esc_attr( $transparent_menu_color_mobile ),
		),
		'.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .focus > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-ancestor > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-ancestor > .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > .menu-link' => array(
			'color' => esc_attr( $transparent_menu_h_color_mobile ),
		),
		// Content Section text color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget-title, .ast-theme-transparent-header .site-header-section [CLASS*="ast-header-html-"] .ast-builder-html-element' => array(
			'color' => esc_attr( $transparent_content_section_text_color_mobile ),
		),
		// Content Section link color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color_mobile ),
		),
		// Content Section link hover color.
		'.ast-theme-transparent-header div.ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color_mobile ),
		),
	);

	/* Parse CSS from array() */
	if ( 'both' === $transparent_header_devices || 'desktop' === $transparent_header_devices ) {
		$css .= astra_parse_css( $transparent_header_base, astra_get_tablet_breakpoint() );

		// If Transparent header is active on mobile + desktop, enqueue CSS without media queeries.
		// If only for desktop add media query for the transparent header.
		if ( 'both' === $transparent_header_devices ) {
			$css .= astra_parse_css( $transparent_header_desktop );
		} else {
			$css .= astra_parse_css( $transparent_header_desktop, astra_get_tablet_breakpoint( '', 1 ) );
		}
	}

	if ( 'mobile' === $transparent_header_devices ) {
		$css .= astra_parse_css(
			array(
				'.transparent-custom-logo' => array(
					'display' => 'none',
				),
			),
			astra_get_tablet_breakpoint()
		);

		$css .= astra_parse_css(
			array(
				'.transparent-custom-logo' => array(
					'display' => 'block',
				),
			),
			'',
			astra_get_tablet_breakpoint()
		);

		$css .= astra_parse_css(
			array(
				'.ast-transparent-desktop-logo' => array(
					'display' => 'none',
				),
			),
			'',
			astra_get_tablet_breakpoint()
		);
	}

	if ( 'desktop' === $transparent_header_devices ) {
		$css .= astra_parse_css(
			array(
				'.transparent-custom-logo' => array(
					'display' => 'none',
				),
			),
			'',
			astra_get_tablet_breakpoint()
		);

		$css .= astra_parse_css(
			array(
				'.ast-transparent-mobile-logo' => array(
					'display' => 'none',
				),
			),
			astra_get_tablet_breakpoint()
		);

		$css .= astra_parse_css(
			array(
				'.ast-transparent-mobile-logo' => array(
					'display' => 'block',
				),
			),
			'',
			astra_get_tablet_breakpoint()
		);
	}

	if ( 'both' === $transparent_header_devices || 'mobile' === $transparent_header_devices ) {
		$css .= astra_parse_css( $transparent_header_base, '', astra_get_tablet_breakpoint() );
		$css .= astra_parse_css( $transparent_header_tablet, '', astra_get_tablet_breakpoint() );
		$css .= astra_parse_css( $transparent_header_mobile, '', astra_get_mobile_breakpoint() );
	}

	$mobile_header_type = astra_get_option( 'mobile-header-type' );

	if ( 'dropdown' === $mobile_header_type || is_customize_preview() ) {
		$header_child_selector = '[CLASS*="-header-wrap"]:nth-last-child(2) > [CLASS*="-header-bar"]';
	} else {
		$header_child_selector = '[CLASS*="-header-wrap"]:last-child > [CLASS*="-header-bar"]';
	}

	if ( 'both' === $transparent_header_devices ) {

		$selector = '.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .main-header-bar';

		if ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) {
			$selector = '.ast-theme-transparent-header #ast-desktop-header > ' . $header_child_selector . ', .ast-theme-transparent-header.ast-header-break-point #ast-mobile-header > ' . $header_child_selector;
		}

		$css .= astra_parse_css(
			array(
				$selector => array(
					'border-bottom-width' => astra_get_css_value( $transparent_header_separator, 'px' ),
					'border-bottom-style' => 'solid',
					'border-bottom-color' => esc_attr( $transparent_header_separator_color ),
				),
			)
		);
	}

	if ( 'mobile' === $transparent_header_devices ) {

		$selector = '.ast-theme-transparent-header.ast-header-break-point .main-header-bar';

		if ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) {
			$selector = '.ast-theme-transparent-header.ast-header-break-point #ast-mobile-header > ' . $header_child_selector;
		}

		$css .= astra_parse_css(
			array(
				$selector => array(
					'border-bottom-width' => astra_get_css_value( $transparent_header_separator, 'px' ),
					'border-bottom-style' => 'solid',
					'border-bottom-color' => esc_attr( $transparent_header_separator_color ),
				),
			),
			'',
			astra_get_tablet_breakpoint()
		);
	}

	if ( 'desktop' === $transparent_header_devices ) {

		$selector = '.ast-theme-transparent-header .main-header-bar';

		if ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) {
			$selector = '.ast-theme-transparent-header #ast-desktop-header > ' . $header_child_selector;
		}

		$css .= astra_parse_css(
			array(
				$selector => array(
					'border-bottom-width' => astra_get_css_value( $transparent_header_separator, 'px' ),
					'border-bottom-style' => 'solid',
					'border-bottom-color' => esc_attr( $transparent_header_separator_color ),
				),
			),
			astra_get_tablet_breakpoint()
		);
	}

	$dynamic_css .= $css;

	return $dynamic_css;
}
