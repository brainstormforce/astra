<?php
/**
 * Footer Navigation Menu component.
 *
 * @package     Astra Builder
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ASTRA_BUILDER_FOOTER_MENU_DIR', ASTRA_THEME_DIR . 'inc/builder/type/footer/menu' );
define( 'ASTRA_BUILDER_FOOTER_MENU_URI', ASTRA_THEME_URI . 'inc/builder/type/footer/menu' );

/**
 * Footer Navigation Menu Initial Setup
 *
 * @since 3.0.0
 */
class Astra_Footer_Menu_Component {

	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once ASTRA_BUILDER_FOOTER_MENU_DIR . '/class-astra-footer-menu-component-loader.php';

		// Include front end files.
		if ( ! is_admin() ) {
			require_once ASTRA_BUILDER_FOOTER_MENU_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Secondary navigation markup
	 *
	 * @since 3.0.0.
	 */
	public static function menu_markup() {

		// Menu Layout.
		$desktop_menu_layout_class = '';
		$tablet_menu_layout_class  = '';
		$mobile_menu_layout_class  = '';
		$menu_layout               = astra_get_option( 'footer-menu-layout' );

		$desktop_menu_layout = ( isset( $menu_layout['desktop'] ) ) ? $menu_layout['desktop'] : '';
		$tablet_menu_layout  = ( isset( $menu_layout['tablet'] ) ) ? $menu_layout['tablet'] : '';
		$mobile_menu_layout  = ( isset( $menu_layout['mobile'] ) ) ? $menu_layout['mobile'] : '';

		if ( ! empty( $desktop_menu_layout ) ) {
			$desktop_menu_layout_class = 'astra-footer-' . esc_attr( $desktop_menu_layout ) . '-menu';
		}

		if ( ! empty( $tablet_menu_layout ) ) {
			$tablet_menu_layout_class = 'astra-footer-tablet-' . esc_attr( $tablet_menu_layout ) . '-menu';
		}

		if ( ! empty( $mobile_menu_layout ) ) {
			$mobile_menu_layout_class = 'astra-footer-mobile-' . esc_attr( $mobile_menu_layout ) . '-menu';
		}

		/**
		 * Filter the classes(array) for Menu (<ul>).
		 *
		 * @since  3.0.0
		 * @var Array
		 */
		$menu_classes = apply_filters( 'astra_menu_classes', array( 'main-header-menu', 'ast-nav-menu', 'ast-flex', $desktop_menu_layout_class, $tablet_menu_layout_class, $mobile_menu_layout_class ) );

		$items_wrap  = '<nav ';
		$items_wrap .= astra_attr(
			'site-navigation',
			array(
				'id'         => 'site-navigation',
				'class'      => 'ast-flex-grow-1 navigation-accessibility footer-navigation',
				'aria-label' => esc_attr__( 'Site Navigation', 'astra' ),
			)
		);
		$items_wrap .= '>';
		$items_wrap .= '<div class="footer-nav-wrap">';
		$items_wrap .= '<ul id="%1$s" class="%2$s">%3$s</ul>';
		$items_wrap .= '</div>';
		$items_wrap .= '</nav>';

		// To add default alignment for navigation which can be added through any third party plugin.
		// Do not add any CSS from theme except header alignment.
		if ( has_nav_menu( 'footer_menu' ) ) {
			wp_nav_menu(
				array(
					'depth'           => 1,
					'menu_id'         => 'astra-footer-menu',
					'menu_class'      => esc_attr( implode( ' ', $menu_classes ) ),
					'container'       => 'div',
					'container_class' => 'footer-bar-navigation',
					'items_wrap'      => $items_wrap,
					'theme_location'  => 'footer_menu',
				) 
			);
		}
	}
}

/**
 *  Kicking this off by creating an object.
 */
new Astra_Footer_Menu_Component();
