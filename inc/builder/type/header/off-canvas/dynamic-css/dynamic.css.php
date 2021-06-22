<?php
/**
 * Off Canvas - Dynamic CSS
 *
 * @package astra-builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Off Canvas Row.
 */
add_filter( 'astra_dynamic_theme_css', 'astra_off_canvas_row_setting', 11 );

/**
 * Off Canvas Row - Dynamic CSS
 *
 * @param  string $dynamic_css          Astra Dynamic CSS.
 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function astra_off_canvas_row_setting( $dynamic_css, $dynamic_css_filtered = '' ) {

	$selector = '.ast-mobile-popup-drawer.active';

	$off_canvas_background       = astra_get_option( 'off-canvas-background' );
	$off_canvas_close_color      = astra_get_option( 'off-canvas-close-color' );
	$offcanvas_content_alignment = astra_get_option( 'header-offcanvas-content-alignment', 'flex-start' );
	$padding                     = astra_get_option( 'off-canvas-padding' );
	$menu_content_alignment      = 'center';
	$inner_spacing               = astra_get_option( 'off-canvas-inner-spacing' );
	$mobile_header_type          = astra_get_option( 'mobile-header-type' );
	$is_site_rtl                 = is_rtl();

	$inner_spacing = ( isset( $inner_spacing ) ) ? (int) $inner_spacing : '';

	if ( 'flex-start' === $offcanvas_content_alignment ) {
		$menu_content_alignment = $is_site_rtl ? 'right' : 'left';
	} elseif ( 'flex-end' === $offcanvas_content_alignment ) {
		$menu_content_alignment = $is_site_rtl ? 'left' : 'right';
	}

	if ( 'off-canvas' === $mobile_header_type || 'full-width' === $mobile_header_type || is_customize_preview() ) {
		$dynamic_css .= astra_off_canvas_static_css();
	}
	if ( 'dropdown' === $mobile_header_type || is_customize_preview() ) {
		$dynamic_css .= astra_dropdown_type_static_css();
	}

	/**
	 * Off-Canvas CSS.
	 */
	$css_output = array(

		$selector . ' .ast-mobile-popup-inner' => astra_get_background_obj( $off_canvas_background ),

		'.ast-mobile-header-wrap .ast-mobile-header-content, .ast-desktop-header-content' => astra_get_background_obj( $off_canvas_background ),
		'.ast-mobile-popup-drawer.active .ast-desktop-popup-content, .ast-mobile-popup-drawer.active .ast-mobile-popup-content' => array(
			// Padding CSS.
			'padding-top'    => astra_responsive_spacing( $padding, 'top', 'desktop' ),
			'padding-bottom' => astra_responsive_spacing( $padding, 'bottom', 'desktop' ),
			'padding-left'   => astra_responsive_spacing( $padding, 'left', 'desktop' ),
			'padding-right'  => astra_responsive_spacing( $padding, 'right', 'desktop' ),
		),
		'.ast-mobile-popup-content > *, .ast-mobile-header-content > *, .ast-desktop-popup-content > *, .ast-desktop-header-content > *' => array(
			'padding-top'    => astra_get_css_value( $inner_spacing, 'px' ),
			'padding-bottom' => astra_get_css_value( $inner_spacing, 'px' ),
		),
		'.content-align-' . esc_attr( $offcanvas_content_alignment ) . ' .ast-builder-layout-element' => array(
			'justify-content' => esc_attr( $offcanvas_content_alignment ),
		),
		'.content-align-' . esc_attr( $offcanvas_content_alignment ) . ' .main-header-menu' => array(
			'text-align' => esc_attr( $menu_content_alignment ),
		),
	);

	$css_output[ $selector . ' .menu-toggle-close' ]['color'] = $off_canvas_close_color;

	/* Parse CSS from array() */
	$css_output = astra_parse_css( $css_output );

	// Tablet CSS.
	$css_output_tablet = array(
		'.ast-mobile-popup-drawer.active .ast-desktop-popup-content, .ast-mobile-popup-drawer.active .ast-mobile-popup-content' => array(
			// Padding CSS.
			'padding-top'    => astra_responsive_spacing( $padding, 'top', 'tablet' ),
			'padding-bottom' => astra_responsive_spacing( $padding, 'bottom', 'tablet' ),
			'padding-left'   => astra_responsive_spacing( $padding, 'left', 'tablet' ),
			'padding-right'  => astra_responsive_spacing( $padding, 'right', 'tablet' ),
		),
	);

	$css_output_mobile = array(

		'.ast-mobile-popup-drawer.active .ast-desktop-popup-content, .ast-mobile-popup-drawer.active .ast-mobile-popup-content' => array(
			// Padding CSS.
			'padding-top'    => astra_responsive_spacing( $padding, 'top', 'mobile' ),
			'padding-bottom' => astra_responsive_spacing( $padding, 'bottom', 'mobile' ),
			'padding-left'   => astra_responsive_spacing( $padding, 'left', 'mobile' ),
			'padding-right'  => astra_responsive_spacing( $padding, 'right', 'mobile' ),
		),
	);

	$css_output .= astra_parse_css( $css_output_tablet, '', astra_get_tablet_breakpoint() );
	$css_output .= astra_parse_css( $css_output_mobile, '', astra_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	return $dynamic_css;
}

/**
 * Add static CSS for Off-canvas flyout.
 *
 * @since 3.4.0
 * @return string.
 */
function astra_off_canvas_static_css() {
	$off_canvas_css = '
	.ast-off-canvas-active body.ast-main-header-nav-open {
		overflow: hidden;
	}
	.ast-mobile-popup-drawer .ast-mobile-popup-overlay {
		background-color: rgba(0, 0, 0, 0.4);
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		visibility: hidden;
		opacity: 0;
		transition: opacity 0.2s ease-in-out;
	}
	.ast-mobile-popup-drawer .ast-mobile-popup-header {
		-js-display: flex;
		display: flex;
		justify-content: flex-end;
		min-height: calc( 1.2em + 24px);
	}
	.ast-mobile-popup-drawer .ast-mobile-popup-header .menu-toggle-close {
		background: transparent;
		border: 0;
		font-size: 24px;
		line-height: 1;
		padding: .6em;
		color: inherit;
		-js-display: flex;
		display: flex;
		box-shadow: none;
	}
	.ast-mobile-popup-drawer.ast-mobile-popup-full-width .ast-mobile-popup-inner {
		max-width: none;
		transition: transform 0s ease-in, opacity 0.2s ease-in;
	}
	.ast-mobile-popup-drawer.active {
		left: 0;
		opacity: 1;
		right: 0;
		z-index: 100000;
		transition: opacity 0.25s ease-out;
	}
	.ast-mobile-popup-drawer.active .ast-mobile-popup-overlay {
		opacity: 1;
		cursor: pointer;
		visibility: visible;
	}
	body.admin-bar .ast-mobile-popup-drawer, body.admin-bar .ast-mobile-popup-drawer .ast-mobile-popup-inner {
		top: 32px;
	}
	body.admin-bar.ast-primary-sticky-header-active .ast-mobile-popup-drawer, body.admin-bar.ast-primary-sticky-header-active .ast-mobile-popup-drawer .ast-mobile-popup-inner  {
		top: 0px;
	}
	@media (max-width: 782px) {
		body.admin-bar .ast-mobile-popup-drawer,body.admin-bar .ast-mobile-popup-drawer .ast-mobile-popup-inner {
		  top: 46px;
		}
	}
	.ast-mobile-popup-content > *,
	.ast-desktop-popup-content > *{
	  padding: 10px 0;
	  height: auto;
	}

	.ast-mobile-popup-content > *:first-child,
	.ast-desktop-popup-content > *:first-child{
	  padding-top: 10px;
	}

	.ast-mobile-popup-content > .ast-builder-menu,
	.ast-desktop-popup-content > .ast-builder-menu{
	  padding-top: 0;
	}
	.ast-mobile-popup-content > *:last-child,
	.ast-desktop-popup-content > *:last-child {
	  padding-bottom: 0;
	}
	.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-icon,
	.ast-mobile-popup-drawer .main-header-bar-navigation .menu-item-has-children .sub-menu,
	.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-icon {
	  display: none;
	}

	.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon.ast-inline-search label,
	.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon.ast-inline-search label {
	  width: 100%;
	}
	.ast-mobile-popup-content .ast-builder-menu-mobile .main-header-menu, .ast-mobile-popup-content .ast-builder-menu-mobile .main-header-menu .sub-menu {
		background-color: transparent;
	}
	.ast-mobile-popup-content .ast-icon svg {
		height: .85em;
		width: .95em;
		margin-top: 15px;
	}
	.ast-mobile-popup-content .ast-icon.icon-search svg {
		margin-top: 0;
	}
	.ast-desktop .ast-desktop-popup-content .astra-menu-animation-slide-up > .menu-item > .sub-menu,
	.ast-desktop .ast-desktop-popup-content .astra-menu-animation-slide-up > .menu-item .menu-item > .sub-menu,
	.ast-desktop .ast-desktop-popup-content .astra-menu-animation-slide-down > .menu-item > .sub-menu,
	.ast-desktop .ast-desktop-popup-content .astra-menu-animation-slide-down > .menu-item .menu-item > .sub-menu,
	.ast-desktop .ast-desktop-popup-content .astra-menu-animation-fade > .menu-item > .sub-menu,
	.ast-mobile-popup-drawer.show,
	.ast-desktop .ast-desktop-popup-content .astra-menu-animation-fade > .menu-item .menu-item > .sub-menu{
	  opacity: 1;
	  visibility: visible;
	}';

	if ( is_rtl() ) {
		$off_canvas_css .= '
		.ast-mobile-popup-drawer {
			position: fixed;
			top: 0;
			bottom: 0;
			right: -99999rem;
			left: 99999rem;
			transition: opacity 0.25s ease-in, right 0s 0.25s, left 0s 0.25s;
			opacity: 0;
		}
		.ast-mobile-popup-drawer .ast-mobile-popup-inner {
			width: 100%;
			transform: translateX(100%);
			max-width: 90%;
			left: 0;
			top: 0;
			background: #fafafa;
			color: #3a3a3a;
			bottom: 0;
			opacity: 0;
			position: fixed;
			box-shadow: 0 0 2rem 0 rgba(0, 0, 0, 0.1);
			-js-display: flex;
			display: flex;
			flex-direction: column;
			transition: transform 0.2s ease-in, opacity 0.2s ease-in;
			overflow-y:auto;
			overflow-x:hidden;
		}
		.ast-mobile-popup-drawer.ast-mobile-popup-left .ast-mobile-popup-inner {
			transform: translateX(-100%);
			left: auto;
			right: 0;
		}
		.ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-popup-drawer .main-header-bar-navigation ul .menu-item .sub-menu .menu-link {
			padding-right: 30px;
		}
		.ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-popup-drawer .main-header-bar-navigation .sub-menu .menu-item .menu-item .menu-link {
			padding-right: 40px;
		}
		.ast-mobile-popup-drawer .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle {
			left: calc( 20px - 0.907em);
		}
		.ast-mobile-popup-drawer.content-align-flex-end .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle {
			right: calc( 20px - 0.907em);
		}
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon,
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon.slide-search,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon.slide-search {
			width: 100%;
			position: relative;
			display: block;
			left: auto;
			transform: none;
		}
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon.slide-search .search-form,
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon .search-form,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon.slide-search .search-form,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon .search-form {
			left: 0;
			visibility: visible;
			opacity: 1;
			position: relative;
			top: auto;
			transform: none;
			padding: 0;
			display: block;
			overflow: hidden;
		}

		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon.ast-inline-search .search-field,
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon .search-field,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon.ast-inline-search .search-field,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon .search-field {
			width: 100%;
			padding-left: 5.5em;
		}
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon .search-submit,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon .search-submit {
			display: block;
			position: absolute;
			height: 100%;
			top: 0;
			left: 0;
			padding: 0 1em;
			border-radius: 0;
		}';
	} else {
		$off_canvas_css .= '
		.ast-mobile-popup-drawer {
			position: fixed;
			top: 0;
			bottom: 0;
			left: -99999rem;
			right: 99999rem;
			transition: opacity 0.25s ease-in, left 0s 0.25s, right 0s 0.25s;
			opacity: 0;
		}
		.ast-mobile-popup-drawer .ast-mobile-popup-inner {
			width: 100%;
			transform: translateX(100%);
			max-width: 90%;
			right: 0;
			top: 0;
			background: #fafafa;
			color: #3a3a3a;
			bottom: 0;
			opacity: 0;
			position: fixed;
			box-shadow: 0 0 2rem 0 rgba(0, 0, 0, 0.1);
			-js-display: flex;
			display: flex;
			flex-direction: column;
			transition: transform 0.2s ease-in, opacity 0.2s ease-in;
			overflow-y:auto;
			overflow-x:hidden;
		}
		.ast-mobile-popup-drawer.ast-mobile-popup-left .ast-mobile-popup-inner {
			transform: translateX(-100%);
			right: auto;
			left: 0;
		}
		.ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-popup-drawer .main-header-bar-navigation ul .menu-item .sub-menu .menu-link {
			padding-left: 30px;
		}
		.ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-popup-drawer .main-header-bar-navigation .sub-menu .menu-item .menu-item .menu-link {
			padding-left: 40px;
		}
		.ast-mobile-popup-drawer .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle {
			right: calc( 20px - 0.907em);
		}
		.ast-mobile-popup-drawer.content-align-flex-end .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle {
			left: calc( 20px - 0.907em);
		}
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon,
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon.slide-search,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon.slide-search {
			width: 100%;
			position: relative;
			display: block;
			right: auto;
			transform: none;
		}
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon.slide-search .search-form,
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon .search-form,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon.slide-search .search-form,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon .search-form {
			right: 0;
			visibility: visible;
			opacity: 1;
			position: relative;
			top: auto;
			transform: none;
			padding: 0;
			display: block;
			overflow: hidden;
		}

		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon.ast-inline-search .search-field,
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon .search-field,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon.ast-inline-search .search-field,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon .search-field {
			width: 100%;
			padding-right: 5.5em;
		}
		.ast-mobile-popup-drawer .ast-mobile-popup-content .ast-search-menu-icon .search-submit,
		.ast-mobile-popup-drawer .ast-desktop-popup-content .ast-search-menu-icon .search-submit {
			display: block;
			position: absolute;
			height: 100%;
			top: 0;
			right: 0;
			padding: 0 1em;
			border-radius: 0;
		}';
	}

	// Adding this CSS to bottom because it needs to be load after above style loads. As it required to hide/show flyout offcanvas.
	$off_canvas_css .= '
	.ast-mobile-popup-drawer.active .ast-mobile-popup-inner {
		opacity: 1;
		visibility: visible;
		transform: translateX(0%);
	}';

	return Astra_Enqueue_Scripts::trim_css( $off_canvas_css );
}

/**
 * Add static CSS for Dropdown Type.
 *
 * @since 3.4.0
 * @return string.
 */
function astra_dropdown_type_static_css() {
	$dropdown_type_css = '
	.ast-mobile-header-content > *,
	.ast-desktop-header-content > * {
	  padding: 10px 0;
	  height: auto;
	}
	.ast-mobile-header-content > *:first-child,
	.ast-desktop-header-content > *:first-child {
	  padding-top: 10px;
	}
	.ast-mobile-header-content > .ast-builder-menu,
	.ast-desktop-header-content > .ast-builder-menu {
	  padding-top: 0;
	}

	.ast-mobile-header-content > *:last-child,
	.ast-desktop-header-content > *:last-child {
	  padding-bottom: 0;
	}
	.ast-mobile-header-content .ast-search-menu-icon.ast-inline-search label,
	.ast-desktop-header-content .ast-search-menu-icon.ast-inline-search label {
	  width: 100%;
	}
	.ast-desktop-header-content .main-header-bar-navigation .ast-submenu-expanded > .ast-menu-toggle::before {
	  transform: rotateX(180deg);
	}
	#ast-desktop-header .ast-desktop-header-content,
	.ast-mobile-header-content .ast-search-icon,
	.ast-desktop-header-content .ast-search-icon,
	.ast-mobile-header-wrap .ast-mobile-header-content,
	.ast-main-header-nav-open.ast-popup-nav-open .ast-mobile-header-wrap .ast-mobile-header-content,
	.ast-main-header-nav-open.ast-popup-nav-open .ast-desktop-header-content {
	  display: none;
	}
	.ast-main-header-nav-open.ast-header-break-point #ast-desktop-header .ast-desktop-header-content,
	.ast-main-header-nav-open.ast-header-break-point .ast-mobile-header-wrap .ast-mobile-header-content {
	  display: block;
	}
	.ast-desktop .ast-desktop-header-content .astra-menu-animation-slide-up > .menu-item > .sub-menu,
	.ast-desktop .ast-desktop-header-content .astra-menu-animation-slide-up > .menu-item .menu-item > .sub-menu,
	.ast-desktop .ast-desktop-header-content .astra-menu-animation-slide-down > .menu-item > .sub-menu,
	.ast-desktop .ast-desktop-header-content .astra-menu-animation-slide-down > .menu-item .menu-item > .sub-menu,
	.ast-desktop .ast-desktop-header-content .astra-menu-animation-fade > .menu-item > .sub-menu,
	.ast-desktop .ast-desktop-header-content .astra-menu-animation-fade > .menu-item .menu-item > .sub-menu {
	  opacity: 1;
	  visibility: visible;
	}
	.ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-header-wrap .ast-mobile-header-content .main-header-bar-navigation {
		width: unset;
		margin: unset;
	}';

	if ( is_rtl() ) {
		$dropdown_type_css .= '
		.ast-mobile-header-content.content-align-flex-end .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle,
		.ast-desktop-header-content.content-align-flex-end .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle {
		  	right: calc( 20px - 0.907em);
		}
		.ast-mobile-header-content .ast-search-menu-icon,
		.ast-mobile-header-content .ast-search-menu-icon.slide-search,
		.ast-desktop-header-content .ast-search-menu-icon,
		.ast-desktop-header-content .ast-search-menu-icon.slide-search {
			width: 100%;
			position: relative;
			display: block;
			left: auto;
			transform: none;
		}
		.ast-mobile-header-content .ast-search-menu-icon.slide-search .search-form,
		.ast-mobile-header-content .ast-search-menu-icon .search-form,
		.ast-desktop-header-content .ast-search-menu-icon.slide-search .search-form,
		.ast-desktop-header-content .ast-search-menu-icon .search-form {
			left: 0;
			visibility: visible;
			opacity: 1;
			position: relative;
			top: auto;
			transform: none;
			padding: 0;
			display: block;
			overflow: hidden;
		}
		.ast-mobile-header-content .ast-search-menu-icon.ast-inline-search .search-field,
		.ast-mobile-header-content .ast-search-menu-icon .search-field,
		.ast-desktop-header-content .ast-search-menu-icon.ast-inline-search .search-field,
		.ast-desktop-header-content .ast-search-menu-icon .search-field {
			width: 100%;
			padding-left: 5.5em;
		}
		.ast-mobile-header-content .ast-search-menu-icon .search-submit,
		.ast-desktop-header-content .ast-search-menu-icon .search-submit {
			display: block;
			position: absolute;
			height: 100%;
			top: 0;
			left: 0;
			padding: 0 1em;
			border-radius: 0;
		}
		.ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-header-wrap .ast-mobile-header-content .main-header-bar-navigation ul .sub-menu .menu-link {
			padding-right: 30px;
		}
		.ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-header-wrap .ast-mobile-header-content .main-header-bar-navigation .sub-menu .menu-item .menu-item .menu-link {
			padding-right: 40px;
		}';
	} else {
		$dropdown_type_css .= '
		.ast-mobile-header-content.content-align-flex-end .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle,
		.ast-desktop-header-content.content-align-flex-end .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle {
		  	left: calc( 20px - 0.907em);
		}
		.ast-mobile-header-content .ast-search-menu-icon,
		.ast-mobile-header-content .ast-search-menu-icon.slide-search,
		.ast-desktop-header-content .ast-search-menu-icon,
		.ast-desktop-header-content .ast-search-menu-icon.slide-search {
			width: 100%;
			position: relative;
			display: block;
			right: auto;
			transform: none;
		}
		.ast-mobile-header-content .ast-search-menu-icon.slide-search .search-form,
		.ast-mobile-header-content .ast-search-menu-icon .search-form,
		.ast-desktop-header-content .ast-search-menu-icon.slide-search .search-form,
		.ast-desktop-header-content .ast-search-menu-icon .search-form {
			right: 0;
			visibility: visible;
			opacity: 1;
			position: relative;
			top: auto;
			transform: none;
			padding: 0;
			display: block;
			overflow: hidden;
		}
		.ast-mobile-header-content .ast-search-menu-icon.ast-inline-search .search-field,
		.ast-mobile-header-content .ast-search-menu-icon .search-field,
		.ast-desktop-header-content .ast-search-menu-icon.ast-inline-search .search-field,
		.ast-desktop-header-content .ast-search-menu-icon .search-field {
			width: 100%;
			padding-right: 5.5em;
		}
		.ast-mobile-header-content .ast-search-menu-icon .search-submit,
		.ast-desktop-header-content .ast-search-menu-icon .search-submit {
			display: block;
			position: absolute;
			height: 100%;
			top: 0;
			right: 0;
			padding: 0 1em;
			border-radius: 0;
		}
		.ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-header-wrap .ast-mobile-header-content .main-header-bar-navigation ul .sub-menu .menu-link {
			padding-left: 30px;
		}
		.ast-hfb-header.ast-default-menu-enable.ast-header-break-point .ast-mobile-header-wrap .ast-mobile-header-content .main-header-bar-navigation .sub-menu .menu-item .menu-item .menu-link {
			padding-left: 40px;
		}';
	}
	return Astra_Enqueue_Scripts::trim_css( $dropdown_type_css );
}
