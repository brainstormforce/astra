<?php
/**
 * Theme Update
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Astra_PB_Compatibility' ) ) {

	/**
	 * Astra_PB_Compatibility initial setup
	 *
	 * @since 1.0.13
	 */
	class Astra_PB_Compatibility {

		/**
		 * Class instance.
		 *
		 * @access private
		 * @var $instance Class instance.
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'maybe_run_pb_compatibility' ) );
		}

		/**
		 * Page builder compatibility database migration was added in v1.0.14, This was 2 Years ago as of right now.
		 * After version 1.8.7 we are stopping from running this to avoid execution of unnecessary database queries.
		 * This code will be removed alltogether in newer versions as it is not working
		 *
		 * @since 2.0.0
		 *
		 * @return void
		 */
		public function maybe_run_pb_compatibility() {

			$is_compatibility_completed = astra_get_option( '_astra_pb_compatibility_completed', false );

			if ( ! $is_compatibility_completed ) {

				// Theme Updates.
				add_action( 'do_meta_boxes', array( $this, 'page_builder_compatibility' ) );
				add_action( 'wp', array( $this, 'page_builder_compatibility' ), 25 );
			}
		}

		/**
		 * Update options of older version than 1.0.13.
		 *
		 * @since 1.0.13
		 * @return void
		 */
		public function page_builder_compatibility() {

			$offset_comp = get_option( '_astra_pb_compatibility_offset', false );
			$comp_time   = get_option( '_astra_pb_compatibility_time', false );

			if ( ! $offset_comp || ! $comp_time ) {
				astra_update_option( '_astra_pb_compatibility_completed', true );
				return;
			}

			// Get current post id.
			$current_post_id = (int) get_the_ID();
			if ( $current_post_id ) {
				$post_date     = strtotime( get_the_date( 'Y-m-d H:i:s', $current_post_id ) );
				$backward_date = strtotime( $comp_time );
				if ( $post_date < $backward_date ) {
					$this->update_meta_values( $current_post_id );
				}
			}

			// get all post types.
			$all_post_type = get_post_types(
				array(
					'public' => true,
				)
			);
			unset( $all_post_type['attachment'] );

			// wp_query array.
			$query = array(
				'post_type'      => $all_post_type,
				'posts_per_page' => '30',
				'no_found_rows'  => true,
				'post_status'    => 'any',
				'offset'         => $offset_comp,
				'date_query'     => array(
					array(
						'before'    => $comp_time,
						'inclusive' => true,
					),
				),
				'fields'         => 'ids',
			);

			// exicute wp_query.
			$posts = new WP_Query( $query );

			$continue = false;
			foreach ( $posts->posts as $id ) {
				$this->update_meta_values( $id );
				$continue = true;
			}

			if ( $continue ) {
				$offset_comp += 30;
				update_option( '_astra_pb_compatibility_offset', $offset_comp );
			} else {
				delete_option( '_astra_pb_compatibility_offset' );
				delete_option( '_astra_pb_compatibility_time' );
				astra_update_option( '_astra_pb_compatibility_completed', true );
			}
		}

		/**
		 * Update meta values
		 *
		 * @since 1.0.13
		 * @param  int $id Post id.
		 * @return void
		 */
		public function update_meta_values( $id ) {

			$layout_flag = get_post_meta( $id, '_astra_content_layout_flag', true );
			if ( empty( $layout_flag ) ) {
				$site_content = get_post_meta( $id, 'site-content-layout', true );

				if ( 'default' == $site_content ) {
					$post_type = get_post_type( $id );
					if ( 'page' == $post_type ) {
						$site_content = astra_get_option( 'single-page-content-layout', '' );
					} elseif ( 'post' == $post_type ) {
						$site_content = astra_get_option( 'single-post-content-layout', '' );
					}

					if ( 'default' == $site_content ) {
						$site_content = astra_get_option( 'site-content-layout', '' );
					}
				}

				$elementor = get_post_meta( $id, '_elementor_edit_mode', true );
				$vc        = get_post_meta( $id, '_wpb_vc_js_status', true );
				if ( 'page-builder' === $site_content ) {
					update_post_meta( $id, '_astra_content_layout_flag', 'disabled' );
					update_post_meta( $id, 'site-post-title', 'disabled' );
					update_post_meta( $id, 'ast-title-bar-display', 'disabled' );
					update_post_meta( $id, 'site-sidebar-layout', 'no-sidebar' );
				} elseif ( 'builder' === $elementor || true === $vc || 'true' === $vc ) {
					update_post_meta( $id, '_astra_content_layout_flag', 'disabled' );
				}
			}
		}
	}
}



/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_PB_Compatibility::get_instance();
