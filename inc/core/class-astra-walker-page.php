<?php
/**
 * Navigation Menu customizations.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.5.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom wp_nav_menu walker.
 *
 * @package Astra WordPress theme
 */
if ( ! class_exists( 'Astra_Walker_Page' ) ) {

	/**
	 * Astra custom navigation walker.
	 *
	 * @since 1.5.4
	 */
	class Astra_Walker_Page extends Walker_Page {

		/**
		 * Outputs the beginning of the current level in the tree before elements are output.
		 *
		 * @since 1.5.4
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
			$output  = apply_filters( 'astra_caret_wrap_filter', $output, $args['sort_column'] );

		}

		/**
		 * Outputs the beginning of the current element in the tree.
		 *
		 * @see Walker::start_el()
		 * @since 1.7.2
		 *
		 * @param string  $output       Used to append additional content. Passed by reference.
		 * @param WP_Post $page         Page data object.
		 * @param int     $depth        Optional. Depth of page. Used for padding. Default 0.
		 * @param array   $args         Optional. Array of arguments. Default empty array.
		 * @param int     $current_page Optional. Page ID. Default 0.
		 */
		public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
			parent::start_el( $output, $page, $depth, $args, $current_page );
			$output = apply_filters( 'astra_walker_nav_menu_start_el', $output, $page, $depth, $args );

		}
	}

}
