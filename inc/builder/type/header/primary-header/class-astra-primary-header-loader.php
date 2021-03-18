<?php
/**
 * Button Styling Loader for Astra theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Initialization
 *
 * @since 3.0.0
 */
class Astra_Primary_Header_Loader {

	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {
		add_action( 'customize_preview_init', array( $this, 'preview_scripts' ), 110 );

		// Markup.
		remove_filter( 'body_class', 'astra_body_classes' );
		add_filter( 'body_class', array( $this, 'astra_body_header_classes' ) );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0.0
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	public function astra_body_header_classes( $classes ) {

		if ( wp_is_mobile() ) {
			$classes[] = 'ast-header-break-point';
		} else {
			$classes[] = 'ast-desktop';
		}

		if ( astra_is_amp_endpoint() ) {
			$classes[] = 'ast-amp';
		}

		// Apply separate container class to the body.
		$content_layout = astra_get_content_layout();
		if ( 'content-boxed-container' == $content_layout ) {
			$classes[] = 'ast-separate-container';
		} elseif ( 'boxed-container' == $content_layout ) {
			$classes[] = 'ast-separate-container ast-two-container';
		} elseif ( 'page-builder' == $content_layout ) {
			$classes[] = 'ast-page-builder-template';
		} elseif ( 'plain-container' == $content_layout ) {
			$classes[] = 'ast-plain-container';
		}
		// Sidebar location.
		$page_layout = 'ast-' . astra_page_layout();
		$classes[]   = esc_attr( $page_layout );

		// Current Astra verion.
		$classes[] = esc_attr( 'astra-' . ASTRA_THEME_VERSION );

		/**
		 * Add class for header width
		 */
		$header_content_layout = astra_get_option( 'hb-header-main-layout-width' );

		if ( 'full' == $header_content_layout ) {
			$classes[] = 'ast-full-width-primary-header';
		}

		return $classes;
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
		wp_enqueue_script( 'astra-heading-primary-customizer-preview-js', ASTRA_PRIMARY_HEADER_URI . '/assets/js/' . $dir_name . '/customizer-preview' . $file_prefix . '.js', array( 'customize-preview', 'astra-customizer-preview-js' ), ASTRA_THEME_VERSION, true );

		// Localize variables for Button JS.
		wp_localize_script(
			'astra-heading-primary-customizer-preview-js',
			'AstraBuilderPrimaryHeaderData',
			array(
				'header_break_point' => astra_header_break_point(),
				'tablet_break_point' => astra_get_tablet_breakpoint(),
				'mobile_break_point' => astra_get_mobile_breakpoint(),
			)
		);
	}
}

/**
*  Kicking this off by creating the object of the class.
*/
new Astra_Primary_Header_Loader();
