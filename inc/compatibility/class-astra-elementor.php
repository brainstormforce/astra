<?php
/**
 * Elementor Compatibility File.
 *
 * @package Astra
 */

namespace Elementor;// phpcs:ignore PHPCompatibility.Keywords.NewKeywords.t_namespaceFound

use Astra_Global_Palette;
use Elementor\Core\Settings\Manager as SettingsManager;

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
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'elementor_add_scripts' ) );

			/**
			 * Compatibility for Elementor Headings after Elementor-v2.9.9.
			 *
			 * @since  2.4.5
			 */
			add_filter( 'astra_dynamic_theme_css', array( $this, 'enqueue_elementor_compatibility_styles' ) );

			add_action( 'rest_request_after_callbacks', array( $this, 'elementor_add_theme_colors' ), 999, 3 );
			add_filter( 'rest_request_after_callbacks', array( $this, 'display_global_colors_front_end' ), 999, 3 );
			add_filter( 'astra_dynamic_theme_css', array( $this, 'generate_global_elementor_style' ), 11 );
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

			$dynamic_css .= astra_parse_css(
				array(
					'.elementor-element .elementor-wc-products .woocommerce[class*="columns-"] ul.products li.product' => array(
						'width'  => 'auto',
						'margin' => '0',
					),
					'.elementor-element .woocommerce .woocommerce-result-count' => array(
						'float' => 'none',
					),
				),
				'',
				astra_get_mobile_breakpoint() 
			);

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

		/**
		 * Display theme global colors to Elementor Global colors
		 *
		 * @since x.x.x
		 * @param object          $response rest request response.
		 * @param array           $handler Route handler used for the request.
		 * @param WP_REST_Request $request Request used to generate the response.
		 * @return object
		 */
		public function elementor_add_theme_colors( $response, $handler, $request ) {

			$route = $request->get_route();

			if ( '/elementor/v1/globals' != $route ) {
				return $response;
			}

			$global_palette = astra_get_option( 'global-color-palette' );
			$data           = $response->get_data();
			$slugs          = Astra_Global_Palette::get_palette_slugs();

			foreach ( $global_palette['palette'] as $key => $color ) {

				$slug = 'astra' . $slugs[ $key ];
				// Remove hyphens from slug.
				$no_hyphens = str_replace( '-', '', $slug );

				$data['colors'][ $no_hyphens ] = array(
					'id'    => esc_attr( $no_hyphens ),
					'title' => $global_palette['labels'][ $key ],
					'value' => $color,
				);
			}

			$response->set_data( $data );
			return $response;
		}

		/**
		 * Display global paltte colors on Elementor front end Page.
		 *
		 * @since x.x.x
		 * @param object          $response rest request response.
		 * @param array           $handler Route handler used for the request.
		 * @param WP_REST_Request $request Request used to generate the response.
		 * @return object
		 */
		public function display_global_colors_front_end( $response, $handler, $request ) {
			$route = $request->get_route();

			if ( 0 !== strpos( $route, '/elementor/v1/globals' ) ) {
				return $response;
			}

			$slug_map      = array();
			$palette_slugs = Astra_Global_Palette::get_palette_slugs();

			foreach ( $palette_slugs as $key => $slug ) {
				// Remove hyphens as hyphens do not work with Elementor global styles.
				$no_hyphens              = 'astra' . str_replace( '-', '', $slug );
				$slug_map[ $no_hyphens ] = $key;
			}

			$rest_id = substr( $route, strrpos( $route, '/' ) + 1 );

			if ( ! in_array( $rest_id, array_keys( $slug_map ), true ) ) {
				return $response;
			}

			$colors   = astra_get_option( 'global-color-palette' );
			$response = rest_ensure_response(
				array(
					'id'    => esc_attr( $rest_id ),
					'title' => Astra_Global_Palette::get_css_variable_prefix() . esc_html( $slug_map[ $rest_id ] ),
					'value' => $colors['palette'][ $slug_map[ $rest_id ] ],
				)
			);
			return $response;
		}

		/**
		 * Generate CSS variable style for Elementor.
		 *
		 * @since x.x.x
		 * @param string $dynamic_css Dynamic CSS.
		 * @return object
		 */
		public function generate_global_elementor_style( $dynamic_css ) {

			$global_palette = astra_get_option( 'global-color-palette' );
			$palette_style  = array();
			$slugs          = Astra_Global_Palette::get_palette_slugs();
			$style          = array();

			if ( isset( $global_palette['palette'] ) ) {
				foreach ( $global_palette['palette'] as $color_index => $color ) {
					$variable_key           = '--e-global-color-astra' . str_replace( '-', '', $slugs[ $color_index ] );
					$style[ $variable_key ] = $color;
				}

				$palette_style[':root'] = $style;
				$dynamic_css           .= astra_parse_css( $palette_style );
			}

			return $dynamic_css;
		}

		/**
		 * Load style inside Elementor editor.
		 *
		 * @since x.x.x
		 * @return void
		 */
		public function elementor_add_scripts() {

			$editor_preferences = SettingsManager::get_settings_managers( 'editorPreferences' );
			$theme              = $editor_preferences->get_model()->get_settings( 'ui_theme' );
			$style              = 'dark' == $theme ? '-dark' : '';

			wp_enqueue_style( 'astra-elementor-editor-style', ASTRA_THEME_URI . 'inc/assets/css/ast-elementor-editor' . $style . '.css', array(), ASTRA_THEME_VERSION );
		}
	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Elementor::get_instance();
