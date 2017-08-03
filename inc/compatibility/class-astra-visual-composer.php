<?php
/**
 * Visual Composer Compatibility File.
 *
 * @package Astra
 */

// If plugin - 'Visual Composer' not exist then return.
if ( ! class_exists( 'Vc_Manager' ) ) {
	return;
}

/**
 * Astra Visual Composer Compatibility
 */
if ( ! class_exists( 'Astra_Visual_Composer' ) ) :

	/**
	 * Astra Visual Composer Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_Visual_Composer {

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
			add_action( 'vc_before_init',            array( $this, 'vc_set_as_theme' ) );
			add_action( 'wp',                        array( $this, 'vc_default_setting' ), 20 );
			add_action( 'vc_frontend_editor_render', array( $this, 'vc_frontend_default_setting' ) );
		}

		/**
		 * VC Updated meta settings
		 *
		 * @since 1.0.13
		 * @return void
		 */
		function vc_update_meta_setting() {
			$id = astra_get_post_id();
			$page_builder_flag = get_post_meta( $id, '_astra_content_layout_flag', true );

			if ( empty( $page_builder_flag ) ) {

				update_post_meta( $id, '_astra_content_layout_flag', 'disabled' );
				update_post_meta( $id, 'site-post-title', 'disabled' );
				update_post_meta( $id, 'ast-title-bar-display', 'disabled' );

				$content_layout = get_post_meta( $id, 'site-content-layout', true );
				if ( empty( $content_layout ) || 'default' == $content_layout ) {
					update_post_meta( $id, 'site-content-layout', 'plain-container' );
				}

				$sidebar_layout = get_post_meta( $id, 'site-sidebar-layout', true );
				if ( empty( $sidebar_layout ) || 'default' == $sidebar_layout ) {
					update_post_meta( $id, 'site-sidebar-layout', 'no-sidebar' );
				}
			}
		}

		/**
		 * Set frontend default setting.
		 *
		 * @since 1.0.13
		 * @return void
		 */
		function vc_frontend_default_setting() {

			global $post;
			$id = astra_get_post_id();

			if ( $id > 0 && empty( $post->post_content ) ) {
				$this->vc_update_meta_setting();
			}
		}

		/**
		 * Set default setting.
		 *
		 * @since 1.0.13
		 * @return void
		 */
		function vc_default_setting() {

			global $post;

			$id = astra_get_post_id();
			$vc_active = get_post_meta( $id, '_wpb_vc_js_status', true );

			if ( is_singular() && ( has_shortcode( $post->post_content, 'vc_row' ) || 'true' == $vc_active ) ) {
				$this->vc_update_meta_setting();
			}
		}

		/**
		 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
		 *
		 * @since 1.0.0
		 */
		function vc_set_as_theme() {

			if ( function_exists( 'vc_set_as_theme' ) ) {
				vc_set_as_theme( true );
				vc_manager()->disableUpdater( true );
			}
		}
	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Visual_Composer::get_instance();
