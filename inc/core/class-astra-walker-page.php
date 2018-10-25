<?php
/**
 * Navigation Menu customizations.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        https://wpastra.com/
 * @since       Astra x.x.x
 */

/**
 * Custom wp_nav_menu walker.
 *
 * @package Astra WordPress theme
 */
if ( ! class_exists( 'Astra_Walker_Page' ) ) {

	/**
	 * Astra custom navigation walker.
	 *
	 * @since x.x.x
	 */
	class Astra_Walker_Page extends Walker_Page {

		/**
		 * Outputs the beginning of the current level in the tree before elements are output.
		 *
		 * @since x.x.x
		 *
		 * @see Walker::start_lvl()
		 *
		 * @param string $output Used to append additional content (passed by reference).
		 * @param int    $depth  Optional. Depth of page. Used for padding. Default 0.
		 * @param array  $args   Optional. Arguments for outputting the next level.
		 *                       Default empty array.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
				$t = "\t";
				$n = "\n";
			} else {
				$t = '';
				$n = '';
			}
			$indent  = str_repeat( $t, $depth );
			$output .= "{$n}{$indent}<ul class='children sub-menu'>{$n}";
		}
	}

}
