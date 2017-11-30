<?php
/**
 * Elementor Compatibility File.
 *
 * @package Astra
 */

namespace Elementor;

// If plugin - 'Elementor' not exist then return.
if ( ! class_exists( '\Elementor\Plugin' ) ) {
	return;
}

/**
 * Astra Elementor Compatibility
 */
if ( ! class_exists( 'Astra_Elementor' ) ) :

	/**
	 * Astra Elementor Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_Elementor {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'wp', array( $this, 'elementor_default_setting' ), 20 );
			add_action( 'do_meta_boxes', array( $this, 'elementor_default_setting' ), 20 );
		}

		/**
		 * Elementor Content layout set as Page Builder
		 *
		 * @return void
		 * @since  1.0.2
		 */
		function elementor_default_setting() {

			$page_builder_compatibility = apply_filters( 'astra_enable_page_builder_compatibility', true );

			if ( false == $page_builder_compatibility ) {
				return;
			}

			global $post;
			$id = astra_get_post_id();

			$page_builder_flag = get_post_meta( $id, '_astra_content_layout_flag', true );
			if ( isset( $post ) && empty( $page_builder_flag ) && ( is_admin() || is_singular() ) ) {

				if ( empty( $post->post_content ) && $this->is_elementor_activated( $id ) ) {

					update_post_meta( $id, '_astra_content_layout_flag', 'disabled' );
					update_post_meta( $id, 'site-post-title', 'disabled' );
					update_post_meta( $id, 'ast-title-bar-display', 'disabled' );
					update_post_meta( $id, 'ast-featured-img', 'disabled' );

					$content_layout = get_post_meta( $id, 'site-content-layout', true );
					if ( empty( $content_layout ) || 'default' == $content_layout ) {
						update_post_meta( $id, 'site-content-layout', 'page-builder' );
					}

					$sidebar_layout = get_post_meta( $id, 'site-sidebar-layout', true );
					if ( empty( $sidebar_layout ) || 'default' == $sidebar_layout ) {
						update_post_meta( $id, 'site-sidebar-layout', 'no-sidebar' );
					}
				}
			}
		}

		/**
		 * Check is elementor activated.
		 *
		 * @param int $id Post/Page Id.
		 * @return boolean
		 */
		function is_elementor_activated( $id ) {
			if ( version_compare( ELEMENTOR_VERSION, '1.5.0', '<' ) ) {
				return ( 'builder' === Plugin::$instance->db->get_edit_mode( $id ) );
			} else {
				return Plugin::$instance->db->is_built_with_elementor( $id );
			}
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Elementor::get_instance();
