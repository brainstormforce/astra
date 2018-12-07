<?php
/**
 * Transparent Header - Dynamic CSS
 *
 * @package Astra Addon
 */

/**
 * Transparent Above Header
 */
add_filter( 'astra_dynamic_css', 'astra_ext_transparent_above_header_sections_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for above header transparent header.
 */
function astra_ext_transparent_above_header_sections_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$above_header_layout = astra_get_option( 'above-header-layout' );

	if ( 'disabled' == $above_header_layout && false === Astra_Ext_Transparent_Header_Markup::is_transparent_header() ) {
		return $dynamic_css;
	}

	/**
	 * Set colors
	 */

	$transparent_bg_color           = astra_get_option( 'transparent-header-bg-color-responsive' );
	$transparent_color_site_title   = astra_get_option( 'transparent-header-color-site-title-responsive' );
	$transparent_color_h_site_title = astra_get_option( 'transparent-header-color-h-site-title-responsive' );
	$transparent_menu_bg_color      = astra_get_option( 'transparent-menu-bg-color-responsive' );
	$transparent_menu_color         = astra_get_option( 'transparent-menu-color-responsive' );
	$transparent_menu_h_color       = astra_get_option( 'transparent-menu-h-color-responsive' );
	$transparent_sub_menu_color     = astra_get_option( 'transparent-submenu-color-responsive' );
	$transparent_sub_menu_h_color   = astra_get_option( 'transparent-submenu-h-color-responsive' );
	$transparent_sub_menu_bg_color  = astra_get_option( 'transparent-submenu-bg-color-responsive' );

	$transparent_content_section_text_color   = astra_get_option( 'transparent-content-section-text-color-responsive' );
	$transparent_content_section_link_color   = astra_get_option( 'transparent-content-section-link-color-responsive' );
	$transparent_content_section_link_h_color = astra_get_option( 'transparent-content-section-link-h-color-responsive' );
	/**
	 * Generate Dynamic CSS
	 */

	$css = '';
	/**
	 * Transparent Header Colors
	 */
	$transparent_header_desktop = array(
		'.ast-theme-transparent-header .ast-above-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul.ast-above-header-menu' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-above-header .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-above-header .slide-search .search-field' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-above-header .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),

		'.ast-theme-transparent-header .ast-above-header-navigation li.current-menu-item > a,.ast-theme-transparent-header .ast-above-header-navigation li.current-menu-ancestor > a' => array(
			'color' => esc_attr( $transparent_menu_h_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-navigation li:hover > a'     => array(
			'color' => esc_attr( $transparent_menu_h_color['desktop'] ),
		),

		'.ast-theme-transparent-header .ast-above-header-navigation a'                => array(
			'color' => esc_attr( $transparent_menu_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu' => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu li:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.focus > a,.ast-theme-transparent-header .ast-above-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['desktop'] ),
		),

		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu, .ast-theme-transparent-header .ast-above-header-menu .sub-menu a' => array(
			'color' => esc_attr( $transparent_sub_menu_color['desktop'] ),
		),

		// Content Section text color.
		'.ast-theme-transparent-header .ast-above-header-section .user-select, .ast-theme-transparent-header .ast-above-header-section .widget, .ast-theme-transparent-header .ast-above-header-section .widget-title' => array(
			'color' => esc_attr( $transparent_content_section_text_color['desktop'] ),
		),
		// Content Section link color.
		'.ast-theme-transparent-header .ast-above-header-section .user-select a, .ast-theme-transparent-header .ast-above-header-section .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color['desktop'] ),
		),
		// Content Section link hover color.
		'.ast-theme-transparent-header .ast-above-header-section .user-select a:hover, .ast-theme-transparent-header .ast-above-header-section .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color['desktop'] ),
		),

	);

	$transparent_header_tablet = array(
		'.ast-theme-transparent-header .ast-above-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul.ast-above-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-below-header-section-separated .ast-below-header-actual-nav' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-above-header .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-above-header .slide-search .search-field' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-above-header .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),

		'.ast-theme-transparent-header .ast-above-header-navigation li.current-menu-item > a,.ast-theme-transparent-header .ast-above-header-navigation li.current-menu-ancestor > a' => array(
			'color' => esc_attr( $transparent_menu_h_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-navigation li:hover > a'     => array(
			'color' => esc_attr( $transparent_menu_h_color['tablet'] ),
		),

		'.ast-theme-transparent-header .ast-above-header-navigation a'                => array(
			'color' => esc_attr( $transparent_menu_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu' => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu li:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.focus > a,.ast-theme-transparent-header .ast-above-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['tablet'] ),
		),

		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu, .ast-theme-transparent-header .ast-above-header-menu .sub-menu a' => array(
			'color' => esc_attr( $transparent_sub_menu_color['tablet'] ),
		),

		// Content Section text color.
		'.ast-theme-transparent-header .ast-above-header-section .user-select, .ast-theme-transparent-header .ast-above-header-section .widget, .ast-theme-transparent-header .ast-above-header-section .widget-title' => array(
			'color' => esc_attr( $transparent_content_section_text_color['tablet'] ),
		),
		// Content Section link color.
		'.ast-theme-transparent-header .ast-above-header-section .user-select a, .ast-theme-transparent-header .ast-above-header-section .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color['tablet'] ),
		),
		// Content Section link hover color.
		'.ast-theme-transparent-header .ast-above-header-section .user-select a:hover, .ast-theme-transparent-header .ast-above-header-section .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color['tablet'] ),
		),
	);

	$transparent_header_mobile = array(
		'.ast-theme-transparent-header .ast-above-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul.ast-above-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-below-header-section-separated .ast-below-header-actual-nav' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-above-header .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-above-header .slide-search .search-field' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-above-header .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),

		'.ast-theme-transparent-header .ast-above-header-navigation li.current-menu-item > a,.ast-theme-transparent-header .ast-above-header-navigation li.current-menu-ancestor > a' => array(
			'color' => esc_attr( $transparent_menu_h_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-navigation li:hover > a'     => array(
			'color' => esc_attr( $transparent_menu_h_color['mobile'] ),
		),

		'.ast-theme-transparent-header .ast-above-header-navigation a'                => array(
			'color' => esc_attr( $transparent_menu_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu' => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu li:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.focus > a,.ast-theme-transparent-header .ast-above-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['mobile'] ),
		),

		'.ast-theme-transparent-header .ast-above-header-menu .sub-menu, .ast-theme-transparent-header .ast-above-header-menu .sub-menu a' => array(
			'color' => esc_attr( $transparent_sub_menu_color['mobile'] ),
		),

		// Content Section text color.
		'.ast-theme-transparent-header .ast-above-header-section .user-select, .ast-theme-transparent-header .ast-above-header-section .widget, .ast-theme-transparent-header .ast-above-header-section .widget-title' => array(
			'color' => esc_attr( $transparent_content_section_text_color['mobile'] ),
		),
		// Content Section link color.
		'.ast-theme-transparent-header .ast-above-header-section .user-select a, .ast-theme-transparent-header .ast-above-header-section .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color['mobile'] ),
		),
		// Content Section link hover color.
		'.ast-theme-transparent-header .ast-above-header-section .user-select a:hover, .ast-theme-transparent-header .ast-above-header-section .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color['mobile'] ),
		),
	);

	/* Parse CSS from array() */
	$css .= astra_parse_css( $transparent_header_desktop );
	$css .= astra_parse_css( $transparent_header_tablet, '', '768' );
	$css .= astra_parse_css( $transparent_header_mobile, '', '544' );

	return $dynamic_css . $css;

}



/**
 * Transparent Below Header
 */
add_filter( 'astra_dynamic_css', 'astra_ext_transparent_below_header_sections_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS.
 */
function astra_ext_transparent_below_header_sections_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	// set page width depending on site layout.
	$below_header_layout = astra_get_option( 'below-header-layout' );

	if ( 'disabled' == $below_header_layout && false === Astra_Ext_Transparent_Header_Markup::is_transparent_header() ) {
		return $dynamic_css;
	}

	/**
	 * Set colors
	 */

	$transparent_bg_color           = astra_get_option( 'transparent-header-bg-color-responsive' );
	$transparent_color_site_title   = astra_get_option( 'transparent-header-color-site-title-responsive' );
	$transparent_color_h_site_title = astra_get_option( 'transparent-header-color-h-site-title-responsive' );
	$transparent_menu_bg_color      = astra_get_option( 'transparent-menu-bg-color-responsive' );
	$transparent_menu_color         = astra_get_option( 'transparent-menu-color-responsive' );
	$transparent_menu_h_color       = astra_get_option( 'transparent-menu-h-color-responsive' );
	$transparent_sub_menu_color     = astra_get_option( 'transparent-submenu-color-responsive' );
	$transparent_sub_menu_h_color   = astra_get_option( 'transparent-submenu-h-color-responsive' );
	$transparent_sub_menu_bg_color  = astra_get_option( 'transparent-submenu-bg-color-responsive' );

	$transparent_content_section_text_color   = astra_get_option( 'transparent-content-section-text-color-responsive' );
	$transparent_content_section_link_color   = astra_get_option( 'transparent-content-section-link-color-responsive' );
	$transparent_content_section_link_h_color = astra_get_option( 'transparent-content-section-link-h-color-responsive' );
	/**
	 * Generate Dynamic CSS
	 */

	$css = '';
	/**
	 * Transparent Header Colors
	 */
	$transparent_header_desktop = array(
		'.ast-theme-transparent-header.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation-wrap, .ast-theme-transparent-header .ast-below-header-actual-nav, .ast-theme-transparent-header.ast-header-break-point .ast-below-header-actual-nav' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-below-header .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-below-header .slide-search .search-field' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),
		'.ast-theme-transparent-header .ast-below-header .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $transparent_bg_color['desktop'] ),
		),
		/**
		 * Below Header Navigation
		 */

		'.ast-theme-transparent-header .ast-below-header-menu, .ast-theme-transparent-header .ast-below-header-menu a' => array(
			'color' => esc_attr( $transparent_menu_color['desktop'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu li:hover > a, .ast-theme-transparent-header .ast-below-header-menu li:focus > a, .ast-theme-transparent-header .ast-below-header-menu li.focus > a' => array(
			'color' => esc_attr( $transparent_menu_h_color['desktop'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_menu_h_color['desktop'] ),
		),

		/**
		 * Below Header Dropdown Navigation
		 */

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu li:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.focus > a' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['desktop'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['desktop'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu'               => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color['desktop'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu, .ast-theme-transparent-header .ast-below-header-menu .sub-menu a' => array(
			'color' => esc_attr( $transparent_sub_menu_color['desktop'] ),
		),

		/**
		 * Content Colors & Typography
		 */
		'.ast-theme-transparent-header .below-header-user-select, .ast-theme-transparent-header .below-header-user-select .widget,.ast-theme-transparent-header .below-header-user-select .widget-title' => array(
			'color' => esc_attr( $transparent_content_section_text_color['desktop'] ),
		),

		'.ast-theme-transparent-header .below-header-user-select a, .ast-theme-transparent-header .below-header-user-select .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color['desktop'] ),
		),

		'.ast-theme-transparent-header .below-header-user-select a:hover, .ast-theme-transparent-header .below-header-user-select .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color['desktop'] ),
		),
	);

	$transparent_header_tablet = array(

		'.ast-theme-transparent-header.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation-wrap, .ast-theme-transparent-header .ast-below-header-actual-nav, .ast-theme-transparent-header.ast-header-break-point .ast-below-header-actual-nav' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-below-header .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-below-header .slide-search .search-field' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),
		'.ast-theme-transparent-header .ast-below-header .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $transparent_bg_color['tablet'] ),
		),
		/**
		 * Below Header Navigation
		 */

		'.ast-theme-transparent-header .ast-below-header-menu, .ast-theme-transparent-header .ast-below-header-menu a' => array(
			'color' => esc_attr( $transparent_menu_color['tablet'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu li:hover > a, .ast-theme-transparent-header .ast-below-header-menu li:focus > a, .ast-theme-transparent-header .ast-below-header-menu li.focus > a' => array(
			'color' => esc_attr( $transparent_menu_h_color['tablet'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_menu_h_color['tablet'] ),
		),

		/**
		 * Below Header Dropdown Navigation
		 */

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu li:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.focus > a' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['tablet'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['tablet'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu'               => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color['tablet'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu, .ast-theme-transparent-header .ast-below-header-menu .sub-menu a' => array(
			'color' => esc_attr( $transparent_sub_menu_color['tablet'] ),
		),

		/**
		 * Content Colors & Typography
		 */
		'.ast-theme-transparent-header .below-header-user-select, .ast-theme-transparent-header .below-header-user-select .widget,.ast-theme-transparent-header .below-header-user-select .widget-title' => array(
			'color' => esc_attr( $transparent_content_section_text_color['tablet'] ),
		),

		'.ast-theme-transparent-header .below-header-user-select a, .ast-theme-transparent-header .below-header-user-select .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color['tablet'] ),
		),

		'.ast-theme-transparent-header .below-header-user-select a:hover, .ast-theme-transparent-header .below-header-user-select .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color['tablet'] ),
		),
	);

	$transparent_header_mobile = array(

		'.ast-theme-transparent-header.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation-wrap, .ast-theme-transparent-header .ast-below-header-actual-nav, .ast-theme-transparent-header.ast-header-break-point .ast-below-header-actual-nav' => array(
			'background-color' => esc_attr( $transparent_menu_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-below-header .ast-search-menu-icon form' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-below-header .slide-search .search-field' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),
		'.ast-theme-transparent-header .ast-below-header .slide-search .search-field:focus' => array(
			'background-color' => esc_attr( $transparent_bg_color['mobile'] ),
		),
		/**
		 * Below Header Navigation
		 */

		'.ast-theme-transparent-header .ast-below-header-menu, .ast-theme-transparent-header .ast-below-header-menu a' => array(
			'color' => esc_attr( $transparent_menu_color['mobile'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu li:hover > a, .ast-theme-transparent-header .ast-below-header-menu li:focus > a, .ast-theme-transparent-header .ast-below-header-menu li.focus > a' => array(
			'color' => esc_attr( $transparent_menu_h_color['mobile'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' => array(
			'color' => esc_attr( $transparent_menu_h_color['mobile'] ),
		),

		/**
		 * Below Header Dropdown Navigation
		 */

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu li:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.focus > a' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['mobile'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > a' => array(
			'color' => esc_attr( $transparent_sub_menu_h_color['mobile'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu'               => array(
			'background-color' => esc_attr( $transparent_sub_menu_bg_color['mobile'] ),
		),

		'.ast-theme-transparent-header .ast-below-header-menu .sub-menu, .ast-theme-transparent-header .ast-below-header-menu .sub-menu a' => array(
			'color' => esc_attr( $transparent_sub_menu_color['mobile'] ),
		),

		/**
		 * Content Colors & Typography
		 */
		'.ast-theme-transparent-header .below-header-user-select, .ast-theme-transparent-header .below-header-user-select .widget,.ast-theme-transparent-header .below-header-user-select .widget-title' => array(
			'color' => esc_attr( $transparent_content_section_text_color['mobile'] ),
		),

		'.ast-theme-transparent-header .below-header-user-select a, .ast-theme-transparent-header .below-header-user-select .widget a' => array(
			'color' => esc_attr( $transparent_content_section_link_color['mobile'] ),
		),

		'.ast-theme-transparent-header .below-header-user-select a:hover, .ast-theme-transparent-header .below-header-user-select .widget a:hover' => array(
			'color' => esc_attr( $transparent_content_section_link_h_color['mobile'] ),
		),
	);

	/* Parse CSS from array() */
	$css .= astra_parse_css( $transparent_header_desktop );
	$css .= astra_parse_css( $transparent_header_tablet, '', '768' );
	$css .= astra_parse_css( $transparent_header_mobile, '', '544' );

	return $dynamic_css . $css;
}
