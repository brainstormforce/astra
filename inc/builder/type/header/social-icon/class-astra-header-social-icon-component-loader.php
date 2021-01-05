<?php
/**
 * Social Icon Styling Loader for Astra theme.
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
 * @since 3.0.0s
 */
class Astra_Header_Social_Icon_Component_Loader {

	/**
	 * Constructor
	 *
	 * @since 3.0.0s
	 */
	public function __construct() {
		add_action( 'customize_preview_init', array( $this, 'preview_scripts' ), 110 );
	}

	/**
	 * Customizer Preview
	 *
	 * @since 3.0.0s
	 */
	public function preview_scripts() {
		/**
		 * Load unminified if SCRIPT_DEBUG is true.
		 */
		/* Directory and Extension */
		$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';
		$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_script( 'astra-heading-social-icon-customizer-preview-js', ASTRA_HEADER_SOCIAL_ICON_URI . '/assets/js/customizer-preview.js', array( 'customize-preview', 'astra-customizer-preview-js' ), ASTRA_THEME_VERSION, true );

		// Localize variables for Astra Breakpoints JS.
		wp_localize_script(
			'astra-heading-social-icon-customizer-preview-js',
			'astraBuilderHeaderSocial',
			array(
				'tablet_break_point'  => astra_get_tablet_breakpoint(),
				'mobile_break_point'  => astra_get_mobile_breakpoint(),
				'footer_social_count' => Astra_Builder_Helper::$num_of_footer_social_icons,
				'header_social_count' => Astra_Builder_Helper::$num_of_header_social_icons,
			)
		);
	}
}

/**
*  Kicking this off by creating the object of the class.
*/
new Astra_Header_Social_Icon_Component_Loader();
