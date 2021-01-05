<?php
/**
 * Menu Styling Loader for Astra theme.
 *
 * @package     astra-builder
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Initialization
 *
 * @since 3.0.0
 */
class Astra_Header_Menu_Component_Loader {

	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {
		add_action( 'customize_preview_init', array( $this, 'preview_scripts' ), 110 );
		// Load Google fonts.
		add_action( 'astra_get_fonts', array( $this, 'add_fonts' ), 1 );
	}

	/**
	 * Enqueue google fonts.
	 *
	 * @since 3.0.0
	 */
	public function add_fonts() {

		for ( $index = 1; $index <= Astra_Builder_Helper::$num_of_header_menu; $index++ ) {

			$_prefix = 'menu' . $index;

			$menu_font_family = astra_get_option( 'header-' . $_prefix . '-font-family' );
			$menu_font_weight = astra_get_option( 'header-' . $_prefix . '-font-weight' );

			Astra_Fonts::add_font( $menu_font_family, $menu_font_weight );
		}

		$mobile_menu_font_family = astra_get_option( 'header-mobile-menu-font-family' );
		$mobile_menu_font_weight = astra_get_option( 'header-mobile-menu-font-weight' );

		Astra_Fonts::add_font( $mobile_menu_font_family, $mobile_menu_font_weight );
	}

	/**
	 * Customizer Preview
	 *
	 * @since 3.0.0
	 */
	public function preview_scripts() {
		/**
		 * Load unminified if SCRIPT_DEBUG is true.
		 */
		/* Directory and Extension */
		$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';
		$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_script( 'astra-heading-menu-customizer-preview-js', ASTRA_HEADER_MENU_URI . '/assets/js/customizer-preview.js', array( 'customize-preview', 'astra-customizer-preview-js' ), ASTRA_THEME_VERSION, true );

		// Localize variables for Menu JS.
		wp_localize_script(
			'astra-heading-menu-customizer-preview-js',
			'AstraBuilderMenuData',
			array(
				'header_menu_count'  => Astra_Builder_Helper::$num_of_header_menu,
				'tablet_break_point' => astra_get_tablet_breakpoint(),
				'mobile_break_point' => astra_get_mobile_breakpoint(),
			)
		);
	}
}

/**
*  Kicking this off by creating the object of the class.
*/
new Astra_Header_Menu_Component_Loader();
