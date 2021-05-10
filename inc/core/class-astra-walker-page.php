<?php
/**
 * Navigation Menu customizations.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
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
			$css_class   = array( 'page_item', 'page-item-' . $page->ID );
			$icon        = '';
			$mobile_icon = '';

			if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
				$css_class[] = 'menu-item-has-children';
				$icon        = Astra_Icons::get_icons( 'arrow' );
				$icon        = '<span role="presentation" class="dropdown-menu-toggle">' . $icon . '</span>';
				// Add toggle button if menu is from Astra.
				if ( true === is_object( $args ) ) {
					if ( isset( $args->theme_location ) &&
					( 'primary' === $args->theme_location ||
					'above_header_menu' === $args->theme_location ||
					'below_header_menu' === $args->theme_location )
					) {
						$mobile_icon = '<button ' . astra_attr(
							'ast-menu-toggle',
							array(
								'aria-expanded' => 'false',
							),
							$page
						) . '><span class="screen-reader-text">' . __( 'Menu Toggle', 'astra' ) . '</span>' . Astra_Icons::get_icons( 'arrow' ) . '</button>';
					}
				} else {
					if ( isset( $page->post_parent ) && 0 === $page->post_parent ) {
						$mobile_icon = '<button ' . astra_attr(
							'ast-menu-toggle',
							array(
								'aria-expanded' => 'false',
							),
							$page
						) . '><span class="screen-reader-text">' . __( 'Menu Toggle', 'astra' ) . '</span>' . Astra_Icons::get_icons( 'arrow' ) . '</button>';
					}
				}
			}

			if ( ! empty( $current_page ) ) {
				$_current_page = get_post( $current_page );
				if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
					$css_class[] = 'current-menu-ancestor';
				}
				if ( $page->ID == $current_page ) {
					$css_class[] = 'current-menu-item';
				} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
					$css_class[] = 'current-menu-parent';
				}
			} elseif ( get_option( 'page_for_posts' ) == $page->ID ) {
				$css_class[] = 'current-menu-parent';
			}

			$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

			$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
			$args['link_after']  = empty( $args['link_after'] ) ? '' : $args['link_after'];

			$output .= sprintf(
				'<li class="%s"><a href="%s" class="menu-link">%s%s%s%s</a>%s',
				$css_classes,
				get_permalink( $page->ID ),
				$args['link_before'],
				apply_filters( 'the_title', $page->post_title, $page->ID ),
				$args['link_after'],
				$icon,
				$mobile_icon
			);
		}
	}

}
