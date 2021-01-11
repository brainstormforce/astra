<?php
/**
 * Search Styling Loader for Astra theme.
 *
 * @package     astra-builder
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Initialization
 *
 * @since 3.0.0
 */
class Astra_Header_Search_Component_Loader {

	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {

		add_filter( 'astra_get_search', array( $this, 'get_search_markup' ), 10, 3 );
		add_action( 'customize_preview_init', array( $this, 'preview_scripts' ), 110 );
	}

	/**
	 * Customizer Preview
	 *
	 * @since 3.0.0
	 */
	public function preview_scripts() {
		wp_enqueue_script( 'astra-header-builder-search-customizer-preview-js', ASTRA_HEADER_SEARCH_URI . '/assets/js/customizer-preview.js', array( 'customize-preview', 'astra-customizer-preview-js' ), ASTRA_THEME_VERSION, true );

	}

	/**
	 * Adding Wrapper for Search Form.
	 *
	 * @since 3.0.0
	 *
	 * @param string $search_markup   Search Form Content.
	 * @return Search HTML structure created.
	 */
	public static function get_search_markup( $search_markup ) {

		if ( is_customize_preview() ) {
			Astra_Builder_UI_Controller::render_customizer_edit_button();
		}

		return $search_markup;
	}
}

/**
*  Kicking this off by creating the object of the class.
*/
new Astra_Header_Search_Component_Loader();
