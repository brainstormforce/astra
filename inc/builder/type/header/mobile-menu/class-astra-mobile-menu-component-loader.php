<?php
/**
 * Mobile Navigation Menu Styling Loader for Astra theme.
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

/**
 * Mobile Navigation Menu Initialization
 *
 * @since 3.0.0
 */
class Astra_Mobile_Menu_Component_Loader {

	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {
		add_action( 'customize_preview_init', array( $this, 'preview_scripts' ), 110 );
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
		wp_enqueue_script( 'astra-mobile-menu-customizer-preview', ASTRA_BUILDER_MOBILE_MENU_URI . '/assets/js/' . $dir_name . '/customizer-preview' . $file_prefix . '.js', array( 'customize-preview', 'astra-customizer-preview-js' ), ASTRA_THEME_VERSION, true );

	}
}

/**
*  Kicking this off by creating the object of the class.
*/
new Astra_Mobile_Menu_Component_Loader();
