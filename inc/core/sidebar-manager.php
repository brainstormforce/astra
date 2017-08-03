<?php
/**
 * Sidebar Manager functions
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

/**
 * Site Sidebar
 */
if ( ! function_exists( 'astra_page_layout' ) ) {

	/**
	 * Site Sidebar
	 *
	 * Default 'right sidebar' for overall site.
	 */
	function astra_page_layout() {

		if ( is_singular() ) {

			// If post meta value is empty,
			// Then get the POST_TYPE sidebar.
			$layout = astra_get_option_meta( 'site-sidebar-layout', '', true );

			if ( empty( $layout ) ) {

				$layout = astra_get_option( 'single-' . get_post_type() . '-sidebar-layout' );

				if ( 'default' == $layout || empty( $layout ) ) {

					// Get the global sidebar value.
					// NOTE: Here not used `true` in the below function call.
					$layout = astra_get_option( 'site-sidebar-layout' );
				}
			}
		} else {

			if ( is_search() ) {

				// Check only post type archive option value.
				$layout = astra_get_option( 'archive-post-sidebar-layout' );

				if ( 'default' == $layout || empty( $layout ) ) {

					// Get the global sidebar value.
					// NOTE: Here not used `true` in the below function call.
					$layout = astra_get_option( 'site-sidebar-layout' );
				}
			} else {

				$layout = astra_get_option( 'archive-' . get_post_type() . '-sidebar-layout' );

				if ( 'default' == $layout || empty( $layout ) ) {

					// Get the global sidebar value.
					// NOTE: Here not used `true` in the below function call.
					$layout = astra_get_option( 'site-sidebar-layout' );
				}// End if().
			}
		}// End if().

		return apply_filters( 'astra_page_layout', $layout );
	}
}// End if().
