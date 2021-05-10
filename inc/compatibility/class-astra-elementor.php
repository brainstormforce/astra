<?php
/**
 * Elementor Compatibility File.
 *
 * @package Astra
 */

namespace Elementor;// phpcs:ignore PHPCompatibility.Keywords.NewKeywords.t_namespaceFound

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
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'wp', array( $this, 'elementor_default_setting' ), 20 );
			add_action( 'elementor/preview/init', array( $this, 'elementor_default_setting' ) );
			add_action( 'elementor/preview/enqueue_styles', array( $this, 'elementor_overlay_zindex' ) );

			/**
			 * Compatibility for Elementor Headings after Elementor-v2.9.9.
			 *
			 * @since  2.4.5
			 */
			add_filter( 'astra_dynamic_theme_css', array( $this, 'enqueue_elementor_compatibility_styles' ) );
		}

		/**
		 * Compatibility CSS for Elementor Headings after Elementor-v2.9.9
		 *
		 * In v2.9.9 Elementor has removed [ .elementor-widget-heading .elementor-heading-title { margin: 0 } ] this CSS.
		 * Again in v2.9.10 Elementor added this as .elementor-heading-title { margin: 0 } but still our [ .entry-content heading { margin-bottom: 20px } ] CSS overrding their fix.
		 *
		 * That's why adding this CSS fix to headings by setting bottom-margin to 0.
		 *
		 * @param  string $dynamic_css Astra Dynamic CSS.
		 * @param  string $dynamic_css_filtered Astra Dynamic CSS Filters.
		 * @return string $dynamic_css Generated CSS.
		 *
		 * @since  2.4.5
		 */
		public function enqueue_elementor_compatibility_styles( $dynamic_css, $dynamic_css_filtered = '' ) {

			global $post;
			$id = astra_get_post_id();

			if ( $this->is_elementor_activated( $id ) ) {

				$elementor_heading_margin_comp = array(
					'.elementor-widget-heading .elementor-heading-title' => array(
						'margin' => '0',
					),
				);

				/* Parse CSS from array() */
				$parse_css = astra_parse_css( $elementor_heading_margin_comp );

				$elementor_base_css = array(
					'.elementor-post.elementor-grid-item.hentry' => array(
						'margin-bottom' => '0',
					),
					'.woocommerce div.product .elementor-element.elementor-products-grid .related.products ul.products li.product, .elementor-element .elementor-wc-products .woocommerce[class*=\'columns-\'] ul.products li.product' => array(
						'width'  => 'auto',
						'margin' => '0',
						'float'  => 'none',
					),
				);
				// Load base static CSS when Elmentor is activated.
				$parse_css .= astra_parse_css( $elementor_base_css );

				if ( is_rtl() ) {
					$elementor_rtl_support_css = array(
						'.ast-left-sidebar .elementor-section.elementor-section-stretched,.ast-right-sidebar .elementor-section.elementor-section-stretched' => array(
							'max-width' => '100%',
							'right'     => '0 !important',
						),
					);
				} else {
					$elementor_rtl_support_css = array(
						'.ast-left-sidebar .elementor-section.elementor-section-stretched,.ast-right-sidebar .elementor-section.elementor-section-stretched' => array(
							'max-width' => '100%',
							'left'      => '0 !important',
						),
					);
				}
				$parse_css .= astra_parse_css( $elementor_rtl_support_css );
				

				$dynamic_css .= $parse_css;
			}
			
			$elementor_archive_page_css = array(
				'.elementor-template-full-width .ast-container' => array(
					'display' => 'block',
				),
			);
			$dynamic_css               .= astra_parse_css( $elementor_archive_page_css );

			return $dynamic_css;
		}

		/**
		 * Elementor Content layout set as Page Builder
		 *
		 * @return void
		 * @since  1.0.2
		 */
		public function elementor_default_setting() {

			if ( false === astra_enable_page_builder_compatibility() || 'post' == get_post_type() ) {
				return;
			}

			// don't modify post meta settings if we are not on Elementor's edit page.
			if ( ! $this->is_elementor_editor() ) {
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

					// In the preview mode, Apply the layouts using filters for Elementor Template Library.
					add_filter(
						'astra_page_layout',
						function() { // phpcs:ignore PHPCompatibility.FunctionDeclarations.NewClosure.Found
							return 'no-sidebar';
						}
					);

					add_filter(
						'astra_get_content_layout',
						function () { // phpcs:ignore PHPCompatibility.FunctionDeclarations.NewClosure.Found
							return 'page-builder';
						}
					);

					add_filter( 'astra_the_post_title_enabled', '__return_false' );
					add_filter( 'astra_featured_image_enabled', '__return_false' );
				}
			}
		}

		/**
		 * Add z-index CSS for elementor's drag drop
		 *
		 * @return void
		 * @since  1.4.0
		 */
		public function elementor_overlay_zindex() {

			// return if we are not on Elementor's edit page.
			if ( ! $this->is_elementor_editor() ) {
				return;
			}

			?>
			<style type="text/css" id="ast-elementor-overlay-css">
				.elementor-editor-active .elementor-element > .elementor-element-overlay {
					z-index: 9999;
				}
			</style>

			<?php
		}

		/**
		 * Check is elementor activated.
		 *
		 * @param int $id Post/Page Id.
		 * @return boolean
		 */
		public function is_elementor_activated( $id ) {
			if ( version_compare( ELEMENTOR_VERSION, '1.5.0', '<' ) ) {
				return ( 'builder' === Plugin::$instance->db->get_edit_mode( $id ) );
			} else {
				return Plugin::$instance->db->is_built_with_elementor( $id );
			}
		}

		/**
		 * Check if Elementor Editor is open.
		 *
		 * @since  1.2.7
		 *
		 * @return boolean True IF Elementor Editor is loaded, False If Elementor Editor is not loaded.
		 */
		private function is_elementor_editor() {
			if ( ( isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) || isset( $_REQUEST['elementor-preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}

			return false;
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Elementor::get_instance();
